<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalExams = Exam::count();
        $totalAttempts = ExamAttempt::count();
        $avgScore = ExamAttempt::avg('score') ?? 0;

        // Ambil 5 ujian terbaru dengan jumlah attempt
        $recentExams = Exam::withCount('attempts')
            ->latest()
            ->take(5)
            ->get();

        // Ambil 5 attempt terbaru
        $recentAttempts = ExamAttempt::with(['user', 'exam'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExams',
            'totalAttempts',
            'avgScore',
            'recentExams',
            'recentAttempts'
        ));
    }
}
