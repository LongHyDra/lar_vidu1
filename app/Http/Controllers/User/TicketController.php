<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::where('user_id', Auth::id())->latest()->get();

        return view('user.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('user.tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        SupportTicket::create($validated + ['user_id' => Auth::id()]);

        return redirect()->route('user.tickets.index')->with('success', 'Đã gửi yêu cầu hỗ trợ.');
    }
}
