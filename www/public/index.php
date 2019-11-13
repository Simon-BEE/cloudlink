<?php

$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$app = App\App::getInstance();
$app->setStartTime();
$app::load();

$app->getRouter($basePath)

    // WEBSITE routes
    ->match('/', 'Site#index', 'home')
    ->match('/all', 'Site#all', 'all')
    ->get('/404', 'Site#notFound', 'notfound')
    ->post('/newlink', 'link#add', 'newlink')
    ->post('/removelink', 'link#delete', 'removelink')
    ->get('/lookingfor', 'search#research', 'search')

    // AUTH routes
    ->match('/login', 'auth#signIn', 'signin')
    //->match('/register', 'auth#signUp', 'signup')
    // ->match('/newpass', 'auth#newPassword', 'new_password')
    ->get('/logout', 'auth#logOut', 'logout')
    // ->get('/check/[*:token]-[i:id]', 'auth#confrmAccount', 'checking')
    
    // USER routes
    // ->get('/profile', 'user#myProfile', 'profile')

    ->run();
