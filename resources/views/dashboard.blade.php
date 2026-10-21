@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6">
<div class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto">
<div class="card-body">
<h1 class="card-title">@lang('Dashboard')</h1>
<div role="status" class="alert alert-success">@lang("You're logged in!")</div>
<a href="{{ route('posts.index') }}" class="btn btn-primary">@lang('My Posts')</a>
<a href="{{ route('profile.edit') }}" class="btn btn-link">@lang('Profile')</a>
</div>
</div>
</div>
@endsection
