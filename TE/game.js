// game.js

let score = 0;
let currentLevel = 0;
let timer;

const difficulties = [
    {
        level: 1,
        gradient: 'linear-gradient(to bottom, #4b6cb7, #182848)',
        equations: [
            { equation: '5 + 3', answer: 8 },
            { equation: '2 + 7', answer: 9 },
            { equation: '6 - 2', answer: 4 },
            { equation: '4 + 4', answer: 8 },
            { equation: '7 - 5', answer: 2 }
        ]
    },
    {
        level: 2,
        gradient: 'linear-gradient(to bottom, #4ca1af, #c4e0e5)',
        equations: [
            { equation: '10 - 3', answer: 7 },
            { equation: '5 * 2', answer: 10 },
            { equation: '9 / 3', answer: 3 },
            { equation: '8 + 7', answer: 15 },
            { equation: '12 - 5', answer: 7 }
        ]
    },
    {
        level: 3,
        gradient: 'linear-gradient(to bottom, #ff512f, #dd2476)',
        equations: [
            { equation: '12 + 8', answer: 20 },
            { equation: '15 - 4', answer: 11 },
            { equation: '6 * 3', answer: 18 },
            { equation: '9 + 6', answer: 15 },
            { equation: '14 - 8', answer: 6 }
        ]
    },
    {
        level: 4,
        gradient: 'linear-gradient(to bottom, #1f4037, #99f2c8)',
        equations: [
            { equation: '20 + 10', answer: 30 },
            { equation: '14 - 6', answer: 8 },
            { equation: '7 * 4', answer: 28 },
            { equation: '16 + 5', answer: 21 },
            { equation: '18 - 7', answer: 11 }
        ]
    },
    {
        level: 5,
        gradient: 'linear-gradient(to bottom, #373b44, #4286f4)',
        equations: [
            { equation: '35 + 27', answer: 62 },
            { equation: '40 - 15', answer: 25 },
            { equation: '9 * 6', answer: 54 },
            { equation: '25 + 18', answer: 43 },
            { equation: '30 - 12', answer: 18 }
        ]
    },
    {
        level: 6,
        gradient: 'linear-gradient(to bottom, #6a11cb, #2575fc)',
        equations: [
            { equation: '50 + 29', answer: 79 },
            { equation: '55 - 22', answer: 33 },
            { equation: '8 * 7', answer: 56 },
            { equation: '40 + 25', answer: 65 },
            { equation: '28 - 19', answer: 9 }
        ]
    },
    {
        level: 7,
        gradient: 'linear-gradient(to bottom, #ff7e5f, #feb47b)',
        equations: [
            { equation: '65 + 33', answer: 98 },
            { equation: '70 - 26', answer: 44 },
            { equation: '7 * 9', answer: 63 },
            { equation: '60 + 32', answer: 92 },
            { equation: '33 - 18', answer: 15 }
        ]
    },
    {
        level: 8,
        gradient: 'linear-gradient(to bottom, #12c2e9, #c471ed, #f64f59)',
        equations: [
            { equation: '80 + 45', answer: 125 },
            { equation: '85 - 35', answer: 50 },
            { equation: '9 * 8', answer: 72 },
            { equation: '100 + 40', answer: 140 },
            { equation: '48 - 28', answer: 20 }
        ]
    },
    {
        level: 9,
        gradient: 'linear-gradient(to bottom, #f12711, #f5af19)',
        equations: [
            { equation: '95 + 56', answer: 151 },
            { equation: '100 - 44', answer: 56 },
            { equation: '10 * 9', answer: 90 },
            { equation: '125 + 37', answer: 162 },
            { equation: '63 - 43', answer: 20 }
        ]
    },
    {
        level: 10,
        gradient: 'linear-gradient(to bottom, #00c6ff, #0072ff)',
        equations: [
            { equation: '110 + 68', answer: 178 },
            { equation: '115 - 52', answer: 63 },
            { equation: '11 * 10', answer: 110 },
            { equation: '150 + 45', answer: 195 },
            { equation: '75 - 53', answer: 22 }
        ]
    },
    {
        level: 11,
        gradient: 'linear-gradient(to bottom, #00c9ff, #92fe9d)',
        equations: [
            { equation: '12 + 12', answer: 24 },
            { equation: '25 - 15', answer: 10 },
            { equation: '10 + 5', answer: 15 },
            { equation: '20 + 20', answer: 40 },
            { equation: '9 - 9', answer: 0 }
        ]
    }
];

function getRandomEquation(level) {
    const equations = difficulties[level].equations;
    return equations[Math.floor(Math.random() * equations.length)];
}

function updateBackground(level) {
    const background = document.getElementById('background');
    background.style.background = difficulties[level].gradient;
}

function startGame() {
    score = 0;
    currentLevel = 0;
    nextQuestion();
}

function nextQuestion() {
    if (currentLevel >= difficulties.length) {
        alert("¡Felicidades! Completaste todos los niveles.");
        startGame();
        return;
    }

    updateBackground(currentLevel);
    const equationObj = getRandomEquation(currentLevel);
    document.getElementById('equation').textContent = equationObj.equation;
    const correctAnswer = equationObj.answer;
    const options = generateOptions(correctAnswer);

    options.forEach((option, index) => {
        document.getElementById(`option${index + 1}`).textContent = option;
        document.getElementById(`option${index + 1}`).dataset.answer = option;
    });

    resetTimer();
}

function generateOptions(correctAnswer) {
    const options = [correctAnswer];
    while (options.length < 4) {
        const randomOption = Math.floor(Math.random() * 100) - 50;
        if (!options.includes(randomOption)) {
            options.push(randomOption);
        }
    }
    return options.sort(() => Math.random() - 0.5);
}

function checkAnswer(button) {
    const selectedAnswer = parseInt(button.dataset.answer, 10);
    const equation = document.getElementById('equation').textContent;
    const correctAnswer = eval(equation.replace('x', '*'));

    if (selectedAnswer === correctAnswer) {
        score += 10;
        currentLevel++;
    } else {
        score = Math.max(0, score - 5);
    }

    document.getElementById('score').textContent = `x${Math.floor(score / 10)} Combo`;
    nextQuestion();
}

function resetTimer() {
    const timerBar = document.getElementById('timer-bar');
    clearTimeout(timer);
    timerBar.style.transition = 'none';
    timerBar.style.width = '100%';
    setTimeout(() => {
        timerBar.style.transition = 'width 5s linear';
        timerBar.style.width = '0';
    }, 10);

    timer = setTimeout(() => {
        alert("¡Tiempo agotado! Fin del juego.");
        startGame();
    }, 5000);
}

window.onload = startGame;
