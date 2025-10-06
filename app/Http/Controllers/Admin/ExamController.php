<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return Exam::withCount(['questions', 'users'])->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required|integer|min:1',
            'start_time' => 'required|date',
        ]);
        return Exam::create($request->all());
    }

    public function show(Exam $exam)
    {
        return $exam->load(['questions.category', 'users']);
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required|integer|min:1',
            'start_time' => 'required|date',
        ]);

        $exam->update($request->all());
        return $exam;
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return response()->noContent();
    }

    /* Tambahan: attach soal & peserta */
    public function attachQuestion(Request $request, Exam $exam)
    {
        $request->validate(['question_ids' => 'required|array', 'question_ids.*' => 'exists:questions,id']);
        $exam->questions()->syncWithoutDetaching($request->question_ids);
        return response()->json(['success' => true]);
    }

    public function attachUser(Request $request, Exam $exam)
    {
        $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:users,id']);
        $exam->users()->syncWithoutDetaching($request->user_ids);
        return response()->json(['success' => true]);
    }

    public function members(Exam $exam)
    {
        return $exam->users()->select('id','name')->get();
    }

    public function questions(Exam $exam)
    {
        return $exam->questions()->select('id','category_id')->with('category:id,name')->get();
    }

}
