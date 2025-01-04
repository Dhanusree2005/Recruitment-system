<!DOCTYPE html>
<?php
    include 'rec_header.php';
    $fname = "";
    $appname="";
    $fpassword = "";
    $message = ""; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $appname=$_POST["displayname"];
        $fname = $_POST["name"];
        $fpassword = $_POST["password"];
        $frole = $_POST["role"];
        $status=$_POST["status"];

        if (empty($fname)) 
        {
            $message = "<div class='error'>Enter username</div>";
        }
        else 
        {
            $server = "localhost";
            $dbname = "root";
            $dbpassword = "";
            $dbname = "inter";

            $conn = mysqli_connect("localhost", "root", "", "inter");
            $sql="SELECT * FROM user where username='$fname'";
            $rst=mysqli_query($conn,$sql);
            
            if(mysqli_num_rows($rst)>0)
            {
                $sql="SELECT * FROM user where username='$fname' AND password='$fpassword'";
                $rst=mysqli_query($conn,$sql);
                if(mysqli_num_rows($rst)>0)
                {
                    $message="<div class='allreg'>Already registered</div>";
                }
                else
                {
                    $message="<div class='exist'>Existing Username</div>";
                }
            }
            else
            {

                if(empty($fpassword))
                {
                    $message="<div class='error'>Enter password</div>";
                }
                else if(empty($frole))
                {
                    $message="<div class='error'>Enter role</div>";
                }
                else
                {
                    $sql = "INSERT INTO user(name,username, password, role,status) VALUES('$appname','$fname', '$fpassword', '$frole','New')";
                    $result = mysqli_query($conn, $sql);
                    $message ="<div class='success'>Successfully registered</div>";
                    $appname="";
                    $fname = "";
                    $fpassword = "";
                } 
            }    
        }
    } 
    else 
        $message = "<div class='welcome'>Create a new username</div>";
    
?>    
<html>
    <head>
        <title>Register form</title>
    </head>
    <link rel="stylesheet" href="recstyle.css"/>
    <script>
            function redirectlogin()
            {
                window.location.href="rec_login.php";
            }
            function checkusername()
            {
                const user=document.getElementById('username').value.trim();
                const xr=new XMLHttpRequest();
                xr.onload=function()
                {
                    if(xr.status===200)
                    {
                        res=xr.responseText;
                        //alert(res)
                        if(res==="NOTFOUND")
                        {
                            document.getElementById('ajaxmess').innerText=user+" is New Username,Type the name and password";
                        }
                        else
                        {
                            document.getElementById('ajaxmess').innerText=user+" is Existing,Create new username";
                            document.getElementById('username').value="";
                            document.getElementById('username').focus();
                        }
                    }
                };
                url='validate_login.php?username='+user+"&name=''";
                xr.open("GET",url,true);
                xr.send();
            }
        </script>
            <style>
                .input_cont{display:grid;grid-template-columns: 40% 50%;grid-gap:5px;line-height: 150%;}
                .ajaxmess{color:crimson;font-size:bold;}
                input:focus{background-color:lightcyan;}
                .infocont{margin:10px;padding:5px;font-size: bold;color:teal}
                input{padding: 4px;}
                select{padding: 4px;}
            </style>
    <body style="background-image:url('img/register.jpg');background-repeat:no-repeat;background-size:cover;">
        <form action="" method="POST">
            <div class="container">
                <h1 class="title-name"><span>&#128741;&#65039;</span>Register</h1>
                <div class="ajaxmess" id="ajaxmess"><?php echo $message; ?></div>
                <div class="input_cont">
                    <label>Username</label> 
                    <input type="text" name="name" id="username" placeholder="username" onchange="checkusername()" 
                    value="<?php echo $fname ?>" />
                    <label>Name</label>
                    <input type="text" name="displayname" placeholder="Name" value="<?php echo $appname;?>"/>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="password"/>
                    <label for="role">Role</label>
                    <select name="role">
                        <option value="">Select option</option>
                        <option value="User">User</option>
                        <option value="Manager">Manager</option>
                    </select><br><br>
                    <input type="hidden" name="status"/>
                    <button class="submit-btn" type="submit">Submit</button>                  
                </div>
                <span class="infocont">If already Registered? <label class="font-group" onclick="redirectlogin()">Login</label></span> 
            </div>
        </form>
    </body>
</html>
