<h1><?php echo $cart->name;?></h1>
<h2>Description:</h2>
<p><?php echo $cart->description;?></p>
<h2>Stocks:</h2>
<div class="table-responsive">
	<table class="table table-hover">
		<tr>
			<th></th>
			<th>Name</th>
			<th>Description</th>
			<th>Cost</th>
			<th>Price</th>
			<th>In Cart</th>
		</tr>
		<?php foreach ($items->result() as $item):?>
			<tr>
				<td><img src="<?php echo base_url().'/assets/uploads/files/'. $item->image;?>" class="img-thumbnail" style="max-height:100px; min-width:45px;" alt="Placeholder image"></td>
				<td><?php echo $item->short_description;?></td>
				<td><?php echo $item->description;?></td>
				<td><?php echo $item->cost;?></td>
				<td><?php echo $item->price;?></td>
				<td><?php echo $item->onhand;?></td>
			</tr>
		<?php endforeach;?>
	</table>
</div>