
<html lang="es" ng-app="puredemocracyapp">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

        <title>Pure Democracy</title>
        <!--<link rel="stylesheet" type="text/css" href="estilos.css">-->
        <!--<link rel="stylesheet" type="text/css" href="../media/css/style.css">-->

        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">-->
        <link rel="stylesheet" href="./css/bootstrap.min.css">

        <!-- Optional theme -->
        <!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="./css/bootstrap-theme.min.css">

        <!-- Importamos jasny bootstrap -->
        <link rel="stylesheet" href="./css/jasny-bootstrap.min.css">

        <!-- Estilos del pushmenu -->
        <link rel="stylesheet" href="./css/navmenu-push.css">

        <!-- Estilos propios -->
        <link rel="stylesheet" type="text/css" href="./css/app.css">

    </head>
    <body>

        <!-- Cargamos el api de facebook -->
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId: '605582532896240',
                    xfbml: true,
                    version: 'v2.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/es_LA/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>


<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
        <script src="./../dev_components/jquery-1.11.0.min.js"></script>

        <!-- Angular CDN -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.1/angular.min.js "></script>-->
        <script src="./../dev_components/angular.min.js "></script>