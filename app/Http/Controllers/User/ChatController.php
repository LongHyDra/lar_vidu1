<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $messageText = trim((string) $request->input('message', ''));

        if ($messageText === '') {
            return response()->json(['error' => 'Nội dung tin nhắn không được để trống.'], 422);
        }

        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return response()->json(['error' => 'Không tìm thấy tài khoản admin.'], 404);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $admin->id,
            'content' => $messageText,
            'is_read' => false,
        ]);

        return response()->json($message->load('sender'));
    }

    public function getMessages()
    {
        $userId = Auth::id();
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return response()->json([]);
        }

        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(
            Message::with(['sender', 'receiver'])
                ->where(function ($query) use ($userId, $admin) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $admin->id);
                })
                ->orWhere(function ($query) use ($userId, $admin) {
                    $query->where('sender_id', $admin->id)
                        ->where('receiver_id', $userId);
                })
                ->orderBy('created_at')
                ->get()
        );
    }
}
