<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('chat-room', function ($user) {
    return $user;
});

Broadcast::channel('chat{id}', function ($user, $userId) {
    return $user->id == $userId;
});

Broadcast::channel('group-chat.{groupId}', function ($user, $groupId) {
    return $user->groups()->where('group_id', $groupId)->exists();
});