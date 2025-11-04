-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 04, 2025 lúc 02:48 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_thoi_trang_hoc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the_san_pham`
--

CREATE TABLE `bien_the_san_pham` (
  `id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `mau_sac` varchar(50) DEFAULT NULL,
  `kich_co` varchar(50) DEFAULT NULL,
  `gia_goc` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gia_ban` decimal(10,2) NOT NULL DEFAULT 0.00,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `hinh_anh_dai_dien` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the_san_pham`
--

INSERT INTO `bien_the_san_pham` (`id`, `san_pham_id`, `sku`, `mau_sac`, `kich_co`, `gia_goc`, `gia_ban`, `so_luong_ton`, `hinh_anh_dai_dien`) VALUES
(1, 10, 'SM196-XBD', 'Xanh Biển Đậm', 'M', 450000.00, 450000.00, 50, '../assets/img/ao-so-mi-denim-theu-just-breath-form-regular-sm196-19487-slide-products-690065603364c.jpg'),
(2, 11, 'SM197-DEN', 'Đen', 'M', 420000.00, 399000.00, 50, '../assets/img/ao-so-mi-tay-ngan-theu-logo-m-form-regular-sm197-19490-slide-products-690069b6c6e7d.jpg'),
(3, 12, 'PO175-TRG', 'Trắng', 'L', 350000.00, 350000.00, 50, '../assets/img/ao-polo-ca-sau-in-2-ben-suon-form-regular-po175-19474-slide-products-6900495a8a1f8.jpg'),
(4, 13, 'PO174-KEM', 'Trắng Kem', 'M', 380000.00, 380000.00, 50, '../assets/img/ao-polo-det-kim-day-keo-form-slimfit-po174-19471-slide-products-69004776eda89.jpg'),
(5, 14, 'QJ120-XAM', 'Xám', '30', 550000.00, 499000.00, 50, '../assets/img/quan-jean-ra-dieu-theu-huy-hieu-4men-form-regular-qj120-19481-slide-products-69005f9741d4f.jpg'),
(6, 15, 'QT069-DEN', 'Đen', '29', 480000.00, 480000.00, 50, '../assets/img/quan-tay-sidetab-tron-form-slimfit-qt069-19364-slide-products-68a29fe79042a.jpg'),
(7, 16, 'GI021-DEN', 'Đen', '40', 890000.00, 890000.00, 50, '../assets/img/giay-penny-loafer-da-bo-gi021-18585-slide-products-665ecb7c84442.jpg'),
(8, 17, 'TL195-DEN', 'Đen', 'One Size', 250000.00, 250000.00, 50, '../assets/img/that-lung-khoa-tu-dong-tl195-19320-slide-products-686261e5da72d.jpg'),
(9, 18, 'AT181-TRG', 'Trắng', 'M', 380000.00, 380000.00, 50, '../assets/img/ao-thun-det-kim-theu-logo-m-form-regular-at181-19460-slide-products-69003b39ca768.jpg'),
(10, 18, 'AT181-XAL', 'Xanh Lá', 'M', 380000.00, 380000.00, 50, '../assets/img/ao-thun-det-kim-theu-logo-m-form-regular-at181-mau-xanh-la-19461-slide-products-69003b73b216d.jpg'),
(11, 19, 'AK079-DEN', 'Đen', 'L', 890000.00, 799000.00, 30, '../assets/img/ao-khoac-da-lon-day-keo-form-regular-ak079-mau-den-19450-slide-products-69002f2f7f833.jpg\r\n'),
(12, 20, 'AK077-XBD', 'Xanh Biển Đậm', 'L', 750000.00, 750000.00, 40, '../assets/img/ao-khoac-jean-tron-tui-hop-2-ben-form-regular-ak077-19447-slide-products-69002b270bc53.jpg'),
(13, 21, 'AH007-BE', 'Be', 'M', 550000.00, 550000.00, 50, '../assets/img/ao-hoodie-phoi-bo-soc-theu-logo-soc-o-co-tay-form-regular-ah007-18929-slide-products-6757ed4a0b076.jpg'),
(14, 22, 'AH006-DEN', 'Đen', 'M', 600000.00, 549000.00, 50, '../assets/img/ao-hoodie-phoi-day-soc-form-regular-ah006-18852-slide-products-672601b4848e1.jpg'),
(15, 23, 'AV039-DEN', 'Đen', '48', 1200000.00, 1200000.00, 20, '../assets/img/ao-vest-tron-slimfit-tui-hai-tang-av039-18331-slide-products-657138f36504b.jpg'),
(16, 24, 'AG001-DEN', 'Đen', 'M', 350000.00, 350000.00, 30, '../assets/img/ao-ghile-thun-caro-theu-logo-form-regular-ag001-mau-den-19015-slide-products-676a420e886b2.jpg'),
(17, 25, 'AV035-XAM', 'Xám', '48', 1300000.00, 1199000.00, 20, '../assets/img/ao-vest-trang-tri-tui-mo-2-coi-form-slimfit-av035-18728-slide-products-66ffcd389b4b7.jpg\r\n'),
(18, 26, 'AL014-KEM', 'Kem', 'M', 450000.00, 450000.00, 40, '../assets/img/ao-len-tay-dai-co-tron-det-van-thung-form-regular-al014-18820-slide-products-6711dafbc2a95.jpg'),
(19, 26, 'AL014-XAM', 'Xám', 'M', 450000.00, 420000.00, 40, '../assets/img/ao-len-tay-dai-co-tron-det-van-thung-form-regular-al014-mau-xam-18822-slide-products-6711dcada97f6.jpg'),
(20, 27, 'QK031-DEN', 'Đen', '30', 500000.00, 500000.00, 60, '../assets/img/quan-kaki-dieu-2-ben-mieng-tui-form-slimfit-qk031-19373-slide-products-68a98adca18f0.jpg'),
(21, 28, 'QK028-BE', 'Be', '30', 520000.00, 480000.00, 60, '../assets/img/quan-kaki-signature-gan-tag-kim-loai-form-slimfit-qk028-18631-slide-products-66a7505a93531.jpg'),
(22, 29, 'QK030-DEN', 'Đen', '30', 550000.00, 550000.00, 60, '../assets/img/quan-kaki-theu-logo-tron-4m-premium-form-straight-qk030-mau-den-19005-slide-products-67654150549c9.jpg'),
(23, 30, 'JO016-DEN', 'Đen', 'M', 480000.00, 480000.00, 50, '../assets/img/quan-jogger-kaki-lung-thun-gan-tag-da-tam-giac-form-regular-jo016-mau-den-19012-slide-products-676cefa969597.jpg'),
(24, 31, 'JO015-XDN', 'Xanh Đen', 'M', 490000.00, 450000.00, 50, '../assets/img/quan-jogger-kaki-lung-thun-tui-hop-form-regular-jo015-mau-den-19008-slide-products-676591f675292.jpg'),
(25, 32, 'QS080-DEN', 'Đen', 'L', 300000.00, 300000.00, 70, '../assets/img/quan-jogger-kaki-lung-thun-tui-hop-form-regular-jo015-mau-den-19008-slide-products-676591f675292.jpg'),
(26, 33, 'QS079-DEN', 'Đen', 'L', 300000.00, 280000.00, 70, '../assets/img/quan-short-the-thao-in-logo-4men-club-form-regular-qs079-19400-slide-products-68c92bcd41d8d.jpg'),
(27, 34, 'QL069-XAM', 'Xám', 'XL', 150000.00, 150000.00, 100, '../assets/img/quan-boxer-lua-bang-thong-hoi-hoa-tiet-ql069-mau-xam-19311-slide-products-685a862653691.jpg'),
(28, 35, 'QL068-XAN', 'Xám Nhạt', 'XL', 140000.00, 140000.00, 100, '../assets/img/quan-boxer-lua-bang-phoi-luoi-hai-ben-ql068-mau-xanh-bien-19307-slide-products-6862591b4dfa8.jpg'),
(29, 36, 'BV060-PHOI', 'Phối Màu', 'One Size', 450000.00, 450000.00, 30, '../assets/img/vi-da-saffiano-khoa-keo-dang-dung-bv060-19434-slide-products-68e78484c996a.jpg'),
(30, 37, 'BV059-DEN', 'Đen', 'One Size', 480000.00, 480000.00, 30, '../assets/img/vi-da-saffiano-mini-dang-dung-bv059-19433-slide-products-68e78415e05bf.jpg'),
(31, 38, 'CV082-SOC', 'Sọc Xanh', 'One Size', 180000.00, 180000.00, 40, '../assets/img/-19068-slide-products-6782226d18403.jpg'),
(32, 39, 'CV081-SOC', 'Sọc Đỏ', 'One Size', 180000.00, 180000.00, 40, '../assets/img/-19069-slide-products-6782238135cdd.jpg'),
(33, 40, 'VO124-DEN', 'Đen', 'One Size', 50000.00, 50000.00, 200, '../assets/img/vo-cong-so-co-trung-soi-modal-thoang-khi-khu-mui-vo124-19092-slide-products-6791f81530d71.jpg'),
(34, 41, 'VO132-TRG', 'Trắng', 'One Size', 45000.00, 45000.00, 200, '../assets/img/vo-co-ngan-chu-mature-det-thong-hoi-soi-cotton-tham-hut-vo132-19408-slide-products-68db45ef56b72.jpg'),
(35, 42, 'MU010-TRG', 'Trắng', 'One Size', 250000.00, 250000.00, 80, '../assets/img/non-luoi-trai-theu-4men-tennis-club-mu010-18615-slide-products-669f8abfab2b7.jpg'),
(36, 43, 'TX018-JEAN', 'Xanh Jean', 'One Size', 450000.00, 399000.00, 30, '../assets/img/tui-xach-jean-tx018-19266-slide-products-6842af1558165.jpg'),
(38, 44, 'AK073-KEM', 'Kem', 'L', 750000.00, 699000.00, 50, '../assets/img/ao-khoac-du-chong-nang-uv-prox-tui-hop-form-regular-ak073-19275-slide-products-6846a74fe2c16.jpg'),
(39, 45, 'DE005-DEN', 'Đen', '41', 450000.00, 450000.00, 50, '../assets/img/dep-sandal-quai-ngang-da-microfiber-de-tpr-de005-18569-slide-products-6656a872e4abc.jpg'),
(40, 46, 'GI020-NAU', 'Nâu', '41', 950000.00, 899000.00, 50, '../assets/img/giay-penny-loafer-da-bo-gi021-18585-slide-products-665ecb7c84442.jpg'),
(41, 47, 'TL194-DEN', 'Đen', 'One Size', 300000.00, 250000.00, 50, '../assets/img/that-lung-khoa-tu-dong-tl194-19320-slide-products-686261e5da72d.jpg'),
(42, 48, 'SM191-TRG', 'Trắng', 'M', 420000.00, 420000.00, 50, '../assets/img/ao-so-mi-tay-ngan-oxford-theu-4m-o-tui-form-regular-sm191-19326-slide-products-686f85d38dbf4.jpg'),
(43, 49, 'SM178-XBL', 'Xanh Biển', 'M', 380000.00, 380000.00, 50, '../assets/img/ao-so-mi-tay-ngan-so-cheo-tron-from-regular-sm178-19314-slide-products-685bc8dfa14b7.jpg'),
(44, 50, 'SM182-XDN', 'Xanh Đen', 'M', 450000.00, 450000.00, 50, '../assets/img/ao-so-mi-dieu-ly-chong-nhan-wrinkle-x-theu-peakhour-form-slimfit-sm182-mau-xanh-den-19164-slide-products-67fe0f0daafc1.jpg'),
(45, 51, 'SM181-KEM', 'Trắng Kem', 'M', 450000.00, 420000.00, 50, '../assets/img/ao-so-mi-tron-chong-nhan-wrinkle-x-theu-peakhour-form-slimfit-sm181-mau-trang-kem-19160-slide-products-67fe0e96898b9.jpg'),
(46, 52, 'PO169-XBL', 'Xanh Biển', 'L', 380000.00, 380000.00, 50, '../assets/img/ao-polo-the-thao-phoi-in-logo-4men-club-sau-lung-form-regular-po169-19394-slide-products-68c925a63314f.jpg'),
(47, 53, 'VO133-DEN', 'Đen', 'One Size', 60000.00, 60000.00, 100, '../assets/img/vo-the-thao-co-cao-soi-cotton-tham-hut-vo133-19408-slide-products-68db45ef56b72.jpg'),
(48, 54, 'MU008-DEN', 'Đen', 'One Size', 280000.00, 250000.00, 100, '../assets/img/non-luoi-trai-theu-mo-neo-mu008-18551-slide-products-6650b20d2a5fb.jpg'),
(49, 55, 'PO165-TRG', 'Trắng', 'M', 350000.00, 350000.00, 50, '../assets/img/ao-polo-rayon-in-logo-banh-lai-form-regular-po165-19193-slide-products-6808be38b5c1e.jpg'),
(50, 56, 'AT174-TRG', 'Trắng', 'L', 280000.00, 280000.00, 50, '../assets/img/ao-thun-in-chu-wave-to-the-beach-form-relax-at174-mau-trang-19244-slide-products-682b5064a7aac.jpg'),
(51, 57, 'AT172-BE', 'Be', 'L', 320000.00, 299000.00, 50, '../assets/img/ao-thun-wash-loang-in-chu-hopeless-dream-form-regular-at172-mau-be-19238-slide-products-682e95b544e49.jpg'),
(52, 58, 'AK064-XDN', 'Xanh Đen', 'XL', 700000.00, 700000.00, 50, '../assets/img/ao-khoac-bomber-2-tui-hop-in-o-nguc-form-regular-ak064-mau-xanh-den-19047-slide-products-677cf105c8e3e.jpg'),
(53, 59, 'AK061-DEN', 'Đen', 'XL', 780000.00, 780000.00, 50, '../assets/img/ao-khoac-kaki-co-non-ra-phoi-preppy-Heritage-Form-Loose-ak061-18733-slide-products-66fffff2d8ab0.jpg'),
(54, 60, 'AH004-DEN', 'Đen', 'L', 550000.00, 499000.00, 50, '../assets/img/ao-hoodie-ra-phoi-theu-home-is-form-regular-ah004-mau-den-18377-slide-products-65feadb0c8013.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(11) NOT NULL,
  `don_hang_id` int(11) NOT NULL,
  `bien_the_id` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `so_sao` tinyint(4) NOT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `binh_luan` text DEFAULT NULL,
  `ngay_danh_gia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `danh_muc_cha_id` int(11) DEFAULT NULL,
  `slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten_danh_muc`, `danh_muc_cha_id`, `slug`) VALUES
(1, 'Áo', NULL, 'ao'),
(2, 'Quần', NULL, 'quan'),
(3, 'Phụ Kiện', NULL, 'phu-kien'),
(4, 'Giày', NULL, 'giay'),
(5, 'Áo Sơ Mi', 1, 'ao-so-mi'),
(6, 'Áo Polo', 1, 'ao-polo'),
(7, 'Quần Jean', 2, 'quan-jean'),
(8, 'Quần Tây', 2, 'quan-tay'),
(9, 'Thắt Lưng', 3, 'that-lung'),
(10, 'Áo Thun', 1, 'ao-thun'),
(11, 'Áo Khoác', 1, 'ao-khoac'),
(12, 'Áo Hoodie', 1, 'ao-hoodie'),
(13, 'Áo Vest', 1, 'ao-vest'),
(14, 'Áo Ghile', 1, 'ao-ghile'),
(15, 'Áo Len', 1, 'ao-len'),
(16, 'Quần Kaki', 2, 'quan-kaki'),
(17, 'Quần Jogger', 2, 'quan-jogger'),
(18, 'Quần Short', 2, 'quan-short'),
(19, 'Đồ Lót', 2, 'do-lot'),
(20, 'Ví Da', 3, 'vi-da'),
(21, 'Cà Vạt', 3, 'ca-vat'),
(22, 'Vớ/Tất', 3, 'vo-tat'),
(23, 'Nón/Mũ', 3, 'non-mu'),
(24, 'Túi Xách', 3, 'tui-xach'),
(25, 'Dép Sandal', 4, 'dep-sandal');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi`
--

CREATE TABLE `dia_chi` (
  `id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `dia_chi_day_du` varchar(255) NOT NULL,
  `phuong_xa` varchar(100) DEFAULT NULL,
  `quan_huyen` varchar(100) DEFAULT NULL,
  `tinh_thanh` varchar(100) DEFAULT NULL,
  `la_mac_dinh` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `email_khach_hang` varchar(150) NOT NULL,
  `dia_chi_giao_hang` text NOT NULL,
  `so_dien_thoai_giao_hang` varchar(15) NOT NULL,
  `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tong_tien` decimal(12,2) NOT NULL,
  `trang_thai` enum('ChoXuLy','DaXacNhan','DangGiao','HoanThanh','DaHuy') DEFAULT 'ChoXuLy',
  `phuong_thuc_thanh_toan` varchar(50) DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ma_giam_gia`
--

CREATE TABLE `ma_giam_gia` (
  `id` int(11) NOT NULL,
  `ma_code` varchar(50) NOT NULL,
  `loai_giam` enum('PhanTram','SoTien') NOT NULL,
  `gia_tri` decimal(10,2) NOT NULL,
  `ngay_het_han` datetime NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `vai_tro` enum('KhachHang','QuanTriVien') DEFAULT 'KhachHang',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `danh_muc_id` int(11) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `trang_thai` enum('DangBan','An','HetHang') DEFAULT 'DangBan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten_san_pham`, `danh_muc_id`, `mo_ta`, `slug`, `trang_thai`) VALUES
(10, 'Áo Sơ Mi Denim Thêu Just Breath Form Regular', 5, '<p>Mô tả chi tiết...</p>', 'sm196-ao-so-mi-denim-theu-just-breath', 'DangBan'),
(11, 'Áo Sơ Mi Tay Ngắn Thêu Logo M Form Regular', 5, '<p>Mô tả chi tiết...</p>', 'sm197-ao-so-mi-tay-ngan-theu-logo', 'DangBan'),
(12, 'Áo Polo Cá Sấu In 2 Bên Sườn Form Regular', 6, '<p>Mô tả chi tiết...</p>', 'po175-ao-polo-ca-sau-in-suon', 'DangBan'),
(13, 'Áo Polo Dệt Kim Dây Kéo Form Slimfit', 6, '<p>Mô tả chi tiết...</p>', 'po174-ao-polo-det-kim-day-keo', 'DangBan'),
(14, 'Quần Jean Rã Diễu Thêu Huy hiệu 4MEN Form Regular', 7, '<p>Mô tả chi tiết...</p>', 'qj120-quan-jean-ra-dieu-theu', 'DangBan'),
(15, 'Quần Tây Sidetab Trơn Form Slimfit', 8, '<p>Mô tả chi tiết...</p>', 'qt069-quan-tay-sidetab-tron-slimfit', 'DangBan'),
(16, 'Giày Derby', 4, '<p>Mô tả chi tiết...</p>', 'gi021-giay-derby', 'DangBan'),
(17, 'Thắt lưng khóa tự động', 9, '<p>Mô tả chi tiết...</p>', 'tl195-that-lung-khoa-tu-dong', 'DangBan'),
(18, 'Áo Thun Dệt Kim Thêu Logo M Form Regular AT181', 10, '<p>...</p>', 'at181-ao-thun-det-kim', 'DangBan'),
(19, 'Áo Khoác Da Lộn Dây Kéo Form Regular AK079', 11, '<p>...</p>', 'ak079-ao-khoac-da-lon', 'DangBan'),
(20, 'Áo Khoác Jean Trơn Túi Hộp 2 Bên Form Regular AK077', 11, '<p>...</p>', 'ak077-ao-khoac-jean-tui-hop', 'DangBan'),
(21, 'Áo Hoodie Phối Bo Sọc Thêu Logo Sọc Ở Cổ Tay Form Regular AH007', 12, '<p>...</p>', 'ah007-ao-hoodie-phoi-bo-soc', 'DangBan'),
(22, 'Áo Khoác Hoodie khóa zip Phối Dây Sọc Form Regular AH006', 12, '<p>...</p>', 'ah006-ao-khoac-hoodie-zip', 'DangBan'),
(23, 'Áo Vest Trơn Form Slimfit AV031', 13, '<p>...</p>', 'av031-ao-vest-tron-slimfit', 'DangBan'),
(24, 'Áo Ghile Thun Caro Thêu Logo Form Regular AG001', 14, '<p>...</p>', 'ag001-ao-ghile-thun-caro', 'DangBan'),
(25, 'Áo Vest Trang Trí Túi Mổ 2 Cơi Form Slimfit AV035', 13, '<p>...</p>', 'av035-ao-vest-trang-tri-tui-mo', 'DangBan'),
(26, 'Áo Len Tay Dài Cổ Tròn Dệt Vặn Thừng Form Regular AL014', 15, '<p>...</p>', 'al014-ao-len-van-thung', 'DangBan'),
(27, 'Quần Kaki Diễu 2 Bên Miệng Túi Form Slimfit QK031', 16, '<p>...</p>', 'qk031-quan-kaki-dieu-tui', 'DangBan'),
(28, 'Quần Kaki Trơn Signature Gắn Tag Kim Loại Form Slimfit QK028', 16, '<p>...</p>', 'qk028-quan-kaki-tron-signature', 'DangBan'),
(29, 'Quần Kaki Thêu logo tròn 4M Premium Form Straight QK030', 16, '<p>...</p>', 'qk030-quan-kaki-theu-logo', 'DangBan'),
(30, 'Quần Jogger Kaki Lưng Thun Gắn Tag Da Tam Giác Form Regular JO016', 17, '<p>...</p>', 'jo016-quan-jogger-kaki-tag-da', 'DangBan'),
(31, 'Quần Jogger Kaki Lưng Thun Túi Hộp Form Regular JO015', 17, '<p>...</p>', 'jo015-quan-jogger-kaki-tui-hop', 'DangBan'),
(32, 'Quần Short Thể Thao In Chữ Active 4MEN Form Regular QS080', 18, '<p>...</p>', 'qs080-quan-short-the-thao-active', 'DangBan'),
(33, 'Quần Short Thể Thao In Logo 4Men Club Form Regular QS079', 18, '<p>...</p>', 'qs079-quan-short-the-thao-4men-club', 'DangBan'),
(34, 'Quần Boxer Lụa Băng Thông Hơi Hoạ Tiết QL069', 19, '<p>...</p>', 'ql069-quan-boxer-lua-bang-hoa-tiet', 'DangBan'),
(35, 'Quần Boxer Lụa Băng Phối Lưới Hai Bên QL068', 19, '<p>...</p>', 'ql068-quan-boxer-lua-bang-phoi-luoi', 'DangBan'),
(36, 'VÍ DA EPSOM MINI DÁNG NGANG PHỐI MÀU BV060', 20, '<p>...</p>', 'bv060-vi-da-epsom-mini', 'DangBan'),
(37, 'VÍ DA SAFFIANO KHÓA KÉO DÁNG ĐỨNG BV059', 20, '<p>...</p>', 'bv059-vi-da-saffiano-khoa-keo', 'DangBan'),
(38, 'Cà Vạt Sọc CV082', 21, '<p>...</p>', 'cv082-ca-vat-soc', 'DangBan'),
(39, 'Cà Vạt Sọc CV081', 21, '<p>...</p>', 'cv081-ca-vat-soc-2', 'DangBan'),
(40, 'Vớ Công Sở Cổ Trung Sợi Modal Thoáng Khí Khử Mùi VO124', 22, '<p>...</p>', 'vo124-vo-cong-so', 'DangBan'),
(41, 'Vớ Lười Trơn Sợi Bamboo Kháng Khuẩn VO132', 22, '<p>...</p>', 'vo132-vo-luoi-tron', 'DangBan'),
(42, 'Nón Lưỡi Trai Thêu 4MEN Tennis Club MU010', 23, '<p>...</p>', 'mu010-non-luoi-trai-tennis', 'DangBan'),
(43, 'Túi Xách Jean TX018', 24, '<p>...</p>', 'tx018-tui-xach-jean', 'DangBan'),
(44, 'Áo Khoác Dù Chống Nắng UV-PROX Túi Hộp Form Regular AK073', 11, '<p>...</p>', 'ak073-ao-khoac-du-chong-nang', 'DangBan'),
(45, 'Dép sandal quai ngang da Microfiber đế TPR DE005', 25, '<p>...</p>', 'de005-dep-sandal-quai-ngang', 'DangBan'),
(46, 'Giày Penny Loafer Da Bò GI020', 4, '<p>...</p>', 'gi020-giay-penny-loafer-da-bo', 'DangBan'),
(47, 'Thắt lưng khóa tự động TL194', 9, '<p>...</p>', 'tl194-that-lung-khoa-tu-dong', 'DangBan'),
(48, 'Áo Sơ Mi Tay Ngắn Oxford Thêu 4M Ở Túi Form Regular SM191', 5, '<p>...</p>', 'sm191-ao-so-mi-oxford', 'DangBan'),
(49, 'Áo Sơ Mi Tay Ngắn Sớ Chéo Trơn Form Regular SM178', 5, '<p>...</p>', 'sm178-ao-so-mi-so-cheo', 'DangBan'),
(50, 'Áo Sơ Mi Diễu Ly Hạn Chế Nhăn Wrinkle X Thêu Peakhour Form Slimfit SM182', 5, '<p>...</p>', 'sm182-ao-so-mi-dieu-ly', 'DangBan'),
(51, 'Áo Sơ Mi Trơn Hạn Chế Nhăn Wrinkle X Thêu Peakhour Form Slimfit SM181', 5, '<p>...</p>', 'sm181-ao-so-mi-tron-wrinkle-x', 'DangBan'),
(52, 'Áo Polo Thể Thao Phối In Logo 4MEN Club Sau Lưng Form Regular PO169', 6, '<p>...</p>', 'po169-ao-polo-the-thao-4men-club', 'DangBan'),
(53, 'Vớ Thể Thao Cổ Cao Sợi Cotton Thấm Hút VO133', 22, '<p>...</p>', 'vo133-vo-the-thao', 'DangBan'),
(54, 'Nón Lưỡi Trai Thêu Mỏ Neo MU008', 23, '<p>...</p>', 'mu008-non-luoi-trai-mo-neo', 'DangBan'),
(55, 'Áo Polo Rayon In Logo Bánh Lái Form Regular PO165', 6, '<p>...</p>', 'po165-ao-polo-rayon-banh-lai', 'DangBan'),
(56, 'Áo Thun In Chữ Wave To The Beach Form Relax AT174', 10, '<p>...</p>', 'at174-ao-thun-wave-to-the-beach', 'DangBan'),
(57, 'Áo Thun Wash Loang In Chữ Hopeless Dream Form Regular AT172', 10, '<p>...</p>', 'at172-ao-thun-wash-loang', 'DangBan'),
(58, 'Áo Khoác Bomber 2 Túi Hộp In Ở Ngực Form Regular AK064', 11, '<p>...</p>', 'ak064-ao-khoac-bomber-2-tui-hop', 'DangBan'),
(59, 'Áo Khoác Kaki Có Nón Rã Phối Preppy Heritage Form Loose AK061', 11, '<p>...</p>', 'ak061-ao-khoac-kaki-co-non', 'DangBan'),
(60, 'Áo Hoodie Rã Phối Thêu Home Is Form Regular AH004', 12, '<p>...</p>', 'ah004-ao-hoodie-ra-phoi-home-is', 'DangBan');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thu_vien_anh`
--

CREATE TABLE `thu_vien_anh` (
  `id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `url_hinh_anh` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_hang_id` (`don_hang_id`),
  ADD KEY `bien_the_id` (`bien_the_id`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `san_pham_id` (`san_pham_id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `danh_muc_cha_id` (`danh_muc_cha_id`);

--
-- Chỉ mục cho bảng `dia_chi`
--
ALTER TABLE `dia_chi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_code` (`ma_code`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `so_dien_thoai` (`so_dien_thoai`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `danh_muc_id` (`danh_muc_id`);

--
-- Chỉ mục cho bảng `thu_vien_anh`
--
ALTER TABLE `thu_vien_anh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `dia_chi`
--
ALTER TABLE `dia_chi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ma_giam_gia`
--
ALTER TABLE `ma_giam_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `thu_vien_anh`
--
ALTER TABLE `thu_vien_anh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  ADD CONSTRAINT `bien_the_san_pham_ibfk_1` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`bien_the_id`) REFERENCES `bien_the_san_pham` (`id`);

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD CONSTRAINT `danh_muc_ibfk_1` FOREIGN KEY (`danh_muc_cha_id`) REFERENCES `danh_muc` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `dia_chi`
--
ALTER TABLE `dia_chi`
  ADD CONSTRAINT `dia_chi_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_muc` (`id`);

--
-- Các ràng buộc cho bảng `thu_vien_anh`
--
ALTER TABLE `thu_vien_anh`
  ADD CONSTRAINT `thu_vien_anh_ibfk_1` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
