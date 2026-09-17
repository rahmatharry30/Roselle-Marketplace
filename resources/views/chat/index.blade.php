@extends('layout')

@section('title', 'Pesan')

@section('content')
    <h1 class="rose-page-title">Pesan</h1>

    <div class="rose-chat-list">
        @forelse ($conversations as $conv)
            @php $other = $conv->otherUser(auth()->id()); @endphp
            <a href="{{ route('chat.show', $conv) }}" class="rose-chat-item">
                <div class="rose-chat-avatar">
                    @if ($other->avatar ?? false)
                        <img src="{{ $other->avatar_url }}" alt="{{ $other->name }}">
                    @else
                        👤
                    @endif
                </div>
                <div class="rose-chat-info">
                    <div class="rose-chat-name">{{ $other->name }}</div>
                    <div class="rose-chat-preview">{{ $conv->lastMessage->body ?? 'Belum ada pesan' }}</div>
                </div>
            </a>
        @empty
            <div class="rose-empty">Belum ada percakapan. Mulai chat dari halaman produk ya 🎀</div>
        @endforelse
    </div>
@endsection
