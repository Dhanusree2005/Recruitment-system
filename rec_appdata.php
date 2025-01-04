<!DOCTYPE html>
<?php
    include 'rec_header.php';
    session_start();
    $login_username=$_SESSION["username"];
    $displayname=$_SESSION['name'];
    if(empty($login_username))
    {   
        $alert="Please login first for entry into application form";
        $_SESSION['mess']=$alert;
        header("location:rec_login.php");
        exit();
    }
    $message="";
    $total_apply=0;
    $display="";
    $jobrole=array
    (
        "Crew","Av Technician","Asst Stores Manager","Fitter","Floor Supervisor","Front office Manager",
        "Cashier","Banquet Waiters","IT Manager","FnB Manager","Mechanical Marine","Security","Logistics controller"
    ); 

    $conn=mysqli_connect("localhost","root","","inter");
    $count_query="SELECT COUNT(*) AS total_apply FROM appdetails";
    $result=mysqli_query($conn,$count_query);

    if($result)
    {
        $row=mysqli_fetch_assoc($result);
        $total_apply=$row['total_apply'];
    }

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $name=$_POST["name"];
        $job=$_POST["jrole"];
        $dob=$_POST["dob"];
        $fname=$_POST["fname"];
        $nation=$_POST["nation"];
        $qual=$_POST["qual"];
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
        $remark=$_POST["remark"];

        $server="localhost";
        $dbname="inter";

        $conn=mysqli_connect("localhost","root","","inter");

        $sql="INSERT INTO appdetails (name,job,dob,fname,nation,quali,addr,peraddr,phone,email,aadhar,exp,lang,ref,
            passportno,status,remarks)
            VALUES('$name','$job','$dob','$fname','$nation','$qual','$addr','$peraddr','$numb','$email','$aadhar',
            '$exp','$lang','$ref','$passno','$status','$remark')";
        $rst=mysqli_query($conn,$sql);
        if($rst)
        {
            $message="<div class='success'>Successfully Submitted</div>";
            $total_apply++;
            $display="<span class='successmsg'>$name : has Submitted the form successfully.</span>";
        }
        else
        {
            $message="<div class='error'>Failed</div>";
        }
    }
