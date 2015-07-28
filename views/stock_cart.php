<script>
	$(document).ready(function() {
		$(".plus").click(function() {
			$(this).parent().siblings(":input[type='number']").each(function() {
				$(this).val(Number($(this).val()) + 1);
				$(this).change();
			});
		});
		
		$(".minus").click(function() {
			$(this).parent().siblings(":input[type='number']").each(function() {
				$(this).val(Number($(this).val()) - 1);
				$(this).change();
			});
		});
	});
	function clearForm(){
		$(":input[type='number']").val(0);
	}
</script>

<h1><?php echo $cart->name;?></h1>
<?php echo form_open('carts/stock_action') ?>
	<input type="hidden" name="cart_id" value="<?php echo $cart->id;?>">
<div class="row">
	<div class="col-md-4">
		<h2>Manage Stocks:</h2>
	</div>
	<div class="col-md-4 col-md-offset-4">
		<button type="submit" class="btn btn-primary">Update Stocks</button>
		<button type="button" onClick="clearForm()" class="btn btn-default">Clear</button>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-hover">
		<tr>
			<th></th>
			<th>Name</th>
			<th>In Stock</th>
			<th>On Cart</th>
			<th>Add/Remove</th>
		</tr>
		<?php foreach ($items->result() as $item):?>
			<tr>
				<td class="col-md-2">
					<img src="<?php echo base_url().'/assets/uploads/files/'. $item->image;?>" 
						class="img-thumbnail" alt="Placeholder image"
						style="max-height:100px; min-width:45px;">
				</td>
				<td class="col-md-3"><?php echo $item->short_description;?></td>
				<td class="col-md-2"><?php echo round($item->stock);?></td>
				<td class="col-md-2"><?php echo $item->quantity;?></td>
				<td class="col-md-3 col-xs-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="minus btn btn-info">
								<span class="glyphicon glyphicon-minus"></span>
							</button>
						</span>
						<input style="min-width:50px" type="number" class="item form-control" name="item_<?php echo $item->id;?>" 
							min="<?php echo ($item->quantity > 0 ? "-".$item->quantity : "0");?>" 
							max="<?php echo $item->stock;?>" value="0">
						<span class="input-group-btn">
							<button type="button" class="plus btn btn-primary">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</span>
					</div>
				</td>
				
			</tr>
		<?php endforeach;?>
	</table>
</div>
</form>