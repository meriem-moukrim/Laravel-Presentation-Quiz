@extends('layouts.app')

@section('content')
    <div class="content">
        <h1>Quiz : Testez vos connaissances</h1>
        <p>Le quiz arrive bientôt ! Préparez-vous à tester vos compétences sur le routage et les middleware Laravel.</p>

        <div style="margin-top: 2rem;">
            <a href="{{ url('/') }}"
                style="display: inline-block; padding: 10px 20px; background-color: var(--accent-color); color: white; border-radius: 8px; font-weight: bold; text-decoration: none;">Retour
                au cours</a>
        </div>
    </div>
@endsection