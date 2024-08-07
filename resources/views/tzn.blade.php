@extends('layouts.master')
@section('content')

        <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
            <div class="text-center">
                <h6 class="text-muted">M-pesa OpenApi</h6>
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <form id="chargeForm" action="{{ url('api/charge') }}" method="get">
                            @csrf <!-- CSRF protection is not necessary for GET requests but included for best practices -->

                            <div class="mb-3">
                                {{-- <label for="input_Amount">Amount:</label> --}}
                                <input type="hidden" value="1" id="input_Amount" name="input_Amount" required>
                            </div>

                            <div class="mb-3">
                                <label for="input_CustomerMSISDN" class="form-label">MSISDN:</label>
                                <input type="number" class="form-control" id="input_CustomerMSISDN" name="input_CustomerMSISDN" required>
                            </div>

                            <div class="mb-3">
                                <label for="input_PurchasedItemsDesc" class="form-label">Purchased Items Description:</label>
                                <input type="text" class="form-control" id="input_PurchasedItemsDesc" name="input_PurchasedItemsDesc" required>
                            </div>

                            <button class="btn btn-primary" type="submit" id="submitButton">
                                <span id="buttonText">Submit</span>
                                <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    @endsection
    @push('mpesa')
    <script>
        $(document).ready(function() {
            $('#chargeForm').on('submit', function(event) {
                event.preventDefault();

                var $submitButton = $('#submitButton');
                var $buttonText = $('#buttonText');
                var $spinner = $('#spinner');

                // Show the spinner and change button text to "Processing..."
                $buttonText.text('Processing...');
                $spinner.show();
                $submitButton.prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response); // Log the response for debugging

                        if (response.code === 'INS-0') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.description
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.description
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Log the error response for debugging
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again.'
                        });
                    },
                    complete: function() {
                        // Hide the spinner and revert button text to "Submit"
                        $buttonText.text('Submit');
                        $spinner.hide();
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>

@endpush
