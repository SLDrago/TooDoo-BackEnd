<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task',
        'category',
        'status',
        'priority',
        'due_date',
        'user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
