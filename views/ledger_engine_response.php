<script>
	$(document).ready(function(){
		$(".panel-heading").click(function(){
			$(this).next(".panel-body").toggle();
		});
		
		$(".panel-body").hide();
	});
</script>

<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel-heading">Response from Ledger Engine</div>
		<div class="panel-body"><?php echo nl2br($StringReceived) ?></div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">JV sent to Ledger Engine</div>
		<div class="panel-body"><?php echo nl2br($POSTString) ?></div>
	</div>
</div>