<div class="container">
	<div class="row">
		<div class="">
			<div class="container" style="width: 800px;">
				<h3>Регистрация</h3>
				<hr>
				<form class="" action="/register" method="post">
					<div class="row">
					
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="firstname">Ваше имя</label>
								<input type="text" class="form-control mt-2" name="firstname" id="firstname" value="<?=set_value('firstname')?>">
							</div>
						</div>
						
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="lastname">Ваша организация</label>
								<input type="text" class="form-control mt-2" name="lastname" id="lastname" value="<?=set_value('lastname')?>">
							</div>
						</div>

						
						<div class="col-12 col-sm-12 mt-3">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control mt-2" name="email" id="email" value="<?=set_value('email')?>">
							</div>
						</div>
						
						<div class="col-12 col-sm-6 mt-3">
							<div class="form-group">
								<label for="password">Пароль</label>
								<input type="password" class="form-control mt-2" name="password" id="password" value="">
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
							<button type="submit" class="btn btn-primary">Регистрация</button>
						</div>
						<div class="col-12 col-sm-8 text-right mt-4">
							<a href="/">Уже есть аккаунт? Войти</a>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>