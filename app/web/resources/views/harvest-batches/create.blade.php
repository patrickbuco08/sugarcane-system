@extends('layouts.app')

@section('title', 'Create Harvest Batch')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Create New Harvest Batch
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('harvest-batches.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('harvest-batches.store') }}" method="POST" class="space-y-6 p-6">
                @csrf
                
                <div class="space-y-4">
                    <!-- Label -->
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700">Label</label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

<!-- Weekly Price ID -->
                    <div>
                        <label for="weekly_price_id" class="block text-sm font-medium text-gray-700">Weekly Price</label>
                        <select name="weekly_price_id" id="weekly_price_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <option value="">-- Select a weekly price --</option>
                            @foreach(\Bocum\Models\WeeklyPrice::orderBy('week_of', 'desc')->get() as $price)
                                <option value="{{ $price->id }}" {{ old('weekly_price_id') == $price->id ? 'selected' : '' }}>
                                    {{ $price->week_of->format('M d, Y') }} - ₱{{ number_format($price->b_domestic, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('weekly_price_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tons Harvested -->
                    <div>
                        <label for="tons_harvested" class="block text-sm font-medium text-gray-700">Tons Harvested</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="tons_harvested" id="tons_harvested" step="0.01" min="0" value="{{ old('tons_harvested') }}" class="block w-full pr-12 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">tons</span>
                            </div>
                        </div>
                        @error('tons_harvested')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recovery Coefficient -->
                    <div>
                        <label for="recovery_coeff" class="block text-sm font-medium text-gray-700">Recovery Coefficient</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="recovery_coeff" id="recovery_coeff" step="0.001" min="0" max="1" value="{{ old('recovery_coeff', 0.09) }}" class="block w-full pr-12 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">0-1</span>
                            </div>
                        </div>
                        @error('recovery_coeff')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Farmer's Share -->
                    <div>
                        <label for="farmers_share" class="block text-sm font-medium text-gray-700">Farmer's Share</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="farmers_share" id="farmers_share" step="0.01" min="0" max="1" value="{{ old('farmers_share', 0.7) }}" class="block w-full pr-12 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">0-1</span>
                            </div>
                        </div>
                        @error('farmers_share')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('harvest-batches.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Create Batch
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
