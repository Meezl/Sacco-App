<?php

namespace App\Commands;

use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Campaign;

class GenerateStats extends Command implements SelfHandling
{

    const SPOILT_VOTES = 'Invalid Responses';

    /**
     *
     * @var Campaign
     */
    private $campaign;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get campaig response stats
     *
     * @return array
     */
    public function handle()
    {
        return $this->formatedStats($this->campaign);
    }

    private function formatedStats(Campaign $campaign)
    {
        $temp = $this->generateStats($campaign);
        $total = 0;
        foreach ($temp as $val) {
            $total += $val['count'];
        }

        if ($total < $campaign->total_contacted) {
            $temp[] = array(
                'key' => 'pending',
                'val' => 'Pending Responses',
                'count' => $campaign->total_contacted - $total
            );
        }

        $stats = [];
        foreach ($temp as $s) {
            if ($total == 0) {
                $s['percent'] = 0;
            } else {
                $s['percent'] = ($s['count'] / $total) * 100; //convert to %  
            }
            $stats[] = (object) $s;
        }

        return $stats;
    }

    private function generateStats(Campaign $campaign)
    {
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
                ->select(\DB::raw('count(*) as count, UCASE(text) as text'))
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

            //spoilt vote
            if (!$found) {
                $options[count($options) - 1]['count'] += $r->count;
            }
        }
        return $options;
    }

    /**
     * Split answers/options into individual parts.
     * Each answer is separated by \n and and answer Looks like
     * (A:human readable option)
     * @param string $answers
     * @return array
     */
    private function possibleResponses($answers)
    {
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

}
