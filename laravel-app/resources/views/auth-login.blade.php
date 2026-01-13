@extends('layouts.auth')

@section('content')
    @auth
        <div class="auth-box" style="text-align: center;">
            <h2>Bienvenue, {{ Auth::user()->name }} !</h2>
            <p class="auth-subtitle">Vous êtes connecté.</p>

            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                    style="border-radius: 50%; width: 80px; height: 80px; margin-bottom: 20px; object-fit: cover;">
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">Se déconnecter</button>
            </form>

            <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('quiz.play') }}" class="btn-primary" style="text-decoration: none; display: inline-block;">🚀 Commencer le Quiz</a>
                <a href="{{ url('/') }}" style="color: var(--subheading-color); font-size: 0.9rem; text-decoration: none;">Retour au cours</a>
            </div>
        </div>
    @else
        <div class="auth-box" id="login-box">
            <h2>Connexion</h2>
            <p class="auth-subtitle">Connectez-vous pour accéder au Quiz</p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Authentification simulée !');">
                <div class="form-group">
                    <label for="login-email">Adresse Email</label>
                    <input type="email" id="login-email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="login-password">Mot de passe</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">Se connecter</button>

                <div class="auth-separator">ou</div>

                <a href="{{ route('auth.google') }}" class="btn-google" style="text-decoration: none;">
                    <svg class="google-icon" viewBox="0 0 48 48">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        <path fill="none" d="M0 0h48v48H0z" />
                    </svg>
                    Se connecter avec Google
                </a>
            </form>

            <p class="auth-footer">
                Pas encore de compte ? <a href="#" onclick="toggleAuth('register')">S'inscrire</a>
            </p>
        </div>

        <div class="auth-box" id="register-box" style="display: none;">
            <h2>Inscription</h2>
            <p class="auth-subtitle">Créez un compte pour sauvegarder vos scores</p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Inscription simulée !');">
                <div class="form-group">
                    <label for="register-name">Nom complet</label>
                    <input type="text" id="register-name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="register-email">Adresse Email</label>
                    <input type="email" id="register-email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="register-password">Mot de passe</label>
                    <input type="password" id="register-password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="register-confirm">Confirmer le mot de passe</label>
                    <input type="password" id="register-confirm" name="password_confirmation" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">S'inscrire</button>

                <div class="auth-separator">ou</div>

                <a href="{{ route('auth.google') }}" class="btn-google" style="text-decoration: none;">
                    <svg class="google-icon" viewBox="0 0 48 48">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        <path fill="none" d="M0 0h48v48H0z" />
                    </svg>
                    S'inscrire avec Google
                </a>
            </form>

            <p class="auth-footer">
                Déjà un compte ? <a href="#" onclick="toggleAuth('login')">Se connecter</a>
            </p>
        </div>
    @endauth

    <script>
        function toggleAuth(type) {
            const loginBox = document.getElementById('login-box');
            const registerBox = document.getElementById('register-box');

            if (type === 'register') {
                loginBox.style.display = 'none';
                registerBox.style.display = 'block';
            } else {
                loginBox.style.display = 'block';
                registerBox.style.display = 'none';
            }
        }
    </script>
@endsection