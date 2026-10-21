@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6">
<div class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto">
<div class="card-body">
<h1 class="card-title">@lang('Confirm Password')</h1>
<p>@lang('This is a secure area of the application. Please confirm your password before continuing.')</p>
<form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
@csrf
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="password">@lang('Password')</label></legend>
    <input id="password" name="password" type="password" class="input w-full @error('password') input-error @enderror" required autocomplete="current-password" />
    @error('password')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<button type="submit" class="btn btn-primary">@lang('Confirm')</button>
</form>
</div>
</div>
</div>
@endsection
