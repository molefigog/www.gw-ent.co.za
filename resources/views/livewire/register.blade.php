<div>

    <form wire:submit="register">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="icon-user"></i></div>
                </div>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    wire:model="name">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="icon-mail_outline"></i></div>
                </div>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    wire:model="email">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i id="togglePassword" class="icon-lock_outline"></i>
                    </div>
                </div>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    wire:model="password">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i id="togglePassword1" class="icon-eye"></i>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm
                Password</label>
            <div class="input-group mb-2">

                <input type="password" class="form-control" id="password_confirmation"
                    wire:model="password_confirmation">
            </div>
        </div>
        <div class="mb-3">

            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                <span wire:loading.remove>Register</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
    @push('modal')
        <script>
            document.getElementById('togglePassword').addEventListener('click', function() {
                var passwordInput = document.getElementById('password');
                var icon = document.getElementById('togglePassword');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('icon-lock_outline');
                    icon.classList.add('icon-lock_open');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('icon-lock_open');
                    icon.classList.add('icon-lock_outline');
                }
            });
        </script>
        <script>
            document.getElementById('togglePassword1').addEventListener('click', function() {
                var passwordInput = document.getElementById('password');
                var icon = document.getElementById('togglePassword1');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('icon-eye');
                    icon.classList.add('icon-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('icon-eye-slash');
                    icon.classList.add('icon-eye');
                }
            });
        </script>
    @endpush

</div>
