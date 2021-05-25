<?php 

/**
 * Класс User - модель для работы с пользователями
 */
class User
{
	protected $user;
	/**
	 * Возвращает идентификатор пользователя, если он авторизирован
	 * Иначе перенаправляет на страницу входа
	 * @return string Идентификатор пользователя
	 */
	public static function checkLogged()
	{
		// Если сессия есть, вернем идентификатор пользователя
		if (isset($_SESSION['user'])) return $_SESSION['user'];

		// Иначе редиректим на страница авторизации
		header("Location: /user/login/");
	}

	/**
	 * Сохраняем данные пользователя в БД 
	 * @param string $second_name Фамилия
	 * @param string $first_name Имя
	 * @param string $patronymic Отчество
	 * @param string $login Логин
	 * @param string $email E-mail
	 * @param string $password Пароль
	 */
	public static function register($second_name, $first_name, $patronymic, $login, $password, $role)
	{
		// Подключаемся к БД
		$db = Db::getConnection();

		// "Вставить в таблицу юзерс в такие-то колонки такие-то значения"
		$sql = 'INSERT INTO users(
                    second_name, 
                    first_name, 
                    patronymic, 
                    login, 
                    password, 
                    role)
                VALUES (
                    :second_name, 
                    :first_name, 
                    :patronymic, 
                    :login, 
                    :password, 
                    :role) ';

        // Подготавливаем запрос к БД
        $result = $db->prepare($sql);
        $result->bindParam(':second_name', $second_name, PDO::PARAM_STR);
        $result->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $result->bindParam(':patronymic', $patronymic, PDO::PARAM_STR);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':role', $role, PDO::PARAM_STR);

        // Выполняем запрос
        return $result->execute();

		// Отправляем пользователя авторизовываться
		header("Location: /user/login");
	}

	/**
	 * Проверяет email
	 * @param string $email <p>E-mail</p>
	 * @return boolean <p>Результат выполнения метода</p>
	 */
	public static function checkLogin($login)
	{
		return $login >= 6;
	}

	/**
	 * Проверяем пароль. Например, он не должен быть короче шести символов.
	 * @param string $password Пароль
	 * @return boolean Результат выполнения метода
	 */
	public static function checkPassword($password)
	{
		return strlen($password) >= 6;
	}

	/**
	 * Проверяем существует ли в БД запись с указанным логином
	 * @param string $login Логин
	 * @return mixed $user массив с данными юзера или текст ошибки
	 */
	public static function findUserInDb($login)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Обращаемся к БД и ищем в ней запись с логином
		$result = $db->prepare('SELECT id, login, password FROM `users` WHERE `login` = :login');
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $user = $result->fetch();

		// Если нашли логин в БД
		if ($user) return $user;

		// Иначе возвращаем текст ошибки
		return 'Пользователя с таким логином не существует';
	}

	/**
	* Проверка пароля на "правильность"
	* @param array $user массив с данными пользователя
	* @param string $password введёный пароль
	* @return bool совпал ли пароль с записью в БД
	*/
	public static function doesPasswordMatch(array $user, string $password) :bool
	{
		return password_verify($password, $user['password']);
	}

	/**
	 * Запоминаем пользователя в браузере
	 * @param array $user массив с инфой об юзере
	 */
	public static function auth($user)
	{
		// Записываем идентификатор пользователя в сессию
		$_SESSION['user'] = $user['id'];
	}



	public static function getEmployers()
	{
		// Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД

        $employersList = $db->query('SELECT * FROM users WHERE `role` = "employee"');


        return $employersList->fetchAll();
	}

	/**
	* Проверяет роль юзера
	* @param int $id айди юзера
	* @return bool юзер == руководитель?
	*/
	public static function isDirector($id)
	{
		$db = Db::getConnection();

		// Создаём запрос в БД, суём айди, выполняем, получаем ассоциативный массив
		$result = $db->prepare('SELECT role FROM `users` WHERE id = :id');
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();
		$user = $result->fetch(PDO::FETCH_ASSOC);

		return $user['role'] == 'director';
	}
}