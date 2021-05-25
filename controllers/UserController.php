<?php

/**
 * Контроллер UserController
 */
class UserController
{
	/**
	 * Action для страницы "Регистрация"
	 */
	public function actionRegister()
	{
		/* Обработка формы
		Если форма отправлена*/
		if (isset($_POST['submit'])) {
			// Получаем данные из формы
			$second_name = $_POST['second_name'];
			$first_name = $_POST['first_name'];
			$patronymic = $_POST['patronymic'];
			$login = $_POST['login'];
			$password = $_POST['password'];
			$role = $_POST['role'];

			/*Валидируем логин
			Если получили не строку (а массив), то пишем ошибку */
			if (!is_string(User::findUserInDb($login))) {
			    $error = 'Пользователь с указанным логином уже зарегистрирован';
			}

			// Если ошибок нет, то регистрируем пользователя
			if (!isset($error)) {
				$result = User::register($second_name, $first_name, $patronymic, $login, password_hash($password, PASSWORD_DEFAULT), $role);
				header('Location: /');
			}
		}

		// Подключаем вид
		require_once(ROOT . '/views/user/register.php');
		return true;
	}

	/**
	 * Action для страницы "Вход на сайт"
	 */
	public function actionLogin()
	{		
		/* Обрабатываем форму
		Если форма отправлена */
		if (isset($_POST['submit'])) {

			// Получаем данные из формы
			$login = $_POST['login'];
			$password = $_POST['password'];

			// Проверяем существует ли пользователь в БД
			$user = User::findUserInDb($login);

			// Если получили не массив, а что-то иное (строку), то записываем ошибку
			if (!is_array($user)) {
				$error = 'Пользователя с таким логином не существует';

			// Если получили массив с данными юзера
			} else {

				// Если указанный пароль не совпадает с паролем к записи в БД, то записываем ошибку
				if (!User::doesPasswordMatch($user, $password)) {
					$error = 'Вы ввели неверный пароль';

				// Если пароль прошёл проверку, то авторизовываем юзера и отправляем его на главную
				} else {
					User::auth($user);
					header('Location: /');
				}
			}
		}

		// Подключаем вид
		require_once(ROOT . '/views/user/login.php');
		return true;
	}

	/**
	 * Удаляем данные о пользователе из сессии
	 */
	public function actionLogout()
	{
		// Стартуем сессию
		session_start();
		
		// Удаляем информацию о пользователе из сессии
		unset($_SESSION["user"]);
		
		// Перенаправляем пользователя на главную страницу
		header("Location: /");
	}
}