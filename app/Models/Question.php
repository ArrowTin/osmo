<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question_text', 'options', 'correct_answer', 'explanation', 'category_id'];

    protected $casts = ['options' => 'array'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class);
    }
}
