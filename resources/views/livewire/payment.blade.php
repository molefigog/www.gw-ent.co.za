<div>
    <div wire:loading>
        <div class="loader2"> <!-- Your loader style or component -->
            <div class="spinner2"></div>
            <p>Do not leave this page. Processing your payment...</p>
        </div>
    </div>

    <form wire:submit.prevent="pay" class="text-center">
        <div class="mb-3">
            <p></p>
            <h6 id="text">Enter Your M-pesa Number To Buy This Song</h6>
            <p>Reka ka mpesa</p>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <img src="{{ asset('assets/vcl1.png') }}" alt="" style="width: 24px; height: 24px;">
                    </div>
                </div>
                <input type="text" wire:model.defer="mobileNumber" class="form-control col-6"
                    placeholder="Enter mpesa number" pattern="5\d{7}" title="Please enter 8 digits starting with 5"
                    maxlength="8" required>
                <input type="hidden" wire:model="amount">
                <input type="hidden" wire:model="client_reference">
                <input type="hidden" wire:model="musicId">

                <button button type="submit" class="btn btn-outline-success btn-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove> Pay M{{ $amount }}</span>
                    <span wire:loading>
                        <div class="d-flex align-items-center justify-content-center ">
                            <div class="spinner-border custom-spinner text-white m-1" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Processing
                        </div>
                    </span>
                </button>
            </div>
        </div>
    </form>

</div>
