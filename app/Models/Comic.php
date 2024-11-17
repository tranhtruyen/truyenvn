<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comics';

    protected $fillable = [
        'name',
        'origin_name',
        'slug',
        'content',
        'status',
        'thumbnail',
        'created_at',
        'updated_at',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'comic_categories', 'comic_id', 'category_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'comic_id', 'id')->orderBy('chapter_number');
    }

    public function daily()
    {
        return $this->hasMany(DailyRank::class, 'comic_id', 'id')->orderBy('total_views', 'desc');
    }

    public function weekly()
    {
        return $this->hasMany(WeeklyRank::class, 'comic_id', 'id')->orderBy('total_views', 'desc');
    }

    public function monthly()
    {
        return $this->hasMany(MonthlyRank::class, 'comic_id', 'id')->orderBy('total_views', 'desc');
    }

    public function author()
    {
        return $this->belongsToMany(Author::class, 'author_comic', 'id_comic', 'id_author');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'comic_id', 'id');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'comic_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comic_id', 'id')->with('replies', 'user')->orderBy('created_at', 'desc');
    }
}
