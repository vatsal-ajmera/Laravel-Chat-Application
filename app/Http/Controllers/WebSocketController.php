<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class WebSocketController extends Controller
{
    public function sendMessage(Request $request)
    {
        broadcast(new ChatMessageEvent($request->message, auth()->user()->name));
        return response()->json(['status' => 'Message sent']);
    }

    public function send_message(Request $request)
    {   
        $sender = auth()->user();
        $receiver = User::find($request->receiver);

        if ($receiver) {
            broadcast(new ChatMessageEvent($request->message, $sender, $receiver));
        }

        return response()->json(['status' => 'Message Sent']);
    }

    public function send_group_message(Request $request)
    {
        $sender = auth()->user();
        $group = Group::find($request->group_id);
        if ($group) {
            broadcast(new ChatMessageEvent($request->message, $sender, null, $group->id));
        }
        return response()->json(['status' => 'Group message sent']);
    }

    function my_groups() {
        $user = auth()->user();
        $groups = $user->groups;
        return response()->json($groups);
    }

}
