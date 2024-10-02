<?php
session_start();

if (!isset($_SESSION['round']) || isset($_POST['reset'])) {
    $_SESSION['round'] = 1; // Initialize the round
    $_SESSION['random'] = mt_rand(1, 100); // Initialize random number
    $_SESSION['attempts'] = 0; // Initialize attempts
    $_SESSION['guesses'] = array(); // Initialize guesses for the round
}

$x = $_SESSION['random'];

if (isset($_POST['submit'])) {
    $num = $_POST['num'];
    $_SESSION['attempts']++;
    $_SESSION['guesses'][$_SESSION['round']][] = $num; // Store the guessed number for the current round

    if ($num < $x) {
        echo "<div class='alert alert-warning'>Your number is lower!!!</div>";
    } elseif ($num > $x) {
        echo "<div class='alert alert-warning'>Your number is higher!!!</div>";
    } elseif ($num == $x) {
        echo "<div class='alert alert-success'>Congratulations! You guessed the hidden number in " . $_SESSION['attempts'] . " attempts.</div>";
        $_SESSION['round']++; // Move to the next round
        $_SESSION['random'] = mt_rand(1, 100); // Generate new random number for the next round
        $_SESSION['attempts'] = 0; // Reset attempts for the next round
        $_SESSION['guesses'][$_SESSION['round']] = array(); // Initialize guesses for the next round
    } else {
        echo "<div class='alert alert-danger'>You never set a number!!!</div>";
    }

    if ($_SESSION['attempts'] >= 5) {
        echo "<div class='alert alert-danger'>You've reached the maximum number of attempts for this round. The correct number was $x.</div>";
        $_SESSION['round']++; // Move to the next round
        $_SESSION['random'] = mt_rand(1, 100); // Generate new random number for the next round
        $_SESSION['attempts'] = 0; // Reset attempts for the next round
        $_SESSION['guesses'][$_SESSION['round']] = array(); // Initialize guesses for the next round
    }
}

if (isset($_POST['destroy'])) {
    session_unset();
    header('Location: guessnumber.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess the Number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: Arial, sans-serif; /* Font style */
        }
        .container {
            max-width: 600px;
            margin: 50px auto; /* Center the container */
            padding: 20px;
            background-color: #fff; /* White background for the container */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        h1 {
            text-align: center; /* Center the title */
        }
        .form-control {
            margin-bottom: 10px; /* Space between input and button */
        }
        .btn {
            width: 100%; /* Full-width buttons */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Guess the Number!</h1>
        <form action="guessnumber.php" method="post">
            <p>Round: <?php echo $_SESSION['round']; ?>, Prev Guesses: <?php echo implode(', ', $_SESSION['guesses'][$_SESSION['round']] ?? array()); ?></p>
            <input type="number" min="1" max="100" name="num" class="form-control" required>
            <button type="submit" name="submit" class="btn btn-primary">Guess</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p6vOiGxz5YrcchD2qk7E+mE3YPsUBoqqL72Uj2wT8uW+E0NqQG4hCoqf2K6+jSDo" crossorigin="anonymous"></script>
</body>
</html>
