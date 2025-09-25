@extends('layouts.app')

@section('title', 'Edit Weekly Price')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Weekly Price - {{ $weeklyPrice->week_of->format('M d, Y') }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('weekly-prices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('weekly-prices.update', $weeklyPrice) }}" method="POST" class="space-y-6 p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Week Of -->
                    <div>
                        <label for="week_of" class="block text-sm font-medium text-gray-700">Week Of</label>
                        <input type="date" name="week_of" id="week_of" value="{{ old('week_of', $weeklyPrice->week_of->format('Y-m-d')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Select the Monday of the week for this price entry</p>
                        @error('week_of')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- B Domestic Price -->
                    <div>
                        <label for="b_domestic" class="block text-sm font-medium text-gray-700">B Domestic Price <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="b_domestic" id="b_domestic" step="0.01" min="0" value="{{ old('b_domestic', $weeklyPrice->b_domestic) }}" class="block w-full pl-7 pr-16 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">/LKG</span>
                            </div>
                        </div>
                        @error('b_domestic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- A US Quota Price -->
                    <div>
                        <label for="a_us_quota" class="block text-sm font-medium text-gray-700">A US Quota Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="a_us_quota" id="a_us_quota" step="0.01" min="0" value="{{ old('a_us_quota', $weeklyPrice->a_us_quota) }}" class="block w-full pl-7 pr-16 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">/LKG</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional - leave blank if not available</p>
                        @error('a_us_quota')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Molasses MT Price -->
                    <div>
                        <label for="molasses_mt" class="block text-sm font-medium text-gray-700">Molasses Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="molasses_mt" id="molasses_mt" step="0.01" min="0" value="{{ old('molasses_mt', $weeklyPrice->molasses_mt) }}" class="block w-full pl-7 pr-16 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">/MT</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional - leave blank if not available</p>
                        @error('molasses_mt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Source -->
                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                        <input type="text" name="source" id="source" value="{{ old('source', $weeklyPrice->source) }}" maxlength="120" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Optional - source of the price data (max 120 characters)</p>
                        @error('source')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('weekly-prices.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Update Price
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Related Harvest Batches -->
        @if($weeklyPrice->harvestBatches->count() > 0)
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Related Harvest Batches</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Harvest batches using this weekly price</p>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($weeklyPrice->harvestBatches as $batch)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">{{ $batch->label }}</div>
                            <div class="text-sm text-gray-500">{{ number_format($batch->tons_harvested, 2) }} tons</div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
