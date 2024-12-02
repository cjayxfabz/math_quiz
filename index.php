
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Game</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <h2>Settings</h2>
        <div class="form-group level-control">
            <label for="level">Level:</label>
            <select id="level" name="level">
                <option value="1">Level 1 (1-10)</option>
                <option value="2">Level 2 (11-100)</option>
                <option value="3">Custom Level</option>
            </select>
        </div>
        <div class="form-group operator-control">
            <label for="operator">Operator:</label>
            <select id="operator" name="operator">
                <option value="addition">Addition</option>
                <option value="subtraction">Subtraction</option>
                <option value="multiplication">Multiplication</option>
            </select>
        </div>

        <div class="form-group range-control">
            <label for="min_range">Custom Level: Min Range</label>
            <input type="number" id="min_range" name="min_range" value="1" min="1">

            <label for="max_range">Custom Level: Max Range</label>
            <input type="number" id="max_range" name="max_range" value="10" min="1">
        </div>
        <div class="form-group max-diff-control">
            <label for="max_diff">Max Difference of Choices:</label>
            <input type="number" id="max_diff" name="max_diff" value="10" min="1" max="50">
        </div>
        <button type="submit" name="start_quiz">Start Quiz</button>
    </form>
    </div>
</body>
</html>