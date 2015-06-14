<?php
namespace App\Http\Controllers;

use App\Models\Message;
/**
 * Description of MessageController
 *
 * @author jameskmb
 */
class MessageController {
    
    /**
     * Handle Incoming messages from urlCallback
     */
    public function postHandleCallback() {
        $data = \Input::all();
        $msg = new Message();
        $msg->text = trim($data['text']);
        $msg->api_id = $data['id'];
        $msg->sender = $data['from'];
        $msg->receiver = $data['to'];
        $msg->save();

        $response = MessageHelper::decode($msg);
        if ($response) {
            $response->save();
        }
    }

}
