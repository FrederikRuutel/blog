@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6">
<div class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto">
<div class="card-body">
<h1 class="card-title">@lang('Reset Password')</h1>
<form method="POST" action="{{ route('password.store') }}" class="space-y-4">
@csrf
<input type="hidden" name="token" value="{{ $request->route('token') }}">
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="email">@lang('Email')</label></legend>
    <input id="email" name="email" type="email" class="input w-full @error('email') input-error @enderror" value="{{ old('email', $request->email) }}" required autocomplete="username" />
    @error('email')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="password">@lang('Password')</label></legend>
    <input id="password" name="password" type="password" class="input w-full @error('password') input-error @enderror" required autocomplete="new-password" />
    @error('password')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="password_confirmation">@lang('Confirm Password')</label></legend>
    <input id="password_confirmation" name="password_confirmation" type="password" class="input w-full @error('password_confirmation') input-error @enderror" required autocomplete="new-password" />
    @error('password_confirmation')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<button type="submit" class="btn btn-primary">@lang('Reset Password')</button>
</form>
</div>
</div>
</div>
@endsection
