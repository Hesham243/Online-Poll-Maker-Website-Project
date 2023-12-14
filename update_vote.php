<?php
session_start();

if(isset($_POST['options'])){
    try {
        require('connection.php');
        $db->beginTransaction();
        $user_id=$_SESSION['user'][0];
        $answer_id=intval($_POST['options']);
        $poll_id=$_GET['poll_id'];
        $sql= $db -> prepare("INSERT INTO voted VALUES(:vid, :usrid, :ansid, :polid)");
        $sql -> bindValue(':vid','');
        $sql -> bindParam(':usrid',$user_id);
        $sql -> bindParam(':ansid',$answer_id);
        $sql -> bindParam(':polid',$poll_id);
        $rs=$sql->execute();
        if($rs==1){
            $sql2= $db -> prepare("UPDATE answers SET num_votes=num_votes+1 WHERE answer_id=?");
            $rs2=$sql2->execute(array($answer_id));
            if($rs2!=1){
                die("Error in inserting into answers");
            }
            $sql3 = $db -> prepare("UPDATE poll SET total_votes=total_votes+1 WHERE poll_id=?");
            $rs3 = $sql3 ->execute(array($poll_id));
            if($rs3 != 1){
                die("Error updating poll");
            } else {
               header("Location:view-poll.php?poll_id=$poll_id");
            }
    
        } else {
            die("Error in insertion into voted");
        }
        $db->commit();
        $db=null;
    } catch (PDOException $ex) {
        $db->rollBack();
        die("Error: ".$ex->getMessage());
    }
    }
?>