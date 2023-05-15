<div class="container">
	<div class="row">
		<div class="">
			<div class="container" style="width:400px; margin-left: 170px;">
				<h3>Вход</h3>
				<hr>
				<?php if(session()->get('success')):?>
				<div class="alert alert-success" role="alert">
					<?= session()->get('success') ?>
				</div>				
				<?php endif;?>
				<form class="" action="/" method="post">
					<div class="form-group mt-3">
						<label for="email">Email</label>
						<input type="text" class="form-control mt-2" name="email" id="email" value="<?=set_value('email')?>">
					</div>
					<div class="form-group mt-3">
						<label for="password">Пароль</label>
						<input type="password" class="form-control mt-2" name="password" id="password" value="">
					</div>
					
					<?php if (isset($validation)): ?>
						<div class="col-12 mt-3">
							<div class="alert alert-danger">
								<?= $validation->listErrors(); ?>
							</div>
						</div>
					<?php endif; ?>
					
					<div class="row">
						<div class="col-12 col-sm-4 mt-3">
							<button type="submit" class="btn btn-primary">Войти</button>
						</div>
						<!-- <div class="col-12 col-sm-8 text-right mt-4">
							<a href="/register">Нет аккаунта? Зарегистрируйтесь!</a>
						</div> -->
					</div>
				</form>
			</div>
		</div>
	</div>
</div>