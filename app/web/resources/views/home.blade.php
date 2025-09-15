@extends('layouts.app')

@section('title', config('app.title'))

@section('content')
    <section class="min-h-screen text-center pt-20">
        <h1 class="text-3xl md:text-4xl font-bold text-theme-primary mb-4 uppercase">
            DESIGN AND DEVELOPMENT OF IOT-ENABLED SYSTEM WITH
            NEAR-INFRARED(NIR) SPECTROSCOPY FOR DETERMINING
            THE QUALITY OF SUGARCANE JUICE (Saccharum Officinarum)
        </h1>
        <p class="text-lg font-semibold text-theme-primary mb-2">
            Balindan, Gerald Christian Rey R.<br>
            Elalto, Hanns Jaspher A.<br>
            Masangcay, Paul Francis B.<br>
            Mojica, Jeroh Lee M.
        </p>
        <p class="text-md text-theme-accent">
            Already tested your sugarcane? <a href="/login"
                class="text-theme-primary font-medium hover:text-honey-dark underline">Log in here</a> to and access your
            results.
        </p>
    </section>
@endsection
