-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2025 at 06:04 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mstore`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_to_cart` (IN `p_user_id` INT, IN `p_product_id` INT, IN `p_quantity` INT, OUT `p_success` BOOLEAN, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE v_stock_quantity INT;
    DECLARE v_current_cart_quantity INT DEFAULT 0;
    
    -- Kiểm tra số lượng tồn kho
    SELECT stock_quantity INTO v_stock_quantity
    FROM products WHERE id = p_product_id;
    
    -- Kiểm tra số lượng hiện tại trong giỏ hàng
    SELECT IFNULL(SUM(quantity), 0) INTO v_current_cart_quantity
    FROM cart WHERE user_id = p_user_id AND product_id = p_product_id;
    
    IF v_stock_quantity IS NULL THEN
        SET p_success = FALSE;
        SET p_message = 'Sản phẩm không tồn tại';
    ELSEIF p_quantity <= 0 THEN
        SET p_success = FALSE;
        SET p_message = 'Số lượng phải lớn hơn 0';
    ELSEIF (v_current_cart_quantity + p_quantity) > v_stock_quantity THEN
        SET p_success = FALSE;
        SET p_message = CONCAT('Chỉ còn ', v_stock_quantity, ' sản phẩm trong kho');
    ELSE
        -- Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        IF EXISTS(SELECT 1 FROM cart WHERE user_id = p_user_id AND product_id = p_product_id) THEN
            -- Cập nhật số lượng
            UPDATE cart 
            SET quantity = quantity + p_quantity
            WHERE user_id = p_user_id AND product_id = p_product_id;
        ELSE
            -- Thêm sản phẩm mới vào giỏ hàng
            INSERT INTO cart (user_id, product_id, quantity)
            VALUES (p_user_id, p_product_id, p_quantity);
        END IF;
        
        SET p_success = TRUE;
        SET p_message = 'Thêm vào giỏ hàng thành công';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `apply_coupon` (IN `p_order_id` INT, IN `p_coupon_code` VARCHAR(50), OUT `p_success` BOOLEAN, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE v_coupon_id INT;
    DECLARE v_discount DECIMAL(10,2);
    DECLARE v_is_percentage BOOLEAN;
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_new_total DECIMAL(10,2);
    
    -- Kiểm tra mã giảm giá
    SELECT id, discount, is_percentage INTO v_coupon_id, v_discount, v_is_percentage
    FROM coupons
    WHERE code = p_coupon_code AND is_active = 1 AND expiry_date >= CURRENT_DATE;
    
    IF v_coupon_id IS NULL THEN
        SET p_success = FALSE;
        SET p_message = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
    ELSE
        -- Lấy tổng giá trị đơn hàng hiện tại
        SELECT total_price INTO v_total FROM orders WHERE id = p_order_id;
        
        -- Tính toán giá sau khi áp dụng giảm giá
        IF v_is_percentage = 1 THEN
            SET v_new_total = v_total * (1 - v_discount/100);
        ELSE
            -- Nếu là giảm giá cố định
            SET v_new_total = GREATEST(0, v_total - v_discount);
        END IF;
        
        -- Cập nhật đơn hàng với mã giảm giá và tổng giá mới
        UPDATE orders SET coupon_id = v_coupon_id, total_price = v_new_total
        WHERE id = p_order_id;
        
        SET p_success = TRUE;
        SET p_message = CONCAT('Đã áp dụng mã giảm giá. Tổng giá mới: ', v_new_total);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_best_selling_products` (IN `p_limit` INT, IN `p_start_date` DATE, IN `p_end_date` DATE)   BEGIN
    SELECT 
        p.id,
        p.name,
        p.category_id,
        c.name AS category_name,
        SUM(od.quantity) AS total_quantity_sold,
        SUM(od.subtotal) AS total_revenue
    FROM products p
    JOIN orderdetails od ON p.id = od.product_id
    JOIN orders o ON od.order_id = o.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE o.status = 'completed'
      AND DATE(o.created_at) BETWEEN p_start_date AND p_end_date
    GROUP BY p.id, p.name, p.category_id, c.name
    ORDER BY total_quantity_sold DESC
    LIMIT p_limit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sales_report` (IN `p_start_date` DATE, IN `p_end_date` DATE)   BEGIN
    SELECT 
        DATE(o.created_at) AS order_date,
        COUNT(o.id) AS total_orders,
        SUM(o.total_price) AS total_revenue,
        AVG(o.total_price) AS average_order_value
    FROM orders o
    WHERE o.status = 'completed'
      AND DATE(o.created_at) BETWEEN p_start_date AND p_end_date
    GROUP BY DATE(o.created_at)
    ORDER BY order_date;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `place_order` (IN `p_user_id` INT, IN `p_coupon_id` INT, IN `p_shipping_address` TEXT, OUT `p_order_id` INT, OUT `p_status` VARCHAR(50))   BEGIN
    DECLARE v_order_success BOOLEAN DEFAULT TRUE;
    DECLARE v_error_message VARCHAR(255);
    
    -- Bắt đầu giao dịch
    START TRANSACTION;
    
    -- Kiểm tra giỏ hàng có sản phẩm không
    IF NOT EXISTS (SELECT 1 FROM cart WHERE user_id = p_user_id) THEN
        SET v_order_success = FALSE;
        SET v_error_message = 'Giỏ hàng trống';
    END IF;
    
    -- Kiểm tra số lượng tồn kho
    IF v_order_success AND EXISTS (
        SELECT 1 FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = p_user_id AND c.quantity > p.stock_quantity
    ) THEN
        SET v_order_success = FALSE;
        SET v_error_message = 'Một số sản phẩm không đủ tồn kho';
    END IF;
    
    -- Kiểm tra mã giảm giá hợp lệ nếu có
    IF v_order_success AND p_coupon_id IS NOT NULL AND NOT EXISTS (
        SELECT 1 FROM coupons 
        WHERE id = p_coupon_id AND is_active = 1 AND expiry_date >= CURRENT_DATE
    ) THEN
        SET v_order_success = FALSE;
        SET v_error_message = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
    END IF;
    
    -- Tạo đơn hàng nếu mọi thứ đều hợp lệ
    IF v_order_success THEN
        -- Tạo đơn hàng mới
        INSERT INTO orders (user_id, coupon_id, shipping_address, status)
        VALUES (p_user_id, p_coupon_id, p_shipping_address, 'pending');
        
        SET p_order_id = LAST_INSERT_ID();
        
        -- Chuyển sản phẩm từ giỏ hàng sang chi tiết đơn hàng
        INSERT INTO orderdetails (order_id, product_id, quantity, unit_price)
        SELECT p_order_id, c.product_id, c.quantity, p.price
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = p_user_id;
        
        -- Xóa sản phẩm khỏi giỏ hàng sẽ được thực hiện bởi trigger after_order_place
        
        SET p_status = 'success';
        COMMIT;
    ELSE
        SET p_order_id = NULL;
        SET p_status = v_error_message;
        ROLLBACK;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `process_payment` (IN `p_order_id` INT, IN `p_payment_method` ENUM('COD','Credit Card','Bank Transfer','PayPal','MoMo'), IN `p_transaction_id` VARCHAR(100), OUT `p_success` BOOLEAN, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE v_user_id INT;
    DECLARE v_amount DECIMAL(10,2);
    DECLARE v_session_id VARCHAR(100);
    
    -- Lấy thông tin đơn hàng
    SELECT user_id, total_price INTO v_user_id, v_amount
    FROM orders
    WHERE id = p_order_id;
    
    IF v_user_id IS NULL THEN
        SET p_success = FALSE;
        SET p_message = 'Đơn hàng không tồn tại';
    ELSE
        -- Tạo phiên thanh toán mới
        INSERT INTO paymentsessions (order_id, session_id, status)
        VALUES (p_order_id, UUID(), 'pending');
        
        SET v_session_id = LAST_INSERT_ID();
        
        -- Ghi nhận lịch sử thanh toán
        INSERT INTO paymenthistory (user_id, order_id, amount, payment_method, payment_gateway, status, transaction_id)
        VALUES (v_user_id, p_order_id, v_amount, p_payment_method, 
                CASE 
                    WHEN p_payment_method = 'Credit Card' THEN 'Stripe'
                    WHEN p_payment_method = 'PayPal' THEN 'PayPal'
                    WHEN p_payment_method = 'MoMo' THEN 'MoMo'
                    WHEN p_payment_method = 'Bank Transfer' THEN 'VNPay'
                    ELSE NULL
                END,
                'successful', p_transaction_id);
        
        -- Cập nhật trạng thái đơn hàng
        UPDATE orders SET status = 'processing' WHERE id = p_order_id;
        
        -- Cập nhật trạng thái phiên thanh toán
        UPDATE paymentsessions SET status = 'completed' WHERE id = v_session_id;
        
        SET p_success = TRUE;
        SET p_message = 'Thanh toán thành công';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_products` (IN `p_keyword` VARCHAR(255), IN `p_category_id` INT, IN `p_min_price` DECIMAL(10,2), IN `p_max_price` DECIMAL(10,2), IN `p_in_stock` BOOLEAN)   BEGIN
    SELECT 
        p.id,
        p.name,
        p.price,
        p.stock_quantity,
        p.image_url,
        c.name AS category_name,
        p.is_active
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 
        (p_keyword IS NULL OR p.name LIKE CONCAT('%', p_keyword, '%') OR p.description LIKE CONCAT('%', p_keyword, '%'))
        AND (p_category_id IS NULL OR p.category_id = p_category_id)
        AND (p_min_price IS NULL OR p.price >= p_min_price)
        AND (p_max_price IS NULL OR p.price <= p_max_price)
        AND (p_in_stock IS NULL OR (p_in_stock = TRUE AND p.stock_quantity > 0) OR (p_in_stock = FALSE))
        AND p.is_active = 1
    ORDER BY p.name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_product_stock` (IN `p_product_id` INT, IN `p_size_id` INT, IN `p_new_quantity` INT, OUT `p_success` BOOLEAN, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE v_product_exists BOOLEAN;
    DECLARE v_size_exists BOOLEAN;
    
    -- Kiểm tra sản phẩm tồn tại
    SELECT EXISTS(SELECT 1 FROM products WHERE id = p_product_id) INTO v_product_exists;
    
    IF NOT v_product_exists THEN
        SET p_success = FALSE;
        SET p_message = 'Sản phẩm không tồn tại';
    ELSE
        -- Nếu size_id được cung cấp, cập nhật số lượng cho kích thước cụ thể
        IF p_size_id IS NOT NULL THEN
            SELECT EXISTS(SELECT 1 FROM size WHERE id = p_size_id AND product_id = p_product_id) INTO v_size_exists;
            
            IF NOT v_size_exists THEN
                SET p_success = FALSE;
                SET p_message = 'Kích thước không tồn tại cho sản phẩm này';
            ELSE
                UPDATE size SET stock_quantity = p_new_quantity WHERE id = p_size_id;
                SET p_success = TRUE;
                SET p_message = 'Cập nhật số lượng kích thước thành công';
            END IF;
        ELSE
            -- Nếu không có size_id, cập nhật số lượng chung cho sản phẩm
            UPDATE products SET stock_quantity = p_new_quantity WHERE id = p_product_id;
            SET p_success = TRUE;
            SET p_message = 'Cập nhật số lượng sản phẩm thành công';
        END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Áo', 'Các loại áo thời trang nam nữ'),
(2, 'Quần', 'Các loại quần thời trang nam nữ'),
(3, 'Giày dép', 'Giày dép thời trang nam nữ'),
(4, 'Phụ kiện', 'Các loại phụ kiện thời trang'),
(5, 'Đồ thể thao', 'Trang phục và phụ kiện thể thao');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `is_percentage` tinyint(1) DEFAULT '1',
  `expiry_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount`, `is_percentage`, `expiry_date`, `is_active`) VALUES
(1, 'WELCOME10', '10.00', 1, '2025-12-31', 1),
(2, 'SUMMER20', '20.00', 1, '2025-06-30', 1),
(3, 'FREESHIP', '30.00', 0, '2025-12-31', 1),
(4, 'VIP50', '50.00', 1, '2025-12-31', 1),
(5, 'FLASH25', '25.00', 1, '2025-05-15', 1),
(6, 'MUNGVU1234', '10.00', 1, '2025-07-30', 1),
(7, 'MUNGVU1235', '10.00', 1, '2025-07-28', 1);

--
-- Triggers `coupons`
--
DELIMITER $$
CREATE TRIGGER `before_coupon_use` BEFORE UPDATE ON `coupons` FOR EACH ROW BEGIN
    IF CURRENT_DATE > NEW.expiry_date AND NEW.is_active = 1 THEN
        SET NEW.is_active = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `size_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`quantity` * `unit_price`)) STORED
) ;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `order_id`, `product_id`, `size_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 2, 1, '150000.00'),
(2, 1, 3, 9, 1, '450000.00'),
(3, 2, 3, 8, 1, '450000.00'),
(4, 3, 2, 6, 1, '250000.00'),
(5, 3, 4, NULL, 1, '550000.00'),
(6, 4, 5, NULL, 1, '350000.00'),
(7, 5, 4, NULL, 1, '550000.00'),
(8, 5, 3, NULL, 1, '450000.00'),
(9, 5, 3, NULL, 1, '450000.00'),
(10, 5, 9, 19, 3, '45234.00'),
(14, 6, 10, 20, 2, '43434.00'),
(19, 6, 8, 18, 1, '111111.00'),
(20, 6, 7, 14, 1, '10000.00'),
(21, 6, 7, 15, 1, '10000.00');

--
-- Triggers `orderdetails`
--
DELIMITER $$
CREATE TRIGGER `after_orderdetail_delete` AFTER DELETE ON `orderdetails` FOR EACH ROW BEGIN
    UPDATE orders SET total_price = (
        SELECT COALESCE(SUM(subtotal), 0) FROM orderdetails WHERE order_id = OLD.order_id
    ) WHERE id = OLD.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_orderdetail_insert` AFTER INSERT ON `orderdetails` FOR EACH ROW BEGIN
    UPDATE orders SET total_price = (
        SELECT SUM(subtotal) FROM orderdetails WHERE order_id = NEW.order_id
    ) WHERE id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_orderdetail_update` AFTER UPDATE ON `orderdetails` FOR EACH ROW BEGIN
    UPDATE orders SET total_price = (
        SELECT SUM(subtotal) FROM orderdetails WHERE order_id = NEW.order_id
    ) WHERE id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_orderdetail_insert` BEFORE INSERT ON `orderdetails` FOR EACH ROW BEGIN
    -- Lấy giá từ bảng products dựa trên product_id
    SET NEW.unit_price = (
        SELECT price 
        FROM products 
        WHERE id = NEW.product_id
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `address_id` int DEFAULT NULL,
  `coupon_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','processing','shipped','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `shipping_address` text COLLATE utf8mb4_general_ci,
  `shipping_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address_id`, `coupon_id`, `total_price`, `status`, `shipping_address`, `shipping_method`, `shipping_fee`, `created_at`) VALUES
(1, 3, NULL, 1, '600000.00', 'completed', '123 Nguyễn Huệ, TP.HCM', 'Standard', '30000.00', '2025-04-09 14:08:49'),
(2, 3, NULL, NULL, '450000.00', 'processing', '456 Lê Lợi, TP.HCM', 'Express', '50000.00', '2025-04-09 14:08:49'),
(3, 4, NULL, 3, '800000.00', 'shipped', '789 Lê Duẩn, Hà Nội', 'Standard', '30000.00', '2025-04-09 14:08:49'),
(4, 4, NULL, NULL, '350000.00', 'pending', '789 Lê Duẩn, Hà Nội', 'Standard', '30000.00', '2025-04-09 14:08:49'),
(5, 7, NULL, 2, '1585702.00', 'processing', '101 Đinh Tiên Hoàng, Đà Nẵng', 'Express', '50000.00', '2025-04-09 14:08:49'),
(6, 7, 6, 3, '217979.00', 'pending', NULL, NULL, '0.00', '2025-04-19 04:00:13');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `after_order_cancel` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF OLD.status != 'cancelled' AND NEW.status = 'cancelled' THEN
        -- Cập nhật số lượng trong bảng products cho các sản phẩm không có size cụ thể
        UPDATE products p
        INNER JOIN orderdetails od ON p.id = od.product_id
        SET p.stock_quantity = p.stock_quantity + od.quantity
        WHERE od.order_id = NEW.id AND od.size_id IS NULL;
        
        -- Cập nhật số lượng trong bảng size cho các sản phẩm có size cụ thể
        UPDATE size s
        INNER JOIN orderdetails od ON s.id = od.size_id
        SET s.stock_quantity = s.stock_quantity + od.quantity
        WHERE od.order_id = NEW.id AND od.size_id IS NOT NULL;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_order_confirm` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF (OLD.status = 'pending' OR OLD.status = 'cancelled') AND NEW.status = 'processing' THEN
        -- Giảm số lượng trong bảng products cho các sản phẩm không có size
        UPDATE products p
        INNER JOIN orderdetails od ON p.id = od.product_id
        SET p.stock_quantity = p.stock_quantity - od.quantity
        WHERE od.order_id = NEW.id AND od.size_id IS NULL;
        
        -- Giảm số lượng trong bảng size cho các sản phẩm có size
        UPDATE size s
        INNER JOIN orderdetails od ON s.id = od.size_id
        SET s.stock_quantity = s.stock_quantity - od.quantity
        WHERE od.order_id = NEW.id AND od.size_id IS NOT NULL;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_order_place` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    -- Chỉ xóa các sản phẩm đã được đặt hàng khỏi giỏ hàng
    DELETE FROM cart 
    WHERE user_id = NEW.user_id 
    AND product_id IN (
        SELECT product_id FROM orderdetails WHERE order_id = NEW.id
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `paymenthistory`
--

CREATE TABLE `paymenthistory` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('COD','Credit Card','Bank Transfer','PayPal','MoMo') COLLATE utf8mb4_general_ci NOT NULL,
  `payment_gateway` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('successful','failed','pending') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `paymenthistory`
--

INSERT INTO `paymenthistory` (`id`, `user_id`, `order_id`, `amount`, `payment_method`, `payment_gateway`, `status`, `transaction_id`, `created_at`) VALUES
(1, 3, 1, '585000.00', 'Credit Card', 'Stripe', 'successful', 'txn_123456789', '2025-04-09 14:09:24'),
(2, 3, 2, '450000.00', 'MoMo', 'MoMo', 'successful', 'txn_234567890', '2025-04-09 14:09:24'),
(3, 4, 3, '770000.00', 'Bank Transfer', 'VNPay', 'successful', 'txn_345678901', '2025-04-09 14:09:24'),
(4, 4, 4, '350000.00', 'COD', NULL, 'pending', NULL, '2025-04-09 14:09:24'),
(5, NULL, 5, '440000.00', 'PayPal', 'PayPal', 'failed', 'txn_567890123', '2025-04-09 14:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `paymentsessions`
--

CREATE TABLE `paymentsessions` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('created','pending','completed','failed') COLLATE utf8mb4_general_ci DEFAULT 'created',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentsessions`
--

INSERT INTO `paymentsessions` (`id`, `order_id`, `session_id`, `status`, `created_at`) VALUES
(1, 1, 'sess_123456789', 'completed', '2025-04-09 14:09:11'),
(2, 2, 'sess_234567890', 'completed', '2025-04-09 14:09:11'),
(3, 3, 'sess_345678901', 'completed', '2025-04-09 14:09:11'),
(4, 4, 'sess_456789012', 'pending', '2025-04-09 14:09:11'),
(5, 5, 'sess_567890123', 'failed', '2025-04-09 14:09:11');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1'
) ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `stock_quantity`, `image_url`, `description`, `is_active`) VALUES
(1, 'Áo thun basic trắng', 1, '150000.00', 100, '/images/ao-thun-trang.jpg', 'Áo thun basic chất liệu cotton 100%, màu trắng, kiểu dáng cơ bản', 1),
(2, 'Áo sơ mi nam kẻ sọc', 1, '250000.00', 80, '/images/ao-somi-ke-soc.jpg', 'Áo sơ mi nam màu xanh kẻ sọc, chất liệu cotton pha polyester', 1),
(3, 'Quần jeans nam regular fit', 2, '450000.00', 60, '/images/quan-jeans-nam.jpg', 'Quần jeans nam kiểu dáng regular fit, màu xanh đậm', 1),
(4, 'Giày thể thao nữ', 3, '550000.00', 40, '/images/giay-the-thao-nu.jpg', 'Giày thể thao nữ màu trắng, đế cao su', 1),
(5, 'Túi xách tay nữ', 4, '350000.00', 30, '/images/tui-xach-tay-nu.jpg', 'Túi xách tay nữ màu đen, chất liệu da PU cao cấp', 1),
(6, 'Quần Âu Nam Slim fit', 1, '15000.00', 100, 'storage/uploads/products/1744947922-1744604907-quanau5.jpg', 'grtmj,kikyjtreww', 1),
(7, 'Áo khoác Classic Varsity', 1, '10000.00', 100, 'storage/uploads/products/1744947969-1744608406-aokhoac2.jpg', 'sfregthjkutkymfgnbfvc', 1),
(8, 'Áo Khoác 3 Lớp Regular Fit', 1, '111111.00', 124, 'storage/uploads/products/1744948083-1744602848-aothun1.jpg', 'nmytsrersHDywm,áyyn', 1),
(9, 'ewrehtarem', 1, '45234.00', 131, 'storage/uploads/products/1744948104-1744603000-aothun2.jpg', 'ỰU46YJNFGNZYFNZY', 1),
(10, 'ENYMSHGMH', 1, '43434.00', 43, 'storage/uploads/products/1744948124-1744603202-aothun5.jpg', 'xỵyjstrjtrhtr', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `is_verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `is_verified`, `created_at`) VALUES
(1, 3, 1, 5, 'Chất lượng sản phẩm rất tốt, đúng như mô tả', 1, '2025-04-09 14:09:33'),
(2, 3, 3, 4, 'Quần đẹp, vải tốt nhưng hơi chật', 1, '2025-04-09 14:09:33'),
(3, 4, 2, 5, 'Áo đẹp, form chuẩn, giao hàng nhanh', 1, '2025-04-09 14:09:33'),
(4, 4, 4, 3, 'Giày đẹp nhưng hơi to so với size thông thường', 1, '2025-04-09 14:09:33'),
(5, NULL, 5, 5, 'Túi xách đẹp, chất liệu tốt, đáng đồng tiền', 0, '2025-04-09 14:09:33');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `before_review_insert` BEFORE INSERT ON `reviews` FOR EACH ROW BEGIN
    DECLARE has_purchased BOOLEAN;
    
    SELECT EXISTS(
        SELECT 1 FROM orders o
        JOIN orderdetails od ON o.id = od.order_id
        WHERE o.user_id = NEW.user_id 
        AND od.product_id = NEW.product_id
        AND o.status = 'completed'
    ) INTO has_purchased;
    
    IF has_purchased THEN
        SET NEW.is_verified = 1;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `size_name` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `stock_quantity` int NOT NULL
) ;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`id`, `product_id`, `size_name`, `stock_quantity`) VALUES
(1, 1, 'S', 20),
(2, 1, 'M', 30),
(3, 1, 'L', 30),
(4, 1, 'XL', 20),
(5, 2, 'S', 15),
(6, 2, 'M', 25),
(7, 2, 'L', 25),
(8, 2, 'XL', 15),
(9, 3, 'X', 10),
(10, 3, 'M', 15),
(11, 3, 'L', 15),
(12, 3, 'XL', 20),
(13, 6, 'S', 100),
(14, 7, 'S', 25),
(15, 7, 'M', 25),
(16, 7, 'L', 25),
(17, 7, 'XL', 25),
(18, 8, 'S', 124),
(19, 9, 'S', 131),
(20, 10, 'S', 43);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','customer','staff') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
(1, 'admin', 'admin@mstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2025-04-09 14:06:37'),
(2, 'staff1', 'staff1@mstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 1, '2025-04-09 14:06:37'),
(3, 'johndoe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 1, '2025-04-09 14:06:37'),
(4, 'janedoe', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 1, '2025-04-09 14:06:37'),
(6, 'admin1', 'mungvu003@gmail.com', '$2y$10$aemqbuc3AgRNc/TusqwKeu9tM2KA8Sshp5vwQ32hRUpXJ/WM6sxia', 'admin', 1, '2025-04-14 16:33:43'),
(7, 'mungvu2004', 'haonamk7@gmail.com', '$2y$10$AtisBnYNYEYXVmqm2eDU/u59fzCQ7NvJXkpr6hbBexu2Oze2PPfia', 'customer', 1, '2025-04-15 03:19:46'),
(8, 'mungvu123', 'phamviethoang170704@gmail.com', '$2y$10$sfvQck2MnSf70/6EJeVuZOueg.xX9ktR2zBs/pAD9xxVdVV/mpSfC', 'customer', 1, '2025-04-17 08:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `address_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `recipient_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address_line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address_line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `address_name`, `recipient_name`, `phone_number`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default`, `image_url`) VALUES
(1, 3, 'Nhà', 'John Doe', '0912345678', '123 Nguyễn Huệ', NULL, 'TP.HCM', NULL, '70000', 'Việt Nam', 1, NULL),
(2, 3, 'Văn phòng', 'John Doe', '0912345678', '456 Lê Lợi', NULL, 'TP.HCM', NULL, '70000', 'Việt Nam', 0, NULL),
(3, 4, 'Nhà', 'Jane Doe', '0923456789', '789 Lê Duẩn', NULL, 'Hà Nội', NULL, '10000', 'Việt Nam', 1, NULL),
(6, 7, 'Nhà', 'Mừng vũ', '012345678', 'Cổ nhuế bắc từ liêm', NULL, '', NULL, '', 'Việt Nam', 1, 'storage/uploads/users/1744912169-WIN_20250126_13_15_35_Pro.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orderdetails_order` (`order_id`),
  ADD KEY `fk_orderdetails_product` (`product_id`),
  ADD KEY `fk_orderdetails_size` (`size_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_coupon` (`coupon_id`),
  ADD KEY `fk_orders_address` (`address_id`);

--
-- Indexes for table `paymenthistory`
--
ALTER TABLE `paymenthistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_user` (`user_id`),
  ADD KEY `fk_payment_order` (`order_id`);

--
-- Indexes for table `paymentsessions`
--
ALTER TABLE `paymentsessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_paymentsession_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_user` (`user_id`),
  ADD KEY `fk_reviews_product` (`product_id`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_size_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_address_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `paymenthistory`
--
ALTER TABLE `paymenthistory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentsessions`
--
ALTER TABLE `paymentsessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `fk_orderdetails_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orderdetails_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orderdetails_size` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `paymenthistory`
--
ALTER TABLE `paymenthistory`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `paymentsessions`
--
ALTER TABLE `paymentsessions`
  ADD CONSTRAINT `fk_paymentsession_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `size`
--
ALTER TABLE `size`
  ADD CONSTRAINT `fk_size_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
