<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Board;
use App\Models\Card;
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

    public function test_new_card_will_be_orderd()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();

        $card1 = Card::factory()->for($board)->create();
        $card2 = Card::factory()->for($board)->create();
        $card3 = Card::factory()->for($board)->create();

        $this->assertEquals(1, $card1->order_column);
        $this->assertEquals(2, $card2->order_column);
        $this->assertEquals(3, $card3->order_column);
    }

    public function test_user_can_order_their_cards()
    {
        $user = User::factory()->create();
        $board = Board::factory()->for($user)->create();
        $card1 = Card::factory()->for($board)->create();
        $card2 = Card::factory()->for($board)->create();
        $card3 = Card::factory()->for($board)->create();

        Card::setNewOrder([2, 3, 1]);

        $this->assertEquals(1, $card2->fresh()->order_column);
        $this->assertEquals(2, $card3->fresh()->order_column);
        $this->assertEquals(3, $card1->fresh()->order_column);
    }

    public function test_each_board_has_its_own_card_order()
    {
        $user = User::factory()->create();
        $board1 = Board::factory()->for($user)->create();
        $board2 = Board::factory()->for($user)->create();
        $card1 = Card::factory()->for($board1)->create();
        $card2 = Card::factory()->for($board1)->create();
        $card3 = Card::factory()->for($board2)->create();
        $card4 = Card::factory()->for($board2)->create();

        Card::setNewOrder([4, 3]);

        $this->assertEquals(1, $card1->fresh()->order_column);
        $this->assertEquals(2, $card2->fresh()->order_column);
        $this->assertEquals(1, $card4->fresh()->order_column);
        $this->assertEquals(2, $card3->fresh()->order_column);
    }
}
