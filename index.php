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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script> -->
</head>

<body>
    <?php
    include "header.php";
    include "banner.php";
    ?>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="room_main_content">
            <!-- <div class="col-lg-8 col-12" id="room_main_content"> -->

            <?php
            include('room_proc.php');
            ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <!-- <div class="col-lg-4 col-12"> -->

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