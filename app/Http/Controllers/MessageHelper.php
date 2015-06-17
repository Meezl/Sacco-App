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

        if (preg_match('/^X[0-9]{1,}$/i', $parts[0])) {
            $campaign = Campaign::find(substr($parts[0], 1));
            if (is_null($campaign)) {
                return null;
            }
            $resp = new Response();
            $resp->campaign_id = $campaign->id;
            //correct response. one character only
            if ($campaign->possible_responses && count($parts) == 2 && strlen($parts[1]) == 1) {
                $resp->text = strtoupper($parts[1]);
            } else {
                $resp->text = implode(' ', array_slice($parts, 1));
            }

            $resp->message_id = $msg->id;
            $resp->save();
            $campaign->total_responses += 1;
            $campaign->save();
            return $resp;
        }

        return null;
    }

    /**
     * Send outbound messages
     * @throws Exception 
     * @param array $contacts
     * @param string $message
     * @return array $status
     */
    public static function sendFromSystem(array $contacts, $message) {
        if (is_null($message) || strlen($message) == 0) {
            throw new \Exception('you cannot send an empty message');
        }

        if (count($contacts) == 0) {
            throw new \Exception("Message Receiver not specified");
        }
        $gateway = self::getGateway();
        $from = config('sms.system_number');

        $results = $gateway->sendMessage(implode(',', $contacts), $message, $from);
        $status = [];
        foreach ($results as $result) {
            $status[] = array(
                'receiver' => $result->number,
                'sender' => $from,
                'status' => $result->status,
                'api_id' => $result->messageId,
                'cost' => $result->cost
            );
        }
        return $status;
    }

    public static function sendRaw($contact, $message) {
        $statuses = self::sendFromSystem([$contact], $message);
        return $statuses[0];
    }

    /**
     * Send
     * @param Message $msg
     */
    public static function send(Message $msg) {
        $statuses = self::sendFromSystem([$msg->receiver], $msg->text);
        return $statuses[0];
    }

    /**
     * sms gateway
     * @return \AfricasTalkingGateway
     */
    private static function getGateway() {
        return new \AfricasTalkingGateway(config('sms.api_username'), config('sms.api_key'));
    }

    /**
     * Map api status to messsage
     * @param array $status
     * @param Message $msg
     */
    public static function map(array $status, Message $msg) {
        $msg->api_id = $status['api_id'];
        $msg->sender = $status['sender'];
        $msg->receiver = $status['receiver'];
        $msg->status = $status['status'];
        $msg->cost = $status['cost'];
    }

}
