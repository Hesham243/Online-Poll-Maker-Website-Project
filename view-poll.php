<?php
    session_start();

    //Initialize all required variables
    $user_id = '';
    $loggedIn = false;
    $isOwner = false;
    $status = 'inactive';
    $end_date = '0000-00-00';
    $voted = false;
    $total_votes = 0;
    $question = '';

    //Get the poll id from the GET method
    if(isset($_GET['poll_id'])){
        $poll_id = $_GET['poll_id'];
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){  //Set user id if user is logged in
            $user_id = $_SESSION['user'][0];
            $loggedIn = true;
        }
<<<<<<< HEAD

        //Retreiving the poll details
        $sql= $db -> prepare("SELECT * FROM poll WHERE poll_id=?");
        $sql->execute(array($poll_id));
        $pollDetails=$sql->fetchAll(PDO::FETCH_ASSOC);
        if(count($pollDetails) != 0){
            //Displaying details for all types of users (logged in or not)
            echo "<h3> Poll Status: " . $pollDetails[0]['poll_status'] . "</h3>";
            if($pollDetails[0]['end_date'] != '0000-00-00' && $pollDetails[0]['poll_status'] == 'active'){
                echo "<h3> Active Till: " . $pollDetails[0]['end_date'] . "</h3>";
            }
            echo "<h3> Question: " . $pollDetails[0]['question'] . "</h3>";

            //only allow votes on active polls
            if($pollDetails[0]['poll_status'] == 'active'){
                $user_id=$_SESSION['user'][0]; //get user id from the session

                if($user_id == $pollDetails[0]['user_id']){
                    //Allow owner to terminate the poll
                    echo "<span>This poll is owned by you. Do you want to stop it?</span>";
                    echo "<form action='view-poll.php?poll_id=".$poll_id."' method='POST'>";
                    echo "<input type='submit' name='end-poll' value='End Poll'>";
                    echo "</form>";
                }

                //Checking whether the user has previously voted or not
                $sql2=$db -> prepare("SELECT * FROM voted WHERE user_id=? and poll_id=?");
                $sql2->execute(array($user_id,$poll_id));
                $result2=$sql2->fetchAll(PDO::FETCH_ASSOC);
                
                //If the user has not already voted display the poll options as radio buttons to allow voting
                if(count($result2)==0){
                    $sql3= $db -> prepare("SELECT * FROM answers WHERE poll_id=?");
                    $sql3 ->execute(array($poll_id));
                    $result3=$sql3->fetchAll(PDO::FETCH_ASSOC);
                    $ans_id=$result3[0]['answer_id'];

                    //send the checked answer to the update_vote.php file
                    echo '
                    <h4>Options:</h4>
                    <form action="update_vote.php?poll_id='.$poll_id.'" method="POST">  
                    ';
                    foreach ($result3 as $key => $options) {
                        extract($options);
                        if($key == 0){
                            echo '
                            <input type="radio" name="options" value="'.$answer_id.'" required>'.$answer.'<br>
                            ';
                        } else {
                            echo '
                            <input type="radio" name="options" id="" value="'.$answer_id.'">'.$answer.'<br>
                            ';
                        }
                    }
                    echo '
                    <input type="submit" value="submit" name="submit-vote">
                    </form>
                    ';
                    echo "<a href='index.php'>Home</a>";


                } else{
                    //If the user has already voted on the poll then show results only
                    $total_votes = $pollDetails[0]['total_votes'];
                    $sql3= $db -> prepare("SELECT * FROM answers WHERE poll_id=?");
                    $sql3->execute(array($poll_id));
                    $result3=$sql3->fetchAll(PDO::FETCH_ASSOC);
                    $ans_id=$result3[0]['answer_id'];
                    echo '
                    <h4>Results:</h4>
                    <h4>Total Votes: ' . $total_votes . '</h4>
                    ';
                    foreach ($result3 as $key => $options) {
                        extract($options);
                        if($total_votes == 0){
                            $ansPercentage =0;
                        } else{
                        $ansPercentage = round(($num_votes / $total_votes),2)*100;
                        }
                        echo "Percentage:" . $ansPercentage . "%, Number of votes: " . $num_votes. "<br>";
                        echo '
                        option:'.$answer.'
                        <div class="option" style="width:400px; border: 1px solid black; border-radius:20px;">
                        <div style="background-color:blue;color:white;width:'.$ansPercentage.'%; border-radius:10px; height:20px;">
                        </div>
                        </div><br>
                        ';
                    }
                    echo "<a href='index.php'>Home</a>";
                }


            } else{
                //If the poll is inactive then show results
                $total_votes = $pollDetails[0]['total_votes'];
                $sql3= $db -> prepare("SELECT * FROM answers WHERE poll_id=?");
                $sql3->execute(array($poll_id));
                $result3=$sql3->fetchAll(PDO::FETCH_ASSOC);
                $ans_id=$result3[0]['answer_id'];
                echo '
                <h4>Results:</h4>
                <h4>Total Votes: ' . $total_votes . '</h4>
                ';
                foreach ($result3 as $key => $options) {
                    extract($options);
                    if($total_votes == 0){
                        $ansPercentage =0;
                    } else{
                    $ansPercentage = round(($num_votes / $total_votes),2)*100;
                    }
                    echo "Percentage:" . $ansPercentage . "%, Number of votes: " . $num_votes. "<br>";
                    echo '
                    option:'.$answer.'
                    <div class="option" style="width:400px; border: 1px solid black; border-radius:20px;">
                    <div style="background-color:blue;color:white;width:'.$ansPercentage.'%; border-radius:10px; height:20px;">
                    </div>
                    </div><br>
                    ';
                }
                echo "<a href='index.php'>Home</a>";
            }


        } else {
            die("400 BAD REQUEST");
        }

        $db=null;
    } catch (PDOException $ex) {
        die("Error: ". $ex->getMessage());
    }
} else{ //If the user is not logged in then show disabled vote or results based on poll status active/inactive
=======
>>>>>>> cb4cc90401aff01a1b41d0c0b0c9756b6daed07f
        try{
            require('connection.php');  //Connect to DB
            
            //Check whether the user wants to end the poll if he is the owner
            if(isset($_POST['end_poll'])){
                $endPollSql = $db -> prepare("UPDATE poll SET poll_status='inactive' WHERE poll_id=?");
                $rs = $endPollSql -> execute(array($poll_id));
                if($rs != 1){
                    die("Failed poll ending");
                }
            }

            //Get the poll details
            $getPollSql = $db -> prepare("SELECT * FROM poll WHERE poll_id=?");
            $getPollSql -> execute(array($poll_id));
            $pollDetails = $getPollSql->fetchAll(PDO::FETCH_ASSOC);

            if(count($pollDetails) != 0){
                if($user_id == $pollDetails[0]['user_id']){ //Check poll ownership
                    $isOwner = true;
                }
                if($pollDetails[0]['poll_status'] == 'active'){ //Check poll status
                    $status = 'active';
                }
                if($pollDetails[0]['end_date'] != '0000-00-00'){ //Check poll end date
                    $end_date = $pollDetails[0]['end_date'];
                }
                
                //update variables
                $total_votes = $pollDetails[0]['total_votes'];
                $question = $pollDetails[0]['question'];

                //Get the options associated with the poll
                $getOptionsSql = $db -> prepare("SELECT * FROM answers WHERE poll_id=?");
                $getOptionsSql -> execute(array($poll_id));
                $options = $getOptionsSql -> fetchAll(PDO::FETCH_ASSOC);
                if(count($options) == 0){
                    die("Error Retreiving Data");
                }

                //Check is user previously voted or not
                $checkVoteSql = $db -> prepare("SELECT * FROM voted WHERE user_id=? AND poll_id=?");
                $checkVoteSql -> execute(array($user_id,$poll_id));
                $voteResult = $checkVoteSql->fetchAll(PDO::FETCH_ASSOC);
                if(count($voteResult) > 0){
                    $voted = true;
                }
            } else {
                die("400 BAD REQUEST");
            }
        }
        catch(PDOExeption $e){
            die($e -> getMessage());
        }
    } else {
        die("400 BAD REQUEST");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Page</title>
    <link rel="stylesheet" href="css/vote.css">
    <link rel="stylesheet" href="css/view-result.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php require('header.php'); ?>
        <div id="vote-con">
            <div class="question-polldate">
                <div class="status-enddate">
                    <?php
                        if($status == 'active' && !$voted){
                            echo "<span>Poll Status: <font style='color:green'>". strtoupper($status) ."</font></span><br>";
                            if($end_date != '0000-00-00'){
                              echo "<span>End Date: $end_date</span>";
                            }
                            echo "<h1>Question: $question</h1>";
                            echo "<h2>Vote for Your Favorite Option</h2>";
                        }
                        
                    ?>
                </div>
            </div>

            <?php
                if($voted || $status == 'inactive'){   //Show results when poll is ended or user already voted
            ?>
            <div class="result">
        <h2 id="poll-result">Poll Results</h2>
        <?php
        if($status == 'active'){
            echo '<h3 style="color: #918181; font-size: 15px;">Poll Status: <font style="color:green">ACTIVE</font></h3>';
            if($end_date != '0000-00-00'){
                echo "<span>End Date: $end_date</span>";
              }
        } else{
            echo '<h3 style="color: #918181; font-size: 15px;">Poll Status: <font style="color:red">INACTIVE</font></h3>';
        }
        ?>
        <h3>Question: <?php echo $question;?></h3>
        <?php
            foreach($options as $option){
                extract($option);
                if($total_votes == 0){
                    $percentage = 0;
                } else {
                $percentage = round(($num_votes/$total_votes),2)*100;
                }
        ?>
        <div class="option-container">
            <div class="option">
                <div class="option-details">
                    <p>Option: <?php echo $answer; ?></p>
                    <p class="percentage"><?php echo $percentage."%"; ?></p>
                </div>
                <div style="border:1px solid; border-radius: 5px;">
                    <div class="bar" style="width: <?php echo $percentage."%"; ?>;"></div>
                </div>
                <p class="votes-details">Number of votes: <?php echo $num_votes; ?></p>
            </div>
        </div>
        <?php } ?>
        <h4>Total Votes: <?php echo $total_votes; ?></h4>

        <?php
            if($voted && $status == 'active'){
                if($isOwner){
                    echo '<form method="POST" <div class="vote-button">
                    <p>This poll is owned by you. Do you want to stop it?</p>
                    <input type="submit" value="End Poll" name="end_poll">
                </div></form>';
                }
            } 
        ?>

        <?php } else if(!$voted && $loggedIn){  //Allow voting if poll is still active and user has not voted
        ?>
            <form action="update_vote.php?poll_id=<?php echo $poll_id; ?>" method="post">
                <?php
                $i=1;
                    foreach($options as $option){
                        extract($option);
                ?>
                <div class="option">
                    <input type="radio" id="option<?php echo $i; ?>" name="options" value="<?php echo $answer_id; ?>" required>
                    <label for="option<?php echo $i; ?>"><?php echo $answer; ?></label>
                </div>
                <?php $i++; } ?>

                <div class="vote-button">
                    <input type="submit" value="Vote Now">
                </div>
                </form>
                <?php
                if($isOwner){
                ?>
                <form action="" method="POST">
                <div class="vote-button">
                    <p>This poll is owned by you. Do you want to stop it?</p>
                    <input type="submit" value="End Poll" name="end_poll">
                </div>
                <?php }?>
                </form>
            <?php } else if(!$loggedIn){  //Disable voting if user is not logged in
                ?>
                    <form action="update_vote.php?poll_id=<?php echo $poll_id; ?>" method="post">
                <?php
                $i=1;
                    foreach($options as $option){
                        extract($option);
                ?>
                <div class="option">
                    <input type="radio" id="option<?php echo $i; ?>" name="options" value="<?php echo $answer_id; ?>" required disabled>
                    <label for="option<?php echo $i; ?>"><?php echo $answer; ?></label>
                </div>
                <?php $i++; } ?>

                <div class="vote-button">
                    <input type="submit" value="Vote Now" disabled>
                    <span style="padding-left:10px; color: #ff0000c2;">*Please <a id="login-vote" href="login.php">login</a> to vote!</span>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>

</body>

</html>