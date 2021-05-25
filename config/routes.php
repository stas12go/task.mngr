<?php 
return [
	// Задачи
    'task/create' => 'task/create',
    'task/edit/([0-9]+)' => 'task/edit/$1',
    'task/edit' => 'task/edit',
    'task/update/([0-9]+)' => 'task/update/$1',
    'task/update' => 'task/update',

	// Пользователи
    'user/login' => 'user/login',
    'user/logout' => 'user/logout',
    'user/register' => 'user/register',

    // Главная страница
	'' => 'site/index',
];