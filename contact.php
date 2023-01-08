<?php 
	print '
	<h1>Contact Form</h1>
	<div id="contact">
	<div class="mapouter">
	<div class="gmap_canvas">
		<iframe src="https://maps.google.com/maps?q=vvg&t=&z=17&ie=UTF8&iwloc=&output=embed" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
		<form action="contactform.php" id="contact_form" name="contact_form" method="POST">
			<label for="fname">First Name *</label>
			<input type="text" id="fname" name="fname" placeholder="First name" required>
			
			<label for="lname">Last Name *</label>
			<input type="text" id="lname" name="lname" placeholder="Last name" required>
				
			<label for="email">Your E-mail *</label>
			<input type="email" id="email" name="email" placeholder="E-mail" required>

			<label for="country">Country*</label>
			<input type="text" id="country" name="country" placeholder="Country" required>

			<label for="subject">Subject</label>
			<textarea id="subject" name="subject" placeholder="Message" style="height:200px"></textarea>

			<input type="submit" value="submit">
		</form>
	</div>';

?>