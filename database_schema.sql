-- ### 1. Tạo bảng Users
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE CHECK (username != ''),
    email VARCHAR(100) NOT NULL UNIQUE CHECK (email != ''),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer', 'staff') NOT NULL DEFAULT 'customer',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ### 2. Tạo bảng Categories
CREATE TABLE Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- ### 3. Tạo bảng Products
CREATE TABLE Products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    stock_quantity INT NOT NULL CHECK (stock_quantity >= 0),
    image_url VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE SET NULL
);

-- ### 4. Tạo bảng Coupons
CREATE TABLE Coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount DECIMAL(10,2) NOT NULL CHECK (discount >= 0 AND discount <= 100),
    is_percentage BOOLEAN DEFAULT TRUE,
    expiry_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- ### 5. Tạo bảng Orders
CREATE TABLE Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    coupon_id INT NULL,
    total_price DECIMAL(10,2) DEFAULT 0,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (coupon_id) REFERENCES Coupons(id) ON DELETE SET NULL
);

-- ### 6. Tạo bảng OrderDetails
CREATE TABLE OrderDetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10,2) NOT NULL CHECK (unit_price >= 0),
    subtotal DECIMAL(10,2) AS (quantity * unit_price) STORED,
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

-- ### 7. Tạo bảng Cart
CREATE TABLE Cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL CHECK (quantity > 0),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- ### 8. Tạo bảng Reviews
CREATE TABLE Reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

-- ### 9. Tạo bảng PaymentHistory
CREATE TABLE PaymentHistory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_id INT,
    amount DECIMAL(10,2) NOT NULL CHECK (amount >= 0),
    payment_method ENUM('COD', 'Credit Card', 'Bank Transfer', 'PayPal', 'MoMo') NOT NULL,
    payment_gateway VARCHAR(50),
    status ENUM('successful', 'failed', 'pending') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE
);

-- ### 10. Tạo bảng PaymentSessions
CREATE TABLE PaymentSessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    session_id VARCHAR(100) NOT NULL,
    status ENUM('created', 'pending', 'completed', 'failed') DEFAULT 'created',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(id) ON DELETE CASCADE
);

-- ### 11. Tạo Trigger
-- Trigger 1: Cập nhật kho sau khi thêm chi tiết đơn hàng
CREATE TRIGGER trg_update_stock_after_order
BEFORE INSERT ON OrderDetails
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    SELECT stock_quantity INTO available_stock 
    FROM Products 
    WHERE id = NEW.product_id;
    
    IF available_stock IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Product not found';
    ELSEIF available_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock available';
    ELSE
        UPDATE Products 
        SET stock_quantity = stock_quantity - NEW.quantity 
        WHERE id = NEW.product_id;
    END IF;
END;

-- Trigger 2: Cập nhật tổng giá đơn hàng sau khi thêm chi tiết đơn hàng
CREATE TRIGGER trg_update_total_price
AFTER INSERT ON OrderDetails
FOR EACH ROW
BEGIN
    UPDATE Orders 
    SET total_price = (SELECT SUM(subtotal) FROM OrderDetails WHERE order_id = NEW.order_id)
    WHERE id = NEW.order_id;
END;

-- Trigger 3: Cập nhật trạng thái đơn hàng sau khi thanh toán thành công
CREATE TRIGGER trg_update_order_status_after_payment
AFTER UPDATE ON PaymentHistory
FOR EACH ROW
BEGIN
    IF NEW.status = 'successful' AND OLD.status != 'successful' THEN
        UPDATE Orders 
        SET status = 'processing'
        WHERE id = NEW.order_id;
    END IF;
END;

-- ### 12. Tạo Stored Procedure để đặt hàng
CREATE PROCEDURE PlaceOrder(IN p_user_id INT, IN p_product_id INT, IN p_quantity INT, IN p_coupon_code VARCHAR(50))
BEGIN
    DECLARE order_id INT;
    DECLARE product_price DECIMAL(10,2);
    DECLARE available_stock INT;
    DECLARE coupon_id INT DEFAULT NULL;
    DECLARE discount_value DECIMAL(10,2) DEFAULT 0;
    DECLARE is_percentage BOOLEAN DEFAULT FALSE;

    SELECT id, discount, is_percentage INTO coupon_id, discount_value, is_percentage 
    FROM Coupons WHERE code = p_coupon_code AND is_active = TRUE AND expiry_date >= CURDATE();
    
    SELECT stock_quantity, price INTO available_stock, product_price 
    FROM Products 
    WHERE id = p_product_id;
    
    IF available_stock IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Product not found';
    ELSEIF available_stock < p_quantity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock available';
    ELSE
        INSERT INTO Orders(user_id, coupon_id, total_price, status) 
        VALUES (p_user_id, coupon_id, 0, 'pending');
        SET order_id = LAST_INSERT_ID();

        INSERT INTO OrderDetails(order_id, product_id, quantity, unit_price) 
        VALUES (order_id, p_product_id, p_quantity, product_price);

        IF coupon_id IS NOT NULL THEN
            IF is_percentage THEN
                UPDATE Orders SET total_price = total_price * (1 - discount_value / 100) WHERE id = order_id;
            ELSE
                UPDATE Orders SET total_price = GREATEST(total_price - discount_value, 0) WHERE id = order_id;
            END IF;
        END IF;
    END IF;
END;

-- ### 13. Tạo View cho OrderSummary
CREATE VIEW OrderSummary AS
SELECT 
    o.id AS order_id, 
    u.username, 
    o.total_price, 
    o.status, 
    o.created_at,
    (SELECT COUNT(*) FROM OrderDetails od WHERE od.order_id = o.id) AS item_count
FROM Orders o 
JOIN Users u ON o.user_id = u.id;

-- ### 14. Tạo các Index để tối ưu hóa truy vấn
CREATE INDEX idx_user_email ON Users(email);
CREATE INDEX idx_product_category ON Products(category_id);
CREATE INDEX idx_order_user ON Orders(user_id);
CREATE INDEX idx_orderdetails_order ON OrderDetails(order_id);
CREATE INDEX idx_cart_user ON Cart(user_id);