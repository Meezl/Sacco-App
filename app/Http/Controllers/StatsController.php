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
    }
    
    /**
     * Retrieve Campaign
     * @param mixed $id Campaign int id or stringId
     * @return Campaign
     */
    public function retrieve($id) {
        $campaign = null;
        if(is_numeric($id)) {
            $campaign = Campaign::find($id);
        }
        else if(strlen($id) > 1) {
            $campaign = Campaign::find(substr($id, 1));
        }
        
        $this->show404Unless($campaign);
        return $campaign;
    }
}
