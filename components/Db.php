<?php
/**
 * Класс Db
 * Компонент для работы с базой данных
 */
class Db
{

    /**
     * Устанавливает соединение с базой данных
     * @return 
     */
    public static function getConnection()
    {
        // Получаем параметры подключения из файла
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);

        // Устанавливаем соединение
        if ($_SERVER['SERVER_NAME'] == "stas12go-task-mngr.herokuapp.com") {
            //Get Heroku ClearDB connection information
            $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
            $cleardb_server = $cleardb_url["host"];
            $cleardb_username = $cleardb_url["user"];
            $cleardb_password = $cleardb_url["pass"];
            $cleardb_db = substr($cleardb_url["path"],1);
            $active_group = 'default';
            $query_builder = TRUE;
            // Connect to DB
            $db = new PDO("mysql:host={$cleardb_server};dbname={$cleardb_db}", $cleardb_username, $cleardb_password);
        } else {
            $db = new PDO("mysql:host={$params['host']};dbname={$params['dbname']}", $params['user'], $params['password']);
        }

        // Задаем кодировку
        $db->exec("set names utf8");

        // Возвращаем запущенное соединение с БД
        return $db;
    }

}
