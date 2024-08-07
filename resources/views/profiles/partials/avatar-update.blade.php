<section>
    <header class="row justify-content-center mb-4">
        <h2 class="h5 font-weight-medium mb-1 text-center" >
            {{ __('Avatar Update') }}
        </h2>

    </header>
    <div class="justify-content-center">
    <img src="{{asset('storage/'.$user->avatar)}}" alt="Avatar" class="avatar" style="width: 50px; height: 50px;">
    </div>
    <!-- Example form action omitted for demonstration -->

    <form method="post" action="{{ route('avatar.update') }}" class="needs-validation" enctype="multipart/form-data" novalidate>
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
            <input type="file" class="filepond" name="avatar" accept="image/png, image/jpeg">

        </div>


        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </form>

</section>
@push('pondcss')
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
@endpush
@push('updated')
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script>
//  document.addEventListener('DOMContentLoaded', function() {
//     const inputElement = document.querySelector('input[type="file"]');
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//     FilePond.create(inputElement, {
//         allowMultiple: false,
//         maxFiles: 1,
//         imageResizeTargetWidth: 100,
//         imageResizeTargetHeight: 100,
//         acceptedFileTypes: ['image/png', 'image/jpeg'],
//         server: {
//             url: '/upload-avatar',
//             process: {
//                 headers: {
//                     'X-CSRF-TOKEN': csrfToken // Add CSRF token in the request header
//                 }
//             }
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const inputElement = document.querySelector('input[type="file"]');
    FilePond.create(inputElement, {
        allowMultiple: false,
        maxFiles: 1,
        imageResizeTargetWidth: 100,
        imageResizeTargetHeight: 100,
        acceptedFileTypes: ['image/png', 'image/jpeg'],
        server: {
            process: {
                url: '/temporary-upload',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // Include CSRF token in the request headers
                }
            },
            revert: {
                url: '/revert-upload',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // Include CSRF token in the request headers
                }
            }
        }
    });
});



</script>
@endpush
