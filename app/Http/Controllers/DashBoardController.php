<?php

namespace App\Http\Controllers;

use App\Models\Message;

/**
 * Description of DashBoardController
 *
 * @author James
 */
class DashBoardController extends Controller {

    public function getIndex() {
        $stats = \DB::table('messages')
                ->select(\DB::raw('count(*) as count, ucase(text) as text'))
                ->where('created_at', '>', '2015-06-20 00:00:00')
                ->where(function($query) {
                    $query->where('text', '=', 'yes')
                    ->orWhere('text', '=', 'no');
                })
                ->groupBy('text')
                ->get();
        return view('dashboard', compact('stats'));
    }

}
