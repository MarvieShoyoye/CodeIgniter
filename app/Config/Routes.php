<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\UserController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    // User routes
    $routes->post('register', 'UserController::register');
    $routes->post('login', 'UserController::login');
    $routes->get('verify-email/(:segment)', 'UserController::verifyEmail/$1');
    $routes->get('user', 'UserController::getUser', ['filter' => 'auth']);
    $routes->put('user/update', 'UserController::update', ['filter' => 'auth']);
    $routes->post('logout', 'UserController::logout', ['filter' => 'auth']);
    $routes->delete('user/delete', 'UserController::deleteUser', ['filter' => 'auth']);

    // Post routes
    $routes->get('posts', 'PostsController::index');
    $routes->get('posts/(:num)', 'PostsController::show/$1');
    $routes->post('posts', 'PostsController::store', ['filter' => 'auth']);
    $routes->put('posts/(:num)', 'PostsController::update/$1', ['filter' => 'auth']);
    $routes->delete('posts/(:num)', 'PostsController::delete/$1', ['filter' => 'auth']);
});
