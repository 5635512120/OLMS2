<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'subject_id'
    ];

    protected $dates = [
        'created_at','updated_at'
    ];

    public function user() {
        return $this->BelongsTo(User::class);
    }
    
    public function subject() {
        return $this->BelongsTo(Subject::class);
    }
}
