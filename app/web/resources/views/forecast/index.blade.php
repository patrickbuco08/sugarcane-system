@extends('layouts.app')

@section('title', 'Forecast')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div id="forecast"></div>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/page/forecast/index.jsx') }}"></script>
@endpush
