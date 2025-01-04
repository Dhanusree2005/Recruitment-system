<?php
include 'rec_header.php';
session_start();
$login_username=$_SESSION["username"];
$displayname=$_SESSION['name'];
$user=$_SESSION['Role'];

    if(empty($user))
    {
        $message="Please login first for entry into Dashboard";
        $_SESSION['mess']=$message;
        header("location:rec_login.php");
        exit();
    }
    $design="";
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $faction=$_POST["action"];

        if($faction=="application")
        {
            header("Location:rec_appdata.php");
            exit();
        }
        else if($faction=="status")
        {
            header("Location:rec_appstatus.php");
            exit();
        }
        else if($faction=="report")
        {
            header("Location:rec_appreport.php");
            exit();
        }
        else if($faction=="approval")
        {
            header("Location:rec_approval.php");
            exit();
        }
        else
        {
            header("location:rec_logout.php");
            exit();
        }
    }
// Applicant Details summary
$appdata=array(
    //"title"=>"Application Details",
    "content"=>"The application system stores and manages applicant details, including personal,
     professional, and additional information, for recruiters to efficiently review profiles."
    );

//Applicant Status summary:

$appstatus=array(
    //"title"=>"Application Status",
    "content"=>"The status shows the candidate's progress in the recruitment process.
        It indicates if they are new, shortlisted, interviewed, selected, onboarded, or rejected."
);

//Applicant Report summaryd:

$appreport=array(
    //"title"=>"Application Report",
    "content"=>"The report tracks the applicant's progress from submission to the final decision.
        It helps recruiters manage the process and decide on the next steps."
);
$reqapproval=array(
    "content"=>"The request approval feature allows higher officials to grant or deny access to the website.
         Users can log in and proceed based on the approval status."
);
$combindata = array(
    "Application Details" => $appdata,
    "Application Status" => $appstatus,
    "Application Report" => $appreport,
    "Request Approval" =>$reqapproval
);
$jsondata=json_encode($combindata);
$boxcolor=array(
    "application" => "#fff5e1",
    "status" => "#d6f0e0",
    "report" => "#d0e7f6",
    "approval"=>"#f7e6a7"
);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="recstyle.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script>
            function goto(nameobj)
            {
                document.getElementById('action').value=nameobj;
                document.getElementById('myform').submit();
            }
            const contentjson=<?php echo $jsondata;?>;
            function showpopup(key)
            {
                const popup=document.getElementById('box');
                if(contentjson[key])
                {
                    
                    popup.style.display="none";
                    document.getElementById('popupheader').innerText=key;
                    document.getElementById('popupcont').innerText=contentjson[key].content;
                }
                const box=document.getElementById("popupheader");
            }
            function clearpopup()
            {
                const popup=document.getElementById('box');
                //const box=document.querySelector(".popupheader");
                popup.style.display="none";
            }
        </script>
        <style>
            .phpmess{font-size: 200%;}
            .menu i {margin-right: 10px;}
            .type{position:absolute;margin:4px; top:30%;left:2%;background-color:#ffefd5;color:#23297a;
                width:50%;height:0;overflow:hidden;transition:all 1s linear;}
            .font-title:hover+ .type{height: 50px;padding:4px;line-height: 150%;}
            .box{display:none;position:absolute;width:25%;max-height:25%;background-color:var(--color-design,purple);
                bottom:9%;right:5px;line-height: 120%;}
            .font-title:hover ~ .box{display: block;}
            .popupheader{text-align:center;background-color:rgba(255,255,255,0.4);margin:1px;}
            .popupcont{padding:4px;box-sizing:border-box;background-color:rgba(255,255,255,0.6);margin:6px 4px;}
            footer {background-color: #343a40;color: white;text-align: center;position: absolute;bottom: 0;left:0;width: 100%;}
        </style>
    </head>
    <body style="background-image: url(img/boat.jpg);background-repeat:no-repeat;background-size:cover;font-family:'Gill Sans',
     'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
    <label class="phpmess">Hi <?php echo $displayname;?>, what would you like to do</label>
        <form id="myform" action="" method="POST"> 
            <?php if($user=='User'|| $user=='Manager')
            {?>
                <div class="font-title" onclick="goto('application')" onmouseover="showpopup('Application Details')"
                onmouseout="clearpopup()">
                    <label><i class="fa fa-address-card"></i> Applicant Details</label>
                </div>
                <?php summary($appdata,$boxcolor['application']);
            }?>
            <?php if($user=='Manager')
            {?>
                <div class="font-title" onclick="goto('status')"onmouseover="showpopup('Application Status')"
                onmouseout="clearpopup()">
                    <label><i class="fa fa-chart-line"></i> Applicant Status</label>
                </div>
                <?php summary($appstatus,$boxcolor['status']);
            }?>
            <?php if ($user == 'User' || $user == 'Manager')
            {?>
                <div class="font-title" onclick="goto('report')" onmouseover="showpopup('Application Report')"
                onmouseout="clearpopup()">
                    <label><i class="fa fa-file-alt"></i> Applicant Report</label>
                </div>
                <?php summary($appreport,$boxcolor['report']);
            }?>
            <?php if ($user == 'Manager')
            {?>
                <div class="font-title" onclick="goto('approval')" onmouseover="showpopup('Request Approval')" onmouseout="clearpopup()">
                    <label><i class="fa fa-thumbs-up"></i> Request Approval</label>
                </div>
                <?php summary($reqapproval,$boxcolor['approval']);
            }?>
            <div class="font-title" onclick="goto('logout')">
                <label><i class="fa fa-sign-out-alt"></i> Logout</label>
            </div>
            <input type="hidden" id="action" name="action"/>
        </form>
        <section class="box" id="box">
            <header class="popupheader" id="popupheader">Header</header>
            <article class="popupcont" id="popupcont">Content</article>
            <span></span>
        </section>
        <footer>
            <p>&copy; 2024 Recruitment System. All rights reserved.</p>
        </footer>
    </body>
</html>
<!--summary of the box-->
<?php
 function summary($contentarr,$desgincolor)
 {
    echo "<section class='type'style='background-color:$desgincolor;'>";
        foreach($contentarr as $eachline)
        {
            echo "$eachline <br>";
        }
    echo "</section>";
 }
?>