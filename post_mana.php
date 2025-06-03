<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title id="title_room_page">Quản Lý Phòng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <script type="text/javascript" src="vendor/bootstrap.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/CSS.css">
    <style type="text/css">
        button {
            margin: 0px 10px;
        }
    </style>

</head>

<body>

    <?php
    include('header.php');
    ?>




    <!-- Phần thân để hiển thị filter và chi tiết căn phòng -->
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <!-- <div class="row"> -->
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12" style="padding: 0px;">
            <?php
            include 'config.php';
            $username = $_SESSION['user_name'];
            $sql = 'SELECT * FROM users WHERE username = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            ?>


        </div>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- <div class="col-xs-12">
                <h2 style="color: green;">Các căn phòng đã đăng</h2>
            </div> -->
            <h2 style="color:green">Các căn phòng đã đăng</h2>

            <div style="height: 50px"></div>



            <?php
            include 'config.php';
            $username = $_SESSION['user_name'];
            $sql1 = 'SELECT * FROM phong_tro WHERE phong_tro.user_name = ?';
            $stmt1 = $conn->prepare($sql1);

            if ($stmt1) {
                $stmt1->bind_param('s', $username);
                $stmt1->execute();
                $result1 = $stmt1->get_result();

                if ($result1 && $result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        $row = $row1; ?>








                        <div class="room-wrapper" style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div class="room-info" style="flex-grow: 1;">
                                <?php include "room.php"; ?>
                            </div>
                            <div class="room-manage" style="padding-left: 15px;">
                                <a href="post_mana_edit.php?id=<?php echo $row['IDPhongTro']; ?>" class="btn btn-primary"
                                    style="background-color: rgb(175, 0, 0);">
                                    Quản lý chi tiết
                                </a>
                            </div>
                        </div>








                        <?php
                    }
                } else {
                    echo "<div class='col-xs-12'>Không tìm thấy phòng trọ cho người dùng này.</div>";
                }

            } else {
                echo "<div class='col-xs-12'>Lỗi chuẩn bị truy vấn: " . $conn->error . "</div>";
            }

            ?>
        </div>
        <!-- </div> -->
    </div>

    <!-- Phần chân trang -->
    <?php
    include('footer.php');
    ?>

    <!-- Nhúng file javascript -->
    <script type="text/javascript" src="scripts/JavaScript.js"></script>

</body>

</html>