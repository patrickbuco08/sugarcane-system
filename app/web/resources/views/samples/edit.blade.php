@extends('layouts.app')

@section('title', 'Edit Sample')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Edit Sample</h2>
            
            <form action="{{ route('samples.update', $sample) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Avg Brix -->
                    <div>
                        <label for="avg_brix" class="block text-sm font-medium text-gray-700">Average Brix</label>
                        <input type="number" step="0.001" name="avg_brix" id="avg_brix" 
                               value="{{ old('avg_brix', $sample->avg_brix) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-theme-accent focus:ring focus:ring-theme-accent focus:ring-opacity-50"
                               required>
                        @error('avg_brix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pol -->
                    <div>
                        <label for="pol" class="block text-sm font-medium text-gray-700">Pol</label>
                        <input type="number" step="0.001" name="pol" id="pol" 
                               value="{{ old('pol', $sample->pol) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-theme-accent focus:ring focus:ring-theme-accent focus:ring-opacity-50"
                               required>
                        @error('pol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700">Label (Optional)</label>
                        <input type="text" name="label" id="label" 
                               value="{{ old('label', $sample->label) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-theme-accent focus:ring focus:ring-theme-accent focus:ring-opacity-50">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harvest Batch -->
                    <div>
                        <label for="harvest_batch_id" class="block text-sm font-medium text-gray-700">Harvest Batch (Optional)</label>
                        <select name="harvest_batch_id" id="harvest_batch_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-theme-accent focus:ring focus:ring-theme-accent focus:ring-opacity-50">
                            <option value="">-- Select a harvest batch --</option>
                            @foreach($harvestBatches as $batch)
                                <option value="{{ $batch->id }}" {{ old('harvest_batch_id', $sample->harvest_batch_id) == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->label }} ({{ $batch->week_of->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('harvest_batch_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                        Update Sample
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
