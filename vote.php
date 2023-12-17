<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Page</title>
    <link rel="stylesheet" href="css/vote.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php require('header.php'); ?>
        <div id="vote-con">
            <div class="question-polldate">
                <div class="status-enddate">
                    <span>Poll Status: active</span>
                    <span>End Date: 2023-12-29</span>
                </div>
                <h2>Vote for Your Favorite Option</h2>
            </div>

            <form action="vote_handler.php" method="post">
                <div class="option">
                    <input type="radio" id="option1" name="vote_option" value="Option 1" required>
                    <label for="option1">Option 1</label>
                </div>

                <div class="option">
                    <input type="radio" id="option2" name="vote_option" value="Option 2" required>
                    <label for="option2">Option 2</label>
                </div>

                <div class="option">
                    <input type="radio" id="option3" name="vote_option" value="Option 3" required>
                    <label for="option3">Option 3</label>
                </div>

                <div class="vote-button">
                    <input type="submit" value="Vote Now">
                    <span style="padding-left:10px; color: #ff0000c2;">*Please <a id="login-vote" href="">login</a> to vote!</span>
                </div>
                <div class="vote-button">
                    <p>This poll is owned by you. Do you want to stop it?</p>
                    <input type="submit" value="End Poll">
                </div>
            </form>
        </div>
    </div>

</body>

</html>