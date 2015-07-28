<?php 
	$has_role = isset($this->session->userdata['role']);
	$is_admin = ($has_role AND $this->session->userdata('role') == 'admin' ? TRUE : FALSE);
	$is_employee = ($has_role AND $this->session->userdata('role') == 'employee' ? TRUE : FALSE);

	$active_segment = $this->uri->segment(1);
	
	$success = $this->session->flashdata('success');
	$warning = $this->session->flashdata('warning');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	  	<title>FrostBit</title>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<?php if(isset($show_login)):?>
			<script>$(document).ready(function(){ $('#loginModal').modal('show');});</script>
		<?php endif;?>
	</head>
	<body>
		
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
			    <div class="navbar-header">
			    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span> 
			      	</button>
			    	<a class="navbar-brand" href="#">FrostBit</a>
			    </div>
			    <div class="collapse navbar-collapse" id="myNavbar">
			        <ul class="nav navbar-nav">
			        	<li <?php echo ($this->uri->total_segments() === 0 ? "class='active'" : "") ?>>
							<a href="<?php echo base_url(); ?>">Home</a>
						</li>
						
						<?php if($is_admin OR $is_employee):?>
				        	<li class="dropdown <?php echo ($active_segment === "sale" ? "active" : "") ?>">
								<a href="<?php echo site_url('sale')?>">Sale</a>
				        	</li>
							<li class="dropdown <?php echo ($active_segment === "carts" ? "active" : "") ?>">
								<a href="<?php echo site_url('carts')?>">Carts</a>
				        	</li>
			        	<?php endif; ?>
						
			        	<?php if($is_admin):?>
				        	<li class="dropdown <?php echo ($active_segment === "accounting" ? "active" : "") ?>">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Accounting <span class="caret"></span></a>
					          	<ul class="dropdown-menu">
					            	<li><a href="<?php echo site_url('accounting/trialBalance')?>">Trial Balance</a></li>
					            	<li><a href="<?php echo site_url('accounting/journal')?>">Journal Entries</a></li>
					            	<li><a href="<?php echo site_url('accounting/auditTrail')?>">Audit Trail</a></li>
					          	</ul>
				        	</li>
							<li class="dropdown <?php echo ($active_segment === "admin" ? "active" : "") ?>">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin <span class="caret"></span></a>
					          	<ul class="dropdown-menu">
					            	<li><a href='<?php echo site_url('admin/order')?>'>Order Goods</a></li>
									<li><a href='<?php echo site_url('admin/generic_order')?>'>Generic Order</a></li>
									<li role="separator" class="divider"></li>
									<li class="dropdown-header">CRUD</li>
									<li><a href='<?php echo site_url('admin/crud/accounts')?>'>Accounts</a></li>
									<li><a href='<?php echo site_url('admin/crud/ledger_accounts')?>'>Ledger Accounts</a></li>
									<li><a href='<?php echo site_url('admin/crud/goods_services')?>'>Goods and Services</a></li>
									<li><a href='<?php echo site_url('admin/crud/orders')?>'>Orders</a></li>
									<li><a href='<?php echo site_url('admin/crud/details')?>'>Details</a></li>
									<li><a href='<?php echo site_url('admin/crud/carts')?>'>Carts</a></li>
									<li><a href='<?php echo site_url('admin/crud/cart_history')?>'>Cart History</a></li>
									<li><a href='<?php echo site_url('admin/crud/cart_stock')?>'>Cart Stock</a></li>	
					          	</ul>
				        	</li>
			        	<?php endif; ?>
			      	</ul>
			      	<ul class="nav navbar-nav navbar-right">
			      		<?php if(!isset($this->session->userdata['first_name'])):?>
					        <li <?php echo ($active_segment === "register" ? "class='active'" : "") ?>><a href="<?php echo base_url('register'); ?>"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
					        <li><a href="#" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
					    <?php else: ?>
					    	<li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name'); ?></a></li>
					        <li><a href="<?php echo site_url('logout'); ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
					    <?php endif; ?>
			      	</ul>
				</div>
			</div>
		</nav>

	  <!-- Modal -->
		<div class="modal fade" id="loginModal" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Login</h4>
					</div>
					<div class="modal-body">
						<?php echo validation_errors(); ?>
						<?php if(isset($status))
						{
							echo "<p class='bg-danger'><span class='glyphicon glyphicon-warning-sign'></span> $status</p>";
						}
						?>
						<?php echo form_open('login',array('role'=>'form')); ?>
							<div class="form-group">
								<label for="username">User Name:</label>
								<input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username'); ?>" required>
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" class="form-control" name="password" id="password" required>
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container">
			<?php if (isset($success) and $success != ''):?>
				<div class="alert alert-success fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Success!</strong> <?php echo $success ?>.
				</div>
			<?php endif; ?>
			<?php if (isset($warning) and $warning != ''):?>
				<div class="alert alert-warning">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Warning!</strong> <?php echo $warning ?>
				</div>
			<?php endif; ?>