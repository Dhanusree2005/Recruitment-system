<?php
    include 'rec_header.php';
    session_start();
    $fname="";
    $fpassword="";
    $message="Enter username and password";
    $color="blue";
    //$redirect=$_SESSION['mess'];
    //$alert=$_SESSION['alert'];
    
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $fname=$_POST["name"];
        $fpassword=$_POST["password"];
        
        if(empty($fname))
        {
            $message="Enter username";
        }
        else
        {
            $server="localhost";
            $dbusername="root";
            $dbpassword="";
            $dbname="inter";

            $conn=mysqli_connect("localhost","root","","inter");

            $sql="SELECT * FROM user where username='$fname'";
            $rst=mysqli_query($conn,$sql);
            
            if(mysqli_num_rows($rst)>0)
            {
                $sql="SELECT * FROM user where username='$fname' AND password='$fpassword'";
                $rst=mysqli_query($conn,$sql);
            
                if(mysqli_num_rows($rst)>0)
                {   
                    $_SESSION['username']=$fname;
                    header("location:rec_menu.php?");
                    exit;
                }
                else
                {
                    if(empty($fpassword))
                    {
                        $message="Enter password";
                    }
                    else 
                    {
                        $message = "Invalid password";
                    }
                }    
            }
            else
            {
                $message="Incorrect username";
            }
        }
    }
    else
    {   
        if(isset($_SESSION['mess']))
        {
            $message=$_SESSION['mess'];
            unset($_SESSION['mess']);
        }
        /*else if(isset($_SESSION['alert']))
        {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        else if(isset($_SESSION['status']))
        {
            echo $_SESSION['status'];
            unset($_SESSION['status']);
        }
        else if(isset($_SESSION['report']))
        {
            echo $_SESSION['report'];
            unset($_SESSION['report']);
        }*/
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
       <link rel="stylesheet" href="recstyle.css"/>
        <script>
            function redirectreg()
            {
                window.location.href="rec_reg.php";
            }
        </script>
    </head>
    <body>
        <form action="" method="POST">
                <div class="container">
                    <h2 class="title-name">Login <span>&#128100;</span></h2>
                    <div class="error"><?php echo $message ?></div>
                    <div class="input-group">
                        Username <input type="text" name="name" placeholder="name" value="<?php echo $fname?>"/><br>
                    </div>
                    <div class="input-group">
                        Password <input type="password" name="password" placeholder="Password"/>
                    </div>
                    <button class="submit-btn">Submit</button>
                        <h4>If not Registered? <label class="font-group" onclick="redirectreg()">Register</label></h4>
                        
                </div>
            </div>
        </form>
    </body>
</html>