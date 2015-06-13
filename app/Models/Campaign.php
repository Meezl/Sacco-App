<?php
namespace App\Models;

/**
 * Description of Campaign
 *
 * @author jameskmb
 */
class Campaign extends \Eloquent{
    
    private $responses;
    
    
    public function getIdString() {
        if($this->id) {
            return sprintf('A%04d', $this->id);
        }
        return null;
    }
    
    /**
     * Query for possible responses
     */
    public function answers() {
        return $this->hasMany('App\Models\Answer');
    }
    
    /**
     * Get All the possible answers
     * @return Collection
     */
    public function getAnswers() {
        if($this->possible_responses && is_null($this->responses)) {
            $this->responses = $this->answers()->get();
        }
        return $this->responses;
    }
    
}
