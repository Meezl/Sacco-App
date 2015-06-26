<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Contact;

/**
 * Description of MessageController
 *
 * @author jameskmb
 */
class MessageController extends Controller {

    const MESSAGES_PER_PAGE = 20;

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

        //for correct counting
        \DB::transaction(function() use ($msg) {
            $response = MessageHelper::decode($msg);
            if ($response) {
                $msg->campaign_id = $response->campaign_id;
                $msg->save();
            }
        });
        return 'Hello World';
    }

    //get inbox
    public function getIndex() {
        $messages = Message::where('sender', '<>', config('sms.system_number'))
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->paginate(self::MESSAGES_PER_PAGE)
                ->setPath(\URL::current());
        self::mapContacts($messages, true);
        return view('messages.index', compact('messages'));
    }

    public function getOutbox() {
        $messages = Message::where('sender', '=', config('sms.system_number'))
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->paginate(self::MESSAGES_PER_PAGE)
                ->setPath(\URL::current());
        self::mapContacts($messages, false);
        return view('messages.outbox', compact('messages'));
    }

    /**
     * Map Messages to contacts
     * @param \Traversable $msgs
     * @param boolean $isSender if true map using message sender otherwise use message receiver
     */
    public static function mapContacts(\Traversable $msgs, $isSender = false) {
        $numbers = [];
        foreach ($msgs as $m) {
            if ($isSender) {
                $numbers[] = $m->sender;
            } else {
                $numbers[] = $m->receiver;
            }
        }

        if (count($numbers) == 0) {
            return;
        }

        $contacts = Contact::whereIn('phone', $numbers)->get();

        foreach ($contacts as $c) {
            foreach ($msgs as $m) {
                if ($isSender && $m->sender == $c->phone) {
                    $m->contact = $c;
                }
                elseif ($m->receiver == $c->phone) {
                    $m->contact = $c;
                }
            }
        }
    }

}
