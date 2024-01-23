<?php 
include("dist/_partials/header.php");
include("conf/config.php");


 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title></title>
 </head>
 <body >
 	<div class="container" style="margin-top:30px; height:600px">
 		<div class="col-md-12">
 			<form method="post" id="formdata">
 			<div class="row">
 				<h5 style="color:#00785b">Mail</h5>
 				<span>Send your compliant via email now!</span>
 				<div class="col-md-4"></div>
 				<div class="col-md-4">
 					<div id="result"></div>
 					<div class="form-group">
 						<label>Name:</label>
 						<input type="text" name="fname" class="form-control" required >
 					</div>
 					 <div class="form-group">
 						<label>Email:</label>
 						<input type="email" name="emails" class="form-control" required>
 					</div>
 					<div class="form-group">
 						<label>Subject:</label>
 						<input type="text" name="subject" class="form-control" required>
 					</div>
 					<div class="form-group">
 						<label>Message:</label>
 						<textarea class="form-control" name="content" required></textarea>
 					</div>
 					<div style="margin-top:30px">
 					<button type="button" name="send_message"  id="send" class="btn btn-outline-primary">Send Message <i class="fa fa-paper-plane" aria-hidden="true"></i>
                       </button>
                    </div>
 				</div>
 				<div class="col-md-4"></div>
 			</div>
 			</form>
 		</div>
 	</div>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#send").click(function(){
        var formData = $("#formdata").serialize();

        $.ajax({
          method:"POST",
          url:"sendmail.php",
          data:formData,
          success:function(response){
            $("#result").html(response);
          }
        })
      })
    });
  </script>
 
 </body>
 </html>