<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::extensions(['json', 'xml']);
Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});
// TODO(Fati): enlever les commentaires autour des POST
Router::scope('/v1', function (RouteBuilder $routes) {
    $routes->connect('/login', ['controller' => 'Login', 'action' => 'basic', /*'_method' => 'POST'*/]);
    $routes->connect('/login/facebook', ['controller' => 'Login', 'action' => 'facebook', /*'_method' => 'POST'*/]);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout', '_method' => 'GET']);


    $routes->connect('/profils', ['controller' => 'Users', 'action' => 'index', '_method' => 'GET']);
    $routes->connect('/users', ['controller' => 'Users', 'action' => 'add', /*'_method' => 'POST'*/]);
    $routes->connect('/users/:uuid',
        ['controller' => 'Users', 'action' => 'get', '_method' => 'GET'],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/users/:uuid/accept',
        ['controller' => 'Users', 'action' => 'accept', /*'_method' => 'POST'*/],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/users/:uuid/decline',
        ['controller' => 'Users', 'action' => 'decline', /*'_method' => 'POST'*/],
        ['uuid' => '[0-9a-zA-Z]+']);


    $routes->connect('/me', ['controller' => 'Profil', 'action' => 'profil', '_method' => 'GET']);
    $routes->connect('/me', ['controller' => 'Profil', 'action' => 'patch', '_method' => 'PATCH']);
    $routes->connect('/me/password', ['controller' => 'Profil', 'action' => 'changePassword', '_method' => 'PUT']);

    $routes->connect('/me/photos', ['controller' => 'Profil', 'action' => 'addPhoto', /*'_method' => 'POST'*/]);
    $routes->connect('/me/photos/:uuid',
        ['controller' => 'Profil', 'action' => 'deletePhoto', '_method' => 'DELETE'],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/me/photos',
        ['controller' => 'Profil', 'action' => 'updatePhotosOrder', '_method' => 'PUT']);

    $routes->connect('/me/chats', ['controller' => 'Profil', 'action' => 'getChats', '_method' => 'GET']);


    $routes->connect('/chats/:uuid',
        ['controller' => 'Chats', 'action' => 'get', '_method' => 'GET'],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/chats/:uuid',
        ['controller' => 'Chats', 'action' => 'addMessage', /*'_method' => 'POST'*/],
        ['uuid' => '[0-9a-zA-Z]+']);


    $routes->connect('/events', ['controller' => 'Events', 'action' => 'index', '_method' => 'GET']);
    $routes->connect('/events/:uuid', ['controller' => 'Events', 'action' => 'get', '_method' => 'GET'],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/events/:uuid/participate',
        ['controller' => 'Events', 'action' => 'participate', /*'_method' => 'POST'*/],
        ['uuid' => '[0-9a-zA-Z]+']);
    $routes->connect('/events/:uuid/decline',
        ['controller' => 'Events', 'action' => 'decline', /*'_method' => 'POST'*/],
        ['uuid' => '[0-9a-zA-Z]+']);
});


/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
