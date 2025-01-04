<?php
    header("Content-Type: application/json");
    $name=$_REQUEST['name'];
    $searchtype = $_REQUEST["searchtype"];

    $server="localhost";
    $dbusername="root";
    $dbpassword="";
    $dbname="inter";
    $message="";
    $resp = array();
    $conn=mysqli_connect("localhost","root","","inter");
    $query="SELECT name,dob,quali,phone,status FROM appdetails";
    if($searchtype=="SPECIFIC")
        $query .= " WHERE name LIKE '%$name%'";
        //echo($name);
    $result=mysqli_query($conn,$query);
    if(mysqli_num_rows($result)>0)
    {
        $resp["STATUS"]="Found";
        $resp["COUNT"]=mysqli_num_rows($result);
        $resp["DATA"]=array();
        while($row=mysqli_fetch_assoc($result))
        {
            $resp["DATA"][]=
            array(
                "name"=>$row["name"],
                "dob"=>$row["dob"],
                "qualification"=>$row["quali"],
                "phone"=>$row["phone"],
                "status"=>$row["status"]
            );
        }
    }
    else 
    {
        $resp["STATUS"] = "NotFound";
        $resp["COUNT"] = 0;
        $resp["DATA"]=array();
    }
        echo json_encode($resp);
?> 