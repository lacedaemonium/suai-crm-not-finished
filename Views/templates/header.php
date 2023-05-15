<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
		<link href="/assets/css/styles.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<title>СУАИ - система управления арестованным имуществом</title>
	</head>
	<body>
	<?php
		$uri = service('uri');
	?>
	<header class="header">
        <div class="container">
            <div class="logo_block pull-left">
                <a href="/" class="logo_block--item">
                    <span class="logo_block--img">
                        <img src="/assets/img/logo-ars.png" alt="">
                    </span>
                    <span class="logo_block--name">
                        Система управления имуществом<br>обращенным в доход государства
                    </span>
                </a>
                <a href="/" class="logo_block--item">
                    <span class="logo_block--img">
                        <img src="/assets/img/logo-ab.png" alt="">
                    </span>
                    <span class="logo_block--name">
                        <span>АКЦИОНЕРНОЕ ОБЩЕСТВО</span>
                        «ВОЛОДЯ И АРТЕМ»
                    </span>
                </a>
                <a href="" class="logo_block--item">
                    <span class="logo_block--img">
                       <!-- <img src="/bitrix/templates/new-2018/img/logo-ets.png" alt=""> -->
                    </span>
                </a>
            </div>
            <div class="h-auth pull-right"> 
				<?php if (session()->get('isLoggedIn')): ?>
				<div class="wrapper-demo"  style="z-index: 3;">
					<div id="dd" class="wrapper-dropdown-5" tabindex="1"><?php echo session()->get('firstname') ?>
						<ul class="dropdown">
							<li><a href="/profile"><i class="icon-user"></i> ✎ Профиль</a></li>
							<li><a href="/logout"><i class="icon-remove"></i> ⮾ Выйти</a></li>
						</ul>
					</div>
				​</div>
				<?php endif; ?>
            </div>
			<script type="text/javascript">
			function DropDown(el) {
				this.dd = el;
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						event.stopPropagation();
					});	
				}
			}

			$(function() {
				var dd = new DropDown( $('#dd') );
				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-5').removeClass('active');
				});

			});
		</script>
			
        </div>
</header>
<div class="navigation">
        <div class="container">
			<ul class="nav nav-pills">
			<?php if (session()->get('isLoggedIn')): ?>
				<li><a href="/deals">Список моих дел</a></li>
				<!--<li><a disabled href="#">Будущий пункт меню 1</a></li>
				<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Будущий пункт меню 2<!--<b class="caret"></b></a>-->
					<ul class="dropdown-menu">
						<li><a disabled href="#">Выпадающий пункт 1</a></li>
						<li><a disabled href="#">Выпадающий пункт 2</a></li>
						<li><a disabled href="#">Выпадающий пункт 3</a></li>
					</ul>
				</li>    
				<!--<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Будущий пункт меню 3<!--<b class="caret"></b></a>-->
					<ul class="dropdown-menu">
						<li><a href="#">Выпадающий пункт 1</a></li>
					</ul>
				</li>
			<?php endif; ?>
			</ul>
		</div>
</div>