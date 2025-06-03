<?php
// Kết nối đến CSDL
include('config.php');

// Xử lý lọc theo tỉnh, quận, giá và diện tích (nếu có)
$where = [];






// Lọc theo tỉnh
if (!empty($_GET['tinh'])) {
	$tinh = mysqli_real_escape_string($conn, $_GET['tinh']);
	$where[] = "TinhThanh = '$tinh'";
}

// Lọc theo quận
if (!empty($_GET['quan'])) {
	$quan = mysqli_real_escape_string($conn, $_GET['quan']);
	$where[] = "QuanHuyen = '$quan'";
}

// Lọc theo giá
if (!empty($_GET['gia'])) {
	$gia = $_GET['gia'];
	switch ($gia) {
		case 'duoi-1-trieu':
			$where[] = "GiaChoThue < 1000000";
			break;
		case '1-2-trieu':
			$where[] = "GiaChoThue >= 1000000 AND GiaChoThue < 2000000";
			break;
		case '2-3-trieu':
			$where[] = "GiaChoThue >= 2000000 AND GiaChoThue < 3000000";
			break;
		case '3-5-trieu':
			$where[] = "GiaChoThue >= 3000000 AND GiaChoThue < 5000000";
			break;
		case '5-7-trieu':
			$where[] = "GiaChoThue >= 5000000 AND GiaChoThue < 7000000";
			break;
		case '7-10-trieu':
			$where[] = "GiaChoThue >= 7000000 AND GiaChoThue < 10000000";
			break;
		case '10-15-trieu':
			$where[] = "GiaChoThue >= 10000000 AND GiaChoThue < 15000000";
			break;
		case 'tren-15-trieu':
			$where[] = "GiaChoThue >= 15000000";
			break;
	}
}

// Lọc theo diện tích
if (!empty($_GET['dientich'])) {
	$dientich = $_GET['dientich'];
	switch ($dientich) {
		case 'duoi-20':
			$where[] = "DienTich < 20";
			break;
		case '20-30':
			$where[] = "DienTich >= 20 AND DienTich < 30";
			break;
		case '30-50':
			$where[] = "DienTich >= 30 AND DienTich < 50";
			break;
		case '50-70':
			$where[] = "DienTich >= 50 AND DienTich < 70";
			break;
		case '70-90':
			$where[] = "DienTich >= 70 AND DienTich < 90";
			break;
		case 'tren-90':
			$where[] = "DienTich >= 90";
			break;
	}
}

// Tạo câu lệnh SQL
$where_sql = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
$sql_select_all = "SELECT IDPhongTro FROM phong_tro $where_sql";

echo $sql_select_all . "<br>"; // In ra câu lệnh SQL để kiểm tra

$result_all = mysqli_query($conn, $sql_select_all);
$row_of_results = mysqli_num_rows($result_all); // Số lượng căn phòng đã đăng
$result_per_page = 12; // Số lượng bài đăng của một trang

$number_of_pages = ceil($row_of_results / $result_per_page); // Số trang hiển thị

// Kiểm tra nếu trang không có biến page thì là trang số 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Kết quả đầu tiên trả về của trang
$this_page_first_result = ($page - 1) * $result_per_page;

// Câu lệnh sql lấy về các tin ứng với trang
$sql_select_each_page = "SELECT pt.IDPhongTro, pt.QuanHuyen, pt.TinhThanh, pt.TieuDe, pt.GiaChoThue, pt.DienTich, pt.user_name, pt.ThoiGianDang, lp.loaiPhong 
                         FROM phong_tro pt
                         JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
                         $where_sql 
                         ORDER BY pt.ThoiGianDang DESC 
                         LIMIT $this_page_first_result, $result_per_page";

$result_each_page = mysqli_query($conn, $sql_select_each_page);

while ($row = mysqli_fetch_assoc($result_each_page)) {
	include 'room.php'; // Hiển thị thông tin phòng trọ
}

// Phân trang
$base_url = "index.php?" . http_build_query(array_merge($_GET, ['page' => '']));
$pre_page = $page; // Kiểm tra xem nút previous có thể click được không
$next_page = $page; // Kiểm tra xem nút next có thể click được không

echo '<div class="col-xs-12">
    <ul class="pagination pull-right">';
if ($page > 1) {
	$pre_page = $page - 1;
	echo '<li><a style="margin: 0px 3px;" type="button" class="btn btn-default" href="' . $base_url . $pre_page . '"><</a></li>';
} else {
	echo '<li class="disabled"> <span><</span> </li>';
}

for ($pos_page = 1; $pos_page <= $number_of_pages; $pos_page++) {
	if ($pos_page == $page) {
		echo '<li class="active"><span>' . $pos_page . '</span></li>';
	} else {
		echo '<li><a style="margin: 0px 3px;" type="button" class="btn btn-default" href="' . $base_url . $pos_page . '">' . $pos_page . '</a></li>';
	}
}

if ($page < $number_of_pages) {
	$next_page = $page + 1;
	echo '<li><a style="margin: 0px 3px;" type="button" class="btn btn-default" href="' . $base_url . $next_page . '">></a></li>';
} else {
	echo '<li class="disabled"> <span>></span> </li>';
}
echo '</ul>
    </div>';
?>