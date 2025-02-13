<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabooom 🎉🎊</title>
    <script src="https://unpkg.com/htmx.org@1.9.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0"></script>
    <style>
        body {
            font-family: 'Comic Sans MS', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #A2D2FF 0%, #FEF9EF 100%);
            color: #2B3A67;
            margin: 0;
            padding: 10px;
            min-height: 100vh;
        }

        #game-box {
            background: white;
            border-radius: 25px;
            padding: 20px;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(43, 58, 103, 0.15);
            margin-top: 10px;
            border: 3px solid #57CC99;
            width: 90%;
            max-width: 400px;
        }

        h1 {
            color: #2B3A67;
            font-size: calc(1.8em + 2vw);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 5px;
        }

        input {
            font-size: 18px;
            padding: 12px;
            border-radius: 15px;
            border: 3px solid #57CC99;
            margin: 10px 0;
            width: 85%;
            max-width: 300px;
            outline: none;
            transition: all 0.3s ease;
            background-color: #FAFAFA;
        }

        input:focus {
            border-color: #22577A;
            box-shadow: 0 0 10px rgba(34, 87, 122, 0.2);
        }

        button {
            font-size: 18px;
            background: #22577A;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        button:hover {
            background: #57CC99;
            transform: scale(1.05);
        }

        #score {
            font-size: calc(20px + 1vw);
            margin: 15px 0;
            color: #22577A;
            font-weight: bold;
        }

        #word-display {
            font-size: calc(24px + 1vw);
            color: #2B3A67;
            margin: 15px 0;
            font-weight: bold;
            padding: 10px 20px;
            background: #F7F9FC;
            border-radius: 15px;
            display: inline-block;
        }

        #feedback {
            font-size: 24px;
            margin-top: 20px;
            min-height: 30px;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            #game-box {
                padding: 15px;
                margin-top: 5px;
            }

            input {
                font-size: 16px;
                padding: 10px;
            }

            button {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>

    <h1>Vocabooom 🚀</h1>
    <div id="score">Score: <span id="score-count">0</span> 🌟</div>

    <div id="game-box">
        <h2 id="word-display">{{ $word->german }}</h2>
        <input type="text" id="answer" placeholder="Type in English..." autocomplete="off">
        <button onclick="checkAnswer()">Check ✨</button>
        <p id="feedback"></p>
    </div>

    <script>
        let score = 0;
        let currentWordId = {{ $word->id }};
        let correctAnswer = "{{ $word->english }}".toLowerCase();

        // Create audio elements
        const milestoneSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2020/2020-preview.mp3');
        const correctSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3');

        // Adjust volume
        correctSound.volume = 0.6;
        milestoneSound.volume = 0.7;

        function celebrateMilestone() {
            milestoneSound.play();
            const duration = 4000;
            const animationEnd = Date.now() + duration;
            const defaults = {
                startVelocity: 45,
                spread: 360,
                ticks: 80,
                zIndex: 0
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 80 * (timeLeft / duration);
                confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                });
                confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                });
                confetti({
                    ...defaults,
                    particleCount: particleCount * 0.5,
                    origin: { x: randomInRange(0.4, 0.6), y: Math.random() - 0.2 }
                });
            }, 200);
        }

        function celebrateStars() {
            milestoneSound.play();
            const defaults = {
                spread: 360,
                ticks: 100,
                gravity: 0,
                decay: 0.94,
                startVelocity: 45,
                colors: ['FFE400', 'FFBD00', 'E89400', 'FFCA6C', 'FDFFB8']
            };

            function shoot() {
                confetti({
                    ...defaults,
                    particleCount: 80,
                    scalar: 1.5,
                    shapes: ['star']
                });

                confetti({
                    ...defaults,
                    particleCount: 20,
                    scalar: 1,
                    shapes: ['circle']
                });
            }

            setTimeout(shoot, 0);
            setTimeout(shoot, 100);
            setTimeout(shoot, 200);
            setTimeout(shoot, 300);
            setTimeout(shoot, 400);
   
        }

        function checkAnswer() {
            let userAnswer = document.getElementById("answer").value.trim().toLowerCase();
            let feedback = document.getElementById("feedback");

            if (userAnswer === correctAnswer) {
                // Clear input and load new word first
                document.getElementById("answer").value = "";
                loadNewWord();

                // Then update score and show feedback
                feedback.innerHTML = "✅ Correct!";
                score++;
                document.getElementById("score-count").innerText = score;

                // Play sound and celebrations last
                correctSound.play();

                // Enhanced basic confetti for regular correct answers
                confetti({
                    particleCount: 150,
                    spread: 100,
                    origin: { y: 0.6 },
                    scalar: 1.2
                });

                // Special celebration for milestones
                if (score % 3 === 0) {
                    celebrateMilestone();
                } else if (score % 5 === 0) {
                    celebrateStars();
                }
            } else {
                feedback.innerHTML = "❌ Try again!";
                document.getElementById("answer").value = "";
            }
        }

        function loadNewWord() {
            fetch("/get-word")
                .then(response => response.json())
                .then(data => {
                    console.log("New word:", data.german, "| Correct:", data.english);

                    document.getElementById("word-display").innerText = data.german;
                    correctAnswer = data.english.toLowerCase();
                    currentWordId = data.id;
                    document.getElementById("feedback").innerText = "";
                })
                .catch(error => console.error("Error loading new word:", error));
        }

        // 🎯 Listen for "Enter" key press
        document.getElementById("answer").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevents accidental form submission
                checkAnswer(); // Calls the checkAnswer function
            }
        });
    </script>
</body>

</html>