?>
<html>
    <head>
        <title>Applicant detail</title>
        <link rel="stylesheet" href="recstyle.css">
        <script>
            function checkname()
            {
                const name=document.getElementById("jname").value;
                const xhr=new XMLHttpRequest();
                xhr.onload=function()
                {
                    if(xhr.status===200)
                    {
                        res=xhr.responseText;
                        //const count=parseInt(reponse,10);
                        respJSON = JSON.parse(res);
                        popup=document.getElementById('pop');
                        //popupcont=document.getElementById('popupCont');
                        popupcontent = document.getElementById('popup-cont');
                        if(respJSON.STATUS==="Found")
                        {
                            count = respJSON.COUNT;
                            document.getElementById("existpop").innerText=`Existing Name - ${count} entries found`;
                            document.getElementById("existpop").setAttribute("data-status","old");

                            document.getElementById("existpop").classList.add("info");

                            let numberList = '';
                            respJSON.DATA.forEach((entry, index) => 
                            {
                                numberList += `<label class="buttonall buttonind"
                                    onclick="showinfo(${index});changecolor()">${index + 1}</label>`;
                            });
                            popupcontent.innerHTML = `<label>Click on a number to view details:</label><span>${numberList}</span>`;
                            //popupcontent.style.background="aqua";
                            window.applicantdata = respJSON.DATA;
                            popup.style.display = 'block';
                        }
                        else
                        {
                            //counterr=respJSON.COUT;
                            //document.getElementById("existpop").style.color="#00a86b";
                            document.getElementById('existpop').innerText="New Name"; 
                            document.getElementById("existpop").setAttribute("data-status","new");
                            document.getElementById("existpop").classList.remove("info");
                            popup.style.display='none';   
                            
                        }
                    }
                };
                url="appdataajax.php?name="+name+"&searchtype=SPECIFIC";
                //alert(url)
                xhr.open("GET",url,true);
                xhr.send();
            }
            
            function showinfo(index)
            {
                const applicant=window.applicantdata[index];
                //const content=document.getElementById('aname');
                //popupcontent.innerHTML=JSON.stringify(applicant);
                document.getElementById('aname').value=applicant.name;
                // changing the yyyy-mm-dd date to dd-mm-yyyy date
                let thisdob = applicant.dob.split("-").reverse().join("-")
                document.getElementById('adob').value=thisdob;
                document.getElementById('aqual').value=applicant.qualification;
                document.getElementById('aphone').value=applicant.phone;
                document.getElementById('astatus').value=applicant.status;
                data.style.display='grid'
                //popup.style.display='none';
            }  
            //close popup for popup details
            function closedetailpop()
            {
                const popupdata = document.getElementById('popupdetails');
                popupdata.style.display = 'none';
            }
            //shows the details of the total count entries                    
            function showdetails(nameobj)
            {
                const popup=document.getElementById('popupbox');
                popup.style.display='block';
                const popupContent=document.getElementById('popup-content');
                while(popupContent.children.length >1){
                    popupContent.removeChild(popupContent.lastChild);
                }
                //popupContent.innerHTML="";
                const xr=new XMLHttpRequest();
                xr.onload=function()
                {
                    if(xr.status===200)
                    {
                        resp=xr.responseText;
                        respjson = JSON.parse(resp);
                        const clonedetails=document.getElementById('dummydiv');
                        if(respjson.STATUS==="Found")
                        {
                            const data=respjson.DATA;
                            data.forEach((applicant,index)=>{
                                let temp=clonedetails.cloneNode(true);
                                temp.style.display="grid";
                                temp.removeAttribute('id');
                                temp.querySelector('.serial').innerText=(index+1);
                                temp.querySelector('.dname').innerText=applicant.name;
                                let yyyy_mm_dd=applicant.dob.split("-").reverse().join("-");
                                temp.querySelector('.ddob').innerText=yyyy_mm_dd;
                                temp.querySelector('.dqual').innerText=applicant.qualification;
                                temp.querySelector('.dphone').innerText=applicant.phone;
                                temp.querySelector('.dstatus').innerText=applicant.status;
                                document.getElementById('popup-content').appendChild(temp);
                                //poppup.style.display = 'grid';
                            });
                        }
                    }
                }
                url="appdataajax.php?name&searchtype=ALL";
                xr.open("GET",url,true);
                xr.send();
            }

            //closepopup for total entries application
            function closetotalPopup()
            {
                const popup = document.getElementById('popupbox');
                popup.style.display = 'none';
            }
            
            //for the usage of escape key
           document.addEventListener('keydown', function(event) 
            {
                if (event.key === 'Escape') 
                { 
                    const Popupdetails = document.getElementById('popupdetails');
                    const popupfilled=document.getElementById('popupbox');
                    if (Popupdetails.style.display === 'block') 
                    {
                        Popupdetails.style.display = 'none';
                    }
                    if(popupfilled.style.display === 'block')
                    {
                        popupfilled.style.display='none';
                    }
                }
            });
            function changecolor()
            {
                let earlierButton =document.querySelector('.buttonactive');
                if(earlierButton)
                {
                    earlierButton.classList.remove("buttonactive");
                    earlierButton.classList.add("buttonind");
                }
                var clickedButton = event.target
                clickedButton.classList.remove("buttonind");
                clickedButton.classList.add("buttonactive");
            }
           
            //popup when click the entries count
            function showpop()
            {
                if(document.getElementById('existpop').getAttribute("data-status")=="old")
                {
                    const popup = document.getElementById('popupdetails');
                    popup.style.display = 'block';
                }
                
            }
            function back()
            {
                window.location.href="rec_logout.php";
            }
            function goback()
            {
                window.location.href="rec_menu.php";
            } 
        </script>
        <style>
            .successmsg{color:#39ff14;}
            .button{display:flex;}
            .btncommon{float:right;margin:8px;padding:4px 12px;width:42px;font-size:bold;border: 1px solid black;
                text-align: center;background-color:#4b0082;color:white;}
            .count{float:left;color:#000;margin:7px;color:#f9f8fa;width:95%;border:1px solid black;background-color:#9d73fe;
                font-size: 60%;padding:4px;}
            .countpopuphead{color:#800080;margin-left:43%;float:none; font-weight:bold;opacity: 0;animation: fadeIn 2s forwards;}
            @keyframes fadeIn{from {opacity: 0;}to{opacity: 1;}}
            .selectopt{.dropdown-wrapper select {width: 100%;max-height: 150px;overflow-y: auto;padding: 8px;font-size: 14px;
                border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;}}
            .info{color: navy; position: relative}
            .info:hover{background: navy; color: white; padding:0 4px; cursor: pointer}
            .info:hover:after{content:'Click to view those details'; position: absolute; 
                background: orange; top:-2em; width: 14em; text-align: center;}
            .font-header{text-align:center;margin:7px;font-weight: bold;}
            .table{display:grid;grid-template-columns:5% 20% 14% 24% 17% auto;border:1px solid black;
                clear:right;overflow: scroll;}
            .table:hover{background-color: yellow;}
            .details{background-color:hsl(280,30%,90%);}
            .editdel{display: grid;grid-template-columns: 30% 40%;}
            .edel{margin:3px;padding:2px;border:1px solid black;text-align: center;}
            .edit{background-color: lightgoldenrodyellow;}
            .delete{background-color: lightcoral;}
            .rowborder{border: 1px solid black;padding:1px;overflow: scroll;}
            .header{border:1px solid black;background-color:#000080;text-align: center;color:#fff;position: sticky;}
            .popup {display: none;position: fixed;top:26%;width: 95vw;padding:22px;background-color:#fff0f5;z-index: 1000;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);border-radius:10px;max-height:60vh;height:40%;overflow:auto}
            .popup-content {font-size: 14px;margin-bottom: 20px;}
            .close-box{color:white;background-color:red;cursor:pointer;float:right;padding:1px;margin:3px;border: 1px solid black;}
            .close-box:hover{color:red;background-color: white;}
            .popupdata{display:none;position:absolute;top:35%;right:2%; width:35%;padding:22px;z-index:1000;
                overflow:auto;height: 50%;background-color:lightpink; border-radius: 8px; box-shadow: 0 0 8px black}
            .data{display:none;padding:2%;grid-template-columns: 22% auto;grid-gap:10px;background-color: white;width:96%}
            .data >label{color:#4b0082;}
            .buttonall{width:20px;margin:2px;padding:2px 5px; border-radius: 4px;cursor: pointer;}
            .buttonind{background-color:lightyellow;}
            .buttonactive{background-color: red;}
            /*.buttonind:focus{background-color:deeppink;}*/
            input:focus{background:lightcyan;}
        </style>
    </head>
    <body>
        <label>Hello <?php echo $displayname,', ',$display;?></label>
        <div class="button">
            <span class="font-name"style="display:grid;grid-template-columns:15% auto 7% 10%;">
                <label class="count"onclick="showdetails();clonepop()">Total Application Filled: <?php echo $total_apply;?>
                </label>
                <label class="font-header">Application Details</label>
                <label onclick='goback()'class="back-btn btncommon">Back</label>
                <label onclick='back()'class="logout-btn btncommon">Logout</label>
            </span>
        </div>
        <!--Total Application Filled Details-->
        <div class="popup" id="popupbox">
        <label class='countpopuphead'>Total Application filled</label>
        <label class="close-box" onclick="closetotalPopup()">X</label>
            <div class="popup-content" id="popup-content">  
                <header class="table">
                    <div class="header" id="serial">S.no</div>
                    <div class="header" id='name'>Name</div>
                    <div class="header" id='dob'>DOB</div>
                    <div class="header" id='qual'>Qualification</div>
                    <div class="header" id='phone'>Phone</div>
                    <div class="header" id='status'>Status</div>
                </header>
            </div>
            <div class= "table details" id="dummydiv" style="display:none;font-family:'Lucida Sans';">
                <label class='serial rowborder'></label>
                <label class='dname rowborder' id='dname'></label>
                <label class='ddob rowborder' id='ddob'></label>
                <label class='dqual rowborder' id='dqual'></label>
                <label class='dphone rowborder' id='dphone'></label>
                <label class='dstatus rowborder' id='dstatus'></label>         
            </div>
        </div>    
        <!--Application Details Forms-->
        <div class="back-border">
            <div class="php-cont"><?php echo $message?></div>
                <form action="" method="POST">
                    <div class="cont">
                        <label>Name</label>
                        <label>
                            <input type="text" name="name" id='jname' onchange='checkname()'style="border:2px solid rgba(13, 165, 165,0.4);
                            line-height: 170%;" title='provide the full name' required/>
                            <label id="existpop" class="info" onclick='showpop()'></label>
                        </label>
                        <label>Job role</label>
                        <select class="selectopt" name="jrole" required>
                            <option value="">Select Option</option>
                            <?php
                                foreach($jobrole as $role):
                            ?>
                            <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>DOB</label>
                        <input type="date" name="dob" class='css' id="jdob" required/>
                        <label>Father's Name</label>
                        <input type="text" name="fname" title="provide the father's name" required/>
                        <label>Nationality</label>
                        <input type="text" name="nation" class="css" title="provide the nationality" required/>
                        <label>Qualification</label>
                        <input type="text" name="qual" class="qual" id="jqual"title="provide the qualification" required/>
                        <label>Address</label>
                        <input type="text" name="addr" title="provide the current address" required/>
                        <label>Permanent address</label>
                        <input type="text" name="preadd"/>
                        <label>Phone Number</label>
                        <input type="tel" name="num" class="qual" id="jphone" title="provide the phone number" required/>
                        <label>Email</label> 
                        <input type="email" name="mail" class="qual" title="provide the email id" required/>
                        <label>AadharCard Number</label>
                        <input type="text" name="aadhar" class="css" title="provide your aadhar number" required/>
                        <label>Experience</label>
                        <input type="text" name="exp" class="css" title="provide the working experience in years" required/>
                        <label>Language Known</label>
                        <input type="text"name="lang" title="provide the language known" required/>
                        <label>Referral</label>
                        <input type="text" name="ref"/>
                        <label>Passport Number</label>
                        <input type="text" name="passno" class="css" required/>
                        <label>Status</label>
                        <select name="status" class="css"id="jstatus">
                            <option>New</option>
                        </select>
                        <label>Remarks</label>
                        <input type="text" name="remark"/>    
                        <button type="submit" class="submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <!--To see the existing details-->
        <div class="popupdata" id="popupdetails">
            <label class="close-box" onclick="closedetailpop()">X</label>
            <div id="popup-cont"></div>
            <!--TO display the form detils in the popup-->
            <div class="data" id="data">
                <label>Name: </label><input type="text" id="aname" readonly />
                <label>Date of Birth: </label><input type="text" id="adob" readonly />
                <label>Qualification: </label><input type="text" id="aqual" readonly />
                <label>Phone: </label><input type="text" id="aphone" readonly />
                <label>Status: </label><input type="text" id="astatus" readonly />
            </div>
        </div>
    </body> 
</html>