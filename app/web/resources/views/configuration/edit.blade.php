@extends('layouts.app')

@section('title', 'Edit Configuration')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Configuration
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('configurations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('configurations.update', $configuration) }}" method="POST" class="space-y-6 p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Key -->
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-700">Key <span class="text-red-500">*</span></label>
                        <input type="text" name="key" id="key" value="{{ old('key', $configuration->key) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">A unique identifier for this configuration setting</p>
                        @error('key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700">Value <span class="text-red-500">*</span></label>
                        <textarea name="value" id="value" rows="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-theme-accent focus:border-theme-accent sm:text-sm font-mono">{{ old('value', is_array($configuration->value) || is_object($configuration->value) ? json_encode($configuration->value, JSON_PRETTY_PRINT) : $configuration->value) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Can be a string, number, boolean, JSON object, or array. Examples:<br>
                            • Text: <code class="bg-gray-100 px-1 rounded">My Application</code><br>
                            • Boolean: <code class="bg-gray-100 px-1 rounded">true</code> or <code class="bg-gray-100 px-1 rounded">false</code> or <code class="bg-gray-100 px-1 rounded">1</code> or <code class="bg-gray-100 px-1 rounded">0</code><br>
                            • JSON Object: <code class="bg-gray-100 px-1 rounded">{"theme": "dark", "lang": "en"}</code><br>
                            • Array: <code class="bg-gray-100 px-1 rounded">["option1", "option2", "option3"]</code>
                        </p>
                        @error('value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('configurations.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-theme-primary hover:bg-theme-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-accent">
                            Update Configuration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
