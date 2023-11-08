<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_user_can_create_a_board(): void
    {
        $user = User::factory()->create();
        $this->be($user);


        $response = $this->postJson('/board', [
            'title' => 'My board',
            'description' => 'Something about my board',
        ]);


        $response->assertCreated();
        $this->assertDatabaseHas('boards', [
            'title' => 'My board',
            'description' => 'Something about my board',
            'user_id' => $user->id,
        ]);
    }


    public function test_guest_cannot_creat_a_board() 
    {
        $response = $this->postJson('/board', [
            'title' => 'My board',
            'description' => 'something in my board',
        ]);


        $response->assertUnauthorized();
    }

    public function test_user_can_create_a_board_without_description()
    {
        $user = User::factory()->create();
        $this->be($user);


        $respons = $this->postJson('board', [
            'title' => 'My board',
        ]);

        $respons->assertCreated();
        $this->assertDatabaseHas('boards', [
            'title' => 'My board',
        ]);
    }


    public function test_board_title_is_required()
    {
        $user = User::factory()->create();
        $this->be($user);

        $respons = $this->postJson('board', [
            'description' => 'something in my board',
        ]);

        $respons->assertJsonValidationErrorFor('title');
    }
}
