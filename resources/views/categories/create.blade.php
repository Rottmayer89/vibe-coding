<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Category') }}
            </h2>
            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest">
                <x-icons.back class="w-4 h-4 mr-2" />
                {{ __('Back to Categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Category Form Card -->
            <x-card title="Category Information" footer='codeyad.com'>
                <x-form.container action="{{ route('categories.store') }}" method="POST" id="categoryForm"
                    class="space-y-6">

                    <!-- Category Name Field -->
                    <x-form.input name="name" label="Category Name" placeholder="Enter category name" required
                        maxlength="100" oninput="validateName(this)" />
                    <div id="name-error" class="mt-1 text-sm text-red-600 hidden"></div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <x-form.button type="button" variant="secondary"
                            onclick="window.location.href='{{ route('categories.index') }}'">
                            {{ __('Cancel') }}
                        </x-form.button>

                        <x-form.button type="submit" id="submitButton" onclick="validateForm(event)">
                            <x-icons.save class="w-4 h-4 mr-2" />
                            {{ __('Save Category') }}
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
            const submitButton = document.getElementById('submitButton');

            // Reset error state
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            input.classList.remove('border-red-500');

            // Check if empty
            if (input.value.trim() === '') {
                errorElement.textContent = 'Category name is required';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            // Check if exceeds max length
            if (input.value.length > 100) {
                errorElement.textContent = 'Category name cannot exceed 100 characters';
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            return true;
        }

        // Validate entire form before submission
        function validateForm(event) {
            const nameInput = document.querySelector('input[name="name"]');
            const isNameValid = validateName(nameInput);

            if (!isNameValid) {
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
</x-app-layout>
