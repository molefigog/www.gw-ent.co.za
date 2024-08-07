<section>
    <header>
        <h2 class="h4 font-weight-medium">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('pass.update') }}" class="mt-3">
        @csrf

        @method('put')
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <div class="input-group">
                <input type="password" id="update_password_current_password" name="current_password" class="form-control" autocomplete="current-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-input="update_password_current_password">
                    <i class="icon-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            @error('current_password')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <div class="input-group">
                <input type="password" id="update_password_password" name="password" class="form-control" autocomplete="new-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-input="update_password_password">
                    <i class="icon-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <div class="input-group">
                <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-input="update_password_password_confirmation">
                    <i class="icon-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            {{-- @if (session('status') === 'password-updated')
                <div class="text-muted" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Saved.') }}
                </div>
            @endif --}}
        </div>
    </form>
</section>

@push('updated')
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const input = document.getElementById(this.getAttribute('data-input'));
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="icon-eye" aria-hidden="true"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="icon-eye-slash" aria-hidden="true"></i>';
            }
        });
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('status') === 'password-updated')
            Swal.fire({
                title: 'Success!',
                text: '{{ __("Your password has been updated successfully.") }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endpush
