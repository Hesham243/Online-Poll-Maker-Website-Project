<?php
    $db = new PDO('mysql:host=localhost;dbname=itcs333-project-group-2;charset=utf8', 'root','rootpass');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>