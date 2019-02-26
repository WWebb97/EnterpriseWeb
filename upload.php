<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="jQuery_Cookie_Plugin_4.1/jquery.cookie.js"></script>

<title>Index</title>
<script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=mgvk1bzzmei1kpg5zqv30qi3z8ci1pz4488fdehxo5jwr6si"></script>
<script>tinymce.init({ selector:'textarea' });</script>
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
                        <a class="nav-link" href="register.html">Register</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">login</a>
                    </li>
                </ul>
            </div>  
        </nav>
        
        <div class="headerideas">
            <h2>Submit an idea</h2>                
        </div>
        <form>
        <div class="contentideas">
            <div class="input-group">
  		        <label>Title</label>
  		        <input type="text" id="name" required>
            </div>
            <div class="input-group" id="category">
  	         <label>Category</label>
                <script>
                       $(document).ready(function(){   
                             $.ajax({
                                type: "POST",
                                url: "PostController.php",

                                data: {
                                    method:"categories",
                                },
                                cache: false,
                                dataType: 'JSON',
                                success: function(data, status, xhttp, errorMessage) {
                                       $('#category').append('<select name="department" id="categoryId" required>');
                                       $('#categoryId').append('<option>Select Option</option>');
                                       $.each(data, function(i ,val) {
                                              $('#categoryId').append('<option value='+val.category_id+'>'+val.name+'</option>');
                                        });
                                        $('#category').append('   </select>');
                                },
                                error: function(result) {
                                    //alert('unsuccessfull upload');
                                }
                            });
                       });
                
                
                </script>
  	         <!--<select name="department" id="categoryId" required>
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
             </select>-->
            </div>
            <div class="input-group">
  	         <label>Select to post anonymously or not</label>
  	         <select name="anonymously" id="anon" required>
                    <option>Select Option</option>
                    <option value=1>Yes</option>
                    <option value=0>No</option>                    
             </select>
            </div>
            <div class="input-group">
                <label>Write your idea here</label>
<!--                <textarea id="description" cols="80" rows="5"></textarea>-->
                <input id="description" cols="80" rows="5"/>
            </div>
            <input type="file" multiple class="formInput" name="files[]" id="filesIn"/>
            <div class="input-group">
  		        <button type="button"  id="submitIdea" class="refresh btn">Submit Idea</button>                

            </div>
<!--        <p id="testingSpace">   </p>-->
        </div>
        </form>
        <script type="text/javascript">
         $(document).ready(function(){   
            $("#submitIdea").click(function(e) {
                var name = $("#name").val();
                var description = $("#description").val();
                var categoryId = $("#categoryId").val();
                var anon = $('#anon').val();
//                var files = $('#filesIn').val();
                
                    
                e.preventDefault();
                
                    $.ajax({
                        type: "POST",
                        url: "PostController.php",
                        
                        data: {
                            method:"addPost",
                            userId: $.cookie("userid"),
                            name:name,
                            description:description,
                            categoryId:categoryId,
                            anon:anon
                        },
                        cache: false,
                        dataType: 'JSON',
                        success: function(data, status, xhttp, errorMessage) {
                            
                             $("#testingSpace").text("upload= "+data.created+"postid= "+data.postId+" errormessage = " +status.errorMessage);
                            
                            
                                <?php 
                                     $fileId= null;
                                        $error=array();
                                        $files = array();
                                        $extension=array("docx, pdf");
                                        $fileCount = 1;
                                        $uploadSuccess = 1;
                                        foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
                                                {
                                                    $file_name=$_FILES["files"]["name"][$key];
                                                    $file_tmp=$_FILES["files"]["tmp_name"][$key];
                                                    $newFileName= "image".$imageCount."postid".$fileId;
                                                    $fileCount ++;
                                                    $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                                                    if(in_array($ext,$extension))
                                                    {
                                                        if(!file_exists("images/".$newFileName))
                                                        {
                                                            move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"attachments2/".$file_name);
                                                            $finalName= $newFileName.".".$ext;
                                                            array_push($files, $finalName);
                                                            
                                                        }
                                                        else
                                                        {
                                                            $uploadSuccess = 0;
                                                            ?>
                                                            alert("well that didnt work");
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        array_push($error,"$file_name, ");
                                                    }
                                                }

                                        //var_dump($error);
                                        //var_dump($files);
                                        $failure = 0;
                                        foreach($files as $var){
                                           // $insert = InsertImage("/coursework/images/", $var, $fileId); 
                                            if(!($insert)){
                                                $failure = 1;
                                            }
                                        }
                                        if($failure === 1){
                                            //echo "There was an error adding images to the post. The post was still created successfully.";
                                        }else{
                                            //echo "Post succsessfully created.";
                                        }
                                ?>
                            if(data.created == true){   
                                alert('Idea successfully uploaded');                                   
                                window.location.href = "index.html";

                            }
                        },
                        error: function(result) {
                            alert('unsuccessfull upload');
                        }
                    });
                });
             });

        </script>
        <!--Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>