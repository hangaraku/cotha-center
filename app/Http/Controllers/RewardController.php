<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::orderBy('is_pinned', 'desc')
            ->orderBy('order_number', 'asc')
            ->paginate(12);
        return view('rewards.index', compact('rewards'));
    }
} 