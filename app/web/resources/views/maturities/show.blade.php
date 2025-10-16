@extends('layouts.app')

@section('title', 'View Maturity')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $maturity->name }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('maturities.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Back to List
                </a>
                <a href="{{ route('maturities.edit', $maturity->id) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Edit
                </a>
            </div>
        </div>

        @php
            $ratio = $maturity->bottom_brix > 0 ? $maturity->top_brix / $maturity->bottom_brix : 0;
            
            if ($ratio < 0.95) {
                $status = 'Immature';
                $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
            } elseif ($ratio >= 0.95 && $ratio <= 1.05) {
                $status = 'Mature';
                $statusColor = 'bg-green-100 text-green-800 border-green-200';
            } else {
                $status = 'Overmature/Abnormal';
                $statusColor = 'bg-red-100 text-red-800 border-red-200';
            }
        @endphp

        <!-- Maturity Status Card -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Maturity Analysis</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Maturity Ratio -->
                    <div class="text-center">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Maturity Ratio</dt>
                        <dd class="text-3xl font-bold text-gray-900">{{ number_format($ratio, 3) }}</dd>
                        <p class="text-xs text-gray-500 mt-1">Top Brix / Bottom Brix</p>
                    </div>

                    <!-- Status -->
                    <div class="text-center">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold border-2 {{ $statusColor }}">
                                {{ $status }}
                            </span>
                        </dd>
                    </div>

                    <!-- Status Legend -->
                    <div class="text-left">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Status Guide</dt>
                        <dd class="text-sm text-gray-700 space-y-1">
                            <div>< 0.95: <span class="font-medium text-yellow-700">Immature</span></div>
                            <div>0.95 - 1.05: <span class="font-medium text-green-700">Mature</span></div>
                            <div>> 1.05: <span class="font-medium text-red-700">Overmature</span></div>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Top Sample Card -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-blue-50 border-b border-blue-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Top Sample</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Label</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $maturity->top_label ?? 'N/A' }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Brix</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($maturity->top_brix, 2) }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Pol</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $maturity->top_pol ? number_format($maturity->top_pol, 2) : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bottom Sample Card -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-green-50 border-b border-green-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Bottom Sample</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Label</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $maturity->bottom_label ?? 'N/A' }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Brix</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($maturity->bottom_brix, 2) }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Pol</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $maturity->bottom_pol ? number_format($maturity->bottom_pol, 2) : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Record Information</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ date('M d, Y h:i A', strtotime($maturity->created_at)) }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ date('M d, Y h:i A', strtotime($maturity->updated_at)) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
