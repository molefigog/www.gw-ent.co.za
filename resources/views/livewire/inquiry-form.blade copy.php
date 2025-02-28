<div>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <form wire:submit.prevent="openPaymentModal">
        <div class="mb-4">
            <label for="type" class="block text-gray-700 dark:text-gray-300 font-medium">Type</label>
            <input type="text" id="type" wire:model="type"
                class="w-full p-2 border rounded-md dark:bg-gray-800 dark:text-white" disabled>
        </div>

        <div class="mb-4">
            <label for="genre" class="block text-white-700 dark:text-gray-300 font-medium">Genre</label>
            <select id="genre" wire:model="genre" class="w-full p-2 border rounded-md dark:bg-gray-800 dark:text-dark">
                <option style="" value="" d>Select Genre</option>
                <option style="color:black" value="Test">test</option>
                <option style="color:black" value="Hip Hop / Local">Hip Hop / Local</option>
                <option style="color:black" value="Sesotho Fesheneng">Sesotho Fesheneng</option>
                <option style="color:black"value="Afrobeat">Afrobeat</option>
                <option style="color:black" value="Trap">Trap</option>
                <option style="color:black" value="Amapiano">Amapiano</option>
                <option style="color:black"value="Custom Beat">Custom Beat</option>
            </select>
            @error('genre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 dark:text-gray-300 font-medium">Price</label>
            <input type="text" id="price" wire:model="price"
                class="w-full p-2 border rounded-md dark:bg-gray-800 dark:text-white" readonly>
        </div>

        <div class="mb-4">
            <label for="userPhone" class="block text-gray-700 dark:text-gray-300 font-medium">Phone Number</label>
            <input type="text" id="userPhone" wire:model="userPhone"
                class="w-full p-2 border rounded-md dark:bg-gray-800 dark:text-white"
                placeholder="Enter your phone number">
            @error('userPhone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full">
            Proceed to Payment
        </button>
    </form>

    <!-- Payment Modal -->
    @if ($paymentModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg w-11/12 sm:w-96">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Confirm Payment</h2>
                <p class="text-gray-600 dark:text-gray-400">You're about to pay <strong
                        class="text-blue-600">LSL{{ number_format($price, 2) }}</strong></p>

                <div class="flex justify-end space-x-2 mt-4">
                    <button wire:click="$set('paymentModal', false)"
                        class="px-4 py-2 bg-gray-400 text-white rounded-md">Cancel</button>
                    <button wire:click="pay({{ $price }})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Pay Now
                    </button>

                </div>
            </div>
        </div>
    @endif
</div>
<script>
    // Define prices as a JavaScript object
    const prices = {
        'Test': 1,
        'Hip Hop / Local': 100,
        'Sesotho Fesheneng': 150,
        'Afrobeat': 100,
        'Trap': 100,
        'Amapiano': 120,
        'Custom Beat': 180
    };

    document.addEventListener('livewire:load', function () {
        const genreSelect = document.getElementById('genre');
        const priceInput = document.getElementById('price');

        genreSelect.addEventListener('change', function () {
            // Get the selected genre
            const selectedGenre = genreSelect.value;

            // Update Livewire with the selected genre
            @this.set('genre', selectedGenre);

            // Update the price input field based on the selected genre
            if (selectedGenre && prices[selectedGenre] !== undefined) {
                priceInput.value = prices[selectedGenre];
                @this.set('price', prices[selectedGenre]);
            } else {
                priceInput.value = '';
                @this.set('price', null);
            }
        });
    });
</script>
