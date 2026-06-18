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

    // Blueprint profissional
    ['GET',  '/dashboard/blueprint',                'BlueprintController@edit',   ['auth']],
    ['POST', '/dashboard/blueprint',                'BlueprintController@update', ['auth', 'csrf']],

    // Depoimentos
    ['GET',  '/dashboard/testimonials',             'TestimonialController@index',   ['auth']],
    ['POST', '/dashboard/testimonials',             'TestimonialController@store',   ['auth', 'csrf']],
    ['POST', '/dashboard/testimonials/{id}',        'TestimonialController@update',  ['auth', 'csrf']],
    ['POST', '/dashboard/testimonials/{id}/delete', 'TestimonialController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/testimonials/{id}/toggle', 'TestimonialController@toggle',  ['auth', 'csrf']],
    ['POST', '/dashboard/testimonials/{id}/move',   'TestimonialController@move',    ['auth', 'csrf']],

    // FAQ do perfil
    ['GET',  '/dashboard/faq',                      'ProfileFaqController@index',   ['auth']],
    ['POST', '/dashboard/faq',                      'ProfileFaqController@store',   ['auth', 'csrf']],
    ['POST', '/dashboard/faq/{id}',                 'ProfileFaqController@update',  ['auth', 'csrf']],
    ['POST', '/dashboard/faq/{id}/delete',          'ProfileFaqController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/faq/{id}/move',            'ProfileFaqController@move',    ['auth', 'csrf']],

    // Linha do tempo
    ['GET',  '/dashboard/timeline',                 'TimelineController@index',   ['auth']],
    ['POST', '/dashboard/timeline',                 'TimelineController@store',   ['auth', 'csrf']],
    ['POST', '/dashboard/timeline/{id}',            'TimelineController@update',  ['auth', 'csrf']],
    ['POST', '/dashboard/timeline/{id}/delete',     'TimelineController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/timeline/{id}/move',       'TimelineController@move',    ['auth', 'csrf']],

    // Galeria
    ['GET',  '/dashboard/gallery',                  'GalleryController@index',   ['auth']],
    ['POST', '/dashboard/gallery',                  'GalleryController@store',   ['auth', 'csrf']],
    ['POST', '/dashboard/gallery/{id}/delete',      'GalleryController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/gallery/{id}/move',        'GalleryController@move',    ['auth', 'csrf']],

    // Cupons
    ['GET',  '/dashboard/coupons',                  'CouponController@index',   ['auth']],
    ['POST', '/dashboard/coupons',                  'CouponController@store',   ['auth', 'csrf']],
    ['POST', '/dashboard/coupons/{id}',             'CouponController@update',  ['auth', 'csrf']],
    ['POST', '/dashboard/coupons/{id}/delete',      'CouponController@destroy', ['auth', 'csrf']],
    ['POST', '/dashboard/coupons/{id}/toggle',      'CouponController@toggle',  ['auth', 'csrf']],
    ['POST', '/dashboard/coupons/{id}/move',        'CouponController@move',    ['auth', 'csrf']],

    // Formulário de contato (gestão)
    ['GET',  '/dashboard/contact',                  'ContactController@index',         ['auth']],
    ['POST', '/dashboard/contact/toggle',           'ContactController@toggleEnabled', ['auth', 'csrf']],
    ['POST', '/dashboard/contact/fields',           'ContactController@storeField',    ['auth', 'csrf']],
    ['POST', '/dashboard/contact/fields/{id}',      'ContactController@updateField',   ['auth', 'csrf']],
    ['POST', '/dashboard/contact/fields/{id}/delete', 'ContactController@destroyField', ['auth', 'csrf']],
    ['POST', '/dashboard/contact/fields/{id}/move', 'ContactController@moveField',     ['auth', 'csrf']],
    ['POST', '/dashboard/contact/messages/{id}/delete', 'ContactController@deleteMessage', ['auth', 'csrf']],

    // QR Code
    ['GET',  '/dashboard/qr',                       'QrController@index',         ['auth']],
    ['GET',  '/dashboard/qr/png',                   'QrController@png',           ['auth']],
    ['POST', '/dashboard/qr/toggle',                'QrController@toggleProfile', ['auth', 'csrf']],

    // Analytics
    ['GET',  '/dashboard/analytics',                'AnalyticsController@index', ['auth']],

    // Perfil público + redirecionamento rastreável
    ['POST', '/u/{username}/contact',               'ContactController@submit', ['csrf']],
    ['GET',  '/u/{username}/qr.png',                'QrController@publicPng'],
    ['GET',  '/u/{username}',                       'ProfileController@show'],
    ['GET',  '/l/{id}',                             'RedirectController@go'],
];
