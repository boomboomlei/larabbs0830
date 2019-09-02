<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    public  function topic(){
    	return $this->belongstO(Topic::class);
    }
    public  function user(){
    	return $this->belongsTo(User::class);
    }
}
