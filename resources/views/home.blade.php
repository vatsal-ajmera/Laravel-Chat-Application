@extends('layouts.app')

@section('content')
    <div class="chat-sidebar">
        <h2>Active Users</h2>
        <ul id="user-list">

        </ul>
        <ul id="group-list">

        </ul>
    </div>

    <div class="chat-window">
        <div class="chat-header">
            <h3><span id="chat-with"></span></h3>
        </div>
        <div class="chat-messages" id="chat-messages">
            <!-- Chat messages will be appended here -->
        </div>
        <div class="chat-footer">
            <input type="text" id="message-input" placeholder="Type a message...">
            <button type="submit" id="send-message">Send</button>
        </div>
    </div>

    <script type="module">
        const userId = {{ auth()->user()->id }};
        let selectedUser = null;
        let currentPrivateChannel = null;
        let selectedGroup = null;
        loadGroups()

        // Utility to create a user list item
        function createUserListItem(user) {
            const listItem = document.createElement('li');
            listItem.textContent = userId === user.id ? "Self Chat" : user.name;
            listItem.setAttribute('data-user-id', user.id);
            listItem.classList.add('user-item');
            return listItem;
        }

        // Update chat messages
        function updateChatMessages(event, isPrivate = false) {
            // console.log(event)
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message');
            messageDiv.classList.add(event.sender.id === userId ? 'user' : 'receiver');
            messageDiv.innerHTML = `
             <div class="message-bubble">
                <div class="message-author">${event.sender.name}</div>
                <div class="message-content">${event.message}</div>
            </div>`;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to bottom
        }

        // Handle user selection
        function selectUser(user) {
            selectedGroup = null;
            selectedUser = user;
            document.getElementById('chat-with').textContent = `Chat with ${user.name}`;
            document.getElementById('chat-messages').innerHTML = ''; // Clear messages for new chat
            updateUnreadCount(user.id, true)

            if (currentPrivateChannel) {
                Echo.leaveChannel(currentPrivateChannel.name);
            }

            currentPrivateChannel = Echo.private(`chat${user.id}`)
                .listen('ChatMessageEvent', (event) => {
                    if (
                        (event.sender.id === userId && event.receiver.id === selectedUser.id) ||
                        (event.receiver.id === userId && event.sender.id === selectedUser.id)
                    ) {
                        updateChatMessages(event, true);
                    }
                });
        }

        function loadGroups() {
            document.addEventListener('DOMContentLoaded', function() {
                axios.get('/my-groups')
                    .then(response => {
                        const groupList = document.getElementById('group-list');
                        groupList.innerHTML = '';

                        response.data.forEach(group => {
                            const listItem = document.createElement('li');
                            listItem.textContent = group.name;
                            listItem.setAttribute('data-group-id', group.id);
                            listItem.classList.add('group-item');
                            listItem.addEventListener('click', () => selectGroup(group));
                            groupList.appendChild(listItem);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching groups:', error);
                    });
            });
        }


        function selectGroup(group) {
            selectedUser = null; // Reset user selection when selecting a group
            selectedGroup = group;
            document.getElementById('chat-with').textContent = `Group: ${group.name}`;
            document.getElementById('chat-messages').innerHTML = '';

            if (currentPrivateChannel) {
                Echo.leaveChannel(currentPrivateChannel.name);
            }
            // Join group channel
            currentPrivateChannel = Echo.channel(`group-chat${group.id}`)
                .listen('ChatMessageEvent', (event) => {
                    updateChatMessages(event);
                });
        }

        // Initialize Echo for presence channel
        Echo.join('chat-room')
            // Provide the list of users who joined this channel
            .here(users => {
                const userList = document.getElementById('user-list');
                userList.innerHTML = '';

                users.forEach(user => {
                    if (user.id != userId) {
                        const listItem = createUserListItem(user);
                        listItem.addEventListener('click', () => selectUser(user));
                        userList.appendChild(listItem);   
                    }
                });
            })
            .joining(user => {
                const userList = document.getElementById('user-list');
                const listItem = createUserListItem(user);
                listItem.addEventListener('click', () => selectUser(user));
                userList.appendChild(listItem);
            })
            .leaving(user => {
                const userList = document.getElementById('user-list');
                const items = [...userList.getElementsByTagName('li')];
                const userItem = items.find(item => item.getAttribute('data-user-id') == user.id);
                if (userItem) userList.removeChild(userItem);
            });

        // Listener for the current user's private channel
        Echo.private(`chat${userId}`)
            .listen('ChatMessageEvent', event => {
                if (event.receiver.id == userId) {
                    updateUnreadCount(event.sender.id)
                }
                if (
                    (event.sender.id === userId && event.receiver.id === selectedUser?.id) ||
                    (event.receiver.id === userId && event.sender.id === selectedUser?.id)
                ) {
                    updateChatMessages(event);
                }
            });

        // Handle sending message
        document.getElementById('send-message').addEventListener('click', () => {
            const message = document.getElementById('message-input').value.trim();
            if (message && (selectedUser || selectedGroup)) {
                // Private chat
                if (selectedGroup === null) {
                    axios.post('/send-message', {
                            message: message,
                            receiver: selectedUser.id
                        })
                        .then(response => {
                            document.getElementById('message-input').value = '';
                        })
                        .catch(error => {
                            console.error("Error sending message:", error);
                        });
                }

                // Group chat
                if (selectedGroup !== null) {
                    axios.post('/send-group-message', {
                            message: message,
                            group_id: selectedGroup.id
                        })
                        .then(response => {
                            document.getElementById('message-input').value = '';
                        })
                        .catch(error => {
                            console.error("Error sending group message:", error);
                        });
                }
            } else {
                alert('Please select a user/group and enter a message.');
            }
        });

        // Example function to update unread message counts
        function updateUnreadCount(userId, markAsRead = false) {
            const userItem = document.querySelector(`.user-item[data-user-id="${userId}"]`);

            if (userItem) {
                let unreadCountElement = userItem.querySelector('.unread-count');
                if (!unreadCountElement) {
                    unreadCountElement = document.createElement('span');
                    unreadCountElement.classList.add('unread-count');
                    userItem.appendChild(unreadCountElement);
                }
                let currentCount;
                if (typeof markAsRead === 'boolean' && markAsRead) {
                    currentCount = 0;
                } else {
                    currentCount = parseInt(unreadCountElement.getAttribute('data-count')) || 0;
                    currentCount++
                }
                unreadCountElement.setAttribute('data-count', currentCount);
                unreadCountElement.textContent = currentCount;
            }
        }
    </script>
@endsection
