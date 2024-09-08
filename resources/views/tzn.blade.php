@extends('layouts.master')
@section('content')
<br>
<div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">

    <div class="row text-center">
        <h6 class="text-muted">OpenApi Testing</h6>
        <div class="col-md-6 mb-4">
            <hr>
            <button class="btn btn-info btn-sm">Pay LSL1,00</button>
            <hr>
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <form id="chargeForm" action="{{ url('api/charge') }}" method="get">
                        @csrf
                        <input type="hidden" value="1" id="input_Amount" name="input_Amount" required>

                        <div class="mb-3">
                            <label for="input_CustomerMSISDN" class="form-label">MSISDN:</label>
                            <input type="text" class="form-control" id="input_CustomerMSISDN" name="input_CustomerMSISDN"
                                pattern="5\d{7}"placeholder="Enter mpesa number" title="Please enter 8 digits starting with 5" maxlength="8" required>
                            <div id="msisdnError" style="color: red; display: none;">MSISDN must be exactly 8 digits long and start with 5.</div>
                        </div>

                        <div class="mb-3">

                            <input type="hidden" class="form-control" id="input_PurchasedItemsDesc" name="input_PurchasedItemsDesc" value="Track 1" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit" id="submitButton">
                            <span id="buttonText">Pay LSL1,00</span>
                            <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Second Card -->
        <div class="col-md-6 mb-4">
            <hr>
            <button class="btn btn-info btn-sm">Bal LSL1,00</button>
            <hr>
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <form id="chargeForm2" action="{{ url('api/b2c') }}" method="get">
                        @csrf
                        <input type="hidden" value="1" id="input_Amount" name="input_Amount" required>

                        <div class="mb-3">
                            <label for="input_CustomerMSISDN" class="form-label">MSISDN:</label>
                            <input type="text" class="form-control" id="input_CustomerMSISDN" name="input_CustomerMSISDN"
                                pattern="5\d{7}"placeholder="Enter mpesa number"placeholder="Enter mpesa number" title="Please enter 8 digits starting with 5" maxlength="8" required>
                            <div id="msisdnError" style="color: red; display: none;">MSISDN must be exactly 8 digits long and start with 5.</div>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="input_PaymentItemsDesc" name="input_PaymentItemsDesc" value="Salary payment" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit" id="submitButton2">
                            <span id="buttonText2">Withdraw LSL1,00</span>
                            <span id="spinner2" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        </button>
                    </form>
                </div>
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

<script>
    $(document).ready(function() {
        $('#chargeForm2').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#submitButton2');
            var $buttonText = $('#buttonText2');
            var $spinner = $('#spinner2');

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
<script>
document.getElementById('input_CustomerMSISDN').addEventListener('input', function() {
    const msisdnInput = this.value;
    const msisdnError = document.getElementById('msisdnError');

    // Check if the input starts with "5" and is 8 digits long
    if (/^5\d{7}$/.test(msisdnInput)) {
        msisdnError.style.display = 'none';
        this.setCustomValidity(''); // Clear custom validity message
    } else {
        msisdnError.style.display = 'block';
        this.setCustomValidity('Invalid'); // Set custom validity message to block form submission
    }
});
</script>
@endpush
