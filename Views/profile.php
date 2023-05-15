<div class="container">
	<div class="row">
		<div class="">
			<div class="container">
				<h3><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h3>
				<hr>
				<?php if(session()->get('success')):?>
				<div class="alert alert-success" role="alert">
					<?= session()->get('success') ?>
				</div>				
				<?php endif;?>
				<form class="" action="/profile" method="post">
					<div class="row">
					
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="firstname">Ваше имя</label>
								<input type="text" class="form-control mt-2" name="firstname" id="firstname" value="<?=set_value('firstname', $user['firstname'])?>">
							</div>
						</div>
						
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="lastname">Ваша организация</label>
								<input type="text" disabled class="form-control mt-2" name="lastname" id="lastname" value="<?=set_value('lastname', $user['lastname'])?>">
							</div>
						</div>
						
						<div class="col-12 col-sm-12 mt-3">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" required class="form-control mt-2" readonly id="email" value="<?= $user['email']?>">
							</div>
						</div>
						
						<div class="col-12 col-sm-12 mt-3">
							<div class="form-group">
								<label for="email">Ваша роль на сайте</label>
								<input type="text" required class="form-control mt-2" readonly id="email" value="<?php if($user['role_id'] === 'admin' || $user['role_id'] === 'superadmin') { echo 'Администратор'; } else { echo 'Пользователь'; } ?> ">
							</div>
						</div>
						
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="password">Пароль</label>
								<input type="password" required class="form-control mt-2" name="password" id="password" value="">
							</div>
						</div>
						
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="password_confirm">Повторите пароль</label>
								<input type="password" class="form-control mt-2" name="password_confirm" id="password_confirm" value="">
							</div>
						</div>
						
					<?php if (isset($validation)): ?>
						<div class="col-12 mt-3">
							<div class="alert alert-danger">
								<?= $validation->listErrors(); ?>
							</div>
						</div>
					<?php endif; ?>
					
					</div>
					
					<div class="row">
						<div class="col-12 col-sm-4 mt-3">
							<button type="submit" class="btn btn-primary">Обновить</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>