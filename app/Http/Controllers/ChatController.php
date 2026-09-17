<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Product;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $conversations = Conversation::with(['buyer', 'seller', 'lastMessage'])
            ->where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->latest('updated_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function start(Request $request, Product $product)
    {
        $buyerId = $request->user()->id;
        $sellerId = $product->user_id;

        if ($buyerId === $sellerId) {
            return back()->with('error', 'Anda tidak bisa chat dengan diri sendiri.');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Request $request, Conversation $conversation)
    {
        $userId = $request->user()->id;
        abort_if($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId, 403);

        $conversation->load('messages.sender', 'buyer', 'seller');
        $otherUser = $conversation->otherUser($userId);

        return view('chat.show', compact('conversation', 'otherUser'));
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $userId = $request->user()->id;
        abort_if($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId, 403);

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $conversation->messages()->create([
            'sender_id' => $userId,
            'body' => $validated['body'],
        ]);

        $conversation->touch();

        return back();
    }

    public function fetchMessages(Request $request, Conversation $conversation)
    {
        $userId = $request->user()->id;
        abort_if($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId, 403);

        $messages = $conversation->messages()->with('sender')->get()->map(function ($msg) use ($userId) {
            return [
                'id' => $msg->id,
                'body' => $msg->body,
                'is_mine' => $msg->sender_id === $userId,
                'sender_name' => $msg->sender->name,
                'time' => $msg->created_at->format('H:i'),
            ];
        });

        return response()->json($messages);
    }
}
