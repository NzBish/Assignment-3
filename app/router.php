<?php
use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();

// example of using a redirect to another route
$collection->attachRoute(
    new Route(
        '/',
        array(
            '_controller' => 'ktc\a2\controller\HomeController::indexAction',
            'methods' => 'GET',
            'name' => 'Home'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/browse/',
        array(
            '_controller' => 'ktc\a2\controller\ProductController::indexAction',
            'methods' => 'GET',
            'name' => 'productIndex'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/users/',
        array(
            '_controller' => 'ktc\a2\controller\UserController::indexAction',
            'methods' => 'GET',
            'name' => 'userIndex'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/users/login/',
        array(
            '_controller' => 'ktc\a2\controller\UserController::loginAction',
            'methods' => array('GET','POST'),
            'name' => 'userLogin'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/users/logout/',
        array(
            '_controller' => 'ktc\a2\controller\UserController::logoutAction',
            'methods' => 'GET',
            'name' => 'userLogout'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/users/create/',
        array(
            '_controller' => 'ktc\a2\controller\UserController::createAction',
            'methods' => array('GET','POST'),
            'name' => 'userCreate'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/users/check/',
        array(
            '_controller' => 'ktc\a2\controller\UserController::checkAction',
            'methods' => 'POST',
            'name' => 'userCheck'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/search/retrieve/',
        array(
            '_controller' => 'ktc\a2\controller\SearchController::retrieveAction',
            'methods' => 'POST',
            'name' => 'searchRetrieve'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/search/',
        array(
            '_controller' => 'ktc\a2\controller\SearchController::searchAction',
            'methods' => array('GET','POST'),
            'name' => 'search'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/welcome/',
        array(
            '_controller' => 'ktc\a2\controller\HomeController::indexAction',
            'methods' => 'GET',
            'name' => 'welcome'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
