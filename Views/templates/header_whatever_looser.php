<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
		<link href="/assets/css/style.css" rel="stylesheet">
		<title></title>
	</head>
	<body>
	<?php
		$uri = service('uri');
	?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	
	  <div class="container">
		<a class="navbar-brand" href="/">WHATEVER LOOSER</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		  <span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<?php if (session()->get('isLoggedIn')): ?>
		 <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item <?php echo ($uri->getSegment(1) == 'dashboard' ? 'active' : null) ?>">
			  <a class="nav-link" aria-current="page" href="/dashboard">Сервисы</a>
			</li>
			<li class="nav-item <?php echo ($uri->getSegment(1) == 'profile' ? 'active' : null) ?>">
			  <a class="nav-link" href="/profile">Профиль</a>
			</li>
		  </ul>
		  <ul class="navbar-nav my-2 my-lg-0">
			<li class="nav-item">
				<a class="nav-link" href="/logout">Выход</a>
			</li>
		  </ul>
		<?php else:?>
		  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item <?php echo ($uri->getSegment(1) == 'login' ? 'active' : null) ?>">
			  <a class="nav-link" aria-current="page" href="/">Вход</a>
			</li>
			<li class="nav-item <?php echo ($uri->getSegment(1) == 'registration' ? 'active' : null) ?>">
			  <a class="nav-link" href="/register">Регистрация</a>
			</li>
		  </ul>
		  <?php endif; ?>
		</div>
	  </div>
</nav>