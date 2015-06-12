<?php
namespace App\Http\Controllers;

/**
 * Description of DashBoardController
 *
 * @author James
 */
class DashBoardController  extends Controller{
    
    public function getIndex() {
        return view('dashboard');
    }
}
