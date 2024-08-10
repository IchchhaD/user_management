<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Comment;
use App\Models\User;

class Post extends Model
{
    protected $table = "posts";

    protected $fillable = ['title','content','user_id'];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
