<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        if (typeof jQuery == 'undefined') {
            document.write('<script src="/path/to/local/jquery.js"><\/script>');
        }
    </script>



    <meta charset="UTF-8">
    <title>AHA TRỌ /
        <?php
        $id = intval($_GET['id'] ?? 0);
        $rs = mysqli_query($conn, "SELECT loaiPhong FROM loai_phong WHERE idLoaiPhong = $id");
        echo $rs && mysqli_num_rows($rs) ? mb_strtoupper(mysqli_fetch_assoc($rs)['loaiPhong'], 'UTF-8') : '';
        ?>
    </title>
    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'banner.php'; ?>


    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="room_main_content">
            <?php
            include 'room_proc.php';
            ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <?php
            include 'filter.php';
            ?>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>
</body>

</html>