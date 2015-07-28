<script>
	$(document).ready(function(){
		$("#quantity").change(function(){
			var cost = <?php echo $item->cost;?>;
			var total = $(this).val() * cost;
			$("#total").val(total.toFixed(2)) ;
		});
	});
</script>
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php echo $item->short_description;?></h1>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<img class="img-responsive" src="../../assets/uploads/files/<?php echo $item->image;?>" alt="">
	</div>
	<div class="col-md-8">
		<h2>Item Description</h2>
		<p><?php echo $item->description;?></p>
		<h2>Item Details</h2>
		<ul>
			<li>Cost per: <?php echo $item->cost;?></li>
			<li>Price per: <?php echo $item->price;?></li>
		</ul>
		
		<h2>Order Item</h2>
		<?php echo form_open('admin/order_action', 'class="form-horizontal" role="form"') ?>
			<input type="hidden" name="item" value="<?php echo $item->id;?>">
			<div class="form-group">
				<label class="control-label col-sm-2" for="quantity">Quantity:</label>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="quantity" name="quantity" min="0" placeholder="" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Total:</label>
				<div class="col-sm-4">
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input id="total" class="form-control" type="text" value="0.00" disabled>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="account">Supplier:</label>
				<div class="col-sm-4">
					<select id="account" name="account" class="form-control">
						<?php foreach($suppliers->result() as $supplier) :?>
							<option value="<?php echo $supplier->id;?>"><?php echo $supplier->last_name;?></option>
						<?php endforeach;?>
					</select>
				</div>
				<span class="help-block"><a href="<?php echo site_url('admin/crud/accounts/add')?>">Add New Supplier</a></span>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="payment">Payment:</label>
				<div class="col-sm-4">
					<select id="payment" name="payment" class="form-control">
						<?php foreach($payments->result() as $payment) :?>
							<option value="<?php echo $payment->id;?>"><?php echo $payment->short_description;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-6">
					<button type="submit" class="btn btn-default">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>