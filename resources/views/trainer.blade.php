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
            background-color: #ffeb99;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        #game-box {
            background: white;
            border-radius: 20px;
            padding: 20px;
            display: inline-block;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
        }

        input {
            font-size: 20px;
            padding: 10px;
            border-radius: 10px;
            border: 2px solid #555;
            margin-top: 10px;
        }

        button {
            font-size: 18px;
            background: #ff5733;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
        }

        button:hover {
            background: #ff2e00;
        }

        #score {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h1>Vocabooom 🚀</h1>
    <div id="score">Score: <span id="score-count">0</span></div>

    <div id="game-box">
        <h2 id="word-display">{{ $word->german }}</h2>
        <input type="text" id="answer" placeholder="Type in English..." autocomplete="off">
        <button onclick="checkAnswer()">Check</button>
        <p id="feedback"></p>
    </div>

    <script>
    let score = 0;
    let currentWordId = {{ $word->id }};
    let correctAnswer = "{{ $word->english }}".toLowerCase();

    function checkAnswer() {
        let userAnswer = document.getElementById("answer").value.trim().toLowerCase();
        let feedback = document.getElementById("feedback");

        console.log("User Answer:", userAnswer);
        console.log("Correct Answer:", correctAnswer);

        if (userAnswer === correctAnswer) {
            feedback.innerHTML = "✅ Correct!";
            score++;
            document.getElementById("score-count").innerText = score;

            // 🎉 CONFETTI EFFECT
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });

            setTimeout(loadNewWord, 1000);
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

