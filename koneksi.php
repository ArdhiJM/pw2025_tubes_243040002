<?php
    $con = mysqli_connect("localhost","root","","pw2025_tubes_243040002");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "failed to connnect to MYSQL: " . mysqli_connect_error();
        exit();
    }
?>