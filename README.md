<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Atrapando el Cuadrado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }

        #gameArea {
            position: relative;
            width: 600px;
            height: 400px;
            background-color: #ffffff;
            border: 2px solid #000;
        }

        #square {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: red;
            cursor: pointer;
        }

        #score {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 20px;
            color: #000;
        }
    </style>
</head>
<body>
    <div id="gameArea">
        <div id="score">Puntuación: 0</div>
        <div id="square"></div>
    </div>

    <script>
        const square = document.getElementById('square');
        const scoreElement = document.getElementById('score');
        const gameArea = document.getElementById('gameArea');

        let score = 0;

        function getRandomPosition() {
            const x = Math.random() * (gameArea.clientWidth - square.clientWidth);
            const y = Math.random() * (gameArea.clientHeight - square.clientHeight);
            return { x, y };
        }

        function moveSquare() {
            const { x, y } = getRandomPosition();
            square.style.left = `${x}px`;
            square.style.top = `${y}px`;
        }

        square.addEventListener('click', () => {
            score++;
            scoreElement.textContent = `Puntuación: ${score}`;
            moveSquare();
        });

        // Mueve el cuadrado por primera vez
        moveSquare();
    </script>
</body>
</html>
