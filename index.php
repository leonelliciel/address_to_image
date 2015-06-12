<?php
include 'Class/GoogleMapClass.php';
/*
if(isset($_GET['address'])) {
    $address = $_GET['address'];
}else{
    die ('Adress error !!!');
}
*/

include('parameters.php');

$map = New GoogleMap($googleAPIKey,$imageFolder);

/*
$map->getGeoInfoFromAddress($address);
echo '<h2>4 Maps avec options : roadmap, satellite, terrain, hybrid </h2>';
$map->setMaptype('roadukygyugmap');
$map->addressMapImage($address);
echo $map->displayMap($map->getMaptype());

$map->setMaptype('satellite');
$map->addressMapImage($address);
echo $map->displayMap($map->getMaptype());

$map->setMaptype('terrain');
$map->addressMapImage($address);
echo $map->displayMap($map->getMaptype());

$map->setMaptype('hybrid');
$map->addressMapImage($address);
echo $map->displayMap($map->getMaptype());

echo '<h2>StreetView defaut</h2>';
$map->addressStreetviewImage($address);
echo $map->displayStreetview();

echo '<h2>StreetView 360</h2>';
$map->addressStreetviewImage360($address);
echo $map->displayStreetview360();
*/
?>

<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-size: 100% auto; background-color: lightgrey;
        }
        .carousel-control.right, .carousel-control.left {
            background-image:none;
        }
        footer {
            margin: 50px 0 0 0;
            border-top: 1px solid forestgreen;
            padding: 20px 10px;
            background-color: lightgray;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand text-uppercase" >LICIEL <span class="label label-success text-capitalize">ADDR2STREETMAP</span></span>
        </div>
    </div>
</nav>

<div class="container" style="background: #FFFFFF; padding-top: 100px;">

    <div class="row text-center">
        <h1 >Address to Google Map & StreetView Image</h1>
    </div>

    <?php
    if(!isset($_GET['address'])) {
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">Convertisseur d'adresse en image Google Map et StreetView</div>
                    <div class="panel-body">

                        <form class="form-horizontal" method="get">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="address">Address</label>
                                    <div class="col-md-8">
                                        <input id="address" name="address" type="text" placeholder="Addresse" class="form-control input-md" required="">
                                        <span class="help-block">Saisir l'adresse</span>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="send">Envoyer</label>
                                    <div class="col-md-4">
                                        <button id="send" name="send" class="btn btn-success">Envoyer</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }else {

    $address = $_GET['address'];
    $map->getGeoInfoFromAddress($address);
    $map->addressStreetviewImage($address);
    ?>
            <style>
                body {
                    background: url("<?php echo $map->getStreetviewImagePath() ?>") no-repeat center center fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
                    filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $map->getStreetviewImagePath() ?>', sizingMethod='scale');
                    -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $map->getStreetviewImagePath() ?>', sizingMethod='scale')";' .
                '
                }
            </style>
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="exampleInputName2">Adresse</label>
                            <input id="address" name="address" type="text" placeholder="Adresse" class="form-control input-md" required="true">
                        </div>

                        <button id="send" name="send" class="btn btn-success">Nouvelle recherche</button>
                    </form>
                </div>
            </div>
            <?php

            $map->setMaptype('roadmap');
            $map->addressMapImage($address);

            $map->setMaptype('satellite');
            $map->addressMapImage($address);

            $map->setMaptype('terrain');
            $map->addressMapImage($address);

            $map->setMaptype('hybrid');
            $map->addressMapImage($address);

            //echo $map->displayStreetview();

            //echo '<h2>StreetView 360</h2>';
            $map->addressStreetviewImage360($address);
            //echo $map->displayStreetview360();
            $tabimg = $map->getStreetview360Images();
            ?>
        <div class="row">
            <div class="col-md-7">
                <h2>Google Maps</h2>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="max-width: 600px;">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <a href="<?php echo $map->getMapImagePath('roadmap'); ?>" target="_blank">
                                <img src="<?php echo $map->getMapImagePath('roadmap'); ?>" alt="roadmap">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $map->getMapImagePath('satellite'); ?>" target="_blank">
                                <img src="<?php echo $map->getMapImagePath('satellite'); ?>" alt="satellite">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $map->getMapImagePath('hybrid'); ?>" target="_blank">
                                <img src="<?php echo $map->getMapImagePath('hybrid'); ?>" alt="hybrid">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $map->getMapImagePath('terrain'); ?>" target="_blank">
                                <img src="<?php echo $map->getMapImagePath('terrain'); ?>" alt="terrain">
                            </a>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-5">
                <h2>T&eacute;l&eacute;charger les maps :</h2>
                <div class="list-group">
                    <a class="list-group-item" href="<?php echo $map->getMapImagePath('roadmap'); ?>" target="_blank">RoadMap</a>
                    <a class="list-group-item" href="<?php echo $map->getMapImagePath('satellite'); ?>" target="_blank">Satellite</a>
                    <a class="list-group-item" href="<?php echo $map->getMapImagePath('hybrid'); ?>" target="_blank">Hybrid</a>
                    <a class="list-group-item" href="<?php echo $map->getMapImagePath('terrain'); ?>" target="_blank">Terrain</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <h2>Google Streetviews</h2>
                <div id="carousel-streetviews" class="carousel slide" data-ride="carousel" style="max-width: 600px;">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <a href="<?php echo $tabimg[0]; ?>" target="_blank">
                                <img src="<?php echo $tabimg[0]; ?>" alt="roadmap">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $tabimg[1]; ?>" target="_blank">
                                <img src="<?php echo $tabimg[1]; ?>" alt="satellite">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $tabimg[2]; ?>" target="_blank">
                                <img src="<?php echo $tabimg[2]; ?>" alt="hybrid">
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?php echo $tabimg[3]; ?>" target="_blank">
                                <img src="<?php echo $tabimg[3]; ?>" alt="terrain">
                            </a>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-streetviews" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-streetviews" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-5">
                <h2>T&eacute;l&eacute;charger les StreetViews :</h2>
                <div class="list-group">
                    <a class="list-group-item" href="<?php echo $tabimg[0]; ?>" target="_blank">0</a>
                    <a class="list-group-item" href="<?php echo $tabimg[1]; ?>" target="_blank">90</a>
                    <a class="list-group-item" href="<?php echo $tabimg[2]; ?>" target="_blank">180</a>
                    <a class="list-group-item" href="<?php echo $tabimg[3]; ?>" target="_blank">270</a>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    <div class="row">
        <footer>
            Copyright @ ADDRSTREETMAP
        </footer>
    </div>
</div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

</body>
</html>

carte_google_maps.php


