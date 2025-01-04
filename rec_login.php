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
            
            $row=mysqli_fetch_array($rst);
            $dispname=$row['name'];
            $user_role=$row['Role'];
            
            if(mysqli_num_rows($rst)>0)
            {
                $sql="SELECT * FROM user where username='$fname' AND password='$fpassword' AND status='Approved'";
                $rst=mysqli_query($conn,$sql);
            
                if(mysqli_num_rows($rst)>0)
                {   
                    $_SESSION['username']=$fname;
                    $_SESSION['name']=$dispname;
                    $_SESSION['Role']=$user_role;
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
                        $checksql="SELECT * FROM user where username='$fname' AND password='$fpassword' AND status='New'";
                        $result=mysqli_query($conn,$checksql);
                        if(mysqli_num_rows($result)>0)
                        {
                            $message="<span class='chat'>$dispname, Pending for Approval</span>";
                        }
                        else
                            $message = "$dispname, is not Approved";
                        $fname="";
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <head>
    <title>Apply login</title>
       <link rel="stylesheet" href="recstyle.css"/>
       <style>
            .chat{color:#990000;}
        </style>
        <script>
            function checkuser()
            {
                const username=document.getElementById("username").value.trim();
                const uname=document.getElementById("name").value.trim();
                const xhr=new XMLHttpRequest();
                xhr.onload = function ()
                {
                    if(xhr.status===200)
                    {
                        resp = xhr.responseText;
                        if(resp ==="NOTFOUND")
                        {
                            document.getElementById('error').innerHTML = "User<span style='color:blue;font-size:bold'> "+username+
                            "</span> not found";
                            document.getElementById("username").value="";
                            document.getElementById("username").focus();
                        }
                        else 
                        {
                            document.getElementById('error').innerHTML= "Hi,<span style='color:green;font-size:bold'> "+resp+
                            "</span> type the password";
                        }
                    }
                };
                url = 'validate_login.php?username='+username+'&name='+uname;
                //alert(url)
                xhr.open('GET', url, true);
                xhr.send();
            }
            function redirectreg()
            {
                window.location.href="rec_reg.php";
            }
            function showpassword()
            {
                var pass=document.getElementById("password");
                if(pass.type==="password")
                {
                    pass.type="text";
                }
                else
                {
                    pass.type="password";
                }
            }
        </script>
    </head>
    <body style="background-image: url(img/login.jpg);background-repeat:no-repeat;background-size:cover; 
            background-color:rgba(255,255,255,0.6);">
        <form action="" method="POST">
                <div class="container">
                    <h2 class="title-name"><span>&#128674;</span>Login</h2>
                    <div id="error" class="error"><?php echo $message ?></div>
                    <div class="input-group">
                        Username<span>&#128100;</span><input type="text" name="name" id="username" placeholder="name" value="<?php echo $fname?>"
                            onchange="checkuser()"/><br>
                    </div>
                    <div class="input-group">
                        Password<span>&#128273;</span><input type="password" name="password" placeholder="Password" id="password"/>
                    </div>
                    <input style="cursor:pointer" type="checkbox" onclick="showpassword()"><span>Show Password</span>
                    <div>
                    <button class="submit-btn">Submit</button>
                    <h4 style="color:darkcyan">If not Registered? <label class="font-group" onclick="redirectreg()">Register</label></h4>
                    </div>
                    <input type="hidden" name="appname" id="name"/>
                </div>
            </div>
        </form>
    </body>
</html>