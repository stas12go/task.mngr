<?php 

/**
 * Класс Task - модель для работы с задачами
 */
class Task
{
	/**
     * Возвращает массив задач из БД
     * @param bool $isDirector роль авторизованного юзера
     * @param int $userId его айди
     * @param string $date_filter фильтр по дате
     * @param int $responsible_filter фильтр по сотр-ку
     * @return array массив задач
     */
    public static function getTasksList($isDirector, $userId, $date_filter = 'all', $responsible_filter = 0)
    {
        $db = Db::getConnection();
     
        // Пишем основу запроса
        $sqlRequest = 'SELECT id, title, end_date, status, priority FROM `tasks`';

        // Выбираем способ фильтрации и дописываем запрос
        switch ($date_filter) {

            // Только задачи на сегодня
            case 'today':
                $sqlRequest .= 'WHERE DATE(end_date) = DATE(CURRENT_DATE())';
                break;

            // Задачи на неделю вперёд
            case 'week':
                $sqlRequest .= 'WHERE DATE(end_date) BETWEEN DATE(CURRENT_DATE()) AND DATE(CURRENT_DATE() + INTERVAL 1 WEEK)';
                break;

            // Все задачи
            default:
                // Дефолт сработает в случае "дефолтного" 'all' и ничего по сути не добавит к запросу
                $sqlRequest .= 'WHERE id > 0';
                break;
        }

        // Есть авторизованный юзер - рук-ль
        if ($isDirector) {

            // То у него есть полномочия отфильтровать и по конкретному сотруднику
            switch ($responsible_filter) {

                // Юзер не выбрал сотрудника, значит, отображаем задачи всех сотр-ков
                case 0:
                    // Разворачиваем логику в другую сторону. Здесь "дефолтный" 0 так же ничего не добавит к запросу и сразу выполнит его
                    $result = $db->query($sqlRequest);
                    break;
                
                /* Если всё же какой-то сотр-к был выбран, то дописываем запрос под конкретного сотр-ка,
                вставляем айди сотр-ка и выполняем запрос */
                default:
                    $sqlRequest .= ' AND responsible = :responsible';
                    $result = $db->prepare($sqlRequest);
                    $result->bindParam(':responsible', $responsible_filter, PDO::PARAM_INT);
                    $result->execute();
                    break;
            }

            // Если - сотр-к, то отобразим только задачи, которые поставлены перед ним
        } else {
            $result = $db->prepare($sqlRequest . ' AND responsible = :userId');
            $result->bindParam(':userId', $userId, PDO::PARAM_INT);
            $result->execute();
        }

        // Получение и возврат результатов
        $i = 0;
        $tasksList = array();
        while ($row = $result->fetch()) {
            $tasksList[$i]['id'] = $row['id'];
            $tasksList[$i]['title'] = $row['title'];
            $tasksList[$i]['end_date'] = $row['end_date'];
            switch ($row['status']) {
                case 'to_be_executed':
                    $tasksList[$i]['status'] = 'К выполнению';
                    break;
                case 'in_progress':
                    $tasksList[$i]['status'] = 'Выполняется';
                    break;
                case 'completed':
                    $tasksList[$i]['status'] = 'Выполнено';
                    break;
                case 'canceled':
                    $tasksList[$i]['status'] = 'Отменено';
                    break;
            }
            switch ($row['priority']) {
                case 'high':
                    $tasksList[$i]['priority'] = 'Высокий';
                    break;
                case 'medium':
                    $tasksList[$i]['priority'] = 'Средний';
                    break;
                case 'low':
                    $tasksList[$i]['priority'] = 'Низкий';
                    break;
            }                 
            $i++;
        }

        return $tasksList;
    }

    /**
     * Добавляем новую задачу
     * @param string $title заголовок задачи
     * @param string $description описание
     * @param date $endDate дата окончания
     * @param date $createDate дата создания
     * @param date $updateDate дата обновления
     * @param integer $priority приоритет
     * @param string $status статус
     * @param string $creator создатель
     * @param string $responsible ответственный
     * @return boolean результат добавления записи в таблицу
     */
    public static function createTask(
    	$title, 
    	$description, 
    	$endDate, 
    	$createDate, 
    	$updateDate, 
    	$priority, 
    	$status, 
    	$creator, 
    	$responsible)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO tasks(
                    title, 
                    description, 
                    end_date, 
                    create_date, 
                    update_date, 
                    priority, 
                    status, 
                    creator, 
                    responsible)
                VALUES (
                    :title, 
                    :description, 
                    :endDate, 
                    :createDate, 
                    :updateDate, 
                    :priority, 
                    :status, 
                    :creator, 
                    :responsible) ';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);
        $result->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $result->bindParam(':createDate', $createDate, PDO::PARAM_STR);
        $result->bindParam(':updateDate', $updateDate, PDO::PARAM_STR);
        $result->bindParam(':priority', $priority, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':creator', $creator, PDO::PARAM_INT);
        $result->bindParam(':responsible', $responsible, PDO::PARAM_INT);

        return $result->execute();
    }


    public static function getTaskById($id)
    {

        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM tasks WHERE id = :id';

        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполняем запрос
        $result->execute();

        // Возвращаем данные в JSON формате, чтоб потом его распарсить в modal.js
        echo json_encode($result->fetch());
    }

    /**
    * Метод обновляет данные конкретной задачи в БД
    * @param $isDirector bool редактор == руководитель?
    * @param $id int айди задачи
    * @param $title string новый заговолок задачи
    * @param $description string новое описание
    * @param $endDate string срок выполнения
    * @param $priority string приоритет
    * @param $status string статус
    * @param $creator int создатель
    * @param $updateDate string дата обновления (сегодня)
    * @param $responsible int ответственный
    */
    public static function updateTaskById(
        $isDirector,
        $id,
        $title,
        $description,
        $endDate,
        $priority,
        $status,
        $creator,
        $updateDate,
        $responsible)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Если задачу изменил рук-ль, то обновляем все данные из формы 
        if ($isDirector) {
            $sql = 'UPDATE tasks
                    SET title = :title,
                        description = :description,
                        end_date = :endDate,
                        update_date = :updateDate,
                        priority = :priority,
                        status = :status,
                        creator = :creator,
                        responsible = :responsible
                    WHERE id = :id';
                    $result = $db->prepare($sql);
                    $result->bindParam(':title', $title, PDO::PARAM_STR);
                    $result->bindParam(':description', $description, PDO::PARAM_STR);
                    $result->bindParam(':priority', $priority, PDO::PARAM_STR);
                    $result->bindParam(':creator', $creator, PDO::PARAM_INT);
                    $result->bindParam(':responsible', $responsible, PDO::PARAM_INT);
                    $result->bindParam(':endDate', $endDate, PDO::PARAM_STR);

        // Если - сотрудник, то только статус и дату обновления
        } else {
            $sql = 'UPDATE tasks
                    SET update_date = :updateDate,
                        status = :status
                    WHERE id = :id';
                    $result = $db->prepare($sql);
        }
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':updateDate', $updateDate, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);

        return $result->execute();
    }
}