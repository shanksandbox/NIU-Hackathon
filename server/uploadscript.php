<?php

error_reporting(0);
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

    $path = "uploads/"; //set your folder path
    $video_local=rand(0,99999)."_".str_replace(" ", "-", $_FILES['video_local']['name']);

    $tmp = $_FILES['video_local']['tmp_name'];
    
    if (move_uploaded_file($tmp, $path.$video_local)) 
    { //check if it the file move successfully.
        echo $video_local;
    } else {
        echo "failed";
    }
    exit;
}