<?php 
	$itemcount = 0; 
?>
<style>
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
		-webkit-appearance: none; 
		margin: 0; 
	}
	input[type=number] {
		-moz-appearance: textfield;
		text-align:center;
		min-width:50px;
	}
	.item {
		margin-bottom: 15px;
		width:200px;	
	}
	
	.row-eq {
		display:flex; 
		align-content:flex-end;
		-webkit-flex-flow: row wrap;
	}
}

</style>

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
		
		$(".item").change(function() {
			quantity = Number($(this).val());
			price = Number($(this).attr('price'));
			total = Number($("#total").html());
			display = "disp_"+$(this).attr('name')
			
			// If at least one item has already been ordered
			if ($("#"+display).length){
				oldQty = Number($("#"+display+"_qty").html());
				if (quantity > 0){
					//update quantity and total
					diff = quantity - oldQty;
					$("#total").html((total+diff*price).toFixed(2));
					$("#"+display+"_qty").html(quantity);
				}
				else {
					//remove item from bill and update total
					$("#total").html((total-oldQty*price).toFixed(2));
					$("#"+display).remove();
					$(this).val(0);
				}
			}
			else {
				if (quantity > 0){
					//add new item and update total
					name = $("#"+$(this).attr('name')+"_name").html();
					newItem = 	"<div id='"+display+"'><h4>"+name+"</h4>" +
								"<h5><span id='"+display+"_qty'>"+quantity+"</span> x $"+price.toFixed(2)+"</h5></div>";
					$("#order_items").append(newItem);
					$("#total").html((total+price*quantity).toFixed(2));
				}
				else {
					//don't allow below 0
					$(this).val(0);	
				}
			}
			
		});
	});
	
	function clearForm(){
		// clears ordered items, zeros out inputs and total
		$("#order_items").html("");
		$(":input[type='number']").val(0);
		$("#total").html("0.00");
	}
</script>

<h1>Quick Sale</h1>
<?php echo form_open('sale/place_order',array('id'=>'orderForm')) ?>
<div class="col-xs-12 col-md-3">
	<h2>Order</h2>
	<div class="well">
		<h3>Ordered Items:</h3>
		<div id="order_items"></div>
	
		<h3>Total: $<span id="total">0.00</span></h3>
		
		
			<button type="submit" class="btn btn-primary">Place Order</button>
			<button type="button" onClick="clearForm()" class="btn btn-default">Clear</button>
	</div>
</div>

<div class="col-xs-12 col-md-9">
	<h2>Availible Items</h2>
	<div class="row row-eq">
	<?php foreach ($items->result() as $item):?>
		<div class="col-xs-4 col-md-3 item" style="margin-top:auto">
			<img class="img-responsive" src="<?php echo base_url().'/assets/uploads/files/'. $item->image;?>" alt="">
			<h3 id="item_<?php echo $item->id;?>_name">
				<?php echo $item->short_description;?>
			</h3>
			<h4>x <?php echo round($item->onhand);?></h4>
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="minus btn btn-info">
						<span class="glyphicon glyphicon-minus"></span>
					</button>
				</span>
				<input type="number" class="item form-control" id="item_<?php echo $item->id;?>"
					name="item_<?php echo $item->id;?>" price="<?php echo $item->price;?>" 
					min="0" max="<?php echo $item->onhand;?>" value="0">
				<span class="input-group-btn">
					<button type="button" class="plus btn btn-primary">
						<span class="glyphicon glyphicon-plus"></span>
					</button>
				</span>
			</div>
		</div>
		
	<?php endforeach;?>
	</div>
</div>
</form>