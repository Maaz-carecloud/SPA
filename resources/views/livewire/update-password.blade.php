<div class="row justify-content-center">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-body">
                <form wire:submit.prevent="updatePassword" autocomplete="off">
                    <x-form.input type="password" name="old_password" label="Old Password" :error="$errors->first('old_password')" wire:model.defer="old_password" required="true" />
                    <x-form.input type="password" name="new_password" label="New Password" :error="$errors->first('new_password')" wire:model.defer="new_password" required="true" />
                    <x-form.input type="password" name="confirm_password" label="Confirm New Password" :error="$errors->first('confirm_password')" wire:model.defer="confirm_password" required="true" />
                    <button type="submit" class="btn btn theme-filled-btn" wire:target="updatePassword" wire:loading.attr="disabled">
                        Update Password
                        <span wire:loading wire:target="updatePassword" class="align-middle ms-2">
                            <span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
