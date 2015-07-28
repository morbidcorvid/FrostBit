<script>
	$(document).ready(function(){
		$("#order_date_select").change(function(){
			path = "<?php echo site_url('accounting/journal')?>";
			window.location = path + "/" + $(this).val();
		});
	});
</script>
<form name="date_selection">
<div class="form-group">
	<label for="order_date_select">Select Journal Date:</label>
	<select class="form-control" name = "date" id="order_date_select">
		<option value="">---</option>
		<?php 
			foreach ($query->result_array() as $row){
				$date =date_format(date_create($row['order_date']),"Y-m-d");
				echo "<option value='$date' " . ($date == $selectedDate ? "selected" : "") . ">$date</option>";
			}
		?>
	</select>
</div>
</form>
