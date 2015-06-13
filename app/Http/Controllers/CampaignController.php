<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Answer;
use App\Models\Contact;

/**
 * Description of CampaignController
 *
 * @author jameskmb
 */
class CampaignController extends Controller {

    const CONTACTS_PER_PAGE = 20;
    const CAMPAIGNS_PER_PAGE = 10;

    public function getNew($id = null) {
        $campaign = $this->retrieve($id);
        return view('campaigns.new', compact('campaign'));
    }

    public function postNew($id = null) {
        $campaign = $this->retrieve($id);

        $rules = array(
            'title' => 'required|max:200',
            'description' => 'required',
            'message' => 'required|max:900',
            'possible_responses' => 'required|integer|between:0,26',
            'category' => 'required|integer'
        );

        $data = \Input::all();

        $validator = \Validator::make($data, $rules);
        $this->map($data, $campaign);
        if ($validator->fails()) {
            \Session::flash('error', 'Please Correct The Higlighted Errors');
            return view('campaigns.new', compact('campaign'))->withErrors($validator->messages());
        }

        //manual
        if ($data['category'] == -1) {
            $campaign->category_id = null;
        } elseif ($data['category'] === 0) {
            //send to all. intentionaly left blank
        } else {
            $cat = Category::find($data['category']);
            if (is_null($cat)) {
                \Session::flash('error', 'Invalid Category Selected');
                return view('campaigns.new', compact('campaign'));
            }
        }

        //prevent duplicates
        $dulicate = Campaign::where('title', $campaign->title)->first();
        if (!is_null($dulicate) && $dulicate->id != $campaign->id) {
            \Session::flash('error', 'A Campaign  With that title aready exists');
            return view('campaigns.new', compact('campaign'));
        }

        $campaign->creator_id = \Auth::user()->id;
        $campaign->save();
        \Session::flash('success', 'Campaign Successfuly Updated');

        //select contacts that will be associated with this campaign
        //add everyone
        if ($campaign->category === 0) {
            $contacts = Contact::lists('id');
        }
        //add from category
        elseif ($campaign->category) {
            $contacts = $cat->contacts()->lists('id');
        }

        //prevent duplicate insertion
        $existing = $campaign->contacts()->lists('id');
        if (count($contacts)) {
            $data = [];
            foreach ($contacts as $c) {
                if (array_search($c, $existing) !== false) {
                    continue;
                }
                $data[] = array(
                    'contact_id' => $c->id,
                    'campaign_id' => $campaign->id
                );
                \DB::table('Campaign_contacts')->insert($data);
            }
        }
        if ($campaign->possible_responses) {
            return \Redirect::action('CampaignController@getAnswers', [$campaign->id]);
        }
        //to do: remove any associated responses
        return \Redirect::action('CampaignController@getContacts', [$campaign->id]);
    }

    public function getAnswers($id) {
        $campaign = $this->retrieve($id);
        return view('campaigns.add-answers', compact('campaign'));
    }

    public function postAnswers($id) {
        $campaign = $this->retrieve($id);
        $existing = $campaign->answers()->count();

        if ($existing == $campaign->possible_responses) {
            \Session::error('error', 'You cannot add any more possible responses. click the back button at the bottom to adjust this');
            return view('campaigns.add-answers', compact('campaign'));
        }

        if (!\Input::has('responses')) {
            \Session::error('error', 'Please enter at least one response');
            return view('campaigns.add-answers', compact('campaign'));
        }

        $responses = \Input::get('responses');
        $ignored = []; //responses not saved
        $data = []; //responses to be saved
        foreach ($responses as $r) {
            $trimed = trim($r);
            if (strlen($trimed) && strlen($trimed) < 80) {
                $data[] = array(
                    'campaign_id' => $campaign->id,
                    'message' => $trimed
                );
            } else {
                $ignored[] = $r;
            }
        }

        if (count($data)) {
            \DB::table('answers')->insert($data);
            \Session::flash('success', count($data) . ' responses have been successfuly added');
        }

        if (count($ignored)) {
            \Session::flash('error', count($ignored) . ' responses were not inserted because they were either missing or exceed the maximum character length(80)');
            return view('campaigns.add-answers', compact('campaign', 'ignored'));
        }

        return \Redirect::action('CampaignController@getAnswers', [$id]);
    }

    public function postRemoveResponses($id) {
        $campaign = $this->retrieve($id);

        if (!\Input::has('responses') || count(\Input::get('responses')) == 0) {
            \Session::flash('error', 'You did not select what you wanted to remove');
        } else {
            Answer::whereIn('id', \Input::get('responses'))
                    ->where('campaign_id', '=', $campaign->id)
                    ->delete();
            \Session::flash('success', 'Responses Successfuly Removed');
        }
        return \Redirect::action('CampaignController@getAnswers', [$id]);
    }

    public function getContacts($id) {
        $campaign = $this->retrieve($id);
        if ($campaign->answers()->count() != $campaign->possible_responses) {
            \Session::flash('error', 'Please Finish adding all the possible responses first');
            return \Redirect::action('CampaignController@getAnswers', [$id]);
        }
        $contacts = $campaign->contacts()
                ->paginate(self::CONTACTS_PER_PAGE)
                ->setPath(\URL::current());
        return view('campaigns.added-contacts', compact('contacts', 'campaign'));
    }

    public function postContacts($id) {
        $campaign = $this->retrieve($id);
        if (!\Input::has('remove') || count(\Input::get('remove')) == 0) {
            \Session::flash('error', 'Please Select The contacts you want to remove');
        } else {
            $count = \DB::table('campaign_contacts')
                    ->whereIn('contact_id', \Input::get('remove'))
                    ->where('campaign_id', '=', $campaign->id)
                    ->delete();
            \Debugbar::info(compact('count'));
            \Session::flash('success', 'Contacts Successfuly Removed');
        }

        return \Redirect::action('CampaignController@getContacts', [$id]);
    }

    public function getAddContacts($id) {
        $campaign = $this->retrieve($id);
        $existing = $campaign->contacts()->lists('id');

        if (count($existing)) {
            $contacts = Contact::whereNotIn('id', $existing)
                    ->paginate(self::CONTACTS_PER_PAGE)
                    ->setPath(\URL::current());
        } else {
            $contacts = Contact::paginate(self::CONTACTS_PER_PAGE)
                    ->setPath(\URL::current());
        }
        return view('campaigns.add-contacts', compact('campaign', 'contacts'));
    }

    public function postAddContacts($id) {
        $campaign = $this->retrieve($id);


        if (!\Input::has('add') || count(\Input::get('add')) == 0) {
            \Session::flash('error', 'Please Select The contacts you want to add');
        } else {
            //prevent dulicates
            $existing = $campaign->contacts()->lists('id');
            $contacts = \Input::get('add');
            $data = [];
            foreach ($contacts as $c) {
                if (array_search($c, $existing) !== false) {
                    continue;
                }
                $data[] = array(
                    'contact_id' => $c,
                    'campaign_id' => $campaign->id
                );
            }
            //insert
            if (count($data)) {
                \DB::table('campaign_contacts')->insert($data);
                \Session::flash('success', 'Contacts Successfuly Added');
            } else {
                \Session::flash('error', 'Nothing Added');
            }
        }
        
        return \Redirect::action('CampaignController@getAddContacts', [$campaign->id]);
    }
    
    public function getReview($id) {
        $campaign = $this->retrieve($id);
        $plain = $campaign->getLengthStats(false);
        $help = $campaign->getLengthStats(true);
        
        return view('campaigns.review', compact('campaign', 'plain', 'help'));
    }

    private function retrieve($id) {
        if (is_null($id)) {
            return new Campaign();
        }

        $campaign = null;
        if (is_numeric($id)) {
            $campaign = Campaign::find($id);
        } else {
            $campaign = Campaign::find(substr($id, 1));
        }

        $this->show404Unless($campaign);
        return $campaign;
    }
    
    public function getIndex() {
        $campaigns = Campaign::paginate(self::CAMPAIGNS_PER_PAGE)
                ->setPath(\URL::current());
        return view('campaigns.index', compact('campaigns'));
    }

    private function map(array $data, Campaign $c) {
        if (array_key_exists('title', $data)) {
            $c->title = $data['title'];
        }
        if (array_key_exists('description', $data)) {
            $c->description = $data['description'];
        }
        if (array_key_exists('message', $data)) {
            $c->message = $data['message'];
        }
        if (array_key_exists('possible_responses', $data)) {
            $c->possible_responses = $data['possible_responses'];
        }

        if (array_key_exists('category', $data)) {
            $c->category_id = $data['category'];
        }
    }

}
