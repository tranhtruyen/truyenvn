<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'content',
        'user_id',
        'comic_id',
        'story_id',
        'chapter_id',
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class, 'comment_id', 'id')->with('user')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
