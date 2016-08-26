<div class="alert alert-<?php echo $alertType;?> danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert"
	aria-hidden="true">
	&times;
	</button>
	<?php foreach($messages as $message){ ?>
	  <p><?php echo $message;?></p>
	<?php } ?>      
</div>