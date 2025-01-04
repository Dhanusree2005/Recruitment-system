<?php
    //header("Content-Type: text/json");
    $fname=$_REQUEST["username"];
    $dispname=$_REQUEST["name"];

    $server="localhost";
    $dbusername="root";
    $dbpassword="";
    $dbname="inter";
    $returnValue = "";

    $conn=mysqli_connect("localhost","root","","inter");
    $sql = "SELECT * FROM user WHERE username = '$fname'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0)
    {
        $row=mysqli_fetch_array($result);
        $returnValue=$row['name'];
    }
    else
        $returnValue = "NOTFOUND";
    echo $returnValue;
?>