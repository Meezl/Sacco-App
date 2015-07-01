<?php

namespace App\Http\Controllers;

use App\Models\Meeting;

/**
 * Description of DashBoardController
 *
 * @author James
 */
class DashBoardController extends Controller {

    public function getIndex($id = false) {
        if ($id == false) {
            $meeting = Meeting::orderBy('created_at', 'desc')->first();
        }
        else {
            $meeting = Meeting::find($id);
            $this->show404Unless($meeting);
        }
        
        $stats = $meeting->getStats();
         $colors = config('sms.colors');
         
        return view('dashboard', compact('meeting', 'colors', 'stats'));
    }
    
    public function postNewMeeting() {
        $rules = [
          'start'   => 'required|date',
           'end' => 'required|date'
        ];
        
        $data = \Input::all();
        $meeting = new Meeting();
        $this->map($data, $meeting);
        
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
           return view('dashboard', compact('meeting'))->withErrors($validator->messages());
        }
        
        $meeting->save();
        \Session::flash('success', 'New Meeting Successfuly Created');
        
        return \Redirect::action('DashBoardController@getIndex');
    }
    
    private static function map(array $data, Meeting $meeting) {
        if (array_key_exists('start', $data)) {
            $meeting->start = $data['start'];
        }
        
        if (array_key_exists('end', $data)) {
            $meeting->end = $data['end'];
        }
    }

}
