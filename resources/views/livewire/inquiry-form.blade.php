<div>
    <table>
        <thead>
            <tr>
                <th><i class="fa-solid fa-list"></i> Genre Categories</th>
                <th><i class="fas fa-dollar-sign"></i> Prices</th>
                <th>
                    Descriptions
                    <i class="fas fa-info-circle" id="openInfoModal" style="cursor: pointer; color: #eb7c2f;"></i>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><i class="fas fa-headphones"></i> Hip Hop / Local</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(100, 'Hip Hop / Local')"
                        class="price-btn">100</button>

                </td>
                <td>Rhythm reference: <i class="fa-solid fa-check-circle"></i><br>Chorus reference: &nbsp;<i
                        class="fa-solid fa-times-circle"></i></td>
            </tr>

            <tr>
                <td><i class="fas fa-drum"></i> Tshepe</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(150, 'Sesotho Fesheneng')"
                        class="price-btn">150</button>
                </td>
                <td>Rhythm reference: <i class="fa-solid fa-check-circle"></i><br>Chorus reference: &nbsp;<i
                        class="fa-solid fa-check-circle"></i></td>
            </tr>

            <tr>
                <td><i class="fas fa-guitar"></i> Afrobeat</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(100, 'Afro Fusion')"
                        class="price-btn">100</button>
                </td>
                <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                        class="fa-solid fa-check-circle"></i></td>
            </tr>
            <tr>
                <td><i class="fas fa-microphone"></i> Trap</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(100, 'Trap')" class="price-btn">100</button>
                </td>
                <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i>
                    <br> Chorus reference: &nbsp;<i class="fa-solid fa-times-circle"></i>
                </td>

            </tr>
            <tr>
                <td><i class="fas fa-drum"></i> Amapiano</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(160, 'Amapiano')" class="price-btn">160</button>
                </td>
                <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                        class="fa-solid fa-check-circle"></i></td>
            </tr>
            <tr>
                <td><i class="fas fa-star"></i> Custom Beat</td>
                <td>
                    <button class="button-50" wire:click="selectPrice(200, 'Custom Beat')"
                        class="price-btn">200</button>
                </td>
                <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                        class="fa-solid fa-check-circle"></td>
            </tr>

        </tbody>
    </table>

    <!-- Confirmation Modal -->
    {{-- <div x-data="{ open: @entangle('paymentModal') }" x-show="open" @keydown.escape.window="open = false"
        class="fixed inset-0 flex justify-center items-center x-modal-overlay z-50">
        <div class="x-modal-container">
            <h5>Confirm Your Selection</h5>
            <p>Genre: <strong>{{ $genre }}</strong> <span>Price: <strong>{{ $price }}</strong></span></p>

            <div class="fields">
                <input wire:model="userPhone" type="text" placeholder="Enter Phone Number"
                    class="w-full p-2 border border-gray-300 rounded mb-4" maxlength="15">
            </div>
            <!-- Phone Number Input Field -->


            <!-- Error Message -->
            @error('userPhone')
                <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
            @enderror

            <!-- Buttons -->
            <button wire:click="processPayment" class="btn btn-success w-full py-2 mt-2">
                Proceed with Payment
            </button>
            <button @click="open = false" class="btn btn-danger w-full py-2 mt-2">
                Cancel
            </button>
        </div> --}}

    <div x-data="{ open: @entangle('paymentModal') }" x-show="open" @keydown.escape.window="open = false"
        class="fixed inset-0 flex justify-center items-center x-modal-overlay z-50">
        <div id="namer">
            <h5>Confirm Your Selection</h5>
            <p>Genre: <strong>{{ $genre }}</strong> <span>Price: <strong>{{ $price }}</strong></span></p>
            <div id="namer-input">
                <input wire:model="userPhone" type="text" placeholder="Enter Phone Number">
            </div>
            @error('userPhone')
                <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
            @enderror
            <div class="namer-controls">
                <div><button wire:click="processPayment" class="button-51">
                        Proceed with Payment
                    </button>
                </div>
                <div><button @click="open = false" class="button-51">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>


</div>
