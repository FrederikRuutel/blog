<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'images', 'tags', 'category', 'likedUsers.user'])->withCount('comments', 'likes')->latest()->orderByDesc('id')->simplePaginate(16);

        return view('welcome', compact('posts'));
    }

    public function buttons()
    {
        return view('buttons');
    }

    public function post(Post $post)
    {
        $post->load(['user', 'images', 'tags', 'category', 'comments.user', 'likedUsers.user'])->loadCount('comments', 'likes');

        return view('post', compact('post'));
    }

    public function user(User $user)
    {
        $posts = $user->posts()->with(['images', 'user', 'tags', 'category', 'likedUsers.user'])->withCount('comments', 'likes')
            ->latest()->orderByDesc('id')->simplePaginate(16);

        return view('welcome', compact('posts', 'user'));
    }

    public function follow(User $user)
    {
        if ($user->authHasFollowed) {
            $user->followers()->detach(Auth::user());
        } else {
            $user->followers()->syncWithoutDetaching([Auth::id()]);
        }

        return redirect()->back();
    }

    public function like(Post $post)
    {
        if ($post->authHasLiked) {
            $post->likes()->where('user_id', Auth::id())->delete();
        } else {
            $like = new Like;
            $like->post()->associate($post);
            $like->user()->associate(Auth::user());
            $like->save();
        }

        return redirect()->back();
    }

    public function feed()
    {
        $posts = Auth::user()->feed()->with(['images', 'user', 'tags', 'category', 'likedUsers.user'])
            ->withCount('comments', 'likes')->latest()->orderByDesc('id')->simplePaginate(16);

        return view('welcome', compact('posts'));
    }
}
