<?php
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


$request = new Request(["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "PATCH", "STREAM"]);
$request->is("HEAD", "OPTIONS");

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



<html>
    <head>
        <meta charset = "UTF-8">
        <title>{DATA.TITLE}</title>
        <link rel="stylesheet" href="web/normalize.css" type="text/css" media="all">
        <link rel="stylesheet" href="web/defaults.css" type="text/css" media="all">

        <style>
            body{
                background: #faf0e6;
            }

            section#container{
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
            }

            fieldset{
                background: #FFF;
                margin: 12px auto;
                width: 600px;
                border-radius: 8px;
                box-shadow: 2px 4px #DADADA;
            }

            fieldset input{
                width: 580px;
            }

            fieldset input[type = "submit"]{
                width: 100%;
                height: 30px;
            }

            fieldset textarea{
                width: 100%;
                height: 80px;
            }

            article.content{
                width: 680px;
                margin: 2px auto;
                background: #f5deb3;
                padding: 12px;
                border: 1px solid #333;
                border-radius: 4px;
                box-shadow: 2px 3px #a9a9a9;
            }

            article.content h1{
                text-align: center;
                font-size: 100%;
                border-bottom: 1px solid #333;
            }

            article.content p.date{
                text-align: right;
                margin: 4px;
            }

            pre.debug{

            }

            /**/
            nav{
                flex-direction: column;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                padding: 0px;
                margin: 12px;
                border: 1px solid #A8BFA3;
                background: #FFF;
                border-radius: 4px;
                box-shadow: 2px 2px 3px #778899;
                overflow: hidden;
            }

            nav ul li{
                list-style: none;
                float: left;
                padding: 4px 12px 10px 12px;
            }
        </style>
    </head>
    <body>
        <section id="container">
            <h1>{DATA.TITLE}</h1>
            <?php echo call_html_template("header"); ?>
            <?php echo call_html_template("form"); ?>
            <article class="content">
                <h1><?php echo $article[0]["title"]; ?></h1>
                <p style="text-align: right;"><?php echo $article[0]["datetime"]; ?></p>
                <?php echo $article[0]["text"]; ?>
                <address></address>
            </article>
        </section>
    </body>
</html>
