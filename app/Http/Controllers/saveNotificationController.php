<?php

namespace App\Http\Controllers;

use App\Events\PublicNotificationEvent;
use Illuminate\Http\Request;

class saveNotificationController extends Controller
{
    public function saveNotification() {
        $message = 'Hellooo';
        event (new PublicNotificationEvent($message));
        return view('notification');
    }
}
