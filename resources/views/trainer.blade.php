<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabooom 🚀</title>
    <script src="https://unpkg.com/htmx.org@1.9.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0"></script>
    <style>
        body {
            font-family: 'Comic Sans MS', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #FFE5E5 0%, #FFE5FF 100%);
            color: #333;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        #game-box {
            background: white;
            border-radius: 25px;
            padding: 30px;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border: 3px solid #FF69B4;
            max-width: 90%;
            width: 400px;
        }

        h1 {
            color: #FF69B4;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        input {
            font-size: 20px;
            padding: 15px;
            border-radius: 15px;
            border: 3px solid #FF69B4;
            margin: 20px 0;
            width: 80%;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #FF1493;
            box-shadow: 0 0 10px rgba(255, 105, 180, 0.3);
        }

        button {
            font-size: 20px;
            background: #FF69B4;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        button:hover {
            background: #FF1493;
            transform: scale(1.05);
        }

        #score {
            font-size: 28px;
            margin: 20px 0;
            color: #FF69B4;
            font-weight: bold;
        }

        #word-display {
            font-size: 32px;
            color: #333;
            margin: 20px 0;
            font-weight: bold;
        }

        #feedback {
            font-size: 24px;
            margin-top: 20px;
            min-height: 30px;
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
    const correctSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3');
    const milestoneSound = new Audio('https://assets.mixkit.co/active_storage/sfx/1435/1435-preview.mp3');

    function celebrateMilestone() {
        milestoneSound.play();
        const duration = 3000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            confetti({
                ...defaults,
                particleCount,
                origin: { x: Math.random(), y: Math.random() - 0.2 }
            });
        }, 250);
    }

    function checkAnswer() {
        let userAnswer = document.getElementById("answer").value.trim().toLowerCase();
        let feedback = document.getElementById("feedback");

        if (userAnswer === correctAnswer) {
            feedback.innerHTML = "✅ Correct!";
            score++;
            document.getElementById("score-count").innerText = score;

            // Play sound and trigger confetti
            correctSound.play();
            
            // Basic confetti for regular correct answers
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });

            // Special celebration for milestones (every 3rd correct answer)
            if (score % 3 === 0) {
                celebrateMilestone();
            }

            setTimeout(loadNewWord, 1500); // Increased delay to enjoy the celebration
        } else {
            feedback.innerHTML = "❌ Try again!";
        }

        document.getElementById("answer").value = "";
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
    document.getElementById("answer").addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevents accidental form submission
            checkAnswer(); // Calls the checkAnswer function
        }
    });

    </script>
</body>
</html>

