@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat Room</h1>

    <div id="active-users">
        <h3>Active Users</h3>
        <ul id="user-list"></ul>
    </div>

    <div id="chat-window" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc;">
        <!-- Chat messages will appear here -->
    </div>

    <textarea id="message" rows="4" cols="50" placeholder="Type your message..."></textarea>
    <button id="send-message">Send</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

@vite('resources/js/app.js')


@endsection
