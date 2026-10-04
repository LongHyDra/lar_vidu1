<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        return view('admin.chat.index');
    }

    public function getUsers()
    {
        $adminId = Auth::id();
        $conversationMessages = Message::where(function ($query) use ($adminId) {
            $query->where('sender_id', $adminId);
        })->orWhere(function ($query) use ($adminId) {
            $query->where('receiver_id', $adminId);
        })->get(['sender_id', 'receiver_id', 'is_read']);
        $conversationUserIds = $conversationMessages
            ->flatMap(fn (Message $message) => [$message->sender_id, $message->receiver_id])
            ->reject(fn (int $userId) => $userId === $adminId)
            ->unique();
        $unreadCounts = $conversationMessages
            ->filter(fn (Message $message) => $message->receiver_id === $adminId && ! $message->is_read)
            ->countBy('sender_id');

        return User::where('id', '!=', $adminId)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($unreadCounts, $conversationUserIds) {
                $user->unread_count = $unreadCounts->get($user->id, 0);
                $user->has_conversation = $conversationUserIds->contains($user->id);
                return $user;
            });
    }

    public function getMessages(int $userId)
    {
        $adminId = Auth::id();
        Message::where('sender_id', $userId)
            ->where('receiver_id', $adminId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Message::with(['sender', 'receiver'])
            ->where(function ($query) use ($userId, $adminId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $adminId);
            })
            ->orWhere(function ($query) use ($userId, $adminId) {
                $query->where('sender_id', $adminId)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'min:1'],
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->user_id,
            'content'     => trim($request->message),
            'is_read'     => false,
        ]);

        return response()->json($message->load('sender'));
    }
}
