<!-- Sidebar -->
<!-- Sidebar -->
<div id="friendsSidebar" style="padding: 1rem">

    <style>
        #closeSidebarBtn {
            padding: 8px 12px;
            font-size: 24px;
        }
    </style>

    <!-- Close Button -->
    <button id="closeSidebarBtn"
        style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 20px; color: #666; cursor: pointer;"
        title="Close Sidebar">
        &times;
    </button>

    <h3 style="margin-bottom: 1rem;">Custom Chats</h3>

    <b style="display: block; margin-bottom: 0.5rem;">Recent Chats</b>

    <div class="chat-history">
        <div class="spinner-border"></div>
    </div>

    <hr style="margin: 1rem 0;">

    <b style="display: block; margin-bottom: 0.5rem;">New Chat</b>

    @foreach (App\Models\User::all() as $user)
        <div class="friend" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
            style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #ddd; padding: 8px; margin-bottom: 8px; border-radius: 5px; cursor: pointer;">
        <img src="{{ $user->profile_photo_path ? url('storage/' . $user->profile_photo_path) : url('assets/profile_photo_placeholder.png') }}"
                alt="{{ $user->name }}"
                style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
        <span style="font-size: 14px;">{{ $user->name }}</span>
        </div>
    @endforeach

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ url('assets/pollinator/pollinator.min.js') }}"></script>
    <script src="{{ url('assets/pollinator/polly.js') }}"></script>
    <script>
        $(document).ready(function () {

            // Close sidebar on button click
            $('#closeSidebarBtn').on('click', function () {
                $('#friendsSidebar').hide(); // Or use fadeOut() if you prefer animation
                $('#overlay').hide(); // Optional: also hide overlay if you use one
            });

            const polling = new PollingManager({
                url: `/chat-history`, // API to fetch data
                delay: 5000, // Poll every 5 seconds
                failRetryCount: 3, // Retry on failure
                onSuccess: (chats) => {
                    $('.chat-history').html("");
                    console.log(chats)

                    chats.forEach(chat => {
                        $('.chat-history').append(`
                            <div class="chat-history-user"
                                data-id="${chat.receiver_id}"
                                data-name="${chat.chat_name}"
                                style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #ddd; padding: 8px; margin-bottom: 8px; border-radius: 5px; cursor: pointer;">

                                <img src="/${chat.receiver_profile_picture ? 'storage/' + chat.receiver_profile_picture : 'assets/profile_photo_placeholder.png'}"
                                    alt="${chat.receiver_name}"
                                    style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">

                                <span style="font-size: 14px;">${chat.receiver_name}</span> -
                                <i>"${chat.latest_message}"</i>
                            </div>
                        `);
                    });
                },
                onError: (error) => {
                    console.error("Error fetching data:", error);
                    // Your custom error handling logic
                }
            });

            // Start polling
            polling.start();

            // $.get('/chat-history', function (chats) {

            // });

            // ✅ Fix: Use event delegation
            $(document).on('click', '.chat-history-user', function () {
                const name = $(this).data('name');
                const friend_id = $(this).data('id'); // ✅ get friend ID

                // Prevent duplicate chatboxes
                if ($('#chatbox-' + friend_id).length > 0) return;

                let auth_id = null; // This is the current logged-in user's ID

                $.get('/user', function (res) {
                    auth_id = res.id;

                    // create new chat

                    $.get('/initialize-chat/' + auth_id + '/' + friend_id, function (res) {

                        const polling = new PollingManager({
                            url: `/fetch-messages/${res}`, // API to fetch data
                            delay: 5000, // Poll every 5 seconds
                            failRetryCount: 3, // Retry on failure
                            onSuccess: (messageResponse) => {
                                console.log(messageResponse);

                                const chatId = messageResponse.chatId;
                                const chatboxSelector = `#chatbox-${chatId}`;

                                // Build messages HTML
                                let messageHtml = '';
                                messageResponse.messages.forEach(msg => {
                                    const isMine = msg.users_id === auth_id;

                                    const timestamp = new Date(msg.created_at).toLocaleString('en-US', {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: '2-digit',
                                        hour12: true,
                                    });

                                    messageHtml += `
                                        <div class="chat-msg ${isMine ? 'mine' : 'other'}">
                                            <div class="bubble">
                                                ${msg.message}
                                                <div class="timestamp">${timestamp}</div>
                                            </div>
                                        </div>
                                    `;
                                });

                                // If chatbox already exists, update messages only
                                if ($(chatboxSelector).length > 0) {
                                    $(`${chatboxSelector} .chatbox-body`).html(messageHtml);
                                } else {
                                    // Otherwise, create a new chatbox
                                    const chatbox = `
                                        <div class="chatbox" id="chatbox-${chatId}">
                                            <div class="chatbox-header">
                                                <span>${messageResponse.chat.name}</span>
                                                <button class="close-chat" title="Close"
                                                        style="background:none;border:none;color:white;font-size:16px;">&times;</button>
                                            </div>

                                            <div class="chatbox-body" id="chatbox-scrollable">
                                                ${messageHtml}
                                                <div id="latest-message"></div>
                                            </div>

                                            <div class="chatbox-footer">
                                                <input type="text" class="chat-input" placeholder="Type a message…">
                                                <button class="send-btn" title="Send">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;

                                    $('#chatboxes-container').append(chatbox);

                                    // 🔥 Scroll to latest message *after* it's in the DOM
                                    setTimeout(() => {
                                        document.querySelector(`#chatbox-${chatId} #latest-message`)?.scrollIntoView({ behavior: 'smooth' });
                                    }, 100); // slight delay ensures DOM is rendered

                                    // Attach event listener to that specific chatbox's send button
                                    $(`#chatbox-${chatId} .send-btn`).click(function () {
                                        let chatInput = $(`#chatbox-${chatId} .chat-input`).val();
                                        let usersId = auth_id;

                                        $.post('/send-chat', {
                                            'message': chatInput,
                                            'chats_id': chatId,
                                            'senders_id': usersId,
                                            "_token": $('meta[name="csrf-token"]').attr('content')
                                        }, function (res) {
                                            console.log(res);
                                            $(`#chatbox-${chatId} .chat-input`).val("");

                                            // Manually append the message (optional, for instant UI update)
                                            $(`#chatbox-${chatId} .chatbox-body`).append(`
                                                <div class="chat-msg mine">
                                                    <div class="bubble">
                                                        ${chatInput}
                                                        <div class="timestamp">just now</div>
                                                    </div>
                                                </div>
                                                <div id="latest-message"></div>
                                            `);

                                            // 🔥 Scroll to the latest message
                                            setTimeout(() => {
                                                document.querySelector(`#chatbox-${chatId} #latest-message`)?.scrollIntoView({ behavior: 'smooth' });
                                            }, 50);
                                        }).fail(err => {
                                            console.log(err);
                                        });
                                    });
                                }
                            },
                            onError: (error) => {
                                console.error("Error fetching data:", error);
                                // Your custom error handling logic
                            }
                        });

                        // Start polling
                        polling.start();

                    }).fail(err => {
                        console.log(err)
                    });

                })
            });
        });
    </script>

</div>
