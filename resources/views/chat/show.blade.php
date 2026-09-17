@extends('layout')

@section('title', 'Chat dengan ' . $otherUser->name)

@section('content')
    <h1 class="rose-page-title">Chat dengan {{ $otherUser->name }}</h1>

    <div class="rose-chat-window">
        <div class="rose-chat-header">{{ $otherUser->name }}</div>

        <div class="rose-chat-body" id="chatBody">
            @foreach ($conversation->messages as $msg)
                <div class="rose-bubble {{ $msg->sender_id === auth()->id() ? 'rose-bubble-mine' : 'rose-bubble-theirs' }}">
                    {{ $msg->body }}
                    <span class="rose-bubble-time">{{ $msg->created_at->format('H:i') }}</span>
                </div>
            @endforeach
        </div>

        <form id="chatForm" class="rose-chat-form">
            @csrf
            <input type="text" name="body" id="chatInput" placeholder="Tulis pesan..." autocomplete="off" required>
            <button type="submit">Kirim</button>
        </form>
    </div>

    <script>
        const chatBody = document.getElementById('chatBody');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const currentUserId = {{ auth()->id() }};
        const sendUrl = "{{ route('chat.send', $conversation) }}";
        const fetchUrl = "{{ route('chat.fetch', $conversation) }}";

        chatBody.scrollTop = chatBody.scrollHeight;

        function renderMessages(messages) {
            chatBody.innerHTML = '';
            messages.forEach(msg => {
                const bubble = document.createElement('div');
                bubble.className = 'rose-bubble ' + (msg.is_mine ? 'rose-bubble-mine' : 'rose-bubble-theirs');
                bubble.innerHTML = msg.body.replace(/</g, '&lt;') + '<span class="rose-bubble-time">' + msg.time + '</span>';
                chatBody.appendChild(bubble);
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        async function pollMessages() {
            try {
                const res = await fetch(fetchUrl);
                const messages = await res.json();
                renderMessages(messages);
            } catch (err) {
                console.error('Gagal ambil pesan:', err);
            }
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const body = chatInput.value.trim();
            if (!body) return;

            chatInput.value = '';

            await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ body: body }),
            });

            pollMessages();
        });

        setInterval(pollMessages, 3000);
    </script>
@endsection
