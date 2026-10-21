@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6">
<div class="card bg-base-300 shadow-sm w-full max-w-xl mx-auto">
<div class="card-body">
<h1 class="card-title">@lang('Verify Email')</h1>
<p>@lang('Please verify your email address using the link we emailed to you. You can request another link below.')</p>
@if (session('status') === 'verification-link-sent')
    <div role="status" class="alert alert-success">@lang('A new verification link has been sent to your email address.')</div>
@endif
<form method="POST" action="{{ route('verification.send') }}">
@csrf
<button type="submit" class="btn btn-primary">@lang('Resend Verification Email')</button>
</form>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="btn btn-link">@lang('Log Out')</button>
</form>
</div>
</div>
</div>
@endsection
