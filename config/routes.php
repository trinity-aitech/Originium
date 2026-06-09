<?php

declare(strict_types=1);

/*
| Cada rota: [MÉTODO, PADRÃO, 'Controller@metodo', [middlewares]]
| Parâmetros dinâmicos usam {chave}.
*/

return [
    // Público
    ['GET',  '/',                                   'HomeController@index'],

    // Autenticação
    ['GET',  '/register',                           'AuthController@showRegister', ['guest']],
    ['POST', '/register',                           'AuthController@register',     ['guest', 'csrf']],
    ['GET',  '/login',                              'AuthController@showLogin',    ['guest']],
    ['POST', '/login',                              'AuthController@login',        ['guest', 'csrf']],
    ['POST', '/logout',                             'AuthController@logout',       ['auth', 'csrf']],

    // Dashboard
    ['GET',  '/dashboard',                          'DashboardController@index',        ['auth']],
    ['GET',  '/dashboard/profile',                  'DashboardController@editProfile',  ['auth']],
    ['POST', '/dashboard/profile',                  'DashboardController@updateProfile', ['auth', 'csrf']],

    // Links (CRUD)
    ['GET',  '/dashboard/links',                    'LinkController@index',   ['auth']],
    ['GET',  '/dashboard/links/create',             'LinkController@create',  ['auth']],
    ['POST', '/dashboard/links',                    'LinkController@store',   ['auth', 'csrf']],
    ['GET',  '/dashboard/links/{id}/edit',          'LinkController@edit',    ['auth']],
    ['POST', '/dashboard/links/{id}',               'LinkController@update',  ['auth', 'csrf']],
    ['POST', '/dashboard/links/{id}/delete',        'LinkController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/links/{id}/toggle',        'LinkController@toggle',  ['auth', 'csrf']],
    ['POST', '/dashboard/links/{id}/move',          'LinkController@move',    ['auth', 'csrf']],

    // Temas
    ['GET',  '/dashboard/themes',                   'ThemeController@index',  ['auth']],
    ['POST', '/dashboard/themes',                   'ThemeController@update', ['auth', 'csrf']],

    // Analytics
    ['GET',  '/dashboard/analytics',                'AnalyticsController@index', ['auth']],

    // Perfil público + redirecionamento rastreável
    ['GET',  '/u/{username}',                       'ProfileController@show'],
    ['GET',  '/l/{id}',                             'RedirectController@go'],
];
