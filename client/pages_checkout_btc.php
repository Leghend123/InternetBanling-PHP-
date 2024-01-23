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
 <body>
 	<div class="container">
 		<div class="col-md-12">
 			<div class="row">
 				<div class="col-md-3"></div>
 				<div class="col-md-6">
 					<div class="form-group mx-4" style="margin-top:30px">
 					<p>Pay your bill through BTC <img src="dist/img/btc.png" style="width: 50px;"> / Contact Admin: <a href="">info@admin.com</a> </p>
 					<label>BTC Address:</label>
                    <input type="text" name="address" value="1CxoDUSB9Y7FwSyKChFroRFrVeWjnGzA2d" class="form-control" readonly onclick="selectText(this)">
 					</div>
 				</div>
 				<div class="col-md-3"></div>
 			</div>
 		</div>
 	</div>
 	<script>
   function selectText(input) {
    input.setSelectionRange(0, input.value.length);
    document.execCommand("copy");
    alert("Address copied to clipboard");
   }
</script>

 
 </body>
 </html>