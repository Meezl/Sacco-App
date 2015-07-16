<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Validators\PhoneValidator;
use App\Models\Message;

/**
 * Description of ContactController
 *
 * @author jameskmb
 */
class ContactController extends Controller {

    const CONTACTS_PER_PAGE = 20;

    public function getIndex() {
        $contacts = Contact::orderBy('first_name')
                ->paginate(self::CONTACTS_PER_PAGE)
                ->setPath(\URL::current());
        return view('contacts.index', compact('contacts'));
    }

    public function getNew($id = null) {
        $contact = $this->retrieve($id);
        return view('contacts.new', compact('contact'));
    }

    /**
     * Create/Update a contact
     * @param int $id id of contact to edit
     */
    public function postNew($id = null) {
        $contact = $this->retrieve($id);

        $data = \Input::all();
        $rules = array(
            'phone_number' => 'required|kmobile',
            'first_name' => 'required',
            'last_name' => 'required',
        );
        $validator = \Validator::make($data, $rules, PhoneValidator::message());
        $this->map($data, $contact);

        if ($validator->fails()) {
            \Session::flash('error', 'Please Correct The higlighted Errors');
            return view('contacts.new', compact('contact'))->withErrors($validator->messages());
        }
        if (strpos($data['phone_number'], '+254') === false) {
            $contact->phone = '+254' . substr($data['phone_number'], 1);
        }

        //prevent duplicates
        $duplicate = Contact::where('phone', '=', $contact->phone)->first();
        if (!is_null($duplicate) && $duplicate->id != $contact->id) {
            \Session::flash('error', 'There already Exists a member with that Phone Number');
            \Session::flash('contact-duplicate', \URL::action('ContactController@getNew', [$duplicate->id]));
            return view('contacts.new', compact('contact'));
        }

        $contact->save();
        \Session::flash('success', 'Contact Successfuly Saved');
        return \Redirect::action('ContactController@getNew');
    }

    public function getDelete($id) {
        $contact = Contact::find($id);
        $this->show404Unless($contact);
        $contact->delete();
        \Session::flash('success', 'Contact for ' . $contact->getFullName() . ' successfuly Deleted');
        return \Redirect::action('ContactController@getIndex');
    }

    public function getTransactions($number) {
        $contact = Contact::where('phone', '=', $number)->first();

        //hide system messages
        if (\Auth::user()->is_admin) {
            $query = Message::where('sender', '=', $number)
                    ->orWhere('receiver', '=', $number);
        } else {
            $query = Message::whereNull('deleted_at')
                    ->where(function($query) use($number) {
                            $query->where('sender', '=', $number)
                            ->orWhere('receiver', '=', $number);
                    });
        }

        $messages = $query
                ->orderBy('created_at', 'desc')
                ->paginate(30)
                ->setPath(\URL::current());

        return view('contacts.transactions', compact('contact', 'messages', 'number'));
    }

    private function map(array $data, Contact $contact) {
        if (array_key_exists('first_name', $data)) {
            $contact->first_name = $data['first_name'];
        }

        if (array_key_exists('last_name', $data)) {
            $contact->last_name = $data['last_name'];
        }

        if (array_key_exists('phone_number', $data)) {
            $contact->phone = $data['phone_number'];
        }
        
        if (array_key_exists('account_no', $data)) {
            $contact->account_no = $data['account_no'];
        }
    }

    /**
     * Retrieve contact
     * @param Contact $id
     */
    private function retrieve($id) {
        if (!is_null($id)) {
            if (preg_match('/^\+254[0-9]{9}$/', $id)) {
                $contact = new Contact();
                $contact->phone = $id;
            } else {
                $contact = Contact::find($id);
                $this->show404Unless($contact);
            }
        } else {
            $contact = new Contact();
        }
        return $contact;
    }

}
