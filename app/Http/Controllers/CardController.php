<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, Board $board)
    {
        if($board->user_id !== $request->user()->id)
        {
            abort('403');
        }

        $validated = $request->validate([
            'title' => 'required|min:3'
        ]);

        return $board->cards()->create($validated);
    }

    public function reorder(Board $board)
    {
        //
    }
}
