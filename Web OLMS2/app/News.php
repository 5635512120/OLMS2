<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public function subject()
	{
	    return $this->belongsTo(subject::class);
	}
}
