<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExamUserController extends Controller
{
    // Dashboard (opsional redirect)
    public function dashboard()
    {
        $ranking = ExamAttempt::with(['user'])
            ->whereNotNull('finished_at')
            ->selectRaw('user_id, exam_id, MAX(score) as score, MAX(finished_at) as finished_at')
            ->groupBy('user_id', 'exam_id')
            ->orderByDesc('score')
            ->take(10) // ambil top 10
            ->get();
    
        return view('user.dashboard', compact('ranking'));
    }
    

    // List ujian dengan card
    public function list()
    {
        $userId = Auth::user()->id;
    
        $exams = Exam::withCount('questions')
            ->with([
                'attempts' => fn($q) => $q
                    ->where('user_id', $userId)
                    ->orderByDesc('finished_at')
            ])
            ->latest()
            ->get()
            ->map(function ($exam) use ($userId) {
                // Cek apakah user sudah pernah mengerjakan ujian ini
                $lastAttempt = $exam->attempts->first();
    
                $exam->has_taken = $lastAttempt !== null;
                $exam->last_score = $lastAttempt->score ?? null;
                $exam->last_finished_at = $lastAttempt->finished_at ?? null;
                $exam->attempt_count = $exam->attempts->count();
    
                return $exam;
            });
    
        return view('user.ujian-list', compact('exams'));
    }
    

    // Halaman kerjakan soal
    public function kerjakan(Exam $exam)
    {
        // Cek sudah pernah ambil
        // if ($exam->results()->where('user_id', Auth::user()->id)->exists()) {
        //     return redirect()->route('students.ujian.pembahasan', $exam);
        // }

        // $questions = $exam->questions;

        // // Buat row hasil awal
        // $result = $exam->results()->create([
        //     'user_id' => Auth::user()->id,
        //     'answers' => [],
        //     'started_at' => Carbon::now(),
        // ]);

        // return view('students.ujian-kerjakan', compact('exam', 'questions', 'result'));

        // Buat attempt baru setiap kali mulai ujian
        $attempt = ExamAttempt::create([
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'started_at' => now(),
        ]);
    
        session(['attempt_id' => $attempt->id]); // simpan di session
    
        $questions = $exam->questions;
    
        return view('user.ujian-kerjakan', compact('exam', 'questions', 'attempt'));
    }

    public function selesai(Request $request, Exam $exam)
    {
        $attemptId = session('attempt_id');

        if (!$attemptId) {
            return redirect()->route('students.dashboard')->with('error', 'Tidak ada sesi ujian aktif.');
        }

        $attempt = ExamAttempt::findOrFail($attemptId);

        $jawabanUser = $request->input('jawaban', []);
        $score = 0;

        foreach ($exam->questions as $q) {
            $jawaban = $jawabanUser[$q->id] ?? null;
            $benar = $jawaban !== null && $q->correct_answer == $jawaban;
            ExamAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $q->id,
                'answer' => $jawaban,
                'is_correct' => $benar,
            ]);

            if ($benar) $score++;
        }

        $attempt->update([
            'score' => round(($score / $exam->questions->count()) * 100),
            'finished_at' => now(),
        ]);

        session()->forget('attempt_id'); // hapus sesi agar bisa buat attempt baru

        // return redirect()->route('students.dashboard')->with('success', 'Ujian selesai! Nilai: '.$attempt->score);
        return redirect()->route('students.ujian.pembahasan', $exam->id);
    
    }

    // Pembahasan
    public function pembahasan(Exam $exam)
    {
        // Ambil attempt terakhir user yang sudah selesai
        $attempt = $exam->attempts()
            ->where('user_id', Auth::user()->id)
            ->whereNotNull('finished_at')
            ->latest('finished_at')
            ->with(['answers.question'])
            ->firstOrFail();

        // Ambil semua soal di ujian ini
        $questions = $exam->questions;
        return view('user.ujian-pembahasan', compact('exam', 'questions', 'attempt'));
    }

    // ============================
    // RANKING GLOBAL (semua ujian)
    // ============================
    public function perangkingan()
    {
        $ranking = ExamAttempt::with('user')
            ->selectRaw('
                user_id,
                MAX(score) as max_score,
                COUNT(DISTINCT exam_id) as exam_count,
                COUNT(*) as attempt_count,
                MAX(finished_at) as last_attempt_at
            ')
            ->whereNotNull('finished_at')
            ->groupBy('user_id')
            ->orderByDesc('max_score')
            ->get();

        return view('user.perangkingan', compact('ranking'));
    }


    // ============================
    // RANKING PER UJIAN
    // ============================
    public function rangkingByExam(Exam $exam)
    {
        $ranking = $exam->attempts()
            ->with('user')
            ->whereNotNull('finished_at')
            ->orderByDesc('score')
            ->get();

        return view('user.ujian-rangking', compact('exam', 'ranking'));
    }
}