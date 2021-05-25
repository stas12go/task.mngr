<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/resources/css/style.css">
	<title>Главная страница</title>
</head>
<body>
<div class="container container_shadow header" style="margin-bottom: 8px; <?= isset($_SESSION['user']) ? 'justify-content: flex-end;"' : '' ?>">


	<?php if ($_SERVER['REQUEST_URI'] != '/') : ?>	
		<a href="/" class="btn btn_add">Главная страница</a>
	<?php endif ?>

	<?php if (isset($_SESSION['user'])) : ?>
		<a href="/user/logout/" class="btn btn_add">Выход</a>
	<?php else : ?>
		<?php if ($_SERVER['REQUEST_URI'] != '/user/login/') : ?>	
			<a href="/user/login/"  class="btn btn_add">Вход</a>
		<?php elseif ($_SERVER['REQUEST_URI'] != '/user/register/') : ?>	
			<a href="/user/register/"  class="btn btn_add">Регистрация</a>
		<?php endif ?>
	<?php endif ?>
</div>