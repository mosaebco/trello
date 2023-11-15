<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_card()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();
        $this->be($user);

        $response = $this->postJson("board/{$board->id}/card", [
            'title' => 'random title',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('cards', [
            'title' => 'random title',
            'board_id' => $board->id,
        ]);
    }
}
