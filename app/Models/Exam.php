<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'duration', 'start_time', 'end_time'];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
