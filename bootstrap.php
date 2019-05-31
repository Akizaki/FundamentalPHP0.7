<?php

/*
  require 'Core/Utility/DateTimeCarbon.class.php';
  $dateTimeCarbon = new DateTimeCarbon("Asia/tokyo");
  var_dump($dateTimeCarbon->getDateTimeAsString());
  die();
 * 
 */

/* FundamentalPHP.classの単体テスト
  require 'Core/FundamentalPHP.class.php';
  require 'Core/Request.class.php';
  require 'Core/Exception/MemberInaccessibleException.class.php';
  require 'Core/Exception/MemberUndefinedException.class.php';
  $request = new Request();
  var_dump($request->test);
 */

require 'Core/Loader.class.php';

$dirs = [
    "Controller",
    "Core/",
    "Core/Db",
    "Core/Interface",
    "Core/Function",
    "Core/Plugin",
    "Core/Exception",
    "Core/Utility",
    "Model/",
];

$extensions = [
    "function" => ".function.php",
    "class" => ".class.php",
    "abstract" => ".abstract.php",
    "interface" => ".interface.php",
    "trait" => ".trait.php",
    "plugin" => ".plugin.php"
];


$loader = new Loader($dirs, $extensions);
$loader->register();

$controller = new ApplicationController(true, "Mysqli");
$controller->dispatch();

$mysqli = new Db_articles();
$mysqli->connect([
    "host" => "localhost",
    "user" => "root",
    "password" => "",
    "db" => "articles"
]);
$article = $mysqli->getResource();
/* TODO::Viewクラスが仕上がったら下記をテンプレート化して読み込ませる */
?>