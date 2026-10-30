@extends('partials.layout')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <a href="{{ route('tags.index') }}" class="btn btn-primary">@lang('Back')</a>
            <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning">@lang('Edit')</a>
            <h1 class="text-2xl font-bold">{{ $tag->name }}</h1>
        </div>
        <p>@lang('Posts'): {{ $posts->total() }}</p>
        {{ $posts->links() }}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
            @forelse ($posts as $post)
                @include('partials.post-card')
            @empty
                <p>@lang('No posts with this tag yet.')</p>
            @endforelse
        </div>
        {{ $posts->links() }}
    </div>
@endsection
