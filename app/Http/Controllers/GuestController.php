<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function leaderboard()
    {
        $recentAttempts = ExamAttempt::with(['user', 'exam'])
        ->take(10)
        ->orderBy('score','desc')
        ->get();

        return view('guest.leaderboard',compact('recentAttempts'));
    }
}
