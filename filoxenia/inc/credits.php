 <div class="row title">
			<div class="small-12 column">
				<h6>Credit</h6>
			</div>
		</div>
		
		<center style="color:#FF4F4F;">
			<?php
				if(!empty($payment->errors)) {
					echo $payment->errors[0];
				} 
			?>
		</center>
		
		<center style="color:#669C5C;">
			<?php
				if(!empty($payment->success)) {
					echo $payment->success[0];
				} 
			?>
		</center>
		
		<center>Choose a value (you can type your custom value)</center><br>
		
		<form class="text-center" action="<?php echo $action_form;?>" method="post">
			<select id="input_payment" style="width:100px; margin:0 auto;" name="pmethod" placeholder="payment">
			   <option value="" disabled selected>Choose</option>
			   <option value="psc">PaySafecard</option>
			   <option value="pp">PayPal</option>
			</select>
			 
			<input placeholder="value" style="width:100px; margin:0 auto;" name="pvalue" type="search" list= "payvalue" />
			 <datalist id="payvalue">
					<option value = "5"> 
					<option value = "10"> 
					<option value = "25"> 
					<option value = "50"> 
					<option value = "75"> 
					<option value = "100"> 
			 </datalist>
			 <span>€</span><br>
			 
			 <input type="text" id="psc_pin" placeholder="PaySafecard PIN" name="pscpin" style="width:160px; margin:0px auto;display:none;">
			 
			 <div class="row">
				<div class="large-12 column">
					<button name="submit_credit" type="submit">Add credit</button>
				</div>
			</div>
		</form>

		
		</section>
		
		<script type="text/javascript">
		   var sel = document.getElementById('input_payment');
		   var psc = document.getElementById('psc_pin');
		   sel.onchange = function() {
			  var show = document.getElementById('show');
			  if(this.value == "psc") {
				   psc.style.display = "inline";
			  } else {
				  psc.style.display = "none";
			  }
		   }
		</script>