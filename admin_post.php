<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title id="title_room_page">Quản Lý Phòng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/CSS.css">
    <style type="text/css">
        button {
            margin: 0px 10px;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
        }

        .search-box input {
            margin-right: 10px;
        }

        .title-container {
            flex-grow: 1;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
                margin-top: 10px;
            }

            .search-box input {
                flex-grow: 1;
            }
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="search-container">
        <div class="title-container">
            <h2 style="color:green; margin: 0;">Danh sách yêu cầu đăng tin</h2>
        </div>


        <div class="search-box">
            <form method="get" action="" class="form-inline">
                <input type="text" class="form-control" name="search" placeholder="Nhập ID hoặc tiêu đề"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Tìm</button>
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <a href="?" class="btn btn-default" style="margin-left: 5px;">Xóa</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Container chứa tiêu đề và ô tìm kiếm -->


            <div style="height: 20px"></div>

            <?php
            $username = $_SESSION['user_name'];
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $sql1 = 'SELECT * FROM phong_tro WHERE user_name = ?';
            $params = array($username);
            $types = 's';

            if (!empty($search_term)) {
                if (is_numeric($search_term)) {
                    // Tìm theo ID nếu search_term là số
                    $sql1 .= ' AND IDPhongTro = ?';
                    $params[] = (int) $search_term;
                    $types .= 'i';
                } else {
                    // Tìm theo tiêu đề nếu search_term là chuỗi
                    $sql1 .= ' AND TieuDe LIKE ?';
                    $params[] = '%' . $search_term . '%';
                    $types .= 's';
                }
            }

            $sql1 .= ' ORDER BY ThoiGianDang DESC';

            $stmt1 = $conn->prepare($sql1);

            if ($stmt1) {
                // Bind parameters động
                $stmt1->bind_param($types, ...$params);
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

                                <?php
                                echo "<div class='status'>";
                                if ($row['status'] == 'pending') {
                                    echo "<span class='label label-warning'>Đang chờ duyệt</span>";
                                } elseif ($row['status'] == 'approved') {
                                    echo "<span class='label label-success'>Đã duyệt</span>";
                                } elseif ($row['status'] == 'rejected') {
                                    echo "<span class='label label-danger'>Bị từ chối</span>";
                                }
                                echo "</div>";
                                ?>


                            </div>
                        </div>
                        <?php



                    }
                } else {
                    echo "<div class='alert alert-info'>Không tìm thấy phòng trọ nào";
                    if (!empty($search_term)) {
                        echo " phù hợp với từ khóa '<strong>" . htmlspecialchars($search_term) . "</strong>'";
                    }
                    echo ".</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Lỗi chuẩn bị truy vấn: " . $conn->error . "</div>";
            }
            ?>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>