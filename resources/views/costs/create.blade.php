<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Cost') }}
            </h2>
            <a href="{{ route('costs.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest">
                <x-icons.back class="w-4 h-4 mr-2" />
                {{ __('Back to Costs') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Cost Form Card -->
            <x-card title="Cost Information" footer='codeyad.com'>
                <x-form.container action="{{ route('costs.store') }}" method="POST" id="costForm"
                    class="space-y-6">

                    <!-- Cost Name Field -->
                    <x-form.input name="name" label="Cost Name" placeholder="Enter cost name" required
                        maxlength="100" oninput="validateName(this)" />
                    <div id="name-error" class="mt-1 text-sm text-red-600 hidden"></div>

                    <!-- Category Selection -->
                    <x-form.select name="category_id" label="Category" placeholder="Select a category" required oninput="validateCategory(this)">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-form.select>
                    <div id="category-error" class="mt-1 text-sm text-red-600 hidden"></div>

                    <!-- Amount Field -->
                    <x-form.input name="amount" label="Amount" type="number" placeholder="Enter amount" required
                        oninput="validateAmount(this)" />
                    <div id="amount-error" class="mt-1 text-sm text-red-600 hidden"></div>

                    <!-- Paid At Field -->
                    <x-forms.datepicker name="paid_at" label="Paid At" required oninput="validateDate(this)" />
                    <div id="paid_at-error" class="mt-1 text-sm text-red-600 hidden"></div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <x-form.button type="button" variant="secondary"
                            onclick="window.location.href='{{ route('costs.index') }}'">
                            {{ __('Cancel') }}
                        </x-form.button>

                        <x-form.button type="submit" id="submitButton" onclick="validateForm(event)">
                            <x-icons.save class="w-4 h-4 mr-2" />
                            {{ __('Save Cost') }}
                        </x-form.button>
                    </div>

                </x-form.container>
            </x-card>

        </div>
    </div>

    <!-- Client-side Validation Scripts -->
    <script>
        // Validate name field
        function validateName(input) {
            const errorElement = document.getElementById('name-error');
            
            // Reset error state
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            input.classList.remove('border-red-500');

            // Check if empty
            if (input.value.trim() === '') {
                errorElement.textContent = 'Cost name is required';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            // Check if exceeds max length
            if (input.value.length > 100) {
                errorElement.textContent = 'Cost name cannot exceed 100 characters';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            return true;
        }

        // Validate category field
        function validateCategory(input) {
            const errorElement = document.getElementById('category-error');
            
            // Reset error state
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            input.classList.remove('border-red-500');

            // Check if empty
            if (input.value === '') {
                errorElement.textContent = 'Category is required';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            return true;
        }

        // Validate amount field
        function validateAmount(input) {
            const errorElement = document.getElementById('amount-error');
            
            // Reset error state
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            input.classList.remove('border-red-500');

            // Check if empty
            if (input.value.trim() === '') {
                errorElement.textContent = 'Amount is required';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            // Check if valid number
            if (isNaN(input.value) || Number(input.value) <= 0) {
                errorElement.textContent = 'Amount must be a positive number';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            return true;
        }

        // Validate date field
        function validateDate(input) {
            const errorElement = document.getElementById('paid_at-error');
            
            // Reset error state
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            input.classList.remove('border-red-500');

            // Check if empty
            if (input.value.trim() === '') {
                errorElement.textContent = 'Paid date is required';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            return true;
        }

        // Validate entire form before submission
        function validateForm(event) {
            const nameInput = document.querySelector('input[name="name"]');
            const categoryInput = document.querySelector('select[name="category_id"]');
            const amountInput = document.querySelector('input[name="amount"]');
            const dateInput = document.querySelector('input[name="paid_at"]');

            const isNameValid = validateName(nameInput);
            const isCategoryValid = validateCategory(categoryInput);
            const isAmountValid = validateAmount(amountInput);
            const isDateValid = validateDate(dateInput);

            if (!isNameValid || !isCategoryValid || !isAmountValid || !isDateValid) {
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
</x-app-layout>
