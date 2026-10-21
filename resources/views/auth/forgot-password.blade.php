@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6">
<div class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto">
<div class="card-body">
<h1 class="card-title">@lang('Forgot Password')</h1>
<p>@lang('Enter your email address and we will send you a password reset link.')</p>
@if (session('status'))
    <div role="status" class="alert alert-success">{{ session('status') }}</div>
@endif
<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
@csrf
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="email">@lang('Email')</label></legend>
    <input id="email" name="email" type="email" class="input w-full @error('email') input-error @enderror" value="{{ old('email') }}" required autocomplete="username" />
    @error('email')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<button type="submit" class="btn btn-primary">@lang('Email Password Reset Link')</button>
</form>
<a href="{{ route('login') }}" class="btn btn-link">@lang('Back to login')</a>
</div>
</div>
</div>
@endsection
