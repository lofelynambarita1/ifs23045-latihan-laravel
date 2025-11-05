<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.app.home');
    }

    public function transactions()
    {
        return view('pages.app.transactions');
    }

    public function todoDetail($todo_id)
    {
        $todo = Todo::where('user_id', auth()->user()->id)
            ->where('id', $todo_id)
            ->firstOrFail();

        return view('pages.app.todos.detail', compact('todo'));
    }
}