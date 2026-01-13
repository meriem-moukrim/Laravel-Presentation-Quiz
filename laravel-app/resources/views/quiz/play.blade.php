@extends('layouts.auth')

@section('content')
    <div class="quiz-container">
        <div class="auth-box quiz-box">
            <div class="quiz-header">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2 style="margin: 0;">Quiz Laravel</h2>
                    <a href="{{ route('home') }}" class="btn-secondary"
                        style="padding: 8px 16px; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Retour au cours
                    </a>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
                <p class="question-counter">Question <span id="current-question-num">1</span>/10</p>
            </div>

            <div id="quiz-content">
                @foreach($questions as $index => $question)
                    <div class="question-step" id="question-{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                        <h3 class="question-text">{{ $question['question'] }}</h3>

                        <div class="options-list">
                            @foreach($question['options'] as $option)
                                <label class="option-item">
                                    <input type="radio" name="question_{{ $question['id'] }}" value="{{ $option }}"
                                        onchange="handleOptionSelect({{ $index }}, '{{ addslashes($option) }}', '{{ addslashes($question['answer']) }}')">
                                    <span class="option-text">{{ $option }}</span>
                                    <span class="indicator"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div id="quiz-result" style="display: none; text-align: center;">
                    <div class="score-circle">
                        <span id="score-value">0</span>/10
                    </div>
                    <h3>Quiz Terminé !</h3>
                    <p id="result-message" class="result-message"></p>

                    <div class="leaderboard-container">
                        <div id="mistakes-section"
                            style="display: none; background: var(--bg-color); padding: 0; border: none;">
                            <h4 style="color: #EF4444; margin-bottom: 20px; font-size: 1.3rem;">⚠️ Vos Points d'Amélioration
                            </h4>
                            <ul id="mistakes-list" style="list-style: none; padding: 0;"></ul>
                        </div>

                        <div id="leaderboard-section">
                            <h4 style="color: var(--accent-color); margin-bottom: 20px; font-size: 1.3rem;">🏆 Top 5
                                Leaderboard</h4>
                            <div
                                style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 12px; padding: 0 15px;">
                                <ul id="leaderboard-list" style="list-style: none; padding: 0; margin: 0;">
                                    <!-- Leaderboard items -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons" style="margin-top: 40px;">
                        <button onclick="retryQuiz()" class="btn-secondary">Réessayer</button>
                        <a href="{{ url('/') }}" class="btn-primary">Retour au cours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .quiz-box {
            max-width: 100% !important;
            width: 98% !important;
            margin: 0 auto;
            padding: 40px;
        }

        .question-text {
            font-size: 1.8rem;
            margin-bottom: 2.5rem;
            color: var(--heading-color);
            text-align: center;
            line-height: 1.4;
        }

        .options-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .option-item {
            position: relative;
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--bg-color);
        }

        .option-item:hover {
            border-color: var(--accent-color);
            background-color: var(--hover-bg);
        }

        .option-item input {
            display: none;
        }

        .option-text {
            font-size: 1.1rem;
            flex-grow: 1;
            font-weight: 500;
            color: var(--text-color);
        }

        /* Feedback Styles */
        .option-item.correct {
            border-color: #10B981;
            background-color: rgba(16, 185, 129, 0.1);
            color: #047857;
        }

        .option-item.wrong {
            border-color: #EF4444;
            background-color: rgba(239, 68, 68, 0.1);
            color: #B91C1C;
        }

        .progress-bar {
            height: 6px;
            background: var(--border-color);
            border-radius: 3px;
            margin: 20px 0 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent-color);
            width: 10%;
            transition: width 0.3s ease;
        }

        .question-counter {
            text-align: right;
            font-size: 0.9rem;
            color: var(--subheading-color);
            margin-bottom: 2rem;
        }

        /* Result Styles */
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--accent-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 25px rgba(255, 45, 32, 0.3);
        }

        .result-message {
            margin-bottom: 2rem;
            font-size: 1.2rem;
            color: var(--subheading-color);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-secondary {
            padding: 12px 24px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--heading-color);
            background: transparent;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: var(--hover-bg);
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        /* Result Section Grid */
        #quiz-result {
            max-width: 100%;
            margin: 0 auto;
        }

        .mistake-item {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid rgba(239, 68, 68, 0.2);
            background: rgba(239, 68, 68, 0.05);
            /* Light red tint */
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .mistake-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .mistake-question {
            font-weight: 700;
            color: #B91C1C;
            margin-bottom: 8px;
            font-size: 1.1rem;
            display: block;
        }

        .mistake-explanation {
            font-size: 1rem;
            color: var(--text-color);
            font-style: normal;
            display: block;
            line-height: 1.5;
        }

        .leaderboard-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            text-align: left;
        }

        @media(min-width: 768px) {
            .leaderboard-container {
                grid-template-columns: 3fr 2fr;
            }
        }

        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 1rem;
        }

        .leaderboard-rank {
            font-weight: bold;
            width: 30px;
            color: var(--accent-color);
        }

        .leaderboard-user {
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .leaderboard-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .leaderboard-score {
            font-weight: bold;
        }
    </style>

    <script>
        let score = 0;
        const totalQuestions = 10;
        let mistakes = [];

        // Store explanations (passed from PHP)
        const explanations = @json($questions);

        let isQuizActive = true;

        document.addEventListener('DOMContentLoaded', () => {
            // Check if user has a saved result (persisted on refresh)
            const savedState = localStorage.getItem('laravel_quiz_result');
            if (savedState) {
                isQuizActive = false; // Quiz already done
                const state = JSON.parse(savedState);
                score = state.score;
                mistakes = state.mistakes;

                // Hide first question if it's visible by default
                const firstQ = document.getElementById('question-0');
                if (firstQ) firstQ.style.display = 'none';

                // Show result immediately
                showResult();
                loadLeaderboard();
            }
        });

        // Strict Anti-Cheating: Disqualify if tab is switched
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isQuizActive) {
                isQuizActive = false;
                score = 0;
                mistakes = []; // Clear mistakes as the quiz is voided

                // Hide all active questions immediately
                document.querySelectorAll('.question-step').forEach(step => step.style.display = 'none');

                finishQuiz();

                // Override the result message to explain the disqualification
                const message = document.getElementById('result-message');
                if (message) {
                    message.innerHTML = "🚫 <span style='color: #EF4444; font-weight: bold;'>Disqualifié !</span><br>Vous avez quitté l'onglet du quiz.";
                }
            }
        });

        window.addEventListener('blur', () => {
            if (isQuizActive) {
                // Using document.title to flash a warning could be a nice touch too, 
                // but alert blocks execution so it's safer for "enforcing" focus.
                console.log("User left window");
                // We won't alert on blur because it can be annoying if they just click another app for a second, 
                // but visibilitychange covers tab switching. 
                // If user wants STRICT check, we can uncomment alert below:
                // alert("⚠️ Attention ! Vous avez quitté la fenêtre du quiz.");
            }
        });

        function handleOptionSelect(index, selectedValue, correctValue) {
            const questionDiv = document.getElementById(`question-${index}`);
            const options = questionDiv.querySelectorAll('.option-item');
            const inputs = questionDiv.querySelectorAll('input');

            // Disable all inputs
            inputs.forEach(input => input.disabled = true);

            // Find selected label
            let selectedLabel;
            options.forEach(label => {
                if (label.querySelector('input').value === selectedValue) {
                    selectedLabel = label;
                }
            });

            // Check answer
            if (selectedValue === correctValue) {
                score++;
                selectedLabel.classList.add('correct');
            } else {
                selectedLabel.classList.add('wrong');
                // Track mistake
                mistakes.push({
                    question: explanations[index].question,
                    explanation: explanations[index].explanation
                });

                // Show correct answer
                options.forEach(label => {
                    if (label.querySelector('input').value === correctValue) {
                        label.classList.add('correct');
                    }
                });
            }

            setTimeout(() => {
                goToNextQuestion(index);
            }, 1500);
        }

        function goToNextQuestion(currentIndex) {
            const currentDiv = document.getElementById(`question-${currentIndex}`);
            const nextDiv = document.getElementById(`question-${currentIndex + 1}`);
            const progressBar = document.getElementById('progress-fill');
            const counterSpan = document.getElementById('current-question-num');

            if (currentDiv) currentDiv.style.display = 'none';

            if (nextDiv) {
                nextDiv.style.display = 'block';
                const progress = ((currentIndex + 2) / totalQuestions) * 100;
                progressBar.style.width = `${progress}%`;
                counterSpan.textContent = currentIndex + 2;
            } else {
                finishQuiz();
            }
        }

        function finishQuiz() {
            isQuizActive = false;
            // Save state to survive refresh
            localStorage.setItem('laravel_quiz_result', JSON.stringify({
                score: score,
                mistakes: mistakes
            }));

            showResult();
            saveScore();
            loadLeaderboard();
        }

        function showResult() {
            const resultDiv = document.getElementById('quiz-result');
            const scoreValue = document.getElementById('score-value');
            const message = document.getElementById('result-message');
            const mistakesSection = document.getElementById('mistakes-section');
            const mistakesList = document.getElementById('mistakes-list');

            const counter = document.querySelector('.question-counter');
            if (counter) counter.style.display = 'none';

            const progress = document.querySelector('.progress-bar');
            if (progress) progress.style.display = 'none';

            resultDiv.style.display = 'block';
            scoreValue.textContent = score;

            // Custom Message
            if (score === 10) {
                message.textContent = "Parfait ! Vous êtes un expert Laravel ! 🏆";
            } else if (score >= 7) {
                message.textContent = "Bravo ! Très bon score. 👏";
            } else if (score >= 5) {
                message.textContent = "Pas mal, mais vous pouvez faire mieux ! 💪";
            } else {
                message.textContent = "Continuez d'apprendre, ça va venir ! 📚";
            }

            // Clear previous mistakes list
            mistakesList.innerHTML = '';

            // Show mistakes
            if (mistakes.length > 0) {
                mistakesSection.style.display = 'block';
                mistakes.forEach(mistake => {
                    const li = document.createElement('li');
                    li.className = 'mistake-item';
                    li.innerHTML = `
                                                <span class="mistake-question">${mistake.question}</span>
                                                <span class="mistake-explanation">💡 ${mistake.explanation}</span>
                                            `;
                    mistakesList.appendChild(li);
                });
            } else {
                // Hide mistakes column if perfect score, make leaderboard full width/centered
                mistakesSection.style.display = 'none';
                document.querySelector('.leaderboard-container').style.display = 'block';
                document.querySelector('.leaderboard-container').style.maxWidth = '100%';
                document.querySelector('.leaderboard-container').style.margin = '0 auto';
            }
        }

        function saveScore() {
            // Check if we just loaded from storage (don't re-save if simply refreshing)
            // But the server handles "only update if higher", so it's safe to call again or skip.
            // We'll call it to be safe, db load is low.
            fetch('{{ route('quiz.score') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ score: score })
            }).catch(err => console.error(err));
        }

        function loadLeaderboard() {
            fetch('{{ route('quiz.leaderboard') }}')
                .then(res => res.json())
                .then(users => {
                    const list = document.getElementById('leaderboard-list');
                    list.innerHTML = '';

                    users.forEach((user, index) => {
                        const li = document.createElement('li');
                        li.className = 'leaderboard-item';

                        const avatar = user.avatar
                            ? `<img src="${user.avatar}" class="leaderboard-avatar" alt="${user.name}">`
                            : `<div class="leaderboard-avatar" style="background: #e5e7eb; display: flex; align-items: center; justify-content: center;">${user.name.charAt(0)}</div>`;

                        li.innerHTML = `
                                                        <span class="leaderboard-rank">#${index + 1}</span>
                                                        <div class="leaderboard-user">
                                                            ${avatar}
                                                            <span>${user.name}</span>
                                                        </div>
                                                        <span class="leaderboard-score">${user.score}/10</span>
                                                    `;
                        list.appendChild(li);
                    });
                })
                .catch(err => console.error(err));
        }

        function retryQuiz() {
            localStorage.removeItem('laravel_quiz_result');
            window.location.reload();
        }
    </script>
@endsection