<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_ticket_and_admin_can_reply(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($customer)
            ->post(route('user.tickets.store'), [
                'subject' => 'Cần hỗ trợ đơn hàng',
                'message' => 'Tôi muốn kiểm tra trạng thái đơn.',
            ])
            ->assertRedirect(route('user.tickets.index'));

        $ticket = SupportTicket::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.tickets.index'))
            ->assertOk()
            ->assertSee('Cần hỗ trợ đơn hàng');

        $this->put(route('admin.tickets.update', $ticket), [
            'status' => 'resolved',
            'admin_reply' => 'Cửa hàng đã kiểm tra và phản hồi bạn.',
        ])->assertRedirect();

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'resolved',
            'admin_reply' => 'Cửa hàng đã kiểm tra và phản hồi bạn.',
        ]);
    }
}
