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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"> -->
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
            margin-top: 120px;
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

        .nav-tabs {
            margin-bottom: 20px;
        }

        .trangThai-label {
            margin-left: 10px;
            font-size: 0.9em;
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
            <h2 style="color:green; margin: 0;">Các căn phòng đã đăng</h2>
        </div>

        <div class="search-box">
            <form method="get" action="" class="form-inline">
                <input type="hidden" name="tab" value="<?php echo isset($_GET['tab']) ? $_GET['tab'] : 'all'; ?>">
                <input type="text" class="form-control" name="search" placeholder="Nhập ID hoặc tiêu đề"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Tìm</button>
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <a href="?tab=<?php echo isset($_GET['tab']) ? $_GET['tab'] : 'all'; ?>" class="btn btn-default"
                        style="margin-left: 5px;">Xóa</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

            <ul class="nav nav-tabs">
                <li class="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'all') ? 'active' : ''; ?>">
                    <a
                        href="?tab=all<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Tất
                        cả tin đăng</a>
                </li>
                <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'cho_duyet') ? 'active' : ''; ?>">
                    <a
                        href="?tab=cho_duyet<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Chờ
                        duyệt</a>
                </li>
                <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'duyet') ? 'active' : ''; ?>">
                    <a
                        href="?tab=duyet<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Đã
                        duyệt</a>
                </li>
                <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'tu_choi') ? 'active' : ''; ?>">
                    <a
                        href="?tab=tu_choi<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Bị
                        từ chối</a>
                </li>
            </ul>

            <?php

            $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

            // $sql1 = 'SELECT * FROM phong_tro WHERE userID = ?';
            $sql1 = 'SELECT pt.*, u.username 
         FROM phong_tro pt 
         JOIN users u ON pt.userID = u.id 
         WHERE pt.userID = ?';

            $params = array($userID);
            $types = 'i';

            switch ($current_tab) {
                case 'cho_duyet':
                    $sql1 .= ' AND trangThai = "cho_duyet"';
                    break;
                case 'duyet':
                    $sql1 .= ' AND trangThai = "duyet"';
                    break;
                case 'tu_choi':
                    $sql1 .= ' AND trangThai = "tu_choi"';
                    break;
            }

            if (!empty($search_term)) {
                if (is_numeric($search_term)) {
                    $sql1 .= ' AND pt.id = ?';
                    $params[] = (int) $search_term;
                    $types .= 'i';
                } else {
                    $sql1 .= ' AND tieuDe LIKE ?';
                    $params[] = '%' . $search_term . '%';
                    $types .= 's';
                }
            }

            $sql1 .= ' ORDER BY thoiGianDang DESC';

            $stmt1 = $conn->prepare($sql1);

            if ($stmt1) {
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
                                <a href="post_mana_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"
                                    style="background-color: rgb(175, 0, 0);">
                                    Quản lý chi tiết
                                </a>

                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info'>Không tìm thấy phòng trọ nào";
                    if (!empty($search_term)) {
                        echo " phù hợp với từ khóa '<strong>" . htmlspecialchars($search_term) . "</strong>'";
                    }
                    echo " trong mục <strong>";
                    switch ($current_tab) {
                        case 'cho_duyet':
                            echo "Chờ duyệt";
                            break;
                        case 'duyet':
                            echo "Đã duyệt";
                            break;
                        case 'tu_choi':
                            echo "Bị từ chối";
                            break;
                        default:
                            echo "Tất cả tin đăng";
                    }
                    echo "</strong>.</div>";
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