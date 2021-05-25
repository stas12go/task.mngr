<?php include ROOT . '/views/layouts/header.php'; ?>

<div class="container container_shadow"><!--registration form-->
	<div class="block">
		<div class="card card_content">
			<h2>Регистрация на сайте</h2>

			<?php if (isset($error)) : ?>
			    <p><?=$error ?></p>
			<?php endif; ?>
			<form action="#" method="post">
				<input class="input__inLine" type="text" name="second_name" placeholder="Фамилия" required value="<?= isset($second_name) ? $second_name : '' ?>"/>
				<input class="input__inLine" type="text" name="first_name" placeholder="Имя" required value="<?= isset($first_name) ? $first_name  : '' ?>"/>
				<input class="input__inLine" type="text" name="patronymic" placeholder="Отчество" value="<?= isset($patronymic) ? $patronymic  : '' ?>"/>
				<input class="input__inLine" type="text" name="login" placeholder="Логин" required value="<?= isset($login) ? $login  : '' ?>"/>
				<input class="input__inLine" type="password" name="password" placeholder="Пароль" required value="<?= isset($password) ? $password  : '' ?>"/>
				<p>
					<input name="role" type="radio" value="director" required>Руководитель
				</p>
				<p>
					<input name="role" type="radio" value="employee">Сотрудник
				</p>
				<p>
					<input type="submit" name="submit" value="Регистрация" class="btn btn-default"/>
				</p>
			</form>
		</div>
	</div>
</div><!--/registration form-->

<?php include ROOT . '/views/layouts/footer.php'; ?>