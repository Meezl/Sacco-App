<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Response;
use App\Models\Contact;

/**
 * Campaign Report Generator
 *
 * @author jameskmb
 */
class StatsController extends Controller {

    const SPOILT_VOTES = 'Invalid Responses';
    
    
    
    public function getOpen($id) {
        $campaign = $this->retrieve($id);
        $campaign->is_closed = 0;
        $campaign->save();
        return \Redirect::action('CampaignController@getIndex');
    }
    
    public function getClose($id) {
        $campaign = $this->retrieve($id);
        $campaign->is_closed = 1;
        $campaign->save();
        return \Redirect::action('CampaignController@getIndex');
    }

    /**
     * Get the statistics of a campaign
     * @param mixed $id int id of the campaign or idString e.g {X0004}
     */
    public function getCampaign($id) {
        $campaign = $this->retrieve($id);
        $campaign->cost = $this->estimatedCost($campaign->sent()->lists('cost'));
        //determine pending votes
        $stats = $this->generateStats($campaign);
        $total = 0;
        foreach($stats as $val) {
            $total += $val['count'];
        }
        
        if($total <= $campaign->total_contacted) {
            $stats[] = array(
                'key' => 'pending',
                'val' => 'Pending Responses',
                'count' => $campaign->total_contacted - $total
            );
        }
        $colors = config('sms.colors');
        $temp = [];
        foreach ($stats as $s) {
            $temp[] = (object) $s;
        }
        $stats = $temp;
        return view('reports.campaign-index', compact('campaign', 'stats', 'colors'));
    }

    /**
     * show Campaign Responses
     * @param mixed $id int id of the campaign or idString e.g {X0004}
     * @return Response
     */
    public function getResponses($id) {
        $campaign = $this->retrieve($id);
        $resps = $campaign->getResponse();
        return view('reports.responses', compact('campaign', 'resps'));
    }

    public function getResponseDetails($campaign_id, $resp_id) {
        $campaign = $this->retrieve($campaign_id);
        $response = $campaign->response()
                ->where('id', '=', $resp_id)
                ->first();
        $this->show404Unless($response);
        $message = $response->getMessage();
        $this->show404Unless($message);
        $contact = Contact::where('phone', '=', $message->sender)->first();
        return view('reports.response-details', compact('campaign', 'response', 'message', 'contact'));
    }

    /**
     * Try to calculate cost 
     * @param array $costs
     */
    private function estimatedCost(array $costs) {
        \Debugbar::info(compact('costs'));
        $total = 0;
        foreach ($costs as $c) {
            foreach ($parts = explode(' ', $c) as $string) {
                if (is_numeric($string)) {
                    $total += $string;
                    break;
                }
            }
        }
        return $total;
    }

    private function generateStats(Campaign $campaign) {
        if (!$campaign->possible_responses) {
            return [];
        }

        $options = $this->possibleResponses(trim($campaign->getResponseText()));
        $options[] = array(
            'key' => 'spoilt',
            'val' => self::SPOILT_VOTES
        );

        //init vote count
        foreach ($options as &$o) {
            $o['count'] = 0;
        }
        unset($o);

        //match
        $responses = $campaign->response()
                ->select(\DB::raw('count(*) as count, text'))
                ->groupBy('text')
                ->get();
        //\Debugbar::info(compact('options'));return;
        foreach ($responses as $r) {
            $found = false;
            foreach ($options as &$o) {
                if ($r->text == $o['key']) {
                    $o['count'] += $r->count;
                    $found = true;
                    break;
                }
            }
            unset($o);

            if (!$found) {
                $options[count($options) - 1]['count'] ++;
            }
        }
        return $options;
    }

    private function possibleResponses($answers) {
        $options = explode("\n", $answers);
        $result = [];
        foreach ($options as $o) {
            $temp = explode(':', $o);
            $result[] = array(
                'key' => $temp[0],
                'val' => $temp[1]
            );
        }
        return $result;
    }

    /**
     * Retrieve active Campaign
     * @param mixed $id Campaign int id or stringId
     * @return Campaign
     */
    public function retrieve($id) {
        $campaign = null;
        $query = Campaign::where('is_active', '=', 1);
        if (is_numeric($id)) {
            $campaign = $query->where('id', '=', $id)->first();
        } else if (strlen($id) > 1) {
            $campaign = $query->where('id_string', '=', $id)->first();
        }

        $this->show404Unless($campaign);
        return $campaign;
    }

}
