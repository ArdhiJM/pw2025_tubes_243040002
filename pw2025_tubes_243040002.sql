-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2025 at 07:07 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pw2025_tubes_243040002`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT 'Admin Muara Rahong',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `image`, `content`, `author`, `created_at`, `updated_at`) VALUES
(1, 'Tips Glamping Nyaman di Muara Rahong Hills', 'tips-glamping-nyaman-muara-rahong-hills', 'blog-1.jpg', '<p>Glamping adalah cara sempurna untuk menikmati alam tanpa harus meninggalkan kenyamanan. Di Muara Rahong Hills, Anda bisa merasakan pengalaman glamping tak terlupakan. Berikut adalah beberapa tips untuk membuat pengalaman glamping Anda lebih nyaman:</p><ul><li>Bawa pakaian hangat</li><li>Siapkan makanan ringan</li><li>Nikmati pemandangan</li></ul><p>Pastikan Anda membawa perlengkapan pribadi yang cukup dan kamera untuk mengabadikan momen-momen indah.</p>', 'Admin Muara Rahong', '2025-06-12 04:24:57', '2025-06-12 04:24:57'),
(2, 'Pesona Matahari Terbit di Pangalengan', 'pesona-matahari-terbit-pangalengan', 'blog-2.jpg', '<p>Matahari terbit di Pangalengan adalah pemandangan yang tak boleh Anda lewatkan. Udara sejuk pegunungan dan kabut tipis menciptakan suasana magis saat fajar menyingsing. Dari area glamping kami, Anda bisa menikmati pemandangan yang menakjubkan ini.</p><p>Sangat direkomendasikan untuk bangun lebih awal dan mencari spot terbaik untuk menyaksikan keindahan alam ini. Jangan lupa membawa jaket tebal!</p>', 'Admin Muara Rahong', '2025-06-12 04:24:57', '2025-06-12 04:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(30, 'Harga Camp Tenda Perorangan'),
(31, 'Paket Camp Weekend'),
(32, 'Paket Camp Weekdays'),
(33, 'Paket Makan Malam'),
(34, 'Paket Makan Siang'),
(35, 'Game'),
(36, 'Fasilitas');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `tanggal_pemesanan` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_mulai_travel` date NOT NULL,
  `jumlah_peserta` int NOT NULL DEFAULT '1',
  `total_harga` decimal(10,2) NOT NULL,
  `status_pemesanan` enum('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
  `catatan_user` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` double NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `detail` text,
  `ketersediaan_stok` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `harga`, `foto`, `detail`, `ketersediaan_stok`) VALUES
(19, 30, 'HARGA CAMP TENDA STANDARD', 750000850000, 'fsvLPTESeMqSFAX8JxVg.png', 'Sewa Tenda Standard Quechua 4.1 - Muara Rahong Hills\r\n\r\nSpesifikasi Produk\r\n\r\n- Nama Produk: Tenda Quechua 4.1\r\n- Kategori: Tenda Standard\r\n- Kapasitas: 4 Orang\r\n- Warna: Biru tua dengan detail abu-abu\r\n- Desain: Kubah dengan vestibule depan\r\n\r\nDaftar Harga Sewa\r\n\r\nHari Kerja (Weekdays)\r\nRp 750.000 per malam\r\n\r\nAkhir Pekan (Weekend)\r\nRp 850.000 per malam\r\n\r\nKeunggulan Tenda Quechua 4.1\r\n\r\n- Desain praktis dengan ruang tambahan di depan (vestibule)\r\n- Material tahan air dan angin\r\n- Ventilasi yang baik untuk sirkulasi udara\r\n- Setup relatif mudah dan cepat\r\n- Ruang tidur luas untuk 4 orang dewasa\r\n- Harga terjangkau untuk kategori standard\r\n- Cocok untuk camping keluarga atau teman-teman\r\n\r\nFitur Unggulan\r\n\r\n- Ruang Vestibule: Area tambahan untuk menyimpan perlengkapan\r\n- Double Layer: Perlindungan ekstra dari cuaca\r\n- Multiple Ventilation: Sistem ventilasi ganda\r\n- Easy Pitch: Sistem pemasangan yang user-friendly\r\n\r\nLokasi &amp; Kontak\r\nMuara Rahong Hills\r\n📍 Pangalengan, Jawa Barat\r\n📞 0822 1832 5132\r\n\r\nCatatan Penting\r\n\r\n- Harga sesuai ketentuan yang berlaku\r\n- Ideal untuk pengalaman camping dengan budget yang lebih ekonomis\r\n- Kualitas standar namun tetap nyaman dan reliable\r\n- Cocok untuk pemula atau yang mencari opsi hemat\r\n\r\n\r\nPilihan tepat untuk camping yang nyaman dengan harga terjangkau di kawasan wisata Muara Rahong Hills, Pangalengan!', 'Tersedia'),
(20, 30, 'HARGA CAMP TENDA VIP', 850000950000, '1WGqAIR069DTGUfvKDJD.png', 'Sewa Tenda VIP Quechua 4.2 - Muara Rahong Hills\r\n\r\nSpesifikasi Produk\r\n\r\n- Nama Produk: Tenda Quechua 4.2\r\n- Kategori: Tenda VIP\r\n- Kapasitas: 4 Orang\r\n- Warna: Biru/Tosca dengan aksen hitam\r\n\r\nDaftar Harga Sewa\r\n\r\nHari Kerja (Weekdays)\r\nRp 850.000 per malam\r\n\r\nAkhir Pekan (Weekend)\r\nRp 950.000 per malam\r\n\r\nKeunggulan Tenda Quechua 4.2\r\n\r\n- Desain modern dan aerodinamis\r\n- Tahan cuaca dengan material berkualitas tinggi\r\n- Setup mudah dan cepat\r\n- Ventilasi optimal untuk kenyamanan tidur\r\n- Cocok untuk keluarga atau grup kecil (4 orang)\r\n-Kategori VIP dengan fasilitas premium\r\n\r\nLokasi &amp;amp; Kontak\r\nMuara Rahong Hills\r\n📍 Pangalengan, Jawa Barat\r\n📞 0822 1832 5132\r\n\r\nCatatan Penting\r\n\r\n- Harga berlaku sesuai ketentuan yang berlaku\r\n- Untuk reservasi dan informasi lebih lanjut, hubungi nomor yang tertera\r\n- Cocok untuk pengalaman camping premium di kawasan wisata Pangalengan\r\n\r\n\r\nNikmati pengalaman berkemah yang tak terlupakan dengan fasilitas tenda VIP di tengah keindahan alam Muara Rahong Hills, Pangalengan!', 'Tersedia'),
(21, 31, 'Paket Camp A', 420000, 'u6HHQG3gFpyHbMxvaV5p.jpg', '📦 PAKET CAMP A\r\nDurasi: 2 Hari 1 Malam\r\nHarga: Rp 420.000/pack\r\nMinimal: 12 orang\r\nFasilitas &amp; Aktivitas Termasuk:\r\n\r\n⛺ Camping - Tenda dan perlengkapan camping\r\n🚣 Rafting 4,8 km (90 Menit) - Petualangan arung jeram seru\r\n🔫 Paintball War Game - Permainan strategi tim yang menegangkan\r\n🍽️ Makan 2x - Makan malam dan sarapan\r\n🌽 Jagung Bakar - Cemilan khas outdoor\r\n🔥 Api Unggun - Momen hangat bersama di malam hari', 'Tersedia'),
(22, 31, 'Paket Camp B', 520000, 'ESkvpEU8qAdATY2c0bp5.jpg', '📦 PAKET CAMP B\r\nDurasi: 2 Hari 1 Malam\r\nHarga: Rp 485.000/pack\r\nMinimal: 12 orang\r\nFasilitas &amp; Aktivitas Termasuk:\r\n\r\n⛺ Camping - Tenda dan perlengkapan camping premium\r\n🚣 Rafting 4,8 km (90 Menit) - Petualangan arung jeram seru\r\n🔫 Paintball War Game - Permainan strategi tim yang menegangkan\r\n🍽️ Makan 2x - Makan malam dan sarapan dengan menu lebih lengkap\r\n🌽 Jagung Bakar - Cemilan khas outdoor\r\n🔥 Api Unggun - Momen hangat bersama di malam hari', 'Tersedia'),
(23, 32, 'Paket Camp A', 395000, 'RX7hwv4NPAdvrLuAwgnm.jpg', '📦 PAKET CAMP A\r\nDurasi: 2 Hari 1 Malam\r\nHarga: Rp 395.000/pack\r\nMinimal: 12 orang\r\nFasilitas &amp; Aktivitas Termasuk:\r\n\r\n⛺ Camping - Tenda dan perlengkapan camping\r\n🚣 Rafting 4,8 km (90 Menit) - Petualangan arung jeram seru\r\n🔫 Paintball War Game - Permainan strategi tim yang menegangkan\r\n🍽️ Makan 2x - Makan malam dan sarapan\r\n🌽 Jagung Bakar - Cemilan khas outdoor\r\n🔥 Api Unggun - Momen hangat bersama di malam hari\r\n', 'Tersedia'),
(24, 32, 'Paket Camp B', 485000, 'MX9RDB4s67B8LoUssOuh.jpg', '📦 PAKET CAMP B\r\nDurasi: 2 Hari 1 Malam\r\nHarga: Rp 485.000/pack\r\nMinimal: 12 orang\r\nFasilitas &amp; Aktivitas Termasuk:\r\n\r\n⛺ Camping - Tenda dan perlengkapan camping premium\r\n🚣 Rafting 4,8 km (90 Menit) - Petualangan arung jeram seru\r\n🔫 Paintball War Game - Permainan strategi tim yang menegangkan\r\n🍽️ Makan 2x - Makan malam dan sarapan dengan menu lebih lengkap\r\n🌽 Jagung Bakar - Cemilan khas outdoor\r\n🔥 Api Unggun - Momen hangat bersama di malam hari', 'Tersedia'),
(25, 33, 'Paket 1', 35000, 'pIlvRoV24drW9buFXbKi.jpg', '🍽️ PAKET 1 - Menu Traditional\r\nHarga: Rp 35.000 per porsi\r\nMenu Lengkap:\r\n\r\n🍚 Nasi Putih - Nasi hangat sebagai makanan pokok\r\n🐟 Acar Ikan Mas - Ikan mas segar dengan bumbu acar yang segar\r\n🍲 Capcay - Tumisan sayuran segar campur\r\n🍖 Tempe Bacem - Tempe dengan bumbu bacem manis gurih\r\n🥗 Sambal Lalab - Sambal segar dengan lalapan sayuran\r\n🥬 Kerupuk - Kerupuk renyah sebagai pelengkap\r\n🥒 Buah - Buah segar sebagai penutup\r\n💧 Air Mineral - Minuman air mineral', 'Tersedia'),
(26, 33, 'Paket 2', 35000, 'GJDu2BFnMxru0JpBBSo1.jpg', '🍽️ PAKET 2 - Menu Special\r\nHarga: Rp 35.000 per porsi\r\nMenu Lengkap:\r\n\r\n🍚 Nasi Putih - Nasi hangat sebagai makanan pokok\r\n🥩 Rendang - Rendang daging dengan bumbu khas yang kaya rasa\r\n🍲 Capcay - Tumisan sayuran segar campur\r\n🌽 Perkedel Jagung - Perkedel jagung goreng yang gurih\r\n🥗 Sambal Lalab - Sambal segar dengan lalapan sayuran\r\n🥬 Kerupuk - Kerupuk renyah sebagai pelengkap\r\n🥒 Buah - Buah segar sebagai penutup\r\n💧 Air Mineral - Minuman air mineral', 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'adminardhi', 'adminardhi@gmail.com', '$2a$12$QDw3XpyjFmNXs0vckJQhx.zb0M1VCYFYD2wikAkDAMiKfPNLLSrVq', 'admin', '2025-06-11 17:44:16'),
(2, 'ardhi', 'ardhi@gmail.com', '$2y$10$zhX.MV8JkKT1Rlki4WHK3ON.qkW9VmdL19yrwd/XJUREDxKSA7Bo.', 'user', '2025-06-11 17:44:16'),
(3, 'shaka', 'shaka@gmail.com', '$2y$10$hONqTnJX7XWRmx4Nr.eYreoe9RQyKvL9mSOzjH6W5tLUGFYiSfVnq', 'user', '2025-06-11 19:50:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `kategori_produk` (`kategori_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `kategori_produk` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
