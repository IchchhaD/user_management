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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
