<?php

namespace App\Http\Controllers;

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
}
