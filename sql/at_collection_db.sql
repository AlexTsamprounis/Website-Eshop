-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1:3307
-- Χρόνος δημιουργίας: 12 Ιαν 2026 στις 21:42:18
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `at_collection_db`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_items` text NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `card_expiry` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `Description`, `image_path`, `category`) VALUES
(1, 'Leather Bag N.694', 65.99, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/694.jpg', 'backpack'),
(4, 'Leather Bag N.699', 65.99, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/699.jpg', 'backpack'),
(5, 'Leather Bag N.700', 75.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/700.jpg', 'backpack'),
(6, 'Leather Bag N.1797', 89.99, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/1797.jpg', 'backpack'),
(7, 'Leather Bag N.1784', 39.99, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/1784.jpg', 'crossbody'),
(9, 'Leather Bag N.X103', 22.99, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/Χ103.jpg', 'crossbody'),
(10, 'Leather Bag N.X104', 27.99, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/Χ104.jpg', 'crossbody'),
(11, 'Leather Bag N.X105', 19.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/Χ105.jpg', 'crossbody'),
(12, 'Leather Bag N.X106', 27.30, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/Χ106.jpg', 'crossbody'),
(13, 'Leather Bag N.1788', 65.99, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/1788.jpg', 'shopping'),
(14, 'Leather Bag N.1790', 74.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/Μ3 - 1790.jpg', 'shopping'),
(15, 'Leather Bag N.1796', 59.99, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/1796.jpg', 'shopping'),
(16, 'Leather Bag N.1802', 60.50, 'Handcrafted leather bag for everyday elegance.', 'Assets/Images/at collecton 2024/1802.jpg', 'shopping'),
(17, 'Leather Bag N.1795', 45.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/1795.jpg', 'shopping'),
(18, 'Leather Bag N.687', 69.99, 'Premium finish, strong stitching, timeless style.\r\n\r\n', 'Assets/Images/at collecton 2024/687.jpg', 'shoulder'),
(19, 'Leather Bag N.1785', 47.99, 'Handcrafted leather bag for everyday elegance.\r\n\r\n', 'Assets/Images/at collecton 2024/1785.jpg', 'shoulder'),
(20, 'Leather Bag N.1786', 36.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/1786.jpg', 'shoulder'),
(21, 'Leather Bag N 1779', 44.99, 'Spacious interior and minimal modern design.\r\n\r\n', 'Assets/Images/at collecton 2024/1779.jpg', 'shoulder'),
(22, 'Leather Bag N.1782', 65.99, 'Handcrafted leather bag for everyday elegance.\r\n\r\n', 'Assets/Images/at collecton 2024/1782.jpg', 'shoulder'),
(23, 'Leather Bag N.698', 64.99, 'Spacious interior and minimal modern design.\r\n\r\n', 'Assets/Images/at collecton 2024/698.jpg', 'shoulder'),
(24, 'Leather Bag N.680MIK', 55.99, 'Handcrafted leather bag for everyday elegance.\r\n\r\n', 'Assets/Images/at collecton 2024/680MIK.jpg', 'bestseller'),
(25, 'Leather Bag N.687', 69.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/687.jpg', 'bestseller'),
(26, 'Leather Bag N.X99', 19.99, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/X99.jpg', 'bestseller'),
(27, 'Leather Bag N.696', 79.99, 'Handcrafted leather bag for everyday elegance.\r\n\r\n', 'Assets/Images/at collecton 2024/696.jpg', 'bestseller'),
(28, 'Leather Bag N.697', 89.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/697.jpg', 'bestseller'),
(29, 'Leather Bag N.1736', 39.99, 'Spacious interior and minimal modern design.\r\n\r\n', 'Assets/Images/at collecton 2024/1736.jpg', 'bestseller'),
(30, 'Leather Bag N.695', 64.99, 'Handcrafted leather bag for everyday elegance.\r\n\r\n', 'Assets/Images/at collecton 2024/695.jpg', 'bestseller'),
(31, 'Leather Bag N.1711MIK', 54.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/1711MIK.jpg', 'bestseller'),
(998, 'Leather Bag N.1780', 79.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/1780.jpg', 'backpack'),
(999, 'Leather Bag N.1780 (Crazy Horse)', 89.99, 'Spacious interior and minimal modern design.', 'Assets/Images/at collecton 2024/DSC_1601.jpg', 'backpack'),
(1002, 'Leather Bag N.1785', 49.99, 'Premium finish, strong stitching, timeless style.', 'Assets/Images/at collecton 2024/1785.jpg', 'crossbody');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `newsletter` varchar(5) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `gender`, `email`, `password_hash`, `newsletter`, `created_at`) VALUES
(32, 'δσφγσδφγ', 'δφσγ', 'male', 'fi@gmail.com', '$2y$10$lm86MGaP561W0HsZF/tmDeLIcWqIn4uE1lpjDrM6dM0KJtPd7smWO', 'yes', '2026-01-12 18:25:38');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT για πίνακα `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
