<?php if (strlen($validation === null)) : ?>
<div class="col-12 col-sm-6 mt-3">
	<div class="alert alert-danger"> 
		<?= $validation->listErrors(); ?>
	</div>
</div>
<?php endif; ?>
<?php if (isset($message)): ?>
<div class="container mt-5">
	<div class="col-12 col-sm-6 mt-3">
		<div class="alert alert-danger">
			<?php print_r($message); ?>
		</div>
	</div>
</div>
<?php endif; ?>