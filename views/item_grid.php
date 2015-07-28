<?php 
	$rowcount = 0; 
?>

<div class="row">
<?php foreach ($items->result() as $item):?>
		<?php if ($rowcount % 4 == 0 and $rowcount != 0):?>
			</div>
			<div class="row">
		<?php endif; ?>
		
		<div class="col-md-3 portfolio-item">
			<a href="order_item/<?php echo $item->id;?>">
				<img class="img-responsive" src="../assets/uploads/files/<?php echo $item->image;?>" alt="">
			</a>
			<h3>
				<a href="order_item/<?php echo $item->id;?>"><?php echo $item->short_description;?></a>
			</h3>
			<p><?php echo $item->description;?></p>
		</div>
<?php $rowcount+=1; endforeach;?>
</div>