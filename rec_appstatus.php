<?php
    include 'rec_header.php';
    session_start();
    $login_username=$_SESSION["username"];
    if(empty($login_username))
    {
        $status="Please login first for entry into Application Status";
        $_SESSION['mess']=$status;
        header("location:rec_login.php");
        exit();
    }
    $displayname=$_SESSION['name'];
    /*$name=$_POST["name"];
    $job=$_POST["jrole"];
    $dob=$_POST["dob"];
    $nation=$_POST["nation"];
    $qual=$_POST["quali"];
    $addr=$_POST["addr"];
    $peraddr=$_POST["preadd"];
    $numb=$_POST["num"];
    $email=$_POST["mail"];
    $aadhar=$_POST["aadhar"];
    $exp=$_POST["exp"];
    $lang=$_POST["lang"];
    $ref=$_POST["ref"];
    $passno=$_POST["passno"];
    $status=$_POST["status"];
    $remark=$_POST["remark"];*/

    $statusopt=array('New','Offer letter','Medical Report','Selected','Rejected','Onboard');
    $message="Select the applicant for the status update";

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $id=$_POST['id'];
        $name=$_POST['name'];
        $appstatus=$_POST['status'];

        $server="localhost";
        $dbname="inter";
        $conn=mysqli_connect("localhost","root","","inter");

        //$checkquery="SELECT * FROM appdetails where name='$name'";
        //$rst=mysqli_query($conn,$checkquery);

        //if(mysqli_num_rows($rst)>0)
        //echo($id);
        $sqlupdate="UPDATE appdetails SET status='$appstatus' WHERE id='$id'";
        $updaterst=mysqli_query($conn,$sqlupdate);
        if($updaterst)
        {
            $message="Status Successfully updated for $name";  
        }
        else
        {
            $message="<div class='error'>Error updating successfully</div>";
        }
    }
    $conn=mysqli_connect("localhost","root","","inter");
    $sql="SELECT * FROM appdetails";
    $result=mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>applicant status</title>
        <link rel="stylesheet" href="recstyle.css"/>
        <script>
            function showdetails(rowobj)
            {   
                /*var id=rowobj.children[0].innerText;
                var name=rowobj.children[1].innerText;
                var qual=rowobj.children[2].innerText;
                var phone=rowobj.children[3].innerText;
                var email=rowobj.children[4].innerText;
                var fname=rowobj.children[5].innerText;
                var job=rowobj.children[6].innerText;
                var dob=rowobj.children[7].innerText;
                var nation=rowobj.children[8].innerText;
                var addr=rowobj.children[9].innerText;
                var peraddr=rowobj.children[10].innerText;
                var aadhar=rowobj.children[11].innerText;
                var exp=rowobj.children[12].innerText; 
                var lang=rowobj.children[13].innerText;
                var ref=rowobj.children[14].innerText;
                var passportno=rowobj.children[15].innerText;
                var status=rowobj.children[17].innerText;
                var remark=rowobj.children[16].innerText;*/
                //var headname=rowobj.children[1].innerText;
                document.getElementById('heading').innerText=rowobj.querySelector("[data-name]").innerText;
                document.getElementById('modal-id').value=rowobj.querySelector("[data-id]").innerText;
                document.getElementById('modal-name').value=rowobj.querySelector("[data-name]").getAttribute("data-name");
                document.getElementById('modal-jrole').value=rowobj.querySelector("[data-job]").innerText;
                document.getElementById('modal-dob').value=rowobj.querySelector("[data-dob]").innerText;
                document.getElementById('modal-fname').value=rowobj.querySelector("[data-fname]").innerText;
                document.getElementById('modal-nation').value=rowobj.querySelector("[data-nation]").innerText;
                document.getElementById('modal-qual').value=rowobj.querySelector("[data-quali]").innerText;
                document.getElementById('modal-addr').value=rowobj.querySelector("[data-addr]").innerText;
                document.getElementById('modal-peraddr').value=rowobj.querySelector("[data-peraddr]").innerText;
                document.getElementById('modal-phone').value=rowobj.querySelector("[data-phone]").innerText;
                document.getElementById('modal-email').value=rowobj.querySelector("[data-email]").innerText;
                document.getElementById('modal-aadhar').value=rowobj.querySelector("[data-aadhar]").innerText;
                document.getElementById('modal-exp').value=rowobj.querySelector("[data-exp]").innerText;;
                document.getElementById('modal-lang').value=rowobj.querySelector("[data-lang]").innerText;
                document.getElementById('modal-ref').value=rowobj.querySelector("[data-ref]").innerText;
                document.getElementById('modal-passno').value=rowobj.querySelector("[data-passport]").innerText;
                document.getElementById('modal-remarks').value=rowobj.querySelector("[data-remark]").innerText;
                document.getElementById('modal-status').value=rowobj.querySelector("[data-status]").innerText;
                var statusField = document.getElementById('modal-status');
                statusField.style.border = "2px solid #00008b";
                //statusField.style.backgroundColor = "#e6e6fa"; 
                statusField.style.color = "#000";
                document.getElementById('mymodal').style.display="block";
            }
            function closemodal()
            {
                document.getElementById('mymodal').style.display="none";
            }
            function back()
            {
                window.location.href="rec_login.php";
            }
            function goback()
            {
                window.location.href="rec_menu.php";
            }
            document.addEventListener('keydown',function(event)
            {
                if(event.key==='Escape')
                {
                    const popupstatus=document.getElementById('mymodal');
                    if(popupstatus.style.display==='block')
                    {
                        popupstatus.style.display='none';
                    }
                }
            });
        </script>
            <style>
                .button{display:flex;}
                .buttoncommon{margin:8px 30px;padding:3px;width:50px;font-size:57%;border: 1px solid black;
                    text-align: center;background-color:#4b0082;color:white;border-radius: 3px;}
                .successmsg{color:#39ff14; margin-bottom:3px;}
                .font{float:left;color:#e30b5d;font-weight: bold;margin:5px;}
                .font-head{text-align: center;color:#000060;margin:6%;font-size:100%;font-weight: bold;}
                .fontcommand{float:left;color:#007f66;margin:8px 10px;font-size:75%;}
                .font-header{text-align:center;margin:7px;font-weight: bold;}
                .table{display:grid;grid-template-columns:5% 15% 20% 20% 20% auto;background-color:azure;}
                .table:hover{background-color:greenyellow;cursor:pointer;}
                .header{border:1px solid black;background-color:burlywood;text-align: center;}
                .uname{border: 1px solid black;}
                .hidden{display: none;}
                .modal {display:none;position:fixed;z-index:1000;top:0;left:5%;top:17%;color:white;width:80%;height:70%;overflow: scroll;
                    background-color: rgb(248,244,255);cursor: pointer;}
                .modal label{font-size: bold;color:#000;}
                .modal >h3{margin:3px;}
                .modal input{background-color:#f0ffff;color:gray;}
                .close-box{color:white;background-color:red;cursor:pointer;float:right;padding:2px;margin:6px;}
                .close-box:hover{color:red;background-color: white;}
                .file{float:left;display:grid;grid-template-columns: 90% auto;grid-gap:10px;}
                .extclass{display:grid;grid-template-columns:30% auto;grid-gap:5px;float:none;margin:2%;
                    border-radius: 5px;}
                .status{display:grid;grid-template-columns: 20% auto;grid-gap:7px;float:right;margin:10px;width: 100%;height:30%;
                    background-color:#fff0f5;border: 1px solid white;box-shadow: 0 0 8px grey;padding:20px;border-radius: 6px;}
                .status > select{margin:8px;padding:5px;width: 50%;}
                .submitbtn{width:90%;padding:8px;border-radius: 5px;background-color: blue;color:white;margin:5px;text-align: center;}
                .submitbtn:hover{background-color:darkmagenta;color:white;cursor: pointer;}
                
            </style>
    </head>
    <body style='background: linear-gradient(to right,#A8C8E8, #FFC0CB, #C6A9E8);'>
       Hello, <?php echo $displayname?>
        <div class="button">
            <span class="font-name"style="display:grid;grid-template-columns:20% auto 5% 10%;grid-gap:5px;margin-bottom:7px;">
                <label class="fontcommand"><?php echo $message; ?></label>
                <label class="font-header">Application Status</label>
                <label onclick="goback()" class="back-btn buttoncommon">Back</label>
                <label onclick="back()" class="logout-btn buttoncommon">Logout</label>
            </span>
        </div>
        <div class="table-cont" style="font-family:Georgia, 'Times New Roman', Times, serif;">
            <header class="table">
                <div class="header">ID</div>
                <div class="header">Name</div>
                <div class="header">Qualification</div>
                <div class="header">Phone Number</div>
                <div class="header">Email</div>
                <div class="header">Status</div>
            </header>
        </div>
            <?php
                if(($result))
                {
                    $serial_number=1;
                    while($row=mysqli_fetch_array($result))
                    {
                        echo "<div class='table' onclick='showdetails(this)'>";
                            echo "<div class='uname'>".$serial_number++."</div>";
                           // echo "<div class='uname' data-name='".$row["name"]."'>The name is ".$row["name"]."</div>"; 
                            echo "<div class='uname' data-name='".$row["name"]."'>".$row["name"]."</div>";
                            echo "<div class='uname' data-quali='".$row["quali"]."'>".$row["quali"]."</div>";
                            echo "<div class='uname' data-phone='".$row["phone"]."'>".$row["phone"]."</div>";
                            echo "<div class='uname' data-email='".$row["email"]."'>".$row["email"]."</div>";
                            echo "<div class='hidden' data-id='".$row["id"]."'>".$row["id"]."</div>";
                            echo "<div class='hidden'data-fname='".$row["fname"]."'>".$row["fname"]."</div>";
                            echo "<div class='hidden'data-job='".$row["job"]."'>".$row["job"]."</div>";
                            echo "<div class='hidden'data-dob='".$row["dob"]."'>".$row["dob"]."</div>";
                            echo "<div class='hidden'data-nation='".$row["nation"]."'>".$row["nation"]."</div>";
                            echo "<div class='hidden'data-addr='".$row["addr"]."'>".$row["addr"]."</div>";
                            echo "<div class='hidden'data-peraddr='".$row["peraddr"]."'>".$row["peraddr"]."</div>";
                            echo "<div class='hidden'data-aadhar='".$row["aadhar"]."'>".$row["aadhar"]."</div>";
                            echo "<div class='hidden'data-exp='".$row["exp"]."'>".$row["exp"]."</div>";
                            echo "<div class='hidden'data-lang='".$row["lang"]."'>".$row["lang"]."</div>";
                            echo "<div class='hidden'data-ref='".$row["ref"]."'>".$row["ref"]."</div>";
                            echo "<div class='hidden'data-passport='".$row["passportno"]."'>".$row["passportno"]."</div>";
                            echo "<div class='hidden'data-remark='".$row["remarks"]."'>".$row["remarks"]."</div>";
                            echo "<div class='uname'data-status='".$row["status"]."'>".$row["status"]."</div>";
                        echo "</div>";
                    }
                }
            ?>
        </div>
        <div id="mymodal" class="modal">
            <span class="font">Update the Status of the selected Applicant <label id="heading"></label></span>
            <button class="close-box" onclick="closemodal()">X</button>
            <span class="font-head">Applicant Detail</span>
            <div id="form-cont">
               <form action="" method="post">
                    <div class="file">
                        <span class="extclass">
                            <label>Name</label>
                            <input type="text" name="name" id="modal-name"readonly/>
                            <label>Job role</label>
                            <input type="text" name="jrole" id="modal-jrole"readonly/>
                            <label>DOB</label>
                            <input type="date" name="dob" class='css' id="modal-dob"readonly/>
                            <label>Father's Name</label>
                            <input type="text" name="fname" id="modal-fname"readonly/>
                            <label>Nationality</label>
                            <input type="text" name="nation" class="css" id="modal-nation" readonly/>
                            <label>Qualification</label>
                            <input type="text" name="qual" class="qual" id="modal-qual" readonly/>
                            <label>Address</label>
                            <input type="text" name="addr" id="modal-addr" readonly/>
                            <label>Permanent address</label>
                            <input type="text" name="preadd" id="modal-peraddr" readonly/>
                            <label>Phone Number</label>
                            <input type="tel" name="num" class="qual" id="modal-phone" readonly/>
                            <label>Email</label> 
                            <input type="email" name="mail" class="qual" id="modal-email" readonly/>
                            <label>AadharCard Number</label>
                            <input type="text" name="aadhar" class="css" id="modal-aadhar"readonly/>
                            <label>Experience</label>
                            <input type="text" name="exp" id="modal-exp" readonly/>
                            <label>Language Known</label>
                            <input type="text"name="lang" id="modal-lang"readonly/>
                            <label>Referral</label>
                            <input type="text" name="ref" id="modal-ref" readonly/>
                            <label>Passport Number</label>
                            <input type="text" name="passno" class="css" id="modal-passno" readonly/>
                        </span>
                        <div class="status">
                            <label>Remarks</label>
                            <input type="text" name="remark" id="modal-remarks"style="background-color:white;
                            border:2px solid navy;padding:5px;color:black"/> 
                            <label>Status</label>
                            <select id="modal-status" name="status" style="background-color:white">
                                <?php
                                    foreach($statusopt as $jobstatus):
                                        {
                                            echo"<option value='$jobstatus'>$jobstatus</option>";
                                        }
                                    endforeach;
                                ?>
                            </select>
                            <input type="hidden" name="id" id="modal-id"/>
                            <button type="submit" class="submitbtn">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>