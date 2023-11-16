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

    public function test_user_cannot_create_boards_on_other_users_boards()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $board = Board::factory()->for($user2)->create();
        $this->be($user1);

        $response = $this->postJson("board/{$board->id}/card", [
            'title' => 'random title',
        ]);

        $response->assertForbidden();
    }

    public function test_title_is_required_for_creating_a_card()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();
        $this->be($user);

        $response = $this->postJson("board/{$board->id}/card", [
            'title' => ''
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_guest_cannot_creat_card()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();

        $response = $this->postJson("board/{$board->id}/card", [
            'title' => 'random title',
        ]);

        $response->assertUnauthorized();
    }

    public function test_card_title_cannot_be_less_than_three_characters()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();
        $this->be($user);

        $response = $this->postJson("board/{$board->id}/card", [
            'title' => 'n'
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    
}
