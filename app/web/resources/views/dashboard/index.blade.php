@extends('layouts.app')

@section('title', 'Sugarcane Samples Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Sugarcane Samples</h1>
                    <p class="mt-1 text-sm text-gray-500">Monitor and analyze your sugarcane sample data</p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('samples.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        New Sample
                    </a>
                </div>
            </div>

            <!-- Filters and Search (placeholder for future implementation) -->
            <div class="mb-6 bg-white shadow-sm rounded-lg p-4">
                <div class="flex items-center flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search"
                                class="focus:ring-theme-accent focus:border-theme-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                placeholder="Search samples...">
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <select id="filter" name="filter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm rounded-md">
                            <option>All Samples</option>
                            <option>This Week</option>
                            <option>This Month</option>
                            <option>With Batch</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sample Cards Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                @foreach ($samples as $sample)
                    <div
                        class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                        <div class="px-4 py-5 sm:p-6 flex-1 flex flex-col">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $sample->label ?? 'Unlabeled Sample' }}</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-theme-accent text-white">
                                    {{ $sample->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Key Metrics -->
                            <div class="mt-4 grid grid-cols-2 gap-4 flex-grow">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Avg Brix</p>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ number_format($sample->avg_brix, 2) }}°</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Pol</p>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ number_format($sample->pol, 2) }}°</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">LKG/TC</p>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ number_format($sample->lkgtc(), 2) }}</p>
                                </div>
                                @if ($sample->sensor_temp_c)
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-sm font-medium text-gray-500">Temp</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">
                                            {{ number_format($sample->sensor_temp_c, 1) }}°C</p>
                                    </div>
                                @endif
                                @if ($sample->profit() !== null)
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-sm font-medium text-gray-500">Estimated Profit</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900 break-words whitespace-normal">
                                            ₱{{ number_format($sample->profit(), 2) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Channel Readings -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Channel Readings
                                </h4>
                                <div class="grid grid-cols-6 gap-1 text-xs">
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">R</div>
                                        <div class="font-semibold">{{ $sample->ch_r }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">S</div>
                                        <div class="font-semibold">{{ $sample->ch_s }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">T</div>
                                        <div class="font-semibold">{{ $sample->ch_t }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">U</div>
                                        <div class="font-semibold">{{ $sample->ch_u }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">V</div>
                                        <div class="font-semibold">{{ $sample->ch_v }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium">W</div>
                                        <div class="font-semibold">{{ $sample->ch_w }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Batch Info -->
                            @if ($sample->harvestBatch)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Batch: {{ $sample->harvestBatch->label }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-sm">
                                <span class="text-gray-500">{{ $sample->created_at->diffForHumans() }}</span>
                                <div class="space-x-4">
                                    <a href="{{ route('samples.edit', $sample) }}" class="font-medium text-theme-primary hover:text-theme-secondary">Edit</a>
                                    <a href="{{ route('samples.show', $sample) }}" class="font-medium text-theme-primary hover:text-theme-secondary">View details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $samples->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script type="module" src="{{ Vite::asset('resources/js/dashboard.js') }}"></script> --}}
    <script type="module" src="{{ Vite::asset('resources/js/page/dashboard/index.jsx') }}"></script>
@endpush
