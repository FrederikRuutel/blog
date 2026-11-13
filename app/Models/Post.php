<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected $fillable = ['title', 'body'];

    public function snippet(): Attribute
    {
        return Attribute::get(function () {
            return explode("\n\n", (string) $this->body)[0];
        });
    }

    public function displayBody(): Attribute
    {
        return Attribute::get(function () {
            return nl2br(htmlspecialchars((string) $this->body));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedUsers()
    {
        return $this->likes()->with('user');
    }

    public function authHasLiked(): Attribute
    {
        return Attribute::get(function () {
            if (! Auth::check()) {
                return false;
            }

            return $this->likes()->where('user_id', Auth::user()->id)->exists();
        });
    }
}
