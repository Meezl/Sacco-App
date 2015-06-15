<?php
namespace App\Http\Controllers;

use App\Models\Campaign;


/**
 * Campaign Report Generator
 *
 * @author jameskmb
 */
class StatsController extends Controller{
    
    /**
     * Get the statistics of a campaign
     * @param mixed $id int id of the campaign or idString e.g {X0004}
     */
    public function getCampaign($id) {
        $campaign = $this->retrieve($id);
        $campaign->cost = $this->estimatedCost($campaign->sent()->lists('cost'));
        return view('reports.campaign-index', compact('campaign'));
    }
    
    /**
     * Try to calculate cost 
     * @param array $costs
     */
    private function estimatedCost(\Traversable $costs) {
        $total = 0;
        foreach($costs as $c) {
            foreach ($parts = explode(' ', $c) as $string) {
                if(is_numeric($string)) {
                    $total += $string;
                    break;
                }
            }
        }
    }
    
    /**
     * Retrieve active Campaign
     * @param mixed $id Campaign int id or stringId
     * @return Campaign
     */
    public function retrieve($id) {
        $campaign = null;
        $query = Campaign::where('is_active', '=', 1);
        if(is_numeric($id)) {
            $campaign = $query->where('id', '=', $id)->first();
        }
        else if(strlen($id) > 1) {
            $campaign = $query->where('id', '=', substr($id, 1))->first();
        }
        
        $this->show404Unless($campaign);
        return $campaign;
    }
}
