@extends('partials.layout')
@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold">@lang('Profile')</h1>
    @include('profile.partials.update-profile-information-form')
    @include('profile.partials.update-password-form')
    @include('profile.partials.delete-user-form')
</div>
@endsection
