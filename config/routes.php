<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/users', function () {
    Router::get('/index', 'App\Controller\UserController@index');
    Router::get('/show/{id:\d+}', 'App\Controller\UserController@show');
    Router::post('/create', 'App\Controller\UserController@create');
});
Router::addGroup('/activities', function () {
    Router::get('/index', 'App\Controller\ActivityController@index');
    Router::get('/show/{id:\d+}', 'App\Controller\ActivityController@show');
    Router::post('/create', 'App\Controller\ActivityController@create');
    Router::post('/create/users', 'App\Controller\ActivityController@createUsers');
    Router::get('/show/{id:\d+}/users', 'App\Controller\ActivityController@userList');
    Router::post('/score/user', 'App\Controller\ActivityController@scoreUser');
    Router::get('/show/{id:\d+}/score', 'App\Controller\ActivityController@userScoreList');
    Router::post('/user/score', 'App\Controller\ActivityController@userScore');
}, ['middleware' => [\App\Middleware\AuthCheckMiddleware::class]]);
