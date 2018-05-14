<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name', 'code', 'user_id'
    ];

    protected $dates = [
        'created_at','updated_at'
    ];

    public function section() {
        return $this->hasMany(Section::class);
    }

    public function owner() {
        return $this->BelongsTo(User::class);
    }

    public function user() {
        return $this->BelongsToMany(User::class)->withTimestamps();
    }

    public function activity() {
        return $this->hasMany(Activity::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
    public function activitys() {
        return $this->BelongsToMany(Activity::class);
    }
   
}