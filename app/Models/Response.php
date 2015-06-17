<?php
namespace App\Models;

/**
 * Description of Response
 *
 * @author jameskmb
 */
class Response extends \Eloquent{
    
    private $msg;
    private $cmpgn;
    
    public function message() {
        return $this->belongsto('App\Models\Message');
    }
    
    public function getMessage() {
        if (is_null($this->msg)) {
            $this->msg = $this->message()->first();
        }
        return $this->msg;
    }
    
    public function campaign() {
        return $this->belongsTo('App\Models\Campaign');
    }
    
    public function getCampaign() {
        if(is_null($this->cmpgn)) {
            $this->cmpgn = $this->campaign()->first();
        }
        return $this->cmpgn;
    }
}
