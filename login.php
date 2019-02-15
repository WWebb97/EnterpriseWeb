<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<title>Login</title>
<meta name="Author" content="ad9047o@gre.ac.uk"/>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <a class="navbar-brand" href="index.html">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Your Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Upload Ideas</a>
                    </li>
                </ul>
            </div>  
        </nav>
        <div class="headerlog">
            <h2>Login</h2>                
        </div>
        <form>
        <div class="contentlog">
            <div class="input-group">
  		        <label>Username</label>
  		        <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
  		        <label>Password</label>
  		        <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
  		        <button type="button"  id="submitButton" class="refresh btn" name="login_user">Login </button>                

            </div>
           <!-- <p id="testingSpace">   </p> -->
            <script>
                $(document).ready(function(){
                     
                    $("#submitButton").click(function(){
                           var username = $("#username").val();
                           var password = $("#password").val();
                        $.ajax({
                            type: "POST",
                            url: "LoginController.php",
                            data: {username:username, password:password},
                            cache: false,
                            dataType: 'JSON',
                            success: function(output) {
                                    $("#testingSpace").text("login= "+output.login+" verified = " +output.verified);
                                
                                if(output.login == true){
                                    //alert('correct login');
                                    document.cookie = "username = " + username;
                                    <?php session_start(); ?>
                                    if(output.verified == false){
                                        window.location.href = "Verification.html";
                                        

                                    }else{
                                        window.location.href = "Index.html";
                                    }
                                    
                                }else{
                                    alert('incorrect Login');
                                }
                            }
                            

                            });
            
                        
                    });
                               
                   
                               
               });
            
            
            
            </script>
  	         <p>
  		        Not yet a member? <a href="register.php">Sign up</a>
  	         </p>
         </div>
        </form>

    </body>
</html>