<script>
	function checkout(cart){
		window.location.href = 'carts/check_out/'+cart;	
	}
	function checkin(cart){
		window.location.href = 'carts/check_in/'+cart;	
	}
</script>

<h1>Manage Carts</h1>
<div class="row">
	<?php foreach ($carts->result() as $cart):?>
		<div class="col-md-4">
			<h2>
				<a href="<?php echo site_url('carts/view/'.$cart->id);?>"><?php echo $cart->name;?></a>
			</h2>
			<p><?php echo $cart->description;?></p>
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onClick="checkout(<?php echo $cart->id;?>)"
					<?php echo ($has_cart ? "disabled" : "") ?>>
					Check Out
				</button>
    			<button type="button" class="btn btn-primary" onClick="checkin(<?php echo $cart->id;?>)"
					<?php echo ((! $has_cart OR ($has_cart and $user_cart != $cart->id)) ? "disabled" : "") ?>>
					Check In
				</button>
			</div>
			<div class="btn-group">
				<a href="<?php echo site_url('carts/view/'.$cart->id)?>"><button type="button" class="btn btn-primary">View</button></a>
    			<a href="<?php echo site_url('carts/stock/'.$cart->id)?>"><button type="button" class="btn btn-primary">Add Stock</button></a>
			</div>
		</div>
	<?php endforeach;?>
</div>