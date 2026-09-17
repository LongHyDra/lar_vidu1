<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getUsers()
    {
        $adminId = Auth::id();
        $userIds = Message::where('receiver_id', $adminId)
            ->orWhere('sender_id', $adminId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Message $message) use ($adminId) {
                return $message->sender_id === $adminId ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->values()
            ->toArray();

        return User::whereIn('id', $userIds)
            ->where('id', '!=', $adminId)
            ->select('id', 'name', 'email')
            ->get()
            ->map(function (User $user) use ($adminId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $adminId)
                    ->where('is_read', false)
                    ->count();
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
