<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicController extends Controller
{
    public function index() {
        $posts = Post::with(['user', 'images', 'tags'])->withCount('comments')->latest()->orderByDesc('id')->simplePaginate(16);
        return view('welcome', compact('posts'));
    }

    public function buttons() {
        return view('buttons');
    }

    public function post(Post $post) {
        $post->load(['user', 'images', 'tags', 'comments.user'])->loadCount('comments');
        return view('post', compact('post'));
    }
    public function user(User $user)
    {
        $posts = $user->posts()->with(['images', 'user', 'tags'])->withCount('comments')
            ->latest()->orderByDesc('id')->simplePaginate(16);

        return view('welcome', compact('posts'));
    }
}
