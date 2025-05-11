<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Costs') }}
            </h2>
            <a href="{{ route('costs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-icons.plus class="w-4 h-4 mr-2" />
                {{ __('New Cost') }}
            </a>
        </div>
    </x-slot>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Costs Table -->
                    @if($costs->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-8.5-6h17" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17H7c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v6c0 1.1-.9 2-2 2Z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No Costs Found') }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ __('Get started by creating your first cost.') }}</p>
                            <a href="{{ route('costs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <x-icons.plus class="w-4 h-4 mr-2" />
                                {{ __('Create Cost') }}
                            </a>
                        </div>
                    @else
                        <x-table.index>
                            <x-table.head>
                                <x-table.th>#</x-table.th>
                                <x-table.th>{{ __('Name') }}</x-table.th>
                                <x-table.th>{{ __('Category') }}</x-table.th>
                                <x-table.th>{{ __('Amount') }}</x-table.th>
                                <x-table.th>{{ __('Paid At') }}</x-table.th>
                                <x-table.th align="right">{{ __('Actions') }}</x-table.th>
                            </x-table.head>
                            
                            <x-table.body>
                                @foreach($costs as $index => $cost)
                                    <x-table.tr :isEven="$loop->even">
                                        <x-table.td>{{ $index + 1 }}</x-table.td>
                                        <x-table.td>
                                            <div class="font-medium">{{ $cost->name }}</div>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-badge.index color="indigo">
                                                {{ $cost->category->name }}
                                            </x-badge.index>
                                        </x-table.td>
                                        <x-table.td>{{ number_format($cost->amount) }}</x-table.td>
                                        <x-table.td>{{ $cost->paid_at->format('Y-m-d') }}</x-table.td>
                                        <x-table.td align="right">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a href="{{ route('costs.edit', $cost) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('Edit') }}">
                                                    <x-icons.pencil class="w-5 h-5" />
                                                </a>
                                                
                                                <button
                                                    type="button"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="{{ __('Delete') }}"
                                                    x-data="{}"
                                                    x-on:click="$dispatch('open-modal', {id: 'confirm-cost-deletion-{{ $cost->id }}'})"
                                                >
                                                    <x-icons.trash class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </x-table.td>
                                    </x-table.tr>
                                    
                                    <!-- Delete Confirmation Modal -->
                                    <x-modal.confirmation title="{{ __('Delete Cost') }}" id="confirm-cost-deletion-{{ $cost->id }}">
                                        {{ __('Are you sure you want to delete this cost?') }}
                                        <div class="mt-2 font-medium">{{ $cost->name }}</div>
                                        <div class="mt-1 text-sm text-red-600">{{ __('This action cannot be undone.') }}</div>
                                        
                                        <x-slot name="footer">
                                            <form method="POST" action="{{ route('costs.destroy', $cost) }}">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <button
                                                    type="button"
                                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                                    x-data="{}"
                                                    x-on:click="$dispatch('close-modal', {id: 'confirm-cost-deletion-{{ $cost->id }}'})"
                                                >
                                                    {{ __('Cancel') }}
                                                </button>
                                                
                                                <button
                                                    type="submit"
                                                    class="ml-3 inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                                >
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-modal.confirmation>
                                @endforeach
                            </x-table.body>
                        </x-table.index>
                        
                        <!-- Pagination Links -->
                        <div class="mt-6">
                            {{ $costs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
