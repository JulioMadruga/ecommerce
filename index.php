<?php
session_start();

require_once("vendor/autoload.php");
require_once("functions.php");

use  \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;


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

    $user = new User();

    $user->get((int)$iduser);

    $user->delete();

    header("Location: /admin/users");
    exit;




});

$app->get('/admin/users/:iduser', function ($iduser){

    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $Page = new PageAdmin();

    $Page->setTpl('users-update',array(
        "user" => $user->getValues()
    ));

});

$app->post('/admin/users/create', function (){

    User::verifyLogin();

    $user = new User();


    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    //var_dump($_POST);

    $user->setData($_POST);

   // var_dump($user);

    $user->save();




    header("Location: /admin/users");
    exit;



});

$app->post('/admin/users/:iduser', function ($iduser){

    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");
    exit;


});

$app->get('/admin/forgot', function (){

    $Page = new PageAdmin([
        "header" =>false,
        "footer" =>false
    ]);

    $Page->setTpl("forgot");

});

$app->post('/admin/forgot', function (){

    User::getForgot($_POST['email']);

    header("Location: /admin/forgot/sent");
    exit;

});


$app->get('/admin/forgot/sent', function (){

    $Page = new PageAdmin([
        "header" =>false,
        "footer" =>false
    ]);

    $Page->setTpl("forgot-sent");

});


$app->get('/admin/forgot/reset', function (){

    $user = User::validForgotDecrypt($_GET["code"]);

    $Page = new PageAdmin([
        "header" =>false,
        "footer" =>false
    ]);

    $Page->setTpl("forgot-reset", array(

        "name" => $user["desperson"],
        "code" => $_GET["code"]

    ));

});


$app->post('/admin/forgot/reset', function (){

    $forgot= User::validForgotDecrypt($_POST["code"]);

    User::setForgotUsed($forgot["idrecovery"]);

    $user = new User();

    $user->get((int)$forgot["iduser"]);

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
        "const" => 12
    ]);

    $user->setPassword($password);



    $Page = new PageAdmin([
        "header" =>false,
        "footer" =>false
    ]);

    $Page->setTpl("forgot-reset-success");


});



$app->get('/admin/categories', function (){

    $categories = Category::listAll();

    $Page = new PageAdmin();

    $Page->setTpl('categories',array(

        "categories" => $categories
    ));


});


$app->get('/admin/categories/create', function (){


    $Page = new PageAdmin();

    $Page->setTpl('categories-create');


});


$app->post('/admin/categories/create', function (){


    $categories = new Category();

    $categories->setData($_POST);

    $categories->save();

    $Page = new PageAdmin();

    $Page->setTpl('categories');


});

$app->run();

 ?>