<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'body', 'user_id'
    ];

    public function author(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
