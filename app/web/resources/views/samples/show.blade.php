@extends('layouts.app')

@section('title', 'Sample Details: ' . ($sample->label ?? 'Untitled'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back button and title -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-theme-primary hover:text-theme-secondary">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Sample Details</h1>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $sample->label ?? 'Unlabeled Sample' }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Created {{ $sample->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-theme-accent text-white">
                            {{ $sample->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <!-- Sample Information -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Sample Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Average Brix</p>
                                    <p class="mt-1 text-2xl font-semibold">{{ number_format($sample->avg_brix, 2) }}°</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Pol</p>
                                    <p class="mt-1 text-2xl font-semibold">{{ number_format($sample->pol, 2) }}°</p>
                                </div>
                                @if($sample->sensor_temp_c)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Temperature</p>
                                    <p class="mt-1 text-2xl font-semibold">{{ number_format($sample->sensor_temp_c, 1) }}°C</p>
                                </div>
                                @endif
                                @if($sample->harvestBatch && $sample->harvestBatch->lkgTc())
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">LKG/TC</p>
                                    <p class="mt-1 text-2xl font-semibold">{{ number_format($sample->harvestBatch->lkgTc(), 2) }}</p>
                                </div>
                                @endif
                            </div>
                        </dd>
                    </div>

                    <!-- Harvest Batch -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Harvest Batch</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <form action="{{ route('samples.update-batch', $sample) }}" method="POST" class="max-w-md">
                                @csrf
                                @method('PATCH')
                                <div class="flex space-x-2">
                                    <select name="harvest_batch_id" id="harvest_batch_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm rounded-md">
                                        <option value="">-- Select a batch --</option>
                                        @foreach($harvestBatches as $batch)
                                            <option value="{{ $batch->id }}" {{ $sample->harvest_batch_id == $batch->id ? 'selected' : '' }}>
                                                {{ $batch->label }} ({{ $batch->week_of->format('M d, Y') }}) - {{ number_format($batch->tons_harvested, 1) }} tons
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </dd>
                    </div>

                    <!-- Channel Readings -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Channel Readings</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel R">
                                    <div class="text-xs font-medium text-gray-500">R</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_r }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel S">
                                    <div class="text-xs font-medium text-gray-500">S</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_s }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel T">
                                    <div class="text-xs font-medium text-gray-500">T</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_t }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel U">
                                    <div class="text-xs font-medium text-gray-500">U</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_u }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel V">
                                    <div class="text-xs font-medium text-gray-500">V</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_v }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center hover:bg-blue-50 transition-colors" title="Channel W">
                                    <div class="text-xs font-medium text-gray-500">W</div>
                                    <div class="text-lg font-semibold">{{ $sample->ch_w }}</div>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <!-- Additional Information -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Additional Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Model Version:</span>
                                    <span class="text-gray-600">{{ $sample->model_version ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Coefficient Hash:</span>
                                    <span class="font-mono text-gray-600">{{ $sample->coeff_hash ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Last Updated:</span>
                                    <span class="text-gray-600">{{ $sample->updated_at->format('M d, Y H:i:s') }}</span>
                                </div>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
