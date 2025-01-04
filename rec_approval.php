<?php
    include 'rec_header.php';
    session_start();
    $login_username=$_SESSION["username"];
    $displayname=$_SESSION['name'];
    if(empty($login_username))
    {
        $report="Please login first for entry into Request Approval";
        $_SESSION['mess']=$report;
        header("location:rec_login.php");
        exit();
    }
    
    $server="localhost";
    $username="root";
    $password="";
    $dbname="inter";
    $message="Select the User for the approval";

    $conn=mysqli_connect("localhost","root","","inter");

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        foreach ($_POST['users'] as $user) 
        {
            if (isset($user['approved']))
            {
                $status = 'Approved';
            }
            else if (isset($user['notapproved']))
            {
                $status = 'Rejected';
            }
            else
            {
                continue;  
            }            
            $username = $user['username'];
    
            $updatesql = "UPDATE user SET status='$status' WHERE username='$username'";
            $rst = mysqli_query($conn, $updatesql);
    
            if ($rst)
            {
                $message = "Status updated successfully for $username.";
            }
            else
            {
                $message = "Status update failed for user: $username.";
            }
        }
    }
    $checksql="SELECT name,username,role,status FROM user";
    $result=(mysqli_query($conn,$checksql));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Request Approval</title>
        <link rel="stylesheet" href="recstyle.css"/>
    </head>
    <style>
        body{counter-reset:section;}
        .button{display:flex;}
        .buttoncommon{margin:8px 30px;padding:3px;width:50px;font-size:57%;border: 1px solid black;
            text-align: center;background-color:#4b0082;color:white;border-radius: 3px;}
        .font-header{text-align:center;margin: 5px;}
        .fontcommand{float:left;color:#4b0082;margin:8px 10px;font-size:75%;}
        .title{width:100%;background-color: aqua;padding: 10px;text-align: center;color:#e4007c;font-size: 200%;}
        .main{width:100%;margin-top:7px;}
        .approvetable{display:grid;grid-template-columns:7% auto 15% 15% 15% 17% 15%;background-color: whitesmoke;}
        .approvetable:hover{background-color:hsl(110,80%,85%);}
        .displayvalue{border:0.5px solid black;padding:3px;}
        .header{background-color:hsl(265,30%,60%);}
        .sno::before{counter-increment:section;content:counter(section);text-align: right;}
        .checkboxapprove{accent-color:lightgreen;}
        .checkboxnotapprove{accent-color: lightcoral;}
        .submit{background-color:hsl(330,100%,70%);width:9%;padding:4px;margin:5px;border-radius: 5px;border:0.5px solid hsl(0,100%,0%);}
        .submit:hover{background-color:hsl(290,70%,80%);}
    </style>
    <script>
        function back()
            {
                window.location.href="rec_logout.php";
            }
            function goback()
            {
                window.location.href="rec_menu.php";
            } 
            function checkbox(event,sno)
            {
                const approvedbox=document.getElementById(`approved-${sno}`);
                const notapprovedbox=document.getElementById(`notapproved-${sno}`);
                const action=event.target;
                if (action===approvedbox && approvedbox.checked) 
                {
                    notapprovedbox.disabled = true;
                } 
                else if (action === approvedbox && !approvedbox.checked) 
                {
                    notapprovedbox.disabled = false;
                }

                if (action === notapprovedbox && notapprovedbox.checked) 
                {
                    approvedbox.disabled = true;
                } 
                else if (action === notapprovedbox && !notapprovedbox.checked)
                {
                    approvedbox.disabled = false;
                }
            }
    </script> 
    <body>
    Hello, <?php echo $displayname;?>
        <div class="button">
            <span class="font-name"style="display:grid;grid-template-columns:20% auto 5% 10%;grid-gap:5px;">
                <label class="fontcommand"><?php echo $message;?></label>
                <label class="font-header">Request For Approval</label>
                <label onclick="goback()" class="back-btn buttoncommon">Back</label>
                <label onclick="back()" class="logout-btn buttoncommon">Logout</label>
            </span>
        </div>
        <div class="main" style="font-family:Georgia, 'Times New Roman', Times, serif;">
            <header class="approvetable">
                <label class="displayvalue header" >S.NO</label>
                <label class="displayvalue header">Name</label>
                <label class="displayvalue header">Username</label>
                <label class="displayvalue header">Role</label>
                <label class="displayvalue header">Status</label>
                <label class="displayvalue header">Approval</label>
                <label class="displayvalue header">NotApproval</label>
            </header>
            <form action="" method="POST">
                <?php
                if($result)
                {
                    $sno=1;
                    while($row=mysqli_fetch_array($result))
                    {
                        //var_dump($row);
                        echo "<div class='approvetable'>";
                            echo "<div class='displayvalue row '>$sno</div>";
                            echo "<label class='displayvalue row'>".$row['name']."</label>";
                            echo "<label class='displayvalue row'>".$row['username']."</label>";
                            echo "<label class='displayvalue row'>".$row['role']."</label>";
                            echo "<label class='displayvalue row'>".$row['status']."</label>";
                            echo "<label class='displayvalue row checkboxapprove'>";
                            if($row['status'] == "New" || $row['status']=="Rejected")
                                echo "<input type='checkbox' id='approved-$sno' name='users[$sno][approved]' value='Approved' onclick='checkbox(event,$sno)'>";
                            echo "Approved</label>";
                            echo "<label class='displayvalue row checkboxnotapprove'>";
                            if($row['status']=="New"|| $row['status']=="Approved")
                            echo "<input type='checkbox' id='notapproved-$sno' name='users[$sno][notapproved]' value='Rejected' onclick='checkbox(event,$sno)'>";
                            echo "Not Approved</label>";
                            echo "<input type='hidden' name='users[$sno][username]['status']' value='" . $row['username'] . "'>";
                            $sno++;
                        echo "</div>";
                    }
                }
                ?>
                <button class="submit" type="submit">Update Status</button>
            </form> 
        </div>
    </body>
</html>