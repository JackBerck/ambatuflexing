<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="DevFlex Is an social media"/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet"/>
    <link rel="icon" href="/images/favicon.png" type="image/x-icon"/>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="/css/style.css">
    <title><?= $model['title'] ?? 'Welcome' ?> | DevFlex</title>
</head>
<body class="bg-light-base text-dark-base font-poppins">
<?php
include_once __DIR__ . "/Components/utils.php";

use JackBerck\Ambatuflexing\App\Flasher;

Flasher::run();
?>

<?php
include_once __DIR__ . "/Components/navbar.php";
?>
