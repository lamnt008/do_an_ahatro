-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 15, 2025 lúc 03:39 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_ahatro`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinh_anh_phong_tro`
--

CREATE TABLE `hinh_anh_phong_tro` (
  `IDimage` int(11) NOT NULL,
  `IDPhongTro` int(11) NOT NULL,
  `DuongDan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hinh_anh_phong_tro`
--

INSERT INTO `hinh_anh_phong_tro` (`IDimage`, `IDPhongTro`, `DuongDan`) VALUES
(1, 2, 'uploads/1..jpg'),
(2, 2, 'uploads/1.1.jpg'),
(3, 2, 'uploads/1.2.jpg'),
(4, 3, 'uploads/2.1.jpg'),
(5, 3, 'uploads/2.2.jpg'),
(6, 3, 'uploads/2.3.jpg'),
(8, 3, 'uploads/2.5.jpg'),
(9, 4, 'uploads/3.1.jpg'),
(10, 4, 'uploads/3.2.jpg'),
(11, 4, 'uploads/3.3.jpg'),
(12, 4, 'uploads/3.4.jpg'),
(13, 5, 'uploads/4.1.jpg'),
(14, 5, 'uploads/4.2.jpg'),
(15, 5, 'uploads/4.3.jpg'),
(16, 5, 'uploads/4.4.jpg'),
(17, 6, 'uploads/5.1.jpg'),
(18, 6, 'uploads/5.2.jpg'),
(19, 6, 'uploads/5.3.jpg'),
(20, 6, 'uploads/5.4.jpg'),
(21, 6, 'uploads/5.5.jpg'),
(22, 7, 'uploads/6.1.jpg'),
(23, 7, 'uploads/6.2.jpg'),
(24, 7, 'uploads/6.3.jpg'),
(25, 7, 'uploads/6.4.jpg'),
(26, 7, 'uploads/6.5.jpg'),
(27, 8, 'uploads/7.2.jpg'),
(28, 8, 'uploads/7.3.jpg'),
(29, 8, 'uploads/7.4.jpg'),
(30, 10, 'uploads/8.1.jpg'),
(31, 10, 'uploads/8.2.jpg'),
(32, 10, 'uploads/8.3.jpg'),
(33, 10, 'uploads/8.4.jpg'),
(34, 11, 'uploads/9.1.jpg'),
(35, 11, 'uploads/9.2.jpg'),
(36, 11, 'uploads/9.3.jpg'),
(37, 12, 'uploads/10.1.jpg'),
(38, 12, 'uploads/10.2.jpg'),
(39, 12, 'uploads/10.3.jpg'),
(40, 13, 'uploads/11.1.jpg'),
(41, 13, 'uploads/11.2.jpg'),
(42, 13, 'uploads/11.3.jpg'),
(43, 13, 'uploads/11.4.jpg'),
(44, 13, 'uploads/11.5.jpg'),
(45, 14, 'uploads/12.1.jpg'),
(46, 14, 'uploads/12.2.jpg'),
(47, 14, 'uploads/12.3.jpg'),
(48, 15, 'uploads/13.1.jpg'),
(49, 15, 'uploads/13.2.jpg'),
(50, 15, 'uploads/13.3.jpg'),
(51, 15, 'uploads/13.4.jpg'),
(52, 48, 'uploads/14.1.jpg'),
(53, 48, 'uploads/14.2.jpg'),
(54, 48, 'uploads/14.3.jpg'),
(55, 48, 'uploads/14.4.jpg'),
(56, 49, 'uploads/15.1.jpg'),
(57, 49, 'uploads/15.2.jpg'),
(58, 49, 'uploads/15.3.jpg'),
(59, 49, 'uploads/15.4.jpg'),
(60, 49, 'uploads/15.5.jpg'),
(61, 50, 'uploads/16.1.jpg'),
(62, 50, 'uploads/16.2.jpg'),
(63, 50, 'uploads/16.3.jpg'),
(64, 52, 'uploads/17.1.jpg'),
(65, 52, 'uploads/17.2.jpg'),
(66, 52, 'uploads/17.3.jpg'),
(67, 53, 'uploads/18.1.jpg'),
(68, 53, 'uploads/18.2.jpg'),
(69, 54, 'uploads/9.1.jpg'),
(70, 54, 'uploads/9.2.jpg'),
(71, 54, 'uploads/9.3.jpg'),
(72, 55, 'uploads/10.1.jpg'),
(73, 56, 'uploads/10.2.jpg'),
(83, 80, 'uploads/13.1.jpg'),
(84, 80, 'uploads/13.2.jpg'),
(85, 80, 'uploads/13.3.jpg'),
(86, 80, 'uploads/13.4.jpg'),
(87, 81, 'uploads/1..jpg'),
(88, 81, 'uploads/1.1.jpg'),
(89, 81, 'uploads/1.2.jpg'),
(90, 82, 'uploads/2.1.jpg'),
(91, 82, 'uploads/2.2.jpg'),
(92, 82, 'uploads/2.3.jpg'),
(93, 83, 'uploads/3.1.jpg'),
(94, 83, 'uploads/3.2.jpg'),
(95, 83, 'uploads/3.3.jpg'),
(96, 83, 'uploads/3.4.jpg'),
(97, 100, 'uploads/4.1.jpg'),
(98, 100, 'uploads/4.2.jpg'),
(99, 100, 'uploads/4.3.jpg'),
(100, 102, 'uploads/5.1.jpg'),
(101, 102, 'uploads/5.2.jpg'),
(102, 102, 'uploads/5.3.jpg'),
(103, 102, 'uploads/5.4.jpg'),
(104, 103, 'uploads/6.1.jpg'),
(105, 103, 'uploads/6.2.jpg'),
(106, 103, 'uploads/6.3.jpg'),
(107, 104, 'uploads/7.1.jpg'),
(108, 104, 'uploads/7.2.jpg'),
(109, 104, 'uploads/7.3.jpg'),
(110, 105, 'uploads/8.1.jpg'),
(111, 105, 'uploads/8.2.jpg'),
(112, 105, 'uploads/8.3.jpg'),
(113, 105, 'uploads/8.4.jpg'),
(114, 106, 'uploads/9.1.jpg'),
(115, 106, 'uploads/9.2.jpg'),
(116, 106, 'uploads/9.3.jpg'),
(117, 107, 'uploads/10.1.jpg'),
(118, 107, 'uploads/11.2.jpg'),
(119, 107, 'uploads/11.3.jpg'),
(120, 107, 'uploads/11.4.jpg'),
(121, 108, 'uploads/9.1.jpg'),
(122, 108, 'uploads/9.2.jpg'),
(123, 108, 'uploads/9.3.jpg'),
(124, 109, 'uploads/10.1.jpg'),
(125, 109, 'uploads/10.2.jpg'),
(126, 109, 'uploads/10.3.jpg'),
(127, 110, 'uploads/11.1.jpg'),
(128, 110, 'uploads/11.2.jpg'),
(129, 110, 'uploads/11.3.jpg'),
(130, 110, 'uploads/11.4.jpg'),
(131, 110, 'uploads/11.5.jpg'),
(132, 111, 'uploads/12.1.jpg'),
(133, 111, 'uploads/12.2.jpg'),
(134, 111, 'uploads/12.3.jpg'),
(135, 112, 'uploads/13.1.jpg'),
(136, 112, 'uploads/13.2.jpg'),
(137, 112, 'uploads/13.3.jpg'),
(138, 112, 'uploads/13.4.jpg'),
(139, 113, 'uploads/9.1.jpg'),
(140, 113, 'uploads/9.2.jpg'),
(141, 113, 'uploads/9.3.jpg'),
(142, 114, 'uploads/10.1.jpg'),
(143, 114, 'uploads/10.2.jpg'),
(144, 114, 'uploads/10.3.jpg'),
(145, 115, 'uploads/1751291733_1..jpg'),
(150, 117, 'uploads/1751340257_1..jpg'),
(151, 117, 'uploads/1751340257_1.1.jpg'),
(152, 117, 'uploads/1751340257_1.2.jpg'),
(166, 62, 'uploads/1752031952_hinh-nen-cam-on_17.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_phong`
--

CREATE TABLE `loai_phong` (
  `idLoaiPhong` int(11) NOT NULL,
  `loaiPhong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_phong`
--

INSERT INTO `loai_phong` (`idLoaiPhong`, `loaiPhong`) VALUES
(1, 'Phòng trọ'),
(2, 'Nhà nguyên căn'),
(5, 'Căn hộ chung cư'),
(6, 'Chung cư mini'),
(7, 'Ở ghép');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_tro`
--

CREATE TABLE `phong_tro` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `idLoaiPhong` int(11) DEFAULT NULL,
  `tieuDe` varchar(50) DEFAULT NULL,
  `diaChi` varchar(50) DEFAULT NULL,
  `quanHuyen` varchar(50) DEFAULT NULL,
  `tinhThanh` varchar(50) DEFAULT NULL,
  `chuTro` varchar(50) DEFAULT NULL,
  `sdt` varchar(11) DEFAULT NULL,
  `giaThue` int(11) DEFAULT NULL,
  `dienTich` double DEFAULT NULL,
  `dien` varchar(30) DEFAULT NULL,
  `nuoc` varchar(30) DEFAULT NULL,
  `veSinh` varchar(50) DEFAULT NULL,
  `doiTuong` varchar(50) DEFAULT NULL,
  `tienIch` varchar(50) DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `thoiGianDang` datetime DEFAULT NULL,
  `trangThai` enum('cho_duyet','duyet','tu_choi') DEFAULT 'cho_duyet',
  `loaiTin` enum('thuong','vip') DEFAULT 'thuong',
  `ngayHienThi` int(11) DEFAULT 30,
  `ngayDuyet` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_tro`
--

INSERT INTO `phong_tro` (`id`, `userID`, `idLoaiPhong`, `tieuDe`, `diaChi`, `quanHuyen`, `tinhThanh`, `chuTro`, `sdt`, `giaThue`, `dienTich`, `dien`, `nuoc`, `veSinh`, `doiTuong`, `tienIch`, `moTa`, `thoiGianDang`, `trangThai`, `loaiTin`, `ngayHienThi`, `ngayDuyet`) VALUES
(2, 4, 2, 'Phòng trọ mới xây full nội thất', '45 Nguyễn Văn Linh', 'Sơn Trà', 'Đà Nẵng', 'Trần An Nhã', '0912345678', 4500000, 30, '4000 đồng/kW', '30000 đồng/ khối', 'Khép kín', 'Nhân viên văn phòng', '                        ', 'Phòng mới xây, view biển, an ninh tốt', '2025-04-07 10:15:00', 'duyet', 'thuong', 7, '2025-07-07 10:44:13'),
(3, 6, 1, 'Cho thuê phòng trọ giá rẻ', '78 Hoàng Diệu', 'Liên Chiểu', 'Đà Nẵng', 'Nguyễn Văn Lam', '0987654321', 2500000, 20, '3000 đồng/kW', '20000 đồng/ khối', 'Chung', 'Sinh viên', 'Wifi, nóng lạnh', 'Phòng tiện nghi cơ bản, gần trường học', '2025-07-07 10:39:13', 'duyet', 'thuong', 7, '2025-07-07 10:49:13'),
(4, 9, 1, 'Phòng trọ cho nữ gần chợ', '12 Trần Phú', 'Cẩm Lệ', 'Đà Nẵng', 'Lê Thị An Yên', '0978123456', 3000000, 22, '3500 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Nữ sinh viên', 'Wifi, nóng lạnh, camera an ninh', 'Khu vực yên tĩnh, an toàn cho nữ', '2025-07-07 10:39:53', 'duyet', 'thuong', 7, '2025-07-07 10:45:13'),
(5, 10, 2, 'Phòng trọ view thành phố', '56 Lê Duẩn', 'Hải Châu', 'Đà Nẵng', 'Nguyễn Thị Lâm', '0905123457', 5000000, 35, '4000 đồng/kW', '30000 đồng/ khối', 'Khép kín', 'Người đi làm', 'Wifi, điều hòa, tủ lạnh, máy giặt', 'View đẹp, không gian sang trọng', '2025-07-07 10:35:13', 'duyet', 'thuong', 7, '2025-07-07 10:44:23'),
(6, 4, 1, 'Phòng trọ mini giá rẻ', '89 Ngô Quyền', 'Gia Lâm', 'Hà Nội', 'Trần An Nhã', '0912345679', 2000000, 18, '3000 đồng/kW', '20000 đồng/ khối', 'Khép kín', 'Sinh viên', '                        ', 'Phòng nhỏ nhưng sạch sẽ, tiện nghi cơ bản', '2025-07-07 08:29:13', 'duyet', 'thuong', 7, '2025-07-08 08:44:13'),
(8, 9, 1, 'Phòng trọ cho thuê dài hạn', '23 Lê Đình Dương', 'Cẩm Lệ', 'Đà Nẵng', 'Lê Thị An Yên', '0978123457', 2800000, 21, '3500 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Sinh viên', 'Wifi, nóng lạnh', 'Phòng yên tĩnh, phù hợp học tập', '2025-07-07 08:35:13', 'duyet', 'thuong', 7, '2025-07-08 08:43:13'),
(10, 6, 1, 'Phòng trọ gần biển Mỹ Khê', '78 Trưng Nữ Vương', 'Sơn Trà', 'Đà Nẵng', 'Trần An Nhã', '0912345680', 3800000, 28, '4000 đồng/kW', '30000 đồng/ khối', 'Khép kín', 'Sinh viên, người đi làm', 'Wifi, điều hòa, nóng lạnh', 'Cách biển 5 phút đi bộ, view đẹp', '2025-07-07 07:49:13', 'duyet', 'thuong', 7, '2025-07-07 08:44:13'),
(11, 10, 1, 'Phòng trọ giá sinh viên', '45 Nguyễn Chí Thanh', 'Liên Chiểu', 'Đà Nẵng', 'Nguyễn Thanh Lam', '0987654323', 2200000, 19, '3000 đồng/kW', '20000 đồng/ khối', 'Khép kín', 'Sinh viên', 'Wifi', 'Phòng tiện nghi cơ bản, giá rẻ', '2025-07-08 10:19:13', 'duyet', 'thuong', 7, '2025-07-08 10:44:13'),
(12, 9, 1, 'Phòng trọ nữ an ninh tốt', '89 Lê Hồng Phong', 'Cẩm Lệ', 'Đà Nẵng', 'Lê Thị An Yên', '0978123458', 2900000, 22, '3500 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Nữ sinh viên', 'Wifi, nóng lạnh, camera', 'Khu vực an ninh, chỉ cho nữ thuê', '2025-07-08 10:27:13', 'duyet', 'thuong', 7, '2025-07-08 10:37:13'),
(13, 4, 2, 'Phòng trọ trung tâm thành phố', '12 Phan Chu Trinh', 'Hải Châu', 'Đà Nẵng', 'Nguyễn Thị Lâm', '0905123459', 4800000, 32, '4000 đồng/kW', '30000 đồng/ khối', 'Khép kín', 'Người đi làm', 'Wifi, điều hòa, tủ lạnh', 'Vị trí đắc địa, thuận tiện đi lại', '2025-07-08 10:49:13', 'duyet', 'thuong', 7, '2025-07-08 10:54:13'),
(14, 6, 1, 'Phòng trọ mới xây đẹp', '34 Lê Thanh Nghị', 'Sơn Trà', 'Đà Nẵng', 'Trần An Nhã', '0912345681', 3600000, 26, '3500 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Sinh viên, công nhân', 'Wifi, nóng lạnh', 'Phòng mới xây, sạch sẽ, thoáng mát', '2025-07-08 11:49:45', 'duyet', 'thuong', 7, '2025-07-08 12:12:13'),
(15, 10, 1, 'Phòng trọ gần trường ĐH', '56 Trần Hưng Đạo', 'Thanh Khê', 'Đà Nẵng', 'Nguyễn Văn Lam', '0987654324', 2400000, 20, '3000 đồng/kW', '20000 đồng/ số', 'Khép kín', 'Sinh viên', 'Wifi', 'Gần các trường ĐH, thuận tiện đi học', '2025-07-08 11:39:45', 'duyet', 'thuong', 7, '2025-07-08 12:15:45'),
(48, 4, 1, 'Phòng cho thuê Quận 5', '11 Trần Hưng Đạo', 'Quận 5', 'Hồ Chí Minh', 'Hoàng Văn Huy', '0967888999', 1800000, 20, '3000 đồng/kW', '23000 đồng/ khối', 'Khép kín', 'Sinh viên', 'Wifi, điều hòa, tủ lạnh', 'Phòng có cửa sổ thoáng.', '2025-07-09 08:15:10', 'duyet', 'thuong', 7, '2025-07-09 10:42:13'),
(49, 4, 2, 'Nhà nguyên căn Tây Hồ', '34 Lạc Long Quân', 'Tây Hồ', 'Hà Nội', 'Mai Thị Huyền', '0911223344', 5000000, 60, '4000 đồng/kW', '24000 đồng/ khối', 'Khép kín', 'Tất cả', 'Wifi, Máy lạnh, Thang máy', 'Khu dân cư cao cấp.', '2025-07-09 07:49:45', 'duyet', 'thuong', 7, '2025-07-09 10:44:13'),
(50, 9, 7, 'Tìm người ở ghép', '9 Lê Duẩn', 'Quận 1', 'Hồ Chí Minh', 'Bùi Như Nga', '0933444555', 4000000, 32, '4000 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Người đi làm', 'Wifi, Máy lạnh, Thang máy', 'Ngay chợ Bến Thành.', '2025-07-06 11:49:45', 'duyet', 'thuong', 7, '2025-07-07 13:44:13'),
(52, 4, 2, 'Nhà nguyên căn sân rộng', '23 Hoàng Văn Thụ', 'Tân Bình', 'Hồ Chí Minh', 'Lê Thu Minh', '0966778899', 5200000, 55, '4000 đồng/kW', '26000 đồng/ khối', 'Khép kín', 'Gia đình', 'Wifi, Sân phơi', 'Phù hợp gia đình nhỏ.', '2025-07-02 16:49:45', 'duyet', 'thuong', 7, '2025-07-02 07:44:13'),
(53, 4, 5, 'Cho thuê căn hộ chung cư quận Cầu Giấy', '99 Trần Quốc Hoàn', 'Cầu Giấy', 'Hà Nội', 'Trần Thị Nụ', '0909988776', 800000, 17, '3000 đồng/kW', '20000 đồng/ khối', 'Khép kín', 'Sinh viên', 'Wifi', 'Phòng Khép kín sạch sẽ, bạn thân thiện.', '2025-07-06 11:52:45', 'duyet', 'thuong', 7, '2025-07-06 12:49:45'),
(54, 4, 6, 'Cho thuê chung cư mini đầy đủ tiện nghi', '21 Nguyễn Thông', 'Quận 3', 'Hồ Chí Minh', 'Phạm Thị Oanh', '0922334455', 5800000, 38, '4000 đồng/kW', '25000 đồng/ khối', 'Khép kín', 'Người đi làm', 'Wifi, Máy giặt, Thang máy', 'Ngay trung tâm Q3.', '2025-07-05 07:49:45', 'duyet', 'thuong', 7, '2025-07-05 10:44:13'),
(55, 4, 1, 'Phòng trọ gần bến xe', '67 Phạm Văn Đồng', 'Bắc Từ Liêm', 'Hà Nội', 'Vũ Thị Phương', '0944332211', 2200000, 20, '3500 đồng/kW', '18000 đồng/ khối', 'Khép kín', 'Sinh viên', 'Wifi, Máy giặt, Thang máy', 'Giờ giấc thoải mái.', '2025-07-01 08:17:29', 'duyet', 'thuong', 7, '2025-07-07 10:44:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `sdt` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `sdt`) VALUES
(1, 'admin', '123', 'admin@gmail.com', 'admin', '0345123321'),
(4, 'ntlam', '123', 'ntlam@gmail.com', 'user', '0345123321'),
(6, 'An Nhã', '123', 'annha@gmail.com', 'user', '0929392392'),
(9, 'Giác Yên', '123', 'giacyen@gmail.com', 'user', '0929392388'),
(10, 'Thanh Lam', '123', 'thanhlam@gmail.com', 'user', '0929392389');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hinh_anh_phong_tro`
--
ALTER TABLE `hinh_anh_phong_tro`
  ADD PRIMARY KEY (`IDimage`),
  ADD KEY `IDPhongTro` (`IDPhongTro`);

--
-- Chỉ mục cho bảng `loai_phong`
--
ALTER TABLE `loai_phong`
  ADD PRIMARY KEY (`idLoaiPhong`);

--
-- Chỉ mục cho bảng `phong_tro`
--
ALTER TABLE `phong_tro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idLoaiPhong` (`idLoaiPhong`),
  ADD KEY `userID` (`userID`);

--
-- Chỉ mục cho bảng `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `saved_posts_ibfk_1` (`post_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hinh_anh_phong_tro`
--
ALTER TABLE `hinh_anh_phong_tro`
  MODIFY `IDimage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT cho bảng `loai_phong`
--
ALTER TABLE `loai_phong`
  MODIFY `idLoaiPhong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `phong_tro`
--
ALTER TABLE `phong_tro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=695;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `phong_tro`
--
ALTER TABLE `phong_tro`
  ADD CONSTRAINT `phong_tro_ibfk_1` FOREIGN KEY (`idLoaiPhong`) REFERENCES `loai_phong` (`idLoaiPhong`),
  ADD CONSTRAINT `phong_tro_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD CONSTRAINT `saved_posts_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `phong_tro` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
