@extends('partials.layout')
@section('content')
    <div class="container mx-auto">
        @include('partials.post-card')
        <section class="mt-6" aria-labelledby="comments-heading">
            <h2 id="comments-heading" class="text-2xl font-bold">@lang('Comments')</h2>
            @if (session('status') === 'comment-created')
                <div role="status" class="alert alert-success my-2">@lang('Your comment has been added.')</div>
            @endif
            @auth
                <form method="POST" action="{{ route('comments.store', $post) }}" class="card bg-base-300 p-4 my-2">
                    @csrf
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend"><label for="comment-body">@lang('Add a comment')</label></legend>
                        <textarea id="comment-body" name="body" class="textarea w-full @error('body') textarea-error @enderror" required>{{ old('body') }}</textarea>
                        @error('body')
                            <p role="alert" class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                    <button type="submit" class="btn btn-primary self-start">@lang('Post comment')</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-link">@lang('Log in to comment')</a>
            @endauth
            @forelse ($post->comments as $comment)
                <article class="card bg-base-300 shadow-sm mt-2">
                    <div class="card-body">
                        <p class="whitespace-pre-line">{{ $comment->body }}</p>
                        <span class="text-base-content/70">{{ $comment->user->name }}</span>
                        <span class="text-base-content/70">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                </article>
            @empty
                <p class="my-4">@lang('No comments yet.')</p>
            @endforelse
        </section>
    </div>
@endsection
