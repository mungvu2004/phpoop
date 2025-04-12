-- MySQL dump for 'mstore' database - Sample Data
-- Generated on April 08, 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Insert sample data into `users`
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
('admin1', 'admin1@mstore.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin', 1, '2025-04-08 08:00:00'),
('customer1', 'customer1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'customer', 1, '2025-04-08 08:05:00'),
('staff1', 'staff1@mstore.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'staff', 1, '2025-04-08 08:10:00'),
('customer2', 'customer2@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'customer', 0, '2025-04-08 08:15:00');

-- Insert sample data into `user_addresses`
INSERT INTO `user_addresses` (`user_id`, `address_type`, `full_name`, `phone_number`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default`, `created_at`) VALUES
(2, 'shipping', 'Nguyen Van A', '0909123456', '123 Đường Láng', NULL, 'Hà Nội', NULL, '100000', 'Vietnam', 1, '2025-04-08 08:20:00'),
(2, 'billing', 'Nguyen Van A', '0909123456', '456 Đường Giải Phóng', NULL, 'Hà Nội', NULL, '100000', 'Vietnam', 0, '2025-04-08 08:25:00'),
(3, 'both', 'Tran Thi B', '0987654321', '789 Đường Trường Chinh', 'Tòa A', 'TP Hồ Chí Minh', NULL, '700000', 'Vietnam', 1, '2025-04-08 08:30:00');

-- Insert sample data into `password_resets`
INSERT INTO `password_resets` (`user_id`, `token`, `expires_at`, `created_at`) VALUES
(2, 'abc123xyz456', '2025-04-09 08:00:00', '2025-04-08 08:00:00'),
(4, 'def789uvw012', '2025-04-09 09:00:00', '2025-04-08 09:00:00'),
(2, 'ghi345rst678', '2025-04-09 10:00:00', '2025-04-08 10:00:00');

-- Insert sample data into `categories`
INSERT INTO `categories` (`name`, `description`) VALUES
('Áo', 'Các loại áo nam, nữ'),
('Quần', 'Các loại quần nam, nữ'),
('Phụ kiện', 'Phụ kiện thời trang như mũ, khăn'),
('Giày', 'Giày dép nam, nữ');

-- Insert sample data into `products`
INSERT INTO `products` (`name`, `category_id`, `price`, `stock_quantity`, `image_url`, `description`, `is_active`) VALUES
('Áo Thun Unisex', 1, 150000.00, 100, 'storage/uploads/products/aothun.jpg', 'Áo thun cotton thoáng mát', 1),
('Quần Jean Slim', 2, 350000.00, 50, 'storage/uploads/products/quanjean.jpg', 'Quần jean form slim ôm sát', 1),
('Mũ Lưỡi Trai', 3, 120000.00, 80, 'storage/uploads/products/muluoinghieng.jpg', 'Mũ lưỡi trai phong cách', 1),
('Giày Sneaker Trắng', 4, 500000.00, 30, 'storage/uploads/products/giaysneaker.jpg', 'Giày sneaker thời trang', 1);

-- Insert sample data into `product_images`
INSERT INTO `product_images` (`product_id`, `image_url`, `is_primary`, `display_order`, `created_at`) VALUES
(1, 'storage/uploads/products/aothun_front.jpg', 1, 1, '2025-04-08 08:00:00'),
(1, 'storage/uploads/products/aothun_back.jpg', 0, 2, '2025-04-08 08:05:00'),
(2, 'storage/uploads/products/quanjean_side.jpg', 1, 1, '2025-04-08 08:10:00'),
(3, 'storage/uploads/products/muluoinghieng_top.jpg', 1, 1, '2025-04-08 08:15:00');

-- Insert sample data into `size`
INSERT INTO `size` (`product_id`, `size_name`, `stock_quantity`) VALUES
(1, 'S', 30),
(1, 'M', 40),
(1, 'L', 30),
(2, 'M', 20),
(2, 'L', 30);

-- Insert sample data into `coupons`
INSERT INTO `coupons` (`code`, `discount`, `is_percentage`, `expiry_date`, `is_active`) VALUES
('SUMMER25', 25.00, 1, '2025-06-30', 1),
('FIXED100K', 100000.00, 0, '2025-12-31', 1),
('WELCOME15', 15.00, 1, '2025-05-31', 1),
('FLASH50', 50.00, 1, '2025-04-15', 0);

-- Insert sample data into `orders`
INSERT INTO `orders` (`user_id`, `coupon_id`, `total_price`, `status`, `shipping_address`, `created_at`) VALUES
(2, 1, 120000.00, 'pending', '123 Đường Láng, Hà Nội', '2025-04-08 09:00:00'),
(3, NULL, 350000.00, 'processing', '789 Đường Trường Chinh, TP HCM', '2025-04-08 09:05:00'),
(2, 2, 250000.00, 'shipped', '123 Đường Láng, Hà Nội', '2025-04-08 09:10:00');

-- Insert sample data into `order_status_history`
INSERT INTO `order_status_history` (`order_id`, `status`, `notes`, `updated_by`, `created_at`) VALUES
(1, 'pending', 'Đơn hàng vừa được tạo', NULL, '2025-04-08 09:00:00'),
(2, 'processing', 'Đang xử lý tại kho', 3, '2025-04-08 09:06:00'),
(3, 'shipped', 'Đã giao cho đơn vị vận chuyển', 3, '2025-04-08 09:11:00'),
(2, 'completed', 'Đơn hàng hoàn tất', NULL, '2025-04-08 09:15:00');

-- Insert sample data into `orderdetails`
INSERT INTO `orderdetails` (`order_id`, `product_id`, `size_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 2, 150000.00),
(2, 2, 4, 1, 350000.00),
(3, 3, NULL, 3, 120000.00),
(1, 4, NULL, 1, 500000.00);

-- Insert sample data into `returns`
INSERT INTO `returns` (`order_id`, `user_id`, `reason`, `status`, `refund_amount`, `created_at`, `updated_at`) VALUES
(2, 3, 'Sản phẩm không đúng kích cỡ', 'requested', 0.00, '2025-04-08 10:00:00', NULL),
(3, 2, 'Hàng bị lỗi', 'approved', 360000.00, '2025-04-08 10:05:00', '2025-04-08 10:10:00'),
(1, 2, 'Không thích sản phẩm', 'rejected', 0.00, '2025-04-08 10:15:00', '2025-04-08 10:20:00');

-- Insert sample data into `return_items`
INSERT INTO `return_items` (`return_id`, `order_detail_id`, `quantity`, `reason`) VALUES
(1, 2, 1, 'Size không vừa'),
(2, 3, 2, 'Sản phẩm bị rách'),
(3, 1, 1, 'Không đúng mô tả');

-- Insert sample data into `cart`
INSERT INTO `cart` (`user_id`, `product_id`, `quantity`, `added_at`) VALUES
(2, 1, 2, '2025-04-08 08:30:00'),
(3, 3, 1, '2025-04-08 08:35:00'),
(4, 4, 3, '2025-04-08 08:40:00');

-- Insert sample data into `paymenthistory`
INSERT INTO `paymenthistory` (`user_id`, `order_id`, `amount`, `payment_method`, `payment_gateway`, `status`, `transaction_id`, `created_at`) VALUES
(2, 1, 120000.00, 'Credit Card', 'Visa', 'pending', 'TXN123456', '2025-04-08 09:01:00'),
(3, 2, 350000.00, 'PayPal', 'PayPal', 'successful', 'TXN789012', '2025-04-08 09:06:00'),
(2, 3, 250000.00, 'MoMo', 'MoMo', 'successful', 'TXN345678', '2025-04-08 09:11:00');

-- Insert sample data into `paymentsessions`
INSERT INTO `paymentsessions` (`order_id`, `session_id`, `status`, `created_at`) VALUES
(1, 'SESSION123', 'pending', '2025-04-08 09:01:00'),
(2, 'SESSION456', 'completed', '2025-04-08 09:06:00'),
(3, 'SESSION789', 'completed', '2025-04-08 09:11:00');

-- Insert sample data into `reviews`
INSERT INTO `reviews` (`user_id`, `product_id`, `rating`, `comment`, `is_verified`, `created_at`) VALUES
(2, 1, 4, 'Áo đẹp, chất lượng tốt', 1, '2025-04-08 10:00:00'),
(3, 2, 3, 'Quần hơi chật ở phần đùi', 1, '2025-04-08 10:05:00'),
(4, 3, 5, 'Mũ rất phong cách, đáng tiền', 0, '2025-04-08 10:10:00'),
(2, 4, 2, 'Giày hơi cứng, cần cải thiện', 1, '2025-04-08 10:15:00');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;