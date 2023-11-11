<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;


class BoardController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
        ]);

        return $request->user()->boards()->create($validated);
    }

    public function show(Board $board)
    {
        return $board;
    }

    public function update(Request $request, Board $board)
    {
        if($board->user_id !== $request->user()->id)
        {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'nullable|min:3',
            'description' => 'nullable',
        ]);

        $board->update($validated);
        
        return $board;
    }

    public function destroy(Board $board, Request $request)
    {
        if($board->user_id !== $request->user()->id)
        {
            abort(403);
        }
        
        $board->delete();
    }
}
