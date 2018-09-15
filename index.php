<?php
session_start();

require_once("vendor/autoload.php");
require_once("functions.php");

use  \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;


$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
  $Page = new Page();

  $Page->setTpl("index");

});


$app->get('/admin', function() {

    User::verifyLogin();

    $Page = new PageAdmin();

    $Page->setTpl("index");

});

$app->get('/admin/login', function() {


    $Page = new PageAdmin([
        "header" =>false,
        "footer" =>false
    ]);

    $Page->setTpl("login");

});

$app->post('/admin/login', function() {

  User::login(post('deslogin'), post('despassword'));

  header("Location: /admin");
  exit;

});


$app->get('/admin/logout', function() {

    User::logout();

    header("Location: /admin/login");
    exit;

});


$app->get('/admin/users', function (){

    User::verifyLogin();

    $users = User::listAll();

    $Page = new PageAdmin();

    $Page->setTpl('users', array(
        "users" => $users
    ));

});


$app->get('/admin/users/create', function (){

    User::verifyLogin();

    $Page = new PageAdmin();

    $Page->setTpl('users-create');

});

$app->get('/admin/users/:iduser/delete', function ($iduser){

    User::verifyLogin();


});

$app->get('/admin/users/:iduser', function ($iduser){

    User::verifyLogin();

    $Page = new PageAdmin();

    $Page->setTpl('users-update');

});

$app->post('/admin/users', function (){

    User::verifyLogin();



});

$app->post('/admin/users/:iduser', function ($iduser){

    User::verifyLogin();


});





$app->run();

 ?>