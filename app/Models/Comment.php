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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
