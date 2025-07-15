<div class="room-container" style="width: 50vw; margin: 10px auto;">
    <?php
    $tinh = isset($_GET['tinh']) ? mysqli_real_escape_string($conn, $_GET['tinh']) : '';
    $quan = isset($_GET['quan']) ? mysqli_real_escape_string($conn, $_GET['quan']) : '';
    $idLoaiPhong = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $gia = $_GET['gia'] ?? '';
    $dienTich = $_GET['dienTich'] ?? '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Thêm điều kiện kiểm tra thời gian hiển thị
    $currentDate = date('Y-m-d H:i:s');
    $where = [
        "pt.trangThai = 'duyet'", // Đổi từ 'duyet' sang 'da_duyet' cho nhất quán
        "DATE_ADD(pt.ngayDuyet, INTERVAL pt.ngayHienThi DAY) >= '$currentDate'"
    ];

    if (!empty($tinh))
        $where[] = "pt.tinhThanh = '$tinh'";
    if (!empty($quan))
        $where[] = "pt.quanHuyen = '$quan'";
    if ($idLoaiPhong > 0)
        $where[] = "pt.idLoaiPhong = $idLoaiPhong";

    switch ($gia) {
        case 'duoi-1-trieu':
            $where[] = "pt.giaThue < 1000000";
            break;
        case '1-2-trieu':
            $where[] = "pt.giaThue >= 1000000 AND pt.giaThue <= 2000000";
            break;
        case '2-3-trieu':
            $where[] = "pt.giaThue >= 2000000 AND pt.giaThue <= 3000000";
            break;
        case '3-5-trieu':
            $where[] = "pt.giaThue >= 3000000 AND pt.giaThue <= 5000000";
            break;
        case '5-7-trieu':
            $where[] = "pt.giaThue >= 5000000 AND pt.giaThue <= 7000000";
            break;
        case '7-10-trieu':
            $where[] = "pt.giaThue >= 7000000 AND pt.giaThue <= 10000000";
            break;
        case '10-15-trieu':
            $where[] = "pt.giaThue >= 10000000 AND pt.giaThue <= 15000000";
            break;
        case 'tren-15-trieu':
            $where[] = "pt.giaThue > 15000000";
            break;
    }

    switch ($dienTich) {
        case 'duoi-20':
            $where[] = "pt.dienTich < 20";
            break;
        case '20-30':
            $where[] = "pt.dienTich >= 20 AND pt.dienTich <= 30";
            break;
        case '30-50':
            $where[] = "pt.dienTich >= 30 AND pt.dienTich <= 50";
            break;
        case '50-70':
            $where[] = "pt.dienTich >= 50 AND pt.dienTich <= 70";
            break;
        case '70-90':
            $where[] = "pt.dienTich >= 70 AND pt.dienTich <= 90";
            break;
        case 'tren-90':
            $where[] = "pt.dienTich > 90";
            break;
    }

    $where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $limit = 12;
    $offset = ($page - 1) * $limit;

    $count_sql = "SELECT COUNT(*) as total FROM phong_tro pt $where_sql";
    $total_rows = mysqli_fetch_assoc(mysqli_query($conn, $count_sql))['total'];
    $total_pages = ceil($total_rows / $limit);

    // Cập nhật query để lấy thêm loại tin và sắp xếp ưu tiên tin VIP
    $sql = "SELECT pt.id, pt.quanHuyen, pt.tinhThanh, pt.tieuDe, pt.giaThue, pt.dienTich,
            pt.userID, pt.thoiGianDang, pt.loaiTin, pt.ngayDuyet, pt.ngayHienThi,
            lp.loaiPhong, u.username
            FROM phong_tro pt
            JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
            JOIN users u ON pt.userID = u.id
            $where_sql
            ORDER BY pt.loaiTin DESC, pt.ngayDuyet DESC
            LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Thêm class vip-room nếu là tin VIP
            echo '<div class="room-item' . ($row['loaiTin'] == 'vip' ? ' vip-room' : '') . '">';
            include 'room.php';
            echo '</div>';
        }
    } else {
        echo "Không có phòng trọ nào phù hợp với điều kiện lọc.";
    }

    // Phan trang
    $query_base = $_GET;
    echo '<div class="col-xs-12"><ul class="pagination pull-right">';
    if ($page > 1) {
        $query_base['page'] = $page - 1;
        echo '<li><a class="btn btn-default" href="?' . http_build_query($query_base) . '"><</a></li>';
    } else
        echo '<li class="disabled"><span><</span></li>';

    for ($i = 1; $i <= $total_pages; $i++) {
        $query_base['page'] = $i;
        if ($i == $page) {
            echo '<li class="active"><span>' . $i . '</span></li>';
        } else {
            echo '<li><a class="btn btn-default" href="?' . http_build_query($query_base) . '">' . $i . '</a></li>';
        }
    }

    if ($page < $total_pages) {
        $query_base['page'] = $page + 1;
        echo '<li><a class="btn btn-default" href="?' . http_build_query($query_base) . '">></a></li>';
    } else
        echo '<li class="disabled"><span>></span></li>';

    echo '</ul></div>';
    ?>
</div>

<style>
    .vip-room {
        border: 2px solid #ffc107;
        background-color: #fff8e1;
        position: relative;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 5px;
    }

    .vip-room::before {
        content: "VIP";
        background: #ffc107;
        color: #fff;
        padding: 3px 8px;
        position: absolute;
        top: 10px;
        right: 10px;
        font-weight: bold;
        border-radius: 3px;
        font-size: 12px;
    }

    .vip-room .room-title {
        color: #d35400;
        font-weight: bold;
    }

    .remaining-days {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }
</style>

<script>
    $(document).ready(function () {
        $('.save-post-btn').on('click', function () {
            var postId = $(this).data('post-id');
            var button = $(this);

            $.ajax({
                url: 'save_post.php',
                type: 'POST',
                data: {
                    post_id: postId
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.trangThai === 'success') {
                        if (data.action === 'saved') {
                            button.html('<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu');
                        } else if (data.action === 'unsaved') {
                            button.html('<i class="far fa-bookmark"></i> Lưu bài');
                        }
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Đã xảy ra lỗi khi thực hiện thao tác.');
                }
            });
        });
    });
</script>