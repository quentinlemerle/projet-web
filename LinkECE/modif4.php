<?php
session_start();
include('connect.php');

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");    
}
else {
    // Makes it easier to read
    $host = 'localhost';
    $user = 'root';
    $pass = 'root'; 
    $db = 'linkece';

    $id_user = $_SESSION['id_user'];
    $mysqli = new mysqli($host,$user,$pass,$db);
    $adresse = $mysqli->escape_string($_POST["adresse"]);
    
    $sql="UPDATE users SET adresse='$adresse' WHERE id_user='$id_user'"; 
    if ($mysqli->query($sql) === TRUE) {
        $_SESSION['adresse']=$adresse;
        echo "<script type='text/javascript'> document.location = 'profil.php'; </script>";
    } 
    else{
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }    
}
?>