<div>
    <style>
        /* Basic loader style */
    .loader2 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    .spinner2 {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader2 p {
        margin-top: 10px;
        font-size: 18px;
        color: #333;
    }
    </style>
    <div wire:loading>
        <div class="loader2"> <!-- Your loader style or component -->
            <div class="spinner2"></div>
            <p>Do not leave this page. Tranfering your credits...</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>error!</strong> {{ session('error') }}
    </div>
    @endif

    {{-- <form wire:submit.prevent="pay">
        <div class="mb-3">
            <label for="mobileNumber" class="form-label">Mobile Number</label>
            <input type="text" id="mobileNumber" wire:model="mobileNumber" class="form-control @error('mobileNumber') is-invalid @enderror">
            @error('mobileNumber') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="text" id="amount" wire:model="amount" class="form-control @error('amount') is-invalid @enderror">
            @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Pay</button>
    </form> --}}




    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Deposit To Your M-pesa {{$credits}}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form wire:submit.prevent="pay">
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="text" id="mobileNumber" wire:model="mobileNumber"
                    class="form-control @error('mobileNumber') is-invalid @enderror">
                @error('mobileNumber')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" id="amount" wire:model="amount"
                    class="form-control @error('amount') is-invalid @enderror">
                @error('amount')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button button type="submit" class="btn btn-outline-success btn-sm" wire:loading.attr="disabled">
                <span wire:loading.remove> Deposit</span>
                <span wire:loading>
                    <div class="d-flex align-items-center justify-content-center ">
                        <div class="spinner-border custom-spinner text-white m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Processing
                    </div>
                </span>
            </button>

        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {{-- <button type="submit" class="btn btn-primary">Deposit</button> --}}
    </div>
</div>
