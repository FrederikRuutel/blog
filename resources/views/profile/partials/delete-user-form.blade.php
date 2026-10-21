<section class="card bg-base-300 shadow-sm" aria-labelledby="delete-heading">
<div class="card-body">
<h2 id="delete-heading" class="card-title">@lang('Delete Account')</h2>
<p>@lang('Deleting your account is permanent. Your comments will be deleted. Your posts will remain without an author.')</p>
<div class="card-actions">
<button type="button" class="btn btn-error" onclick="document.getElementById('confirm-user-deletion').showModal()">@lang('Delete Account')</button>
</div>
<dialog id="confirm-user-deletion" class="modal" aria-labelledby="delete-confirmation-heading">
<div class="modal-box">
<h3 id="delete-confirmation-heading" class="text-lg font-bold">@lang('Are you sure you want to delete your account?')</h3>
<p>@lang('Enter your current password to confirm permanent account deletion.')</p>
<form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
@csrf
@method('delete')
<fieldset class="fieldset">
    <legend class="fieldset-legend"><label for="delete-password">@lang('Password')</label></legend>
    <input id="delete-password" name="password" type="password" class="input w-full @error('password', 'userDeletion') input-error @enderror" required autocomplete="current-password" />
    @error('password', 'userDeletion')
        <p role="alert" class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
<div class="modal-action">
<button type="button" class="btn" onclick="document.getElementById('confirm-user-deletion').close()">@lang('Cancel')</button>
<button type="submit" class="btn btn-error">@lang('Delete Account')</button>
</div>
</form>
</div>
<form method="dialog" class="modal-backdrop">
<button>@lang('Close')</button>
</form>
</dialog>
@if ($errors->userDeletion->isNotEmpty())
    <script>document.getElementById('confirm-user-deletion').showModal();</script>
@endif
</div>
</section>
