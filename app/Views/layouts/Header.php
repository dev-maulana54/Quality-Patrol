<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title> <?= $title ?? 'Home' ?> | SFP</title>

    <!--General CSS Files -->
    <link rel="stylesheet" href="<?= base_url() ?>template/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="icon" href="<?= base_url() ?>assets/favicon.ico" type="image/gif">

    <link rel="stylesheet" href="<?= base_url() ?>template/node_modules/@fortawesome/fontawesome-free/css/all.css">
    <!--CSS Libraries -->
    <!-- Pada template, beberapa page membutuhkan library CSS yang spesifik, oleh karena itu perlu dinamis dalam load CSS -->
    <?php
    if (isset($cssLibrariesLocation)) {
        foreach ($cssLibrariesLocation as $value) {
    ?>
            <link rel="stylesheet" href="<?= base_url() . "template/" . $value ?>">
    <?php
        }
    }
    ?>

    <!-- JS Libraries -->
    <!-- Pada template, beberapa page membutuhkan library JS yang spesifik, oleh karena itu perlu dinamis dalam load JS -->
    <?php
    if (isset($jsLibrariesLocationHeader)) {
        foreach ($jsLibrariesLocationHeader as $value) {
    ?>
            <script src="<?= base_url() . "template/" . $value ?>"></script>
    <?php
        }
    }
    ?>
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/assets/css/components.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">

</head>

<body>


    <div class="background">
        <!-- Pada custom.js, membutuhkan base_url() untuk melakukan call melalui Ajax. Agar bisa mendapatkan informasi base_url sekarang maka dibutuhkan baris berikut -->
        <input type="hidden" class="baseUrl" value="<?= base_url() ?>">