<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with('messages', 'userOne', 'userTwo')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    public function start(User $user)
    {
        $auth = Auth::user();
        $conversation = Conversation::where(function ($q) use ($auth, $user) {
            $q->where('user_one_id', $auth->id)->where('user_two_id', $user->id);
        })->orWhere(function ($q) use ($auth, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $auth->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $auth->id,
                'user_two_id' => $user->id,
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('conversation', 'messages'));
    }

    public function store(Conversation $conversation, Request $request)
    {
        $user = Auth::user();
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $data['message'],
            'is_read' => false,
        ]);
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'message' => e($message->message),
                'created_at' => $message->created_at->toDateTimeString(),
                'created_at_formatted' => $message->created_at->format('d.m.Y H:i'),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function updates(Conversation $conversation, Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $after = $request->query('after');
        $query = $conversation->messages()->orderBy('created_at');
        if ($after) {
            $query->where('created_at', '>', $after);
        }

        $messages = $query->get();

        // mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $data = $messages->map(function (Message $msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'message' => e($msg->message),
                'created_at' => $msg->created_at->toDateTimeString(),
                'created_at_formatted' => $msg->created_at->format('d.m.Y H:i'),
            ];
        });

        return response()->json($data);
    }
}
