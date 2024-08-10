<?php

namespace App\Models;

use App\Models\User;
use App\Models\Post;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comments";

    protected $fillable = ['comment','post_id'];

    public $timestamps = true;

    public function posts()
    {
        return $this->belongsTo(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
