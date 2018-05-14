<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section'
    ];

    protected $dates = [
        'created_at','updated_at'
    ];

    public function subject() {
        return $this->BelogsTo(Subject::class);
    }
}
