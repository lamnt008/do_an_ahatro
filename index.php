<?php
session_start();
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AHA TRỌ</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    include "header.php";
    include "banner.php";
    ?>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-sx-12" id="room_main_content">
            <?php
            include('room_proc.php');
            ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <?php
            include 'filter.php';
            ?>
        </div>
    </div>

    <?php
    include "footer.php";
    ?>

</body>

</html>