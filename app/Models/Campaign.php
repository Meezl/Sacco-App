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

    const EXCERPT_LENGTH = 70;
    
    const TABLE_CAMPAIGN_CONTACTS = 'campaign_contacts';

    public function getExcerpt() {
        if (strlen($this->description) > 50) {
            return substr($this->description, 0, self::EXCERPT_LENGTH) . '...';
        }
        return $this->description;
    }

    public function getIdString() {
        if ($this->id) {
            return sprintf('X%04d', $this->id);
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
        return $this->belongsToMany('App\Models\Contact', self::TABLE_CAMPAIGN_CONTACTS);
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
        $number = config('sms.system_number');
        $format = "Reply for free to $number in the format( EGERS %s %s ";
        if ($this->possible_responses) {
            return sprintf($format, $this->getIdString(), 'A )where A is your reply');
        } else {
            return sprintf($format, $this->getIdString(), 'reply )');
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

    public function getLengthStats() {
        $sms = $this->getSms();
        
        if($this->send_greeting) {
            $sms = "Hello userlastName\n". $sms;
        }
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

    public function getSms() {
        $withHelp = $this->help_text;
        $sms = $this->message? : '';
        $sms .= "\n" . ($this->getResponseText()? : '');
        $sms .= ($withHelp ? $this->getHelpText() : '');
        if (!$withHelp && $this->getIdString()) {
            return $this->getIdString() . "\n" . $sms;
        }
        return $sms;
    }

}
