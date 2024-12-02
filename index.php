
<?php
session_start();

if (!isset($_SESSION['settings'])) {
    $_SESSION['settings'] = [
        'level' => 1,
        'operator' => 'addition',
        'num_items' => 5,
        'max_diff' => 10,
        'min_range' => 1,
        'max_range' => 10,
    ];
}

function generateProblem($level, $operator, $min_range = null, $max_range = null, $max_diff = 10) {
    if ($level === 3) {
        $min = $min_range;
        $max = $max_range;
    } else {
        $min = $level === 1 ? 1 : 11;
        $max = $level === 1 ? 10 : 100;
    }

    $num1 = rand($min, $max);
    $num2 = rand($min, $max);

    switch ($operator) {
        case 'subtraction':
            $answer = $num1 - $num2;
            $symbol = '-';
            break;
        case 'multiplication':
            $answer = $num1 * $num2;
            $symbol = '×';
            break;
        case 'addition':
        default:
            $answer = $num1 + $num2;
            $symbol = '+';
            break;
    }


    $choices = [$answer];
    while (count($choices) < 4) {
        $option = $answer + rand(-$max_diff, $max_diff);
        if (!in_array($option, $choices)) {
            $choices[] = $option;
        }
    }

    shuffle($choices); 

    return [$num1, $symbol, $num2, $answer, $choices];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_quiz'])) {
        $_SESSION['settings']['level'] = (int)$_POST['level'];
        $_SESSION['settings']['operator'] = $_POST['operator'];
        $_SESSION['settings']['num_items'] = (int)$_POST['num_items'];
        $_SESSION['settings']['max_diff'] = (int)$_POST['max_diff'];

        if ($_POST['level'] == 3) {
            $_SESSION['settings']['min_range'] = (int)$_POST['min_range'];
            $_SESSION['settings']['max_range'] = (int)$_POST['max_range'];
        }

        $_SESSION['quiz'] = [
            'problems' => [],
            'score' => 0,
            'correct' => 0,
            'wrong' => 0,
        ];

        for ($i = 0; $i < $_SESSION['settings']['num_items']; $i++) {
            $_SESSION['quiz']['problems'][] = generateProblem(
                $_SESSION['settings']['level'],
                $_SESSION['settings']['operator'],
                $_SESSION['settings']['min_range'],
                $_SESSION['settings']['max_range']
            );
        }
    } elseif (isset($_POST['answer'])) {
        if (!empty($_SESSION['quiz']['problems'])) {
            $current = array_shift($_SESSION['quiz']['problems']);
            $userAnswer = (int)$_POST['answer'];

            if ($userAnswer === $current[3]) {
                $_SESSION['quiz']['correct']++;
                $_SESSION['quiz']['score'] += 10;
            } else {
                $_SESSION['quiz']['wrong']++;
            }
        }
    } elseif (isset($_POST['restart'])) {
        unset($_SESSION['quiz']);
    }
}

$gameOver = isset($_SESSION['quiz']['problems']) && empty($_SESSION['quiz']['problems']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Game</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Simple Math Quiz PHP</h1>

        <?php if ($gameOver): ?>
            <h2>Game Over</h2>
            <div class="score-board">
                <p>Score: <?php echo $_SESSION['quiz']['score']; ?></p>
                <p>Correct: <?php echo $_SESSION['quiz']['correct']; ?></p>
                <p>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></p>
            </div>
            <form method="post">
                <button type="submit" name="restart">Restart Quiz</button>
            </form>
        <?php else: ?>
            <?php if (!isset($_SESSION['quiz'])): ?>
    <form method="post">
    <h2>Settings</h2>
            <label for="level">Level:</label>
            <select id="level" name="level">
                <option value="1">Level 1 (1-10)</option>
                <option value="2">Level 2 (11-100)</option>
                <option value="3">Custom Level</option>
            </select>
            <label for="operator">Operator:</label>
            <select id="operator" name="operator">
                <option value="addition">Addition</option>
                <option value="subtraction">Subtraction</option>
                <option value="multiplication">Multiplication</option>
            </select>
            <label for="min_range">Custom Level: Min Range</label>
            <input type="number" id="min_range" name="min_range" value="1" min="1">

            <label for="max_range">Custom Level: Max Range</label>
            <input type="number" id="max_range" name="max_range" value="10" min="1">
            <label for="max_diff">Max Difference of Choices:</label>
            <input type="number" id="max_diff" name="max_diff" value="10" min="1" max="50">
        <button type="submit" name="start_quiz">Start Quiz</button>
    </form>

        <?php else: ?>
            <h2>Question</h2>
            <div class="question">
            <?php $current = $_SESSION['quiz']['problems'][0]; ?>
            <p><?php echo "{$current[0]} {$current[1]} {$current[2]} = ?"; ?></p>
        </div>
        <form method="post">
            <?php
            $choices = $current[4]; 
            $labels = ['A', 'B', 'C', 'D']; 
            foreach ($choices as $index => $choice): ?>
                <button type="submit" name="answer" value="<?php echo $choice; ?>" class="choice-button">
                    <?php echo "{$labels[$index]}: {$choice}"; ?>
                </button>
                <br>
            <?php endforeach; ?>
            </form>
            <div class="stats">
                <span>Score: <?php echo $_SESSION['quiz']['score']; ?></span>
                <span>Correct: <?php echo $_SESSION['quiz']['correct']; ?></span>
                <span>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></span>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>