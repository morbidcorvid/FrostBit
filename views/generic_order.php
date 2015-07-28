<script>
	$(document).ready(function(){
		$("#quantity").change(function(){
			calculateTotal();
		});
		
		$("#item").change(function() {
			$("#cost_each").hide();
			if ($("#item option:selected").attr("type") == 'finance') {
				$("#cost_each").show();	
			}
			calculateTotal();
		});
		
		$("#cost").change(function(){
			calculateTotal();	
		});
		
		$("#cost_each").hide();
	});
	
	function calculateTotal (){
		if ($("#item option:selected").attr("type") == 'finance'){
			cost = Number($("#cost").val());
		}
		else{
			cost = Number($("#item option:selected").attr("cost"));
		}
		quantity = Number($("#quantity").val());
		total = quantity * cost;
		$("#total").val(total.toFixed(2));
	}
</script>

<h2>Generic Order</h2>
<?php echo form_open('admin/order_action',array('role'=>'form', 'class'=>'form-horizontal')) ?>
	<div class="form-group">
		<label class="control-label col-sm-2" for="item">Good/Service:</label>
		<div class="col-sm-4">
			<select id="item" name="item" class="form-control" required>
				<option value="">---</option>
				<?php foreach($items->result() as $item) :?>
					<option type="<?php echo $item->class;?>" cost="<?php echo $item->cost;?>" value="<?php echo $item->id;?>">
						<?php echo ucfirst($item->class) ." - ". $item->short_description;?>
					</option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2" for="quantity">Quantity:</label>
		<div class="col-sm-4">
			<input type="number" class="form-control" id="quantity" name="quantity" value="1" required>
		</div>
	</div>
	<div class="form-group" id="cost_each">
		<label class="control-label col-sm-2" for="quantity">Cost Each:</label>
		<div class="col-sm-4">
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="number" class="form-control" id="cost" name="cost" value="0.00">
			</div>
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
		<label class="control-label col-sm-2" for="account">Account:</label>
		<div class="col-sm-4">
			<select id="account" name="account" class="form-control">
				<?php foreach($accounts->result() as $account) :?>
					<option value="<?php echo $account->id;?>">
						<?php echo ucfirst($account->role) ." - ". $account->last_name;?>
					</option>
				<?php endforeach;?>
			</select>
		</div>
		<span class="help-block"><a href="<?php echo site_url('admin/crud/accounts/add')?>">Add New Account</a></span>
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