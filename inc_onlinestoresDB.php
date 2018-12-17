<?php
    $errorMessage = array();
    $hostname = "localhost";
    $username = "adminer";
    $password = "seven-which-26";
    $DBName = "onlinestores1";
    $DBConnect = @new mysqli($hostname, $username, $password, $DBName);

    if ($DBConnect->connect_error) {
        $errorMessage[] = "Unable to connect to the database server." . "Error Code " . $DBConnect->connect_errno . ": " . $DBConnect->connect_error;
    }
?>