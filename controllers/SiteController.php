<?php

/**
 * Контроллер SiteController
 */
class SiteController
{
	/**
     * Action для главной страницы
     */
    public function actionIndex()
    {
	    // Проверяем авторизирован ли пользователь. Если нет, он будет переадресован
        $userId = User::checkLogged();

        // Проверяем роль
        $isDirector = User::isDirector($userId);

        // Если применён фильтр
        if (isset($_POST['filter'])) {

            // Записываем параметры фильтрации
            $date_filter = isset($_POST['date_filter']) ? $_POST['date_filter'] : 'all';
            $responsible_filter = isset($_POST['responsible_filter']) ? (int)$_POST['responsible_filter'] : 0;

            // Достаём задачи из БД
            $tasksList = Task::getTasksList($isDirector, $userId, $date_filter, $responsible_filter);
        } else {
            // Достаём задачи из БД
            $tasksList = Task::getTasksList($isDirector, $userId);
        }

        // Получаем список "сотрудников" для назначения задач
        $employersList = User::getEmployers();
        
    	// Подключаем вид
        require_once(ROOT . '/views/site/index.php');
        // return true;
	}
}