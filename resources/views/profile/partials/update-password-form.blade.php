<section class="card bg-base-300 shadow-sm" aria-labelledby="password-heading">
<div class="card-body">
<h2 id="password-heading" class="card-title">@lang('Update Password')</h2>
<p>@lang('Ensure your account is using a long, random password to stay secure.')</p>
<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
@csrf
@method('put')
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="update-current_password">@lang('Current Password')</label></legend>
    <input id="update-current_password" name="current_password" type="password" class="input w-full @error('current_password', 'updatePassword') input-error @enderror" required autocomplete="current-password" />
    @error('current_password', 'updatePassword')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="update-password">@lang('New Password')</label></legend>
    <input id="update-password" name="password" type="password" class="input w-full @error('password', 'updatePassword') input-error @enderror" required autocomplete="new-password" />
    @error('password', 'updatePassword')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="update-password_confirmation">@lang('Confirm Password')</label></legend>
    <input id="update-password_confirmation" name="password_confirmation" type="password" class="input w-full @error('password_confirmation', 'updatePassword') input-error @enderror" required autocomplete="new-password" />
    @error('password_confirmation', 'updatePassword')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>@if (session('status') === 'password-updated')
    <div role="status" class="alert alert-success">@lang('Password updated.')</div>
@endif
<button type="submit" class="btn btn-primary">@lang('Save')</button>
</form>
</div>
</section>
