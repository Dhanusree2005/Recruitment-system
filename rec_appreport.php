<?php
    include 'rec_header.php';
    session_start();
    $login_username=$_SESSION["username"];
    if(empty($login_username))
    {
        $report="Please login first for entry into Application Report";
        $_SESSION['mess']=$report;
        header("location:rec_login.php");
        exit();
    }
    $displayname=$_SESSION['name'];

    //$result = mysqli_query($conn, $sql);
    $result="";
    $message="Click the Generate the report to see all the applicant details";
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $frole=$_POST['role'];
        $fqual=$_POST['qual'];
        $fphone=$_POST['phone'];
        $flang=$_POST['lang'];
        $server="localhost";
        $dbname="inter";
        $conn=mysqli_connect("localhost","root","","inter");
        if(empty($frole) and empty($fqual) and empty($fphone) and empty($flang))
        {
            $sql="SELECT * FROM appdetails";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            $message="Give the following Details for Generating the Report";
        }
        else
        {
            //partial matching
            $sql = "SELECT * FROM appdetails WHERE 
            ('$frole' = '' OR job LIKE '%$frole%') AND 
            ('$fqual' = '' OR quali LIKE '%$fqual%') AND 
            ('$fphone' = '' OR phone LIKE '%$fphone%') AND 
            ('$flang' = '' OR lang LIKE '%$flang%')";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

            if(mysqli_num_rows($result)>0)
            {
                //given value in the form are displayed
                $message="You have queried for ".
                (!empty($frole)? "(Role:$frole )":"").
                (!empty($fqual)? "(Qualification:$fqual )":"").
                (!empty($fphone)? "(Phone Number:$fphone )":"").
                (!empty($flang)? "(Language:$flang )":"");
            }
            else
            {
                $message="<div class=err>No match found</div>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>applicant report</title>
        <link rel="stylesheet" href="recstyle.css"/>
        <style>
            body{counter-reset:section;}/*serial number using css*/
            .err{color:red;}
            .button{display:flex;}
            .fontcommand{color:darkviolet;margin:10px 4px;font-size:66%;font-size: bold;}
            .font-header{text-align: center;font-weight: bold;margin:8px;}/*heading name*/
            .buttoncommon{margin:8px 50px;padding:5px;font-size:57%;text-align:center;background-color:#4b0082;
                color:white;width:50px;border-radius: 5px;}
            .btn{margin:4px;background-color: blue;color:aliceblue;}
            .btn:hover{background-color:darkmagenta;color:white;}
            .aclass{float:left;width:97%;background-color:#f8f4ff;margin:2px;border:1px solid darkblue;padding:9px;
                border-radius: 6px;}/*common for the form box*/
            .table-cont{width:100%;height:64vh;overflow: auto;position:relative}/*common div for the entire table*/
            .table{display:grid;grid-template-columns:2% 6% 6% 6% 7% 8% 7% 7% 6% 5% 5% 5% 5% 5% 5% 5% 5% auto;
                width:200%;}/*header and the content table*/
            .header{border:1px solid black;text-align: center;font-weight: bold;padding:5px;background-color:#b57edc;}/*table header*/
            .uname{border:1px solid black;word-wrap: break-word;padding:3px;}/*applicant details rows*/
            .table:hover{cursor:pointer;background-color:aquamarine;}
            .sno::after{counter-increment: section;content:counter(section);font-size: medium;}/*serial number using css*/
            .colorbox{width:20px;height:20px;border:1px solid grey;}/*common status color box*/
            .statusbox{display:flex;gap:10px;margin:5px;background-color:whitesmoke;padding:10px}/*whole common background color box*/
            .status-color{display:flex;gap:3px;border:1px solid grey; padding: 5px 10px;text-align: center;}/*outside of the color box*/
            .samplebox{border: 1px solid white;padding:5px 10px;background-color: #f3e5f5;border-radius: 8px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); text-align: center;font-weight: bold;color: #4b0082;}
            .statusboxcolor{background-color:#191970 ;color: white; padding:5px 10px;border:3px solid #e7feff;}
            .status-color:nth-child(n+2){margin-left:5px}/*calculation for the margin-left for two box in the status color*/
            .columnsticky{position:sticky; left: 0;font-family:'Georgia';background-color: #b57edc;}/*sticky which will not move*/
            .iconsize{font-size:12px} 
            .sample{left:4%}

            .new{display: inline-block;position: relative;background-color: #faf0e6;}
            .new::after {content:"New Entry";position: absolute;bottom: 100%;left: 50%;background-color: #faf0e6;color: #000;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .new:hover::after {visibility: visible;opacity: 1;}
            .offer{display: inline-block;position: relative;background-color:#e6a8d7;}
            .offer::after {content:"Offer Letter provide";position: absolute;bottom: 100%;left: 50%;background-color: #e6a8d7;color: white;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .offer:hover::after {visibility: visible;opacity: 1;}
            .medical{display: inline-block;position: relative;background-color:#add8e6;}
            .medical::after {content:"Wait for medical report";position: absolute;bottom: 100%;left: 50%;background-color: #add8e6;color: white;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .medical:hover::after {visibility: visible;opacity: 1;}
            .selected{display: inline-block;position: relative;background-color:hsl(110,80%,85%);}
            .selected::after {content:"Approved, proceed.";position: absolute;bottom: 100%;left: 50%;background-color:hsl(110,80%,85%);color: #000;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .selected:hover::after {visibility: visible;opacity: 1;}
            .rejected{display: inline-block;position: relative;background-color:hsl(0,100%,85%);}
            .rejected::after {content:"Rejected";position: absolute;bottom: 100%;left: 50%;background-color: hsl(0,100%,85%);color: white;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .rejected:hover::after {visibility: visible;opacity: 1;}
            .onboard{display: inline-block;position: relative;background-color:#d1e189;}
            .onboard::after {content:"Joined ";position: absolute;bottom: 100%;left: 50%;background-color: #d1e189;color: white;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .onboard:hover::after {visibility: visible;opacity: 1;}
            .all{display: inline-block;position: relative;background:linear-gradient(in hsl longer hue 45deg, lightblue 0 0);}
            .all::after {content:"All applicant details";position: absolute;bottom: 100%;left: 50%;background:linear-gradient(in hsl longer hue 45deg, lightblue 0 0);color: white;padding: 2px 5px;
                font-size: small;visibility: hidden;opacity: 0;}
            .all:hover::after {visibility: visible;opacity: 1;}
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
            function showreport(status)
            {
                lines=document.querySelectorAll('#table');
                //alert(lines)
                lines.forEach(line=>{
                    if(line.getAttribute('data-status')==status||status=='All')
                    {
                        //alert(status)
                        line.style.display="grid";
                    }
                    else{
                        line.style.display="none";
                    }
                });
            }
            function colorchange(clickbutton)
            {
                let colorbutton=document.querySelector('.statusboxcolor');
                if(colorbutton)
                {
                    colorbutton.classList.remove("statusboxcolor");
                    colorbutton.classList.add("samplebox");
                }
                clickbutton.classList.remove("samplebox");
                clickbutton.classList.add("statusboxcolor");
            }
            function filterbox()
            {
                const statcolor=document.getElementById('statusbox');
                statcolor.style.display="none";
            }
        </script>
    </head>
    <body>
        Hello, <?php echo $displayname;?>
        <div class="button">
            <span class="font-name" style="display:grid;grid-template-columns:23% 55% 5% 10%;grid-gap:3px;">
                <label class="fontcommand"><?php echo $message;?></label>
                <label class="font-header">Applicant Report</label>
                <label onclick="goback()" class="back-btn buttoncommon">Back</label>
                <label onclick="back()" class="logout-btn buttoncommon">logout</label>
            </span>
        </div>
        <div class="form">
            <form action="" method="post">
                <!--form details to show related details-->
                <div class="aclass">
                    <label>Role</label>
                    <input type="text" name="role" onchange="filterbox()"/>
                    <label>Qualification</label>
                    <input type="text" name="qual" onchange="filterbox()"/>
                    <label>Phone Number</label>
                    <input type="text" name="phone" onchange="filterbox()"/>
                    <label>Language</label>
                    <input type="text" name="lang" onchange="filterbox()"/>
                    <button type="submit" class="btn">Generate a report</button>
                </div>    
            </form>
        </div>
        <?php
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {?>
            <label class="statusbox" id="statusbox">
                <div class="status-color samplebox" id="newstatus" onclick="showreport('New');colorchange(this)">
                    <div class="colorbox new"></div>
                    <span>New<span class="iconsize">&#x1F195;</span></span>
                </div>
                <div class="status-color samplebox" id="offerstatus" onclick="showreport('Offer letter');colorchange(this)">
                    <div class="colorbox offer"></div>
                    <span>Offer Letter<span class="iconsize">&#128209;</span></span>
                </div>
                <div class="status-color samplebox" id="medicalstatus" onclick="showreport('Medical Report');colorchange(this)">
                    <div class="colorbox medical"></div>
                    <span>Medical Report<span class="iconsize"> &#129658;</span></span>
                </div>
                <div class="status-color samplebox" id="selectstatus" onclick="showreport('Selected');colorchange(this)">
                    <div class="colorbox selected"></div>
                    <span>Selected<span class="iconsize"> &#128077;</span></span>
                </div>
                <div class="status-color samplebox" id="rejectstatus" onclick="showreport('Rejected');colorchange(this)">
                    <div class="colorbox rejected"></div>
                    <span>Rejected<span class="iconsize"> &#10060;</span></span>
                </div>
                <div class="status-color samplebox"id="onboardstatus" onclick="showreport('Onboard');colorchange(this)">
                    <div class="colorbox onboard"></div>
                    <span>Onboard<span class="iconsize"> &#9989;</span></span>
                </div>
                <div class="status-color samplebox" id="allstatus" onclick="showreport('All');colorchange(this)">
                    <div class="colorbox all"></div>
                    <span>All</span>
                </div>
            </label>
        <?php
        }?>
        <div class="table-cont">
            <header class="table" style="position:sticky;top:0;font-family:Georgia;z-index:4;">
                <div class="header columnsticky" >ID</div>
                <div class="header sample columnsticky">Name</div>
                <div class="header">Role</div>
                <div class="header">DOB</div>
                <div class="header">Father's Name</div>
                <div class="header">Nationality</div>
                <div class="header">Qualification</div>
                <div class="header">Address</div>
                <div class="header">Peraddress</div>
                <div class="header">Phone</div>
                <div class="header">Email</div>
                <div class="header">Aadhar Number</div>
                <div class="header">Experience</div>
                <div class="header">Language Known</div>
                <div class="header">Referral</div>
                <div class="header">Passport Number</div>
                <div class="header">Remarks</div>
                <div class="header">Status</div>
            </header>
            <?php
                if(($result))
                {
                    //$serial_number=1;
                    while($row=mysqli_fetch_array($result))
                    {
                        $statuscolor="";
                        $statusicon="";

                        if($row['status']==="New")
                        {
                            $statuscolor="background-color:#faf0e6";
                            $statusicon="&#x1F195";
                        }
                        elseif($row['status']==="Offer letter")
                        {
                            $statuscolor="background-color:#e6a8d7";
                            $statusicon="&#128209;";
                        }
                        elseif($row['status']==="Medical Report")
                        {
                            $statuscolor="background-color:#add8e6";
                            $statusicon="&#129658;";
                        }
                        elseif($row['status']==="Selected")
                        {
                            $statuscolor="background-color:hsl(110,80%,85%)";
                            $statusicon="&#128077;";
                        }
                        elseif($row['status']==="Rejected")
                        {
                            $statuscolor="background-color:hsl(0,100%,85%)";
                            $statusicon="&#10060;";
                        }
                        elseif($row['status']==="All")
                        {
                            $statuscolor="background-color:linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet)";
                        }
                        else
                        {
                            $statuscolor="background-color:#d1e189";
                            $statusicon="&#9989;";
                        }
                        echo "<div class='table'id='table' style='$statuscolor' data-status='" .$row['status'] ."'>";
                            echo "<div class='uname sno columnsticky'><span class='iconsize'>$statusicon</span></div>";
                            echo "<div class='uname columnsticky sample'>".$row['name']."</div>";
                            echo "<div class='uname'>".$row['job']."</div>";
                            echo "<div class='uname'>".$row['dob']."</div>";
                            echo "<div class='uname'>".$row['fname']."</div>";
                            echo "<div class='uname'>".$row['nation']."</div>";
                            echo "<div class='uname'>".$row['quali']."</div>";
                            echo "<div class='uname'>".$row['addr']."</div>";
                            echo "<div class='uname'>".$row['peraddr']."</div>";
                            echo "<div class='uname'>".$row['phone']."</div>";
                            echo "<div class='uname'>".$row['email']."</div>";
                            echo "<div class='uname'>".$row['aadhar']."</div>";
                            echo "<div class='uname'>".$row['exp']."</div>";
                            echo "<div class='uname'>".$row['lang']."</div>";
                            echo "<div class='uname'>".$row['ref']."</div>";
                            echo "<div class='uname'>".$row['passportno']."</div>";
                            echo "<div class='uname'>".$row['remarks']."</div>";
                            echo "<div class='uname'name='status'>".$row['status']."</div>";
                        echo"</div>";
                    }
                }
            ?>
        </div>
    </body>
</html>