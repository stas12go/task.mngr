<?php include ROOT . '/views/layouts/header.php'; ?>

<div class="container container_shadow"><!--sign up form-->
	<div class="block">
		<div class="card card_content">
		    <h2>Вход на сайт</h2>
			<?php if (isset($error)) : ?>
			    <p><?=$error ?></p>
			<?php endif; ?>
			<form action="#" method="post" style="/*display: contents;*/">
		        <input class="input__inLine" type="login" name="login" placeholder="Логин" required value="<?= isset($login) ? $login  : '' ?>" />
		        <input class="input__inLine" type="password" name="password" placeholder="Пароль" required value="<?= isset($password) ? $password  : '' ?>" />
		        <input type="submit" name="submit" class="btn btn-default" value="Вход" />
		    </form>
		</div>
	</div>
</div><!--/sign up form-->
<!-- <?php var_dump($_SESSION) ?> -->
<?php include ROOT . '/views/layouts/footer.php'; ?>