<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Response;
use App\Models\Contact;
use App\Commands\GenerateStats;

/**
 * Campaign Report Generator
 *
 * @author jameskmb
 */
class StatsController extends Controller
{

    public function getOpen($id)
    {
        $campaign = $this->retrieve($id);
        $campaign->is_closed = 0;
        $campaign->save();
        return \Redirect::action('CampaignController@getIndex');
    }

    public function getClose($id)
    {
        $campaign = $this->retrieve($id);
        $campaign->is_closed = 1;
        $campaign->save();
        return \Redirect::action('CampaignController@getIndex');
    }

    /**
     * Get the statistics of a campaign
     * @param mixed $id int id of the campaign or idString e.g {X0004}
     */
    public function getCampaign($id)
    {
        $campaign = $this->retrieve($id);
        $campaign->cost = $this->estimatedCost($campaign->sent()->lists('cost'));

        $generator = new GenerateStats($campaign);
        $stats = $generator->handle();

        //recount to include invalid/mising responses
        $campaign->total_responses = 0;
        foreach ($stats as $s) {
            $campaign->total_responses += $s->count;
        }

        $colors = config('sms.colors');

        return view('reports.campaign-index', compact('campaign', 'stats', 'colors'));
    }

    public function getReport($id)
    {
        $campaign = $this->retrieve($id);
        $statsGenerator = new \App\Commands\GenerateStats($campaign);

        $pdf = new \App\Models\Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->loadDefaults($campaign, $statsGenerator->handle());
        $pdf->intro();
        $pdf->Ln();
        $pdf->renderQuestion();
        $pdf->Ln();
        $pdf->overview();
        $pdf->AddPage();
        $pdf->renderMessages();
        $name = $campaign->getIdString().date('Y_d_m_H_i_s').'.pdf';
        $pdf->Output($name, 'I');
    }

    /**
     * show Campaign Responses
     * @param mixed $id int id of the campaign or idString e.g {X0004}
     * @return Response
     */
    public function getResponses($id)
    {
        $campaign = $this->retrieve($id);
        $resps = $campaign->getResponse();
        return view('reports.responses', compact('campaign', 'resps'));
    }

    public function getResponseDetails($campaign_id, $resp_id)
    {
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
    private function estimatedCost(array $costs)
    {
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

    /**
     * Retrieve active Campaign
     * @param mixed $id Campaign int id or stringId
     * @return Campaign
     */
    public function retrieve($id)
    {
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
