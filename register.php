<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">    
<?php
    session_start();
    define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ad9047o/register.php');
?>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<title>Register</title>
<meta name="Author" content="ad9047o@gre.ac.uk"/>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="checkpass.js"></script>
</head>
    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <a class="navbar-brand" href="https://stuweb.cms.gre.ac.uk/~ad9047o/register.php">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://stuweb.cms.gre.ac.uk/~ad9047o/register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://stuweb.cms.gre.ac.uk/~ad9047o/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://">Your Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://">Upload Ideas</a>
                    </li>
                </ul>
            </div>  
        </nav>
        <div class="header">
            <h2>Register</h2>                
        </div>
        <form method="post" action="register.php" enctype="application/x-www-form-urlencoded">
            
            <div class="content">
            <div class="input-group">
  	         <label>First Name</label>
  	         <input type="text" name="first_name" id="first_name" value="<?php echo $_POST['first_name']; ?>" required>
            </div>
            <div class="input-group">
  	         <label>Last Name</label>
  	         <input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name']; ?>" required>
            </div>
            <div class="input-group">
  	         <label>Username</label>
  	         <input type="text" name="username" id="username" value="<?php echo $_POST['username']; ?>" required>
            </div>
            <div class="input-group">
  	         <label>Email</label>
  	         <input type="email" name="email" id="email"  pattern=".+@gre.ac.uk" title="University of Greenwich emails only.." value="<?php echo $_POST['email']; ?>" required>
            </div>
            <div class="fieldWrapper">
            <div class="input-group">
  	         <label>Password</label>
  	         <input type="password" name="password" id="password" value="<?php echo $_POST['password']; ?>" required>
            </div>
            <div class="input-group">
  	         <label>Confirm password</label>
  	         <input type="password" name="password_2" id="password_2" onkeyup="checkPass(); return false;" value="<?php echo $_POST['password_2']; ?>" required>
             <span id="confirmMessage" class="confirmMessage"></span>
            </div>
            </div>
            <div class="input-group">
  	         <label>Department</label>
  	         <select name="department" id="departmentID" value="<?php echo $_POST['department']; ?>" required>
                    <option>Select Option</option>
                    <option value="1">testing</option>
                    <option value="2">Architecture</option>
                    <option value="3">Computing</option>
                    <option value="4">Humanities</option>
                    <option value="5">Business</option>
                    <option value="6">Education</option>
                    <option value="7">Health</option>
                    <option value="8">Engineering</option>
                    <option value="9">Science</option>
             </select>
            </div>
            <div class="captcha">
                <img src="captcha.php" >
                </div>
                <div class="inputcapt-group"  >
                    <strong>Enter CAPTCHA code below</strong><br/>
                <input type="text" id="captchabox" name="captchabox">
                </div><br/>
                                      
                <div class="inputreg-group">                        
                    <button type="button" name="add" id="add_user" class="refresh btn">Submit Details</button>                
                </div>
                <p id="testingSpace">   </p>
                <script>
                $(document).ready(function(){
                     
                    $("#add_user").click(function(){
                           var first_name = $("#first_name").val();
                           var last_name = $("#last_name").val();
                           var username = $("#username").val();
                           var email = $("#email").val();
                           var password = $("#password").val();
                           var departmentID = $("#departmentID").val();
                           var captchabox =$("#captchabox").val();
                        $.ajax({
                            type: "POST",
                            url: "RegisterController.php",
                            data: {first_name:first_name, last_name:last_name, username:username, email:email, password:password, departmentID:departmentID, captchabox:captchabox},
                            cache: false,
                            dataType: 'JSON',
                            success: function(output) {
                                    $("#testingSpace").text("Registration= "+output.register+" errorCode = " +output.errorCode);
                                }

                            });
            
                        
                    });
                               
                   
                               
               });
            
            
            
            </script>		             
                
                <button type="submit" class="refresh btn" onclick="this.form.action='<?php echo URLFORM ?>'">Reset Form</button>
                
                <p>
                Already a member? <a href="login.php">Sign in</a>
                </p>

            </div>
        </form>

    </body>
</html>

       
    