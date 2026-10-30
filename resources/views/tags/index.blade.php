@extends('partials.layout')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold mb-4">@lang('Tags')</h1>
        <a href="{{ route('tags.create') }}" class="btn btn-primary">@lang('New Tag')</a>
        {{ $tags->links() }}
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Posts')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr class="hover:bg-base-300">
                            <td>{{ $tag->name }}</td>
                            <td>{{ $tag->posts_count }}</td>
                            <td>
                                <div class="join">
                                    <a href="{{ route('tags.show', $tag) }}" class="btn btn-info join-item">@lang('View')</a>
                                    <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning join-item">@lang('Edit')</a>
                                    <button form="delete-tag-{{ $tag->id }}" type="submit" class="btn btn-error join-item">@lang('Delete')</button>
                                </div>
                                <form id="delete-tag-{{ $tag->id }}" action="{{ route('tags.destroy', $tag) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">@lang('No tags yet.')</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tags->links() }}
    </div>
@endsection
