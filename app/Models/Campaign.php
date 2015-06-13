<?php

namespace App\Models;

/**
 * Description of Campaign
 *
 * @author jameskmb
 */
class Campaign extends \Eloquent {

    private $responses;
    private $myContacts;
    private $totalContacts;

    public function getIdString() {
        if ($this->id) {
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
        if ($this->possible_responses && is_null($this->responses)) {
            $this->responses = $this->answers()->orderBy('message')->get();
        }
        return $this->responses;
    }

    public function contacts() {
        return $this->belongsToMany('App\Models\Contact', 'campaign_contacts');
    }

    public function getContacts() {
        if (is_null($this->myContacts)) {
            $this->myContacts = $this->contacts()->get();
        }
        return $this->myContacts;
    }

    public function getHelpText() {
        if (!$this->id) {
            return null;
        }
        $format = "Reply for free to this sms in the format: EGERS %s %s";
        if ($this->possible_responses) {
            return sprintf($format, $this->getIdString(), 'A');
        } else {
            return sprintf($format, $this->getIdString(), 'reply');
        }
    }

    public function getResponseText() {
        if (!$this->possible_responses) {
            return null;
        }

        $resps = $this->getAnswers();
        if (is_null($resps) || $resps->isEmpty()) {
            return null;
        }

        //concatenate responses with options A-Z
        $string = '';
        $current = $start = 'A';
        $end = 'Z';
        foreach ($resps as $r) {
            if ($current == $end) {
                break;
            }
            $string .= $current . ': ' . trim($r->message) . "\n";
            $current++;
        }
        return $string;
    }

    /**
     * 
     * @param type $withHelp
     */
    public function getLengthStats($withHelp = true) {
        $sms = $this->getSms($withHelp);
        if (is_null($this->totalContacts)) {
            $this->totalContacts = $this->contacts()->count();
        }

        $data = array(
            'sms' => $sms,
            'length' => strlen($sms),
            'cost' => ceil(strlen($sms) / 160),
            'users' => $this->totalContacts
        );

        $data['total'] = $data['cost'] * $data['users'];
        return (object) $data;
    }

    public function getSms($withHelp = true) {
        $sms = $this->message? : '';
        $sms .= "\n" . ($this->getResponseText()? : '');
        $sms .= ($withHelp ? $this->getHelpText() : '');
        if (!$withHelp && $this->getIdString()) {
            return $this->getIdString() . "\n" . $sms;
        }
        return $sms;
    }

}
