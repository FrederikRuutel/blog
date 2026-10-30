@extends('partials.layout')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <form action="{{ route('tags.store') }}" method="POST" class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto p-4">
            <h1 class="card-title">@lang('New Tag')</h1>
            @csrf
            <fieldset class="fieldset">
                <legend class="fieldset-legend"><label for="name">@lang('Name')</label></legend>
                <input id="name" name="name" value="{{ old('name') }}" type="text" maxlength="255" required
                    class="input w-full @error('name') input-error @enderror" />
                @error('name')
                    <p role="alert" class="label text-error">{{ $message }}</p>
                @enderror
            </fieldset>
            <div class="card-actions mt-4">
                <button type="submit" class="btn btn-primary">@lang('Create')</button>
                <a href="{{ route('tags.index') }}" class="btn btn-link">@lang('Back')</a>
            </div>
        </form>
    </div>
@endsection
