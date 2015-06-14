<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Response;
use App\Models\Campaign;

/**
 * Description of MessageHelper
 *
 * @author jameskmb
 */
class MessageHelper {

    /**
     * Decode a message from users into a response that can be added to the system
     * @param Message $msg
     * @return Response response
     */
    public static function decode(Message $msg) {

        //extract campaign code
        $parts = explode(' ', $msg->text);
        if (count($parts) == 0) {
            return null;
        }
                
        if (preg_match('/A[0-9]{4,}/i', $parts[0])) {
            $campaign = Campaign::find(substr($parts[0], 1));
            if (is_null($campaign)) {
                return null;
            }
            $resp = new Response();
            $resp->campaign_id = $campaign->id;
            //correct response. one character only
            if ($campaign->possible_responses && count($parts) == 2 && strlen($parts[1]) == 1) {
                $resp->text = $parts[1];
            } else {
               $resp->text = implode(' ', array_slice($parts, 1));
            }
            
            $resp->message_id = $msg->id;
            return $resp;
        }

        return null;
    }

    /**
     * Send outbound messages
     * @throws Exception 
     * @param \Traversable $messages
     */
    public static function sendFromSystem(\Traversable $messages) {
        
    }

    /**
     * Send
     * @param Message $msg
     */
    public static function send(Message $msg) {
        $this->sendFromSystem([$msg]);
    }

}
