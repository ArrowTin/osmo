<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        return Question::with('category:id,name')->latest()->get();
    }

    public function store(Request $request)
    {
        
        if ($request->has('options') && is_string($request->options)) {
            $request->merge(['options' => json_decode($request->options, true)]);
        }

        $request->validate([
            'question_text'   => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            'options'         => 'required|array|min:2',
            'options.*'       => 'required|string',
            'correct_answer'  => 'required|string',
            'explanation'     => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            'category_id'     => 'required|exists:categories,id'
        ]);

        // simpan file pertanyaan
        $questionFile = $request->file('question_text')->store('questions', 'public');

        $explanationFile = null;
        if ($request->hasFile('explanation')) {
            $explanationFile = $request->file('explanation')->store('questions', 'public');
        }

        $question = Question::create([
            'question_text'  => $questionFile,
            'options'        => $request->options,
            'correct_answer' => $request->correct_answer,
            'explanation'    => $explanationFile,
            'category_id'    => $request->category_id,
        ]);

        return response()->json($question->load('category'), 201);
    }

    public function show(Question $question)
    {
        return $question->load('category');
    }

    public function update(Request $request, Question $question)
    {

        if($request->has('options')) {
            $request->merge(['options' => json_decode($request->options, true)]);
        }

        $request->validate([
            'question_text'   => 'sometimes|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            'options'         => 'required|array|min:2',
            'options.*'       => 'required|string',
            'correct_answer'  => 'sometimes|required|string',
            'explanation'     => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            'category_id'     => 'sometimes|required|exists:categories,id'
        ]);

        if ($request->hasFile('question_text')) {
            if ($question->question_text) {
                Storage::disk('public')->delete($question->question_text);
            }
            $question->question_text = $request->file('question_text')->store('questions', 'public');
        }

        if ($request->hasFile('explanation')) {
            if ($question->explanation) {
                Storage::disk('public')->delete($question->explanation);
            }
            $question->explanation = $request->file('explanation')->store('questions', 'public');
        }

        if ($request->has('options')) {
            $question->options = $request->options;
        }

        if ($request->has('correct_answer')) {
            $question->correct_answer = $request->correct_answer;
        }

        if ($request->has('category_id')) {
            $question->category_id = $request->category_id;
        }

        $question->save();

        return response()->json($question->load('category'));
    }

    public function destroy(Question $question)
    {
        if ($question->question_text) {
            Storage::disk('public')->delete($question->question_text);
        }
        if ($question->explanation) {
            Storage::disk('public')->delete($question->explanation);
        }

        $question->delete();
        return response()->noContent();
    }
}
