<?php

$host = getenv('IP');
$username = getenv('C9_USER');
$password = '';
$database = 'cheapomail';

// Starting of session.
session_start();

try{
    $connect = new PDO("mysql:host=$host;database=$database", $username, $password);
}
catch(PDOException $e){
    echo $e;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     
     // user added
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $pass = sha1($_POST["password"]);
    
    // user login
    $username = $_POST["username"];
    $pass = sha1($_POST["password"]);

    // send message
    $subject = $_POST["subject"];
    $rec = $_POST["recipients"];
    $msg = $_POST["message"];
    
    //read message
    $readID = $_POST["readID"];
    
    // Logging out
    $logout = $_POST["logout"];
    
    //login
    if(isset($username) && isset($password)){
        $sql = "SELECT * FROM Users WHERE username = '$username' AND password = 'password';";
        $stmt = $connect->query($sql);
        $res = $stmt->fetch();
        
        if($res != null){
            $_SESSION["username"] = $res["username"];
            $_SESSION["userID"] = $res["ID"];
            echo "User found";
        }
        else{
            echo "User not found. Search Again.";
        }
    }
    
    //add a user
    if (isset($username) && isset($password) && isset($firstname) && isset($lastname)){
        $sql = "INSERT INTO users(firstname, lastname, username, password) VALUES('$firstname', '$lastname', '$username', '$password');";
        $connect->exec($sql);
        
        echo 'User added and can now use CheapoMail.';
    }
    
    //user logout
    if($logout == "true"){
        session_unset();
        session_destroy();
    }

    // read message
    if(isset($readerID)){
        $date_read = date("YY/MM/DD");
        $userID = $_SESSION["userID"];
        
        $sql = "INSERT INTO Read_msgs(messageID, readerID, date_read) VALUES('$readID', '$userID', '$date_read');";
        $connect->exec($sql);
        
        echo "Message read.";
    }
    
    //send a message
    if (isset($rec) && isset($subject) && isset($msg)){
        
        //sender ID
        $sendID = $_SESSION["userID"];
    
        $date_sent = date("YY/MM/DD");
        
        $rec = explode(",", $rec); //split strings by comma
    
        //insert message for each recipient
        foreach($rec as $recp){
            
            //receiver ID
            $stmt1 = $connect->query("SELECT id FROM Users WHERE username = '$recp'");
            $sender = $stmt1->fetch();
            $recv = $sender["ID"];
    
            // send query
            $q = "INSERT INTO Messages(recID, userID, subject, msg, date_sent) VALUES('$recv', '$sender', '$subject', '$msg', '$date_sent');";
            $connect->exec($q);
        }
        
        echo 'Message sent';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // user id to recieve mail
    $recvr = $_SESSION["userID"];
    $getMsg = strip_tags($_GET["getMessage"]);
    
    if ($getMsg == 'true'){
        
        $stmt = $connect->query("SELECT * FROM Messages WHERE recID= '$rcvr' ORDER BY date_sent LIMIT 10;");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt2 = $conn->query("SELECT ID FROM Read_msgs;");
        $res2 = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);
        
        if(count($res) == 0){
            echo "<h2>Message not found!</h2>";
        }
        
        else{
            foreach($res as $msg){
                
                $new = $connect->query("SELECT username FROM Users WHERE ID = '" . $msg["userID"] . "';");
                $send = $new->fetch();
                
                if (in_array($msg["ID"], $res2)){
                    echo '<div class="msgread">';
                }
                else{
                    echo '<div class="msgunread">';
                }
                
                echo '<p>From: ' . $send["username"] . '</p>';
                echo '<p>Subject: ' . $msg["subject"] . '</p>';
                echo '<p class="recv"> Message: ' . $msg["msg"] . '</p>';
                echo '<input type="submit" class="submit" value="Read"/>';
                echo '<p class="hide">' . $msg["ID"] . '</p>';
                echo '</div> <br><br>';
            }
        }
    }
}