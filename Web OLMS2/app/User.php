<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRole;

    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    protected $dates = [
        'created_at','updated_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'pivot', 
    ];

    public function owner() {
        return $this->hasMany(Subject::class, 'owner_id');
    }

    public function subject() {
        return $this->BelongsToMany(Subject::class)->withTimestamps();
    }

    public function activity() {
        return $this->hasMany(Activity::class);
    }
    
}
