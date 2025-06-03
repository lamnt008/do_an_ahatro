<?php

// --- NHẬN THAM SỐ GET ---
$tinh = isset($_GET['tinh']) ? mysqli_real_escape_string($conn, $_GET['tinh']) : '';
$quan = isset($_GET['quan']) ? mysqli_real_escape_string($conn, $_GET['quan']) : '';
$idLoaiPhong = isset($_GET['id']) ? intval($_GET['id']) : 0;
$gia = $_GET['gia'] ?? '';
$dientich = $_GET['dientich'] ?? '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$where = [];
if (!empty($tinh))
    $where[] = "pt.TinhThanh = '$tinh'";
if (!empty($quan))
    $where[] = "pt.QuanHuyen = '$quan'";
if ($idLoaiPhong > 0)
    $where[] = "pt.idLoaiPhong = $idLoaiPhong";

// Lọc giá
switch ($gia) {
    case 'duoi-1-trieu':
        $where[] = "pt.GiaChoThue < 1000000";
        break;
    case '1-2-trieu':
        $where[] = "pt.GiaChoThue >= 1000000 AND pt.GiaChoThue <= 2000000";
        break;
    case '2-3-trieu':
        $where[] = "pt.GiaChoThue >= 2000000 AND pt.GiaChoThue <= 3000000";
        break;
    case '3-5-trieu':
        $where[] = "pt.GiaChoThue >= 3000000 AND pt.GiaChoThue <= 5000000";
        break;
    case '5-7-trieu':
        $where[] = "pt.GiaChoThue >= 5000000 AND pt.GiaChoThue <= 7000000";
        break;
    case '7-10-trieu':
        $where[] = "pt.GiaChoThue >= 7000000 AND pt.GiaChoThue <= 10000000";
        break;
    case '10-15-trieu':
        $where[] = "pt.GiaChoThue >= 10000000 AND pt.GiaChoThue <= 15000000";
        break;
    case 'tren-15-trieu':
        $where[] = "pt.GiaChoThue > 15000000";
        break;
}

// Lọc diện tích
switch ($dientich) {
    case 'duoi-20':
        $where[] = "pt.DienTich < 20";
        break;
    case '20-30':
        $where[] = "pt.DienTich >= 20 AND pt.DienTich <= 30";
        break;
    case '30-50':
        $where[] = "pt.DienTich >= 30 AND pt.DienTich <= 50";
        break;
    case '50-70':
        $where[] = "pt.DienTich >= 50 AND pt.DienTich <= 70";
        break;
    case '70-90':
        $where[] = "pt.DienTich >= 70 AND pt.DienTich <= 90";
        break;
    case 'tren-90':
        $where[] = "pt.DienTich > 90";
        break;
}

$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$limit = 12;
$offset = ($page - 1) * $limit;

$count_sql = "SELECT COUNT(*) as total FROM phong_tro pt $where_sql";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $count_sql))['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT pt.IDPhongTro, pt.QuanHuyen, pt.TinhThanh, pt.TieuDe, pt.GiaChoThue, pt.DienTich,
        pt.user_name, pt.ThoiGianDang, lp.loaiPhong
        FROM phong_tro pt
        JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
        $where_sql
        ORDER BY pt.ThoiGianDang DESC
        LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);






if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        include 'room.php';
    }
} else {

    echo
        "Không có phòng trọ nào phù hợp với điều kiện lọc.";

}







// Phân trang
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





<script>
    $(document).ready(function () {
        $('.save-post-btn').on('click', function () {
            var postId = $(this).data('post-id');
            var button = $(this);

            $.ajax({
                url: 'save_post.php', // File PHP xử lý lưu/bỏ lưu
                type: 'POST',
                data: {
                    post_id: postId
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
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