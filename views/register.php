<?php echo validation_errors(); ?>

<?php echo form_open('register_action',array('role'=>'form')); ?>
	<div class="form-group">
	    <label for="fname">First Name:</label>
	    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>">

	    <label for="lname">Last Name:</label>
	    <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>">
	</div>
	<div class="form-group">
	    <label for="uname">User Name:</label>
	    <input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo set_value('user_name'); ?>">

		<label for="pwd">Password:</label>
    	<input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>">

		<label for="email">Email address:</label>
  		<input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
	</div>
	<div class="form-group">
	    <label for="street">Street Address:</label>
	    <input type="text" class="form-control" name="street" id="street" value="<?php echo set_value('street'); ?>">

	    <label for="street2">Steet Address 2:</label>
	    <input type="text" class="form-control" name="street2" id="street2" value="<?php echo set_value('street2'); ?>">

	    <label for="citystate">City/State:</label>
	    <input type="text" class="form-control" name="city_state" id="city_state" value="<?php echo set_value('city_state'); ?>">

	    <label for="zip">Zip Code:</label>
	    <input type="text" class="form-control" name="zip" id="zip" value="<?php echo set_value('zip'); ?>">
	</div>
  	
  	<button type="submit" class="btn btn-default">Submit</button>
  	<button onclick="window.history.back();" class="btn btn-default">Cancel</button>
</form>