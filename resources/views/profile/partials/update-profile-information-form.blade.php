<section class="card bg-base-300 shadow-sm" aria-labelledby="profile-heading">
<div class="card-body">
<h2 id="profile-heading" class="card-title">@lang('Profile Information')</h2>
<p>@lang('Update your name and email address.')</p>
<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
@csrf
</form>
<form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
@csrf
@method('patch')
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="profile-name">@lang('Name')</label></legend>
    <input id="profile-name" name="name" type="text" class="input w-full @error('name') input-error @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name" />
    @error('name')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="profile-email">@lang('Email')</label></legend>
    <input id="profile-email" name="email" type="email" class="input w-full @error('email') input-error @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username" />
    @error('email')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <p>@lang('Your email address is unverified.')</p>
    <button type="submit" form="send-verification" class="btn btn-link">@lang('Resend Verification Email')</button>
@endif
@if (session('status') === 'verification-link-sent')
    <div role="status" class="alert alert-success">@lang('A new verification link has been sent to your email address.')</div>
@endif
@if (session('status') === 'profile-updated')
    <div role="status" class="alert alert-success">@lang('Profile updated.')</div>
@endif
<button type="submit" class="btn btn-primary">@lang('Save')</button>
</form>
</div>
</section>
