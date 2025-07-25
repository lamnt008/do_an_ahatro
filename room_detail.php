<?php
include 'config.php';
include 'header.php';

if (isset($_GET['id'])) {
	$idPhong = intval($_GET['id']);

	$sql = "SELECT pt.*, lp.loaiPhong 
	        FROM phong_tro pt
	        JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
	       WHERE pt.id = $idPhong";
	if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'user') {
		$sql .= " AND trangThai = 'duyet'";
	}

	$result = mysqli_query($conn, $sql);
	if ($result->num_rows == 0) {
		echo "<div class='alert alert-warning' style='margin-top: 130px; margin-bottom: 180px; '>Tin này không tồn tại hoặc chưa được duyệt</div>";
		include('footer.php');
		exit();
	}

	if (mysqli_num_rows($result) > 0) {
		$phong = mysqli_fetch_assoc($result);
		$sql_img = "SELECT DuongDan FROM hinh_anh_phong_tro WHERE IDPhongTro = $idPhong";

		$result_img = mysqli_query($conn, $sql_img);
	} else {
		echo "Không tìm thấy phòng trọ.";
		exit;
	}
} else {
	echo "Thiếu ID phòng.";
	exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?php echo htmlspecialchars($phong['tieuDe']); ?></title>
	<style>
		body {
			font-family: "Segoe UI", sans-serif;
			background-color: #f0f2f5;
			margin: 0;
			padding: 0;
		}

		.detail-container {
			background: white;
			margin-top: 75px;
			padding-top: 30px;
			margin-left: auto;
			padding-left: 150px;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
			border-radius: 10px;
		}

		h1 {
			font-size: 26px;
			margin-bottom: 20px;
			color: green;
		}

		.images {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}

		.images img {
			height: 220px;
			width: auto;
			border-radius: 8px;
			object-fit: cover;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
		}

		.info-block {
			margin-top: 30px;
			line-height: 1.8;
		}

		.info-block h2 {
			font-size: 22px;
			border-left: green;
			padding-left: 10px;
			color: #222;
			margin-bottom: 15px;
		}

		.info-block p {
			font-size: 16px;
			margin: 5px 0;
		}

		.info-block strong {
			color: #555;
		}

		.contact-box {
			margin-top: 40px;
			background: #fff0f0;
			border-left: green;
			padding: 20px;
			border-radius: 8px;
		}

		.contact-box h3 {
			font-size: 20px;
			margin-bottom: 10px;
			color: green;
		}

		.contact-box p {
			font-size: 16px;
		}

		.contact-box a {
			color: green;
			text-decoration: none;
			font-weight: bold;
		}

		.contact-box a:hover {
			text-decoration: underline;
		}

		@media (max-width: 768px) {
			.images {
				flex-direction: column;
			}

			.images img {
				width: 100%;
				height: auto;
			}
		}
	</style>

</head>

<body>

	<div class="detail-container">
		<h1><?php echo htmlspecialchars($phong['tieuDe']); ?></h1>

		<div class="images">
			<?php while ($img = mysqli_fetch_assoc($result_img)) {
				echo '<img src="' . htmlspecialchars($img['DuongDan']) . '" alt="Hình ảnh">';
			} ?>
		</div>

		<div class="info-block">
			<h2>Thông tin chi tiết</h2>
			<p><strong>Mã tin: </strong><?php echo $phong['id']; ?></p>
			<p><strong>Loại phòng:</strong> <?php echo $phong['loaiPhong']; ?></p>
			<p><strong>Địa chỉ:</strong>
				<?php echo $phong['diaChi'] . ', ' . $phong['quanHuyen'] . ', ' . $phong['tinhThanh']; ?></p>
			<p><strong>Giá thuê:</strong> <?php echo number_format($phong['giaThue'], 0, ',', '.') . ' VNĐ'; ?></p>
			<p><strong>Diện tích:</strong> <?php echo $phong['dienTich']; ?> m²</p>
			<p><strong>Tiện ích:</strong> <?php echo nl2br(htmlspecialchars($phong['tienIch'])); ?></p>
			<p><strong>Vệ sinh:</strong> <?php echo $phong['veSinh']; ?></p>
			<p><strong>Đối tượng thuê:</strong> <?php echo $phong['doiTuong']; ?></p>
			<p><strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($phong['moTa'])); ?></p>
			<p><strong>Giá điện:</strong> <?php echo htmlspecialchars($phong['dien']); ?></p>
			<p><strong>Giá nước:</strong> <?php echo htmlspecialchars($phong['nuoc']); ?></p>
		</div>

		<div class="contact-box">
			<h3>Liên hệ chủ trọ</h3>
			<p><strong>Chủ trọ:</strong> <?php echo htmlspecialchars($phong['chuTro']); ?></p>
			<p><strong>Số điện thoại:</strong> <a
					href="tel:<?php echo $phong['sdt']; ?>"><?php echo $phong['sdt']; ?></a></p>
		</div>
	</div>

	<?php
	include 'footer.php';
	?>
</body>

</html>