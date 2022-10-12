<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comics Service</title>
    <!--     script for subscribe -->
    <script>    
        //To validate the email using regex 
        function validator(user_mail) {
              const regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (regEx.test(user_mail)) {
                 return true;
                   }
              else {
                   return false;
                 }
              }

              //otp enter
              function otpEnter(){
                    document.getElementById("secstep").style.display = "block";
                    document.getElementById("user_mail").disabled = true;
                    document.getElementById("s-otp-button").value = "Submit";
                    document.getElementById("s-otp-button").setAttribute("flag", "flag-2");
                    document.getElementById("email-warn").innerHTML = "";
                }

                //congrats
                function final(){
                     document.getElementById("mainform-style-div").style.display = "none";
                     document.getElementById("tick-icon-div").style.display = "block";
                   }
            
                   function send_otp_ajax(user_mail) {
                          console.log("ajax me aa gya");
                         var email_warn = document.getElementById("email-warn");
                         var s_otp_btn = document.getElementById("s-otp-button");
                         s_otp_btn.value = "Sending...";
                         email_warn.innerHTML = "";
                         const xhttp = new XMLHttpRequest();
                          xhttp.open("POST", "./users/php/send_otp.php", true);
                         xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                          xhttp.onload = function () {
                       if (this.readyState == 4 && this.status == 200) {
                                 if(this.responseText.trim() == "OTP Sent Successfully"){
                                       console.log("step two");
                                      otpEnter();
                                      }
                                  else if(this.responseText.trim() == "Email is Already Verified"){
                                       s_otp_btn.value = "Send OTP";
                                         email_warn.innerHTML = "Email is Already Verified !";
                                         console.log("step two ke niche");
                                      }
                                 else if(this.responseText.trim() == "Invalid Email"){
                                       s_otp_btn.value = "Send OTP";
                                        email_warn.innerHTML = "Invalid Email !";
                                         console.log("step two ke niche k niche");

                                      }
                                  else{
                                       s_otp_btn.value = "Send OTP";
                                       email_warn = "Please try Again !";
                                      
                                              
                                      }            
                             }
                     }
                      xhttp.send("email="+user_mail);
                    }


                    function verify_otp(user_mail,otp){
                             var otp_warn = document.getElementById("otp-warn");
                             var s_otp_btn = document.getElementById("s-otp-button");
                             s_otp_btn.value = "Verifying...";
                             const xhttp = new XMLHttpRequest();
                             xhttp.open("POST", "./users/php/verify_otp.php", true);
                             xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                             xhttp.onload = function () {
                                 if (this.readyState == 4 && this.status == 200) {
                                         if(this.responseText.trim() == "Email Verified"){
                                             final();
                                         }
                                         else if(this.responseText.trim() == "Invalid OTP"){
                                             s_otp_btn.value = "Submit";
                                             otp_warn.innerHTML = "Invalid OTP !";
                                         }
                                         else{
                                             s_otp_btn.value = "Submit";
                                             otp_warn.innerHTML = "Please try Again !";
                                         }           
                                    }
                              }
                              xhttp.send("email="+user_mail+"&otp="+otp);
                            }


                            function send_otp() {
                                     var email_warn = document.getElementById("email-warn");
                                     var otp_warn = document.getElementById("otp-warn");
                                     var user_mail = document.getElementById("user_mail").value;
                                     var otp = document.getElementById("otp").value;
                                     var s_otp_btn = document.getElementById("s-otp-button");
                                     if (s_otp_btn.getAttribute("flag") == "firststep") {
                                         if (user_mail != "" && validator(user_mail)) {
                                             send_otp_ajax(user_mail);
                                             console.log("clicked");
                                         }
                                         else {
                                             s_otp_btn.value = "Send OTP";
                                             email_warn.innerHTML = "Invalid Email !";
                                             console.log("clicked a");
                                         }
                                     }
                                     else {
                                         email_warn.innerHTML = "";
                                         if(otp != "" & otp.length==6){
                                             otp_warn.innerHTML = "";
                                             verify_otp(user_mail,otp);
                                             console.log("clicked a");
                                         }
                                         else {
                                             s_otp_btn.value = "Submit";
                                             otp_warn.innerHTML = "Invalid OTP !";
                                             console.log("clicked a");
                                         }        
                                     }
                                    }




                                    function email_validate(user_mail) {
    const validRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (validRegex.test(user_mail)) {
        return true;
    }
    else {
        return false;
    }
}

function final_step(){
    document.getElementById("form-style-div").style.display = "none";
    document.getElementById("tick-icon-div").style.display = "block";
}

function unsubscribe_request(user_mail){
    var email_warn = document.getElementById("email-warn");
    var s_otp_btn = document.getElementById("s-otp-button");
    document.getElementById("s-otp-button").value = "Unsubscribing...";
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../php/unsubscribe-verify.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText.trim() == "Email Unsubscribed"){
                final_step();
            }
            else if(this.responseText.trim() == "Invalid Email"){
                s_otp_btn.value = "Unsubscribe";
                email_warn.innerHTML = "Invalid Email !";
            }
            else if(this.responseText.trim() == "Email not Found"){
                s_otp_btn.value = "Unsubscribe";
                email_warn.innerHTML = "Email not Found !";
            }
            else{
                s_otp_btn.value = "Unsubscribe";
                email_warn.innerHTML = "Please try Again !";
            }            
        }
    }
    xhttp.send("email="+user_mail);
}

function unsubscribe(){
    var email_warn = document.getElementById("email-warn");
    var user_mail = document.getElementById("user_mail").value;
    if (user_mail != "" & email_validate(user_mail)) {
        email_warn.innerHTML = "";
        unsubscribe_request(user_mail);
    }
    else {
        email_warn.innerHTML = "Invalid Email !";
    }
}






        </script>

<link rel="icon" href="https://www.bricks4kidz.com.au/boroondara/wp-content/uploads/sites/8/2020/11/Comic-Creator-Icon.jpg" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
</head>

<body>
<div class="mainform-style">

<div id="tick-icon-div">
    <img src="https://c.tenor.com/LB7jqdRLzVgAAAAM/done-and-done-finished.gif" />
    <div>
        <span>Congratulations! your Email has been successfully verified.</span>
    </div>
    <a href="./users/php/unsubscribe.php">Click to Unsubscribe</a>
</div>


<div id="mainform-style-div">
    <h1>Get XKCD Comics</h1>
    <form>

        <div id="firststep">
            <div class="section">Enter your Email Address</div>
            <div class="inner-wrap">
                <label>Email Address <input type="email" name="user_mail" id="user_mail" placeholder="Eg- email@gmail.com" /></label>
                <label id="email-warn"></label>
            </div>
        </div>

        <div id="secstep">
            <div class="section">Enter OTP</div>
            <div class="inner-wrap">
                <label>Enter OTP sent to your Email <input type="number" name="otp" id="otp" /></label>
                <label id="otp-warn"></label>
            </div>
        </div>

        <div class="button-section">
            <input type="button" value="Send OTP" onclick="send_otp();" flag="firststep" id="s-otp-button" />
        </div>

    </form>
</div>
</div>

    
</body>
</html>