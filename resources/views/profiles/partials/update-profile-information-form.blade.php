<section>
    <header class="mb-4">
        <h2 class="h5 font-weight-medium mb-1">
            {{ __('Profile Information') }}
        </h2>
        <p class="mb-0">
            {{ __("Ensure thay your phone number start with 266, 27, 267, 263 depending on your country") }}
        </p>
    </header>

    <!-- Example form action omitted for demonstration -->
    <form method="post" action="{{ route('profile.update') }}" class="needs-validation"  novalidate>
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted">
                        {{ __('Your email address is unverified.') }}
                        <button id="verify" type="button" class="btn btn-link p-0 m-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="mobile_number" class="form-label">{{ __('Phone') }} <span style="font-size: 9px;">Use international dialling code eg. 26659073443 </span></label>
            <input type="number" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" required autofocus autocomplete="mobile_number">
            @error('mobile_number')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            {{-- @if (session('status') === 'profile-updated')
                <div class="alert alert-success" role="alert">
                    {{ __('Saved.') }}
                </div>
            @endif --}}
        </div>
    </form>
</section>
@push('updated')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        @if (session('status') === 'profile-updated')
            Swal.fire({
                title: 'Success!',
                text: '{{ __("Saved.") }}',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
<script>
    $(document).ready(function() {
        $('#verify').on('click', function() {
            $.ajax({
                url: '{{ route('verification.send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Email Sent!',
                        text: 'A verification link has been re-sent to your email address.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong, please try again later.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endpush
