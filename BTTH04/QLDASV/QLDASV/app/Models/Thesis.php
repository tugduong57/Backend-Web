<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    // Định nghĩa các cột có thể điền (fillable)
    protected $fillable = ['title', 'student_id', 'program', 'supervisor', 'abstract', 'submission_date', 'defense_date'];

    // Định nghĩa mối quan hệ với Student (thesis thuộc về một sinh viên)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
