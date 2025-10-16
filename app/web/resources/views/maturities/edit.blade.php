@extends('layouts.app')

@section('title', 'Edit Maturity')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Maturity Record
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('maturities.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('maturities.update', $maturity->id) }}" method="POST" class="space-y-6 p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $maturity->name) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm" placeholder="e.g., Field A - Section 1">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Top Sample -->
                    <div>
                        <label for="top_sample_id" class="block text-sm font-medium text-gray-700">Top Sample</label>
                        <select name="top_sample_id" id="top_sample_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <option value="">-- Select a top sample --</option>
                            @foreach($samples as $sample)
                                <option value="{{ $sample->id }}" {{ (old('top_sample_id', $maturity->top_sample_id) == $sample->id) ? 'selected' : '' }}>
                                    {{ $sample->label }} - Brix: {{ number_format($sample->avg_brix, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('top_sample_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bottom Sample -->
                    <div>
                        <label for="bottom_sample_id" class="block text-sm font-medium text-gray-700">Bottom Sample</label>
                        <select name="bottom_sample_id" id="bottom_sample_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                            <option value="">-- Select a bottom sample --</option>
                            @foreach($samples as $sample)
                                <option value="{{ $sample->id }}" {{ (old('bottom_sample_id', $maturity->bottom_sample_id) == $sample->id) ? 'selected' : '' }}>
                                    {{ $sample->label }} - Brix: {{ number_format($sample->avg_brix, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('bottom_sample_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Maturity Ratio:</strong> Top Brix / Bottom Brix<br>
                                    <strong>Status:</strong> < 0.95 = Immature | 0.95-1.05 = Mature | > 1.05 = Overmature/Abnormal
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('maturities.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Update Maturity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
