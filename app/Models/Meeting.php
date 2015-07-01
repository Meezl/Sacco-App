<?php

namespace App\Models;

/**
 * A meeting scheduled for members to confirm thier attendance
 *
 * @author jameskmb
 */
class Meeting extends \Eloquent {

    private $stats = null;

    public function getStats() {
        if (is_null($this->stats)) {
            $this->stats = Message::select(\DB::raw('count(*) as count, ucase(text) as text'))
                    ->where('created_at', '>', self::dbDate($this->start). ' 00:00:00')
                    ->where('created_at', '<', self::dbDate($this->end). ' 00:00:00')
                    ->where(function($query) {
                        $query->where('text', '=', 'yes')
                        ->orWhere('text', '=', 'no');
                    })
                    ->groupBy('text')
                    ->get();
        }
        
        return $this->stats;
    }

    public static function dbDate($date) {
        return date('Y-m-d', strtotime($date));
        }
    }
    