<?php

/**
 * Контроллер TaskController
 */
class TaskController
{
	/**
	* Метод добавляет новую запись о задаче в БД
	*/
	public function actionCreate()
	{
		// Если форма отправлена, получаем данные из формы
		if (isset($_POST['submit'])) {
			$title = $_POST['title'];
			$description = $_POST['description'];
			$endDate = $_POST['end_date'];
			$createDate = date('Y-m-d H:i:s');
			$priority = $_POST['priority'];
			$status = $_POST['status'];
			$creator = User::checkLogged();
			$responsible = isset($_POST['responsible']) ? $_POST['responsible'] : User::checkLogged();

			// И создаём запись о задаче в БД
			$result = Task::createTask(
				$title, 
				$description, 
				$endDate, 
				$createDate, 
				$updateDate, 
				$priority, 
				$status, 
				$creator, 
				$responsible);
		}

		// Отправляем на главную страницу
		header('Location: /');
	}


	public function actionEdit(int $id)
	{
		return Task::getTaskById($id);
	}

	/**
	* Метод обновляет запись о задаче в БД
	* @param int $id айди задачи
	*/
	public function actionUpdate($id)
	{
		// Получаем айди юзера, очевидно, что он авторизован
        $userId = User::checkLogged();

        // Проверяем роль
        $isDirector = User::isDirector($userId);

        // Если форма отправлена, то в переменные записываем полученную инфу
    	if (isset($_POST['submit'])) {
    		$title = isset($_POST['title']) ? $_POST['title'] : null;
			$description = isset($_POST['description']) ? $_POST['description'] : null;
			$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
			$priority = isset($_POST['priority']) ? $_POST['priority'] : null;
			$creator = $userId;
			$responsible = isset($_POST['responsible']) ? $_POST['responsible'] : User::checkLogged();
	        $status = $_POST['status'];
	        $updateDate = date('Y-m-d H:i:s');

	        // И обновлеяем задачу
	        Task::updateTaskById(
	        	$isDirector,
	        	$id,
	        	$title,
				$description,
				$endDate,
				$priority,
				$status,
				$creator,
				$updateDate,
				$responsible);
        }

		// Редиректим
		header("Location: /");
	}
}