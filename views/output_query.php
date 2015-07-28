<div class="table-responsive">
	<table class="table table-hover">
		<tr>
			<?php 
				foreach ($query->list_fields() as $field){
					echo "<th>$field</th>";	
				}
			?>
		</tr>
		<?php 
			foreach ($query->result_array() as $row){
				echo "<tr>";
				foreach ($row as $field){
					echo "<td>$field</td>";
				}
				echo "</tr>";
			}
		?>
	</table>
</div>