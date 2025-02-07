<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vokabel Trainer</title>
    <script src="https://unpkg.com/htmx.org@1.9.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0"></script>
</head>

<body>
    <h1>VocaBOOOM Trainer</h1>
    @foreach ($words as $word)
    <div>
        <strong>{{ $word->german }}</strong>
        <input type="text" name="answer" placeholder="Translate to English"
            hx-post="/check"
            hx-trigger="change"
            hx-target="#result-{{ $word->id }}"
            hx-vals='{"id": {{ $word->id }} }'>
        <span id="result-{{ $word->id }}"></span>
    </div>
    @endforeach

    <script>
        document.body.addEventListener("htmx:afterRequest", function(evt) {
            if (evt.detail.xhr.responseText.includes('"correct":true')) {
                confetti({
                    particleCount: 100,
                    spread: 70
                });
            }
        });
    </script>
</body>

</html>
