<?php

namespace App;

/**
 * Lớp Controller cơ sở cung cấp các chức năng chung cho tất cả các controller
 * 
 * Lớp này cung cấp các phương thức tiện ích như ghi log, upload file và quản lý phiên đăng nhập.
 */
class Controller
{
    /**
     * Ghi log lỗi vào file
     * 
     * @param string $message Nội dung lỗi cần ghi
     */
    public function logError($message)
    {
        $date = date('d-m-Y');

        error_log($message, 3, "storage/logs/$date.log");
    }
    
    /**
     * Upload file lên server
     * 
     * @param array $file Thông tin file upload từ $_FILES
     * @param string|null $folder Thư mục con để lưu file (nếu có)
     * @return string Đường dẫn đến file đã upload
     * @throws \Exception Nếu có lỗi trong quá trình upload
     */
    public function uploadFile(array $file, $folder = null)
    {
        // Kiểm tra file upload hợp lệ
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception("File upload không hợp lệ.");
        }

        // Giới hạn kích thước file (ví dụ: 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxFileSize) {
            throw new \Exception("File quá lớn. Kích thước tối đa là 5MB.");
        }

        // Chỉ cho phép một số loại file
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedTypes)) {
            throw new \Exception("Loại file không được phép. Chỉ chấp nhận: " . implode(', ', $allowedTypes));
        }

        // Tạo tên file an toàn
        $fileName = time() . '-' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $file['name']);
        $uploadDir = 'storage/uploads/' . ($folder ? trim($folder, '/') : '') . '/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) || !is_writable($uploadDir)) {
                throw new \Exception("Không thể tạo hoặc ghi vào thư mục: $uploadDir");
            }
        }

        $destPath = $uploadDir . $fileName;

        // Di chuyển file
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return $destPath;
        }

        throw new \Exception("Có lỗi xảy ra khi upload file.");
    }
    /**
     * Đăng xuất người dùng và chuyển hướng về trang chủ
     */
    public static function logout() {
        session_unset();
        session_destroy();
        header('Location: /');  
        exit;
    }
}