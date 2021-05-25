<?php

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{

    /**
     * Свойство для хранения массива маршрутов
     * @var array 
     */
    private $routes;

    /**
     * Конструктор вызывается при создании экземпляра класса
     */
    public function __construct()
    {
        // Путь к файлу с маршрутами, хранящимися в виде массива
        $routesPath = ROOT . '/config/routes.php';

        // Получаем маршруты из файла
        $this->routes = include($routesPath);
    }

    /**
     * Возвращаем строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        // Получаем строку запроса
        $uri = $this->getURI();

        // Проверяем наличие такого запроса в массиве маршрутов (routes.php)
        foreach ($this->routes as $uriPattern => $path) {

            // Сравниваем $uriPattern и $uri, т.е. "если путь, по которому мы перешли находится в массиве маршрутов, то..."
            if (preg_match("~$uriPattern~", $uri)) {

                // Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определяем название контроллера, экшена, параметры. Для этого разбиваем внутр. путь на составные части.
                $segments = explode('/', $internalRoute);

                // Название контроллера будет начинаться в заглавной буквы и состоять из 1ой части внутр. пути и слова Controller
               	$controllerName = ucfirst(array_shift($segments) . 'Controller');

               	// Название экшена будет начинаться со слова action и 2ой части внутр. пути, начинающейся с заглавной буквы
                $actionName = 'action' . ucfirst(array_shift($segments));

                // Оставшееся в сегментах назовём параметрами
                $parameters = $segments;

                // Подключаем файл класса-контроллера
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) include_once($controllerFile);

                // Создаём объект
                $controllerObject = new $controllerName;

                /* Вызываем необходимый метод ($actionName) класса определенного 
                объекта ($controllerObject) с заданными параметрами ($parameters) */
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                break;
            }
        }
    }
}
