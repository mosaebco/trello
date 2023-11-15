<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, Board $board)
    {
        return $board->cards()->create([
            'title' => $request->title,
        ]);
    }
}
