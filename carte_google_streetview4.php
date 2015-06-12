<?php
include 'Class/GoogleMapClass.php';
if(isset($_GET['adresse'])) {
    $address = $_GET['adresse'];
}else{
    die ('Adress error !!!');
}

include('parameters.php');

$map = New GoogleMap($googleAPIKey,$imageFolder);
$map->setHeading(270);

$map->getGeoInfoFromAddress($address);
$map->addressStreetviewImage($address);

$filename = $map->getStreetviewImagePath();

$file_extension = strtolower(substr(strrchr(basename($filename),"."),1));

switch( $file_extension ) {
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpeg"; break;
    default:
}

header('Content-type: ' . $ctype);

readfile($filename);

unlink($filename);