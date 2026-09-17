<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(20);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function update(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'admin_reply' => ['nullable', 'string', 'max:5000'],
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Đã cập nhật ticket hỗ trợ.');
    }
}
