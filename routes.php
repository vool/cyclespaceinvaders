<?php

// Custom 404 Handler
$router->set404('ErrorController@notFound');

//Before Router Middleware
$router->before('GET', '/.*', function () {
    header('X-Powered-By: Blood, sweat and gears');
});

$router->get('/', 'HomeController@home');

$router->get('/(\d+)', function ($page) use ($router) {
    call_user_func_array([new CycleSpaceInvaders\Controllers\HomeController, 'Home'], [$page]);
});

/*
 * Leader Board
 */
$router->get('/leader-board(/\w+)?', 'LeaderBoardController@allTime');//function () use ($tpl) {);

/*
 * Invaders
 */

$router->get('/tag(/\w+)?(/\d+)?', 'InvaderController@tag');

$router->get('/invaders(/\d+)?(/\w+)?', 'InvaderController@index');

$router->get('/invader(/\d+)?', 'InvaderController@show');

/*
Players
*/
$router->get('/players(/\d+)?', 'PlayerController@index');

$router->get('/player/(@\w+)(/\d+)?', function ($username, $page) {
    call_user_func_array([new CycleSpaceInvaders\Controllers\PlayerController, 'Show'], [$username, is_null($page) ? 1 : $page]);
});

$router->get('/get-involved', 'PageController@getInvolved');

/*
Sitemap
*/
$router->get('/sitemap.xml', 'SitemapController@sitemap');


/*
Actions
*/
$router->mount('/actions', function () use ($router) {
    $router->get('/update', 'ActionsController@update');

    //$router->get('/import', 'ActionsController@import');

    //$router->get('/init', 'DbSetupController@initDB');

    $router->get('/update-players', 'ActionsController@updatePlayers');
});
