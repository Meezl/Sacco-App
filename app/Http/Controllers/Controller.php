<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;
        
        public function show404Unless($test, $message = null) {
            if($test == false) {
                \App::abort(404, $message);
            }
        }
        
        /**
         * Show a notification message using gritter
         * @param type $title notification title
         * @param type $content  message
         * @return type
         */
        public function showGritterMsg($title, $content, $sticky = false, $image = '') {
            $msg = array(
                'title' => $title,
                'text' => $content,
                'sticky' => $sticky,
                'class_name' => 'my-sticky-class'
                );
            if($image != '') {
                $msg['image'] = $image;
            }
            $msgs = [];
            if(\Session::has('gritter')) {
                $msgs = json_decode(\Session::get('gritter'), true);
            }
            array_push($msgs, $msg);
            
            \Session::flash('gritter', json_encode($msgs));
        }
       

}
