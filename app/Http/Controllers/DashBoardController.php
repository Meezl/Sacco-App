<?php
namespace App\Http\Controllers;

use App\Models\Message;

/**
 * Description of DashBoardController
 *
 * @author James
 */
class DashBoardController  extends Controller{
    
    public function getIndex() {
        $stats = \DB::table('messages')
                ->select(\DB::raw('count(*) as count, ucase(text) as text'))
                ->where('text', '=', 'yes')
                ->orWhere('text', '=', 'no')
                ->groupBy('text')
                ->get();
        return view('dashboard', compact('stats'));
    }    
}
