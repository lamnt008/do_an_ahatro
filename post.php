<?php
include "config.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {

	header("Location: login.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Đăng tin</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		h4 {
			color: green;
		}

		input,
		select,
		textarea {
			background-color: #f8f8f8;
			border: solid #32cc66 1px;
			padding: 5px 0px;
			width: 100%;
		}

		input {
			padding: 5px 10px;
		}

		.notes {
			background-color: #dff0d8;
			padding: 5px 10px;
		}

		h4 span {
			color: red;
		}

		.view_images {
			height: 200px;
		}
	</style>

</head>

<body>

	<?php
	include('header.php');
	?>

	<div class="container">
		<div class="row">

			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
				<div class="row">

					<form method="POST" action="post_proc.php" onsubmit="return validateForm()"
						enctype="multipart/form-data">

						<div class="col-xs-12">
							<h3>Các thông tin cơ bản</h3>
						</div>

						<div class="col-xs-12">
							<div class="col-xs-12">
								<h4>Tiêu đề tin <span>* </span> <span class="error_input" id="error_input_title"></span>
								</h4>
							</div>
							<div class="col-xs-12">
								<input id="input_title" type="text" name="tieuDe" maxlength="80" style="width: 100%"
									placeholder="Hãy đặt tiêu đề đầy đủ ý nghĩa, khách sẽ quan tâm hơn" title="Tiêu đề">
							</div>
						</div>

						<div class="col-xs-12">
							<h4 class="col-xs-12">Chọn loại phòng <span>*</span> <span class="error_input"
									id="error_input_kind_of_room"></span></h4>

							<?php
							$sql = "SELECT idLoaiPhong, loaiPhong FROM loai_phong";
							$result = mysqli_query($conn, $sql);
							while ($row = mysqli_fetch_assoc($result)) {
								?>
								<div class="col-sm-6 col-xs-12">
									<input name="idLoaiPhong" type="radio" value="<?php echo $row['idLoaiPhong']; ?>"
										style="width: 13px;" />
									<?php echo $row['loaiPhong']; ?>
								</div>
								<?php
							}
							?>

						</div>

						<div class="col-xs-12">
							<h4 class="col-xs-12">Kiểu vệ sinh <span>*</span> <span class="error_input"
									id="error_input_kind_of_toilet"></span></h4>
							<div class="col-sm-6 col-xs-12">
								<input name="VeSinh" type="radio" value="Khép kín" style="width: 13px;" /> Khép kín
							</div>
							<div class="col-sm-6 col-xs-12">
								<input name="VeSinh" type="radio" value="Không khép kín" style="width: 13px;" /> Không
								khép kín
							</div>
						</div>

						<div class="col-xs-6">
							<div class="col-xs-12">
								<h4>Giá cho thuê <span>*</span> <span class="error_input"
										id="error_input_room_price"></span></h4>
							</div>
							<div class="col-xs-12">
								<input id="gia_hienthi" type="text" placeholder="Giá cho thuê (VNĐ)"
									title="Giá thuê phòng">
								<input id="input_room_price" type="hidden" name="gia">

							</div>
						</div>

						<div class="col-xs-6">
							<div class="col-xs-12">
								<h4>Diện tích <span>*</span> <span class="error_input"
										id="error_input_room_area"></span></h4>
							</div>
							<div class="col-xs-12">
								<input id="input_room_area" type="number" name="dienTich" min="0"
									placeholder="Diện tích(đơn vị m²)" title="Diện tích căn phòng">
							</div>
						</div>

						<div class="col-xs-6">
							<div class="col-xs-12">
								<h4>Giá sử dụng điện</h4>
							</div>
							<div class="col-xs-12">
								<input type="text" name="dien" min="0" placeholder="Ghi rõ VNĐ/số hay VNĐ/tháng"
									title="Giá điện">
							</div>
						</div>

						<div class="col-xs-6">
							<div class="col-xs-12">
								<h4>Giá sử dụng nước</h4>
							</div>
							<div class="col-xs-12">
								<input type="text" name="nuoc" min="0" placeholder="Ghi rõ VNĐ/số hay VNĐ/tháng"
									title="Giá nước">
							</div>
						</div>

						<div class="col-xs-6">
							<div class="col-xs-12">
								<h4>Đối tượng cho thuê</h4>
							</div>
							<div class="col-xs-12">
								<select name="doiTuong">
									<option value="Tất cả">Tất cả</option>
									<option value="Sinh viên">Sinh viên</option>
									<option value="Người đi làm">Người đi làm</option>
									<option value="Gia đình">Gia đình</option>
								</select>
							</div>
						</div>

						<div class="col-xs-12">
							<div class="col-xs-12">
								<h4>Các tiện ích</h4>
							</div>
							<div class="col-xs-12">
								<input type="text" name="tienIch" maxlength="80" style="width: 100%"
									placeholder="VD: có bình nóng lạnh, điều hòa, chỗ để xe,..." title="Tiện ích">
							</div>
						</div>

						<div class="col-xs-12">
							<h3>Địa chỉ</h3>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Tỉnh thành <span>*</span>
								</h4>
							</div>
							<div class="col-xs-12">
								<select id="tinh" name="tinhThanh">
									<option value="">Chọn tỉnh thành</option>
								</select>
								<input type="hidden" name="tinhThanh" id="tenTinhHidden">
							</div>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Quận/ huyện <span>*</span>
								</h4>
							</div>
							<div class="col-xs-12">
								<select id="quan" name="quanHuyen">
									<option value="">Chọn quận/ huyện</option>
								</select>
							</div>
						</div>

						<div class="col-xs-12">
							<div class="col-xs-12">
								<h4>Địa chỉ chính xác <span>*</span> </h4>
							</div>
							<div class="col-xs-12">
								<input type="text" name="diaChi"
									placeholder="Hãy nhập địa chỉ chính xác để khách dễ tìm hơn"
									id="input_exacly_address" title="Địa chỉ">
							</div>
						</div>

						<div class="col-xs-12">
							<h3>Thông tin liên lạc</h3>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Tên chủ trọ</h4>
							</div>
							<div class="col-xs-12">
								<input type="text" name="chuTro" maxlength="40" title="Tên chủ trọ">
							</div>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Số điện thoại <span>*</span> <span class="error_input"
										id="error_input_phone_number"></span></h4>
							</div>
							<div class="col-xs-12">
								<input id="input_phone_number" type="text" name="sdt" pattern="[0-9]*"
									title="Số điện thoại">
							</div>
						</div>


						<div class="col-xs-12">
							<h3>Thông tin mô tả</h3>
						</div>

						<div class="col-xs-12">
							<div class="col-xs-12">
								<h4>Mô tả <span>*</span> <span class="error_input" id="error_input_describle"></span>
								</h4>
							</div>
							<div class="col-xs-12">
								<p class="notes">Giới thiệu mô tả về tin đăng của bạn. Ví dụ: Khu nhà có vị trí thuận
									lợi: Gần công viên, gần trường học ... Tổng diện tích 52m2, đường đi ô tô vào tận
									cửa</p>
							</div>
							<div class="col-xs-12">
								<textarea id="input_room_describle" rows="5" title="Mô tả căn phòng"
									name="moTa"></textarea>
							</div>
						</div>

						<div class="col-xs-12">
							<div class="col-xs-12">
								<h4>Hình ảnh <span>*</span> <span class="error_input" id="error_input_image"></span>
								</h4>
							</div>
							<div class="col-xs-12">
								<p class="notes">Tin đăng có hình ảnh rõ ràng sẽ được xem và gọi gấp nhiều lần so với
									tin rao không có ảnh. Hãy đăng ảnh để được giao dịch nhanh chóng!</p>
							</div>
							<div class="col-xs-12">
								<input type="file" id="upload_images" style="border: none;" name="fileToUpload[]"
									onchange="previewImages()" multiple="multiple">
							</div>
							<div class="col-sm-4  col-xs-12">
								<div class="preview_images  col-xs-12" id="preview_images"
									style="margin: 15px 0px; padding: 0px;"></div>
							</div>
						</div>













						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Loại tin đăng <span>*</span></h4>
							</div>
							<div class="col-xs-12">
								<select name="loaiTin" id="loaiTin" onchange="updatePrice()">
									<option value="thuong">Tin thường (Miễn phí)</option>
									<option value="vip">Tin VIP (Ưu tiên hiển thị)</option>
								</select>
							</div>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="col-xs-12">
								<h4>Số ngày hiển thị <span>*</span></h4>
							</div>
							<div class="col-xs-12">
								<select name="ngayHienThi" id="ngayHienThi">
									<option value="3">3 ngày</option>
									<option value="5">5 ngày</option>
									<option value="7" selected>7 ngày</option>
									<option value="15">15 ngày</option>
								</select>
							</div>
						</div>

						<!-- <div class="col-xs-12" id="vipPriceInfo"
							style="display: none; background-color: #fff8e1; padding: 10px; margin-bottom: 15px;">
							<h4>Thông tin tin VIP</h4>
							<p>Tin VIP sẽ được ưu tiên hiển thị trước và nổi bật hơn. Phí đăng tin VIP: 5.000 VNĐ/ngày.

						</div> -->














						<div class="col-xs-12" style="width: 100%; margin-top: 20px; text-align: center;">
							<button id="upload_room_button" type="submit" class="btn btn-success" name="DangTin"
								style="background-color: rgb(175, 0, 0); border-color: red;">Đăng tin</button>
						</div>

					</form>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<h3>Hướng dẫn đăng tin</h3>
				<b>
					<p>
						- Thông tin có dấu <span style="color: red;">*</span> là bắt buộc.
					</p>
					<p>
						- Các thông tin phải viết bằng Tiếng Việt có dấu, đúng chính tả, không viết tắt.
					</p>
					<p>
						- Phần tiêu đề phải ít hơn 80 kí tự
					</p>
					<p>- Các bạn nên điền đầy đủ thông tin vào các mục để tin đăng có hiệu quả hơn! Giữ phím Ctrl để
						đăng nhiều ảnh cùng lúc.</p>
					<p>- Tin đăng có hình ảnh rõ ràng sẽ được xem và gọi gấp nhiều lần so với tin rao không có ảnh. Hãy
						đăng ảnh để được giao dịch nhanh chóng!</p>
				</b>
			</div>

		</div>
	</div>


	<?php
	include('footer.php');
	?>

	<script type="text/javascript" src="JSDangTin.js"></script>
	<script>
		const initialTinhName = "";
		const initialQuanName = ""; 
	</script>
	<script type="text/javascript" src="address.js"></script>
	<script>
		const giaHienThi = document.getElementById('gia_hienthi');
		const giaGoc = document.getElementById('input_room_price');

		giaHienThi.addEventListener('input', function () {
			let rawValue = this.value.replace(/\D/g, '');
			giaGoc.value = rawValue;
			this.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		});
	</script>


	<script>
		function updatePrice() {
			const loaiTin = document.getElementById('loaiTin').value;
			const ngayHienThi = document.getElementById('ngayHienThi').value;
			const vipPriceInfo = document.getElementById('vipPriceInfo');
			const totalPriceElement = document.getElementById('totalPrice');

			if (loaiTin === 'vip') {
				vipPriceInfo.style.display = 'block';
				const totalPrice = 50000 * parseInt(ngayHienThi);
				totalPriceElement.textContent = `Tổng phí: ${totalPrice.toLocaleString('vi-VN')} VNĐ (${ngayHienThi} ngày)`;
			} else {
				vipPriceInfo.style.display = 'none';
			}
		}

		// Cập nhật khi thay đổi số ngày
		document.getElementById('ngayHienThi').addEventListener('change', updatePrice);
	</script>
</body>

</html>