<?php
session_start();

//Checking if the user is logged in or not
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
    //Handling requests if user is logged in
    try {
        require('connection.php');
        //storing the poll id from the GET request
        $poll_id=$_GET['poll_id'];
        //Checking if the owner of the poll wants to end it
        if(isset($_POST['end-poll'])){
            $sql = $db -> prepare("UPDATE poll SET poll_status='inactive' WHERE poll_id=?");
            $rs = $sql -> execute(array($poll_id));
            // $rs = $db->exec($sql);
            if($rs != 1){
                die("Failed poll ending");
            }
        }

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
        try{
            require('connection.php');
            //storing the poll id from the GET request
            $poll_id=$_GET['poll_id'];
            //Retreiving the poll details
            $sql= $db -> prepare("SELECT * FROM poll WHERE poll_id=?");
            $sql->execute(array($poll_id));
            $pollDetails=$sql->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($pollDetails)){
                //Displaying details for all types of users (logged in or not)
                echo "<h3> Question: " . $pollDetails[0]['question'] . "</h3>";
                echo "<h3> Poll Status: " . $pollDetails[0]['poll_status'] . "</h3>";
                if($pollDetails[0]['end_date'] != '0000-00-00' && $pollDetails[0]['poll_status'] == 'active'){
                    echo "<h3> Active Till: " . $pollDetails[0]['end_date'] . "</h3>";
                }

                if($pollDetails[0]['poll_status'] == 'active'){ //If the poll is active and guest user, show disabled voting
                    $sql3= $db -> prepare("SELECT * FROM answers WHERE poll_id=?");
                    $sql3->execute(array($poll_id));
                    $result3=$sql3->fetchAll(PDO::FETCH_ASSOC);
                    $ans_id=$result3[0]['answer_id'];
                    
                    echo "<h4>Please <a href='login.php'>Login</a> to vote.!</h4>";
                    echo '
                    <h4>Options:</h4>
                    <form action="update_vote.php?poll_id='.$poll_id.'" method="POST">  
                    ';
                    foreach ($result3 as $key => $options) {
                        extract($options);
                        if($key == 0){
                            echo '
                            <input type="radio" name="options" value="'.$answer_id.'" required disabled>'.$answer.'<br>
                            ';
                        } else {
                            echo '
                            <input type="radio" name="options" id="" value="'.$answer_id.'" disabled>'.$answer.'<br>
                            ';
                        }
                    }
                    echo '
                    <input type="submit" value="submit" name="submit-vote" disabled>
                    </form>
                    ';
                    echo "<a href='index.php'>Home</a>";

                } else { //If the poll is inactive then show results
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
            $db = null;
        } 
        catch(PDOException $e){
            die($e -> getMessage());
        }

    }
?>