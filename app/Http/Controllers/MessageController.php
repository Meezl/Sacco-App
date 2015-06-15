<?php
namespace App\Http\Controllers;

use App\Models\Message;
/**
 * Description of MessageController
 *
 * @author jameskmb
 */
class MessageController extends Controller{
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

        MessageHelper::decode($msg);        
    }
    
    //get inbox
    public function getIndex() {
        $messages = Message::where('sender', '<>', config('sms.system_number'))
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->paginate(self::MESSAGES_PER_PAGE)
                ->setPath(\URL::current());
        return view('messages.index', compact('messages'));
    }
    
    public function getOutbox() {
        $messages = Message::where('sender', '=', config('sms.system_number'))
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->paginate(self::MESSAGES_PER_PAGE)
                ->setPath(\URL::current());
        return view('messages.outbox', compact('messages'));
    }

}
