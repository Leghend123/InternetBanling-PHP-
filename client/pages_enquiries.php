<?php 
include("dist/_partials/header.php");

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title></title>
 </head>
 <body style="height:750px">
 	<div class="container" style="margin-top:30px">
 		<div class="col-md-12">
      <h4 style="color:#007b85">Enquiries</h4>
 			<div class="row">
 				<div class="col-md-6">
 					<div class="row">
 						<div class="col-md-6">
              <form method="post" id="formdata">
                <div id="result"></div>
 							<div class="input-group input-group-sm mb-3">
                               <span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
                               <input type="text" class="form-control" name="fname" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                             </div>
                             <div class="input-group input-group-sm mb-3">
                               <span class="input-group-text" id="inputGroup-sizing-sm">Zip Code</span>
                               <input type="text" class="form-control" name="Z_code" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                             </div>
 						</div>
 						<div class="col-md-6">
 							<div class="input-group input-group-sm mb-3">
                              <span class="input-group-text" id="inputGroup-sizing-sm">Phone Number</span>
                              <input type="text" class="form-control" name="phone" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                            </div>
                            <div class="input-group input-group-sm mb-3">
                              <span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
                              <input type="email" class="form-control" name="emails" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                            </div>
 						</div>

 					</div>
 					<div class="row">
 						<div class="col-md-12">
 							<select class="form-select form-select-sm" name="subject" aria-label=".form-select-sm example">
                              <option selected>I'm looking for help with *</option>
                              <option value="Customer Service">Customer service</option>
                              <option value="Other">Others</option>
                              <option value=" Cash Management">Cash Management</option>
                            </select>
                             <div style="margin-top:20px">
                             	How can we help you?*
                             
                            <div class="input-group" >
                               <!-- <span class="input-group-text"></span> -->
                               <textarea class="form-control" name="content" aria-label="With textarea"></textarea>
                             </div>
                         </div>
 						</div>
 					</div>
 					<div class="row" style="margin-top:20px">
 						<div class="col-md-6"></div>
 						<div class="col-md-4">
 							<button type="button" name="send_message"  id="send" class="btn btn-outline-primary">Send Message <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button>

 						</div>
 					</div>
        </form>
 				</div>
 				
 					 <div class="vr"></div>
 				
                <div class="col-md-5">
                	<div class="row">
                		<div class="col-md-6">
                	         <div class="card text-bg-white mb-1" style="max-width: 15rem;">
                              <div class="card-header"> 	Call Us <i class="fa fa-phone" aria-hidden="true"></i>
                              </div>
                              <div class="card-body">
                                <p class="card-text">
                                 Please call our Customer Care Team at (055 488-8822) and listen to each of our helpful options to connect you with the right team member. We are available Monday - Friday 8:00 am - 6:00 pm.</p>
                              </div>
                            </div>
                       </div>
                       <div class="col-md-6">   
                              <div class="card text-bg-white mb-1" style="max-width: 13rem;">
                                <div class="card-header">Account Assistance
                                </div>
                                <div class="card-body">
                                  <p class="card-text">
                                    Call us toll-free at (024 456-6767) for automated account information or to report a lost or stolen card. Our automated telephone service is available 24 hours a day, 7 days a week.</p>
                                </div>
                              </div>
                              </div>


                 </div>
                </div>
 			</div>
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