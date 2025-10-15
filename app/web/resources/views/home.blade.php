@extends('layouts.app')

@section('title', config('app.title'))

@section('content')
    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center relative overflow-hidden">

        <div class="relative z-10 max-w-6xl mx-auto px-4 py-12">
            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header Accent Bar -->
                <div class="h-2 bg-gradient-to-r from-theme-primary via-theme-accent to-theme-secondary"></div>

                <div class="p-8 md:p-12 lg:p-16">
                    <!-- Icon/Logo Area -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden mb-4">
                                <img src="{{ asset('sugarcane-logo.png') }}" alt="Sugarcane Logo"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -top-1 -right-1 w-8 h-8 bg-theme-accent rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Project Title -->
                    <h1
                        class="text-2xl md:text-3xl lg:text-4xl font-bold text-theme-primary mb-6 text-center leading-tight">
                        DESIGN AND DEVELOPMENT OF IOT-ENABLED SYSTEM WITH
                        NEAR-INFRARED(NIR) SPECTROSCOPY FOR DETERMINING
                        THE QUALITY OF SUGARCANE JUICE <span class="text-theme-accent">(Saccharum Officinarum)</span>
                    </h1>

                    <!-- Divider -->
                    <div class="flex items-center my-10">
                        <div class="flex-1 border-t-2 border-sugarcaneLight-border"></div>
                        <div class="px-4">
                            <div class="w-3 h-3 bg-theme-accent rounded-full"></div>
                        </div>
                        <div class="flex-1 border-t-2 border-sugarcaneLight-border"></div>
                    </div>

                    <!-- Research Team -->
                    <div class="text-center mb-10">
                        <h2 class="text-xl font-bold text-theme-secondary mb-1">Research Team</h2>
                        <p class="text-sm text-sugarcaneLight-textMuted mb-6">Undergraduate Thesis Project</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto">
                            <div class="bg-sugarcaneLight-surface p-4 rounded-lg border border-sugarcaneLight-border">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-theme-primary to-theme-accent rounded-full mx-auto mb-3 flex items-center justify-center text-white font-bold text-xl">
                                    GB
                                </div>
                                <p class="font-semibold text-theme-primary text-sm">Gerald Christian Rey R. Balindan</p>
                            </div>
                            <div class="bg-sugarcaneLight-surface p-4 rounded-lg border border-sugarcaneLight-border">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-theme-primary to-theme-accent rounded-full mx-auto mb-3 flex items-center justify-center text-white font-bold text-xl">
                                    HE
                                </div>
                                <p class="font-semibold text-theme-primary text-sm">Hanns Jaspher A. Elalto</p>
                            </div>
                            <div class="bg-sugarcaneLight-surface p-4 rounded-lg border border-sugarcaneLight-border">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-theme-primary to-theme-accent rounded-full mx-auto mb-3 flex items-center justify-center text-white font-bold text-xl">
                                    PM
                                </div>
                                <p class="font-semibold text-theme-primary text-sm">Paul Francis B. Masangcay</p>
                            </div>
                            <div class="bg-sugarcaneLight-surface p-4 rounded-lg border border-sugarcaneLight-border">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-theme-primary to-theme-accent rounded-full mx-auto mb-3 flex items-center justify-center text-white font-bold text-xl">
                                    JM
                                </div>
                                <p class="font-semibold text-theme-primary text-sm">Jeroh Lee M. Mojica</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="text-center mt-12 pt-8 border-t-2 border-sugarcaneLight-border">
                        <p class="text-lg text-sugarcaneLight-text mb-4">
                            Already tested your sugarcane?
                        </p>
                        <a href="/login"
                            class="inline-flex items-center px-8 py-4 bg-theme-primary text-white font-semibold rounded-lg shadow-lg hover:bg-theme-secondary hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Log In to Access Your Results
                        </a>
                        <p class="text-sm text-sugarcaneLight-textMuted mt-4">
                            View your test history, analysis reports, and quality metrics
                        </p>
                    </div>
                </div>

                <!-- Footer Accent Bar -->
                <div class="h-2 bg-gradient-to-r from-theme-secondary via-theme-accent to-theme-primary"></div>
            </div>
        </div>
    </section>
@endsection
