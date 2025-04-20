<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controller;

/**
 * Lớp UserController quản lý các thao tác liên quan đến người dùng trong phần quản trị
 * 
 * Lớp này xử lý các chức năng như đăng nhập, đăng ký, quản lý thông tin người dùng
 * và các thao tác CRUD đối với người dùng.
 */
class UserController extends Controller
{
    /**
     * @var User Model xử lý dữ liệu người dùng
     */
    private User $user;

    /**
     * Khởi tạo controller và model User
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Xác định quyền hạn của người dùng sau khi đăng nhập
     * 
     * @param string $role Vai trò của người dùng
     * @return bool Trả về true nếu là admin, false nếu là người dùng thường
     */
    private function role($role) {
        if ($role == 'admin') {
            $_SESSION['msg'] = ['Đã đăng nhập thành công với quyền admin.'];
            return true;
        } else {
            $_SESSION['msg'] = ['Đã đăng nhập thành công.'];
            return false;
        }
    }

    /**
     * Xử lý đăng nhập tài khoản
     * 
     * @return mixed View đăng nhập hoặc chuyển hướng tùy theo kết quả
     */
    public function account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = htmlspecialchars($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
    
            if (empty($name) || empty($password)) {
                $_SESSION['msg'] = ['Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.'];
                return view('elements.dashboard-login');
            }
            $account = $this->user->login($name);
            if (empty($account) || count($account) != 1) {
                $_SESSION['msg'] = ['Tên đăng nhập hoặc mật khẩu không đúng.'];
                return view('elements.dashboard-login');
            }
            $user = $account[0];
            if (!password_verify($password, $user['password_hash'])) {
                $_SESSION['msg'] = ['Tên đăng nhập hoặc mật khẩu không đúng.'];
                return view('elements.dashboard-login');
            }
            $role = $user['role'];
            if ($this->role($role)) {
                $_SESSION['admin'] = $user;
                header('Location: /admin');
                exit;
            } else {
                $_SESSION['user'] = $user;
                header('Location: /');
                exit;
            }
        }
    
        header('Location: /login');
                exit;
    }
    
    /**
     * Kiểm tra tính hợp lệ của email
     * 
     * @param string $email Email cần kiểm tra
     * @return bool True nếu email hợp lệ, false nếu không hợp lệ
     */
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Kiểm tra tính hợp lệ của mật khẩu
     * 
     * @param string $pass Mật khẩu cần kiểm tra
     * @return bool True nếu mật khẩu đáp ứng yêu cầu, false nếu không
     */
    private function validatePass($pass) {
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($passwordRegex, $pass);
    }

    /**
     * Xử lý đăng ký tài khoản mới
     * 
     * @return void Chuyển hướng về trang đăng nhập sau khi xử lý
     */
    public function signUp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username    = trim($_POST['username'] ?? '');
            $password    = trim($_POST['password'] ?? '');
            $passwordRe  = trim($_POST['password-re'] ?? '');
            $email       = trim($_POST['email'] ?? '');

            $validators = [
                ['check' => empty($username) || empty($password) || empty($passwordRe) || empty($email), 'message' => 'Vui lòng nhập đầy đủ thông tin cần thiết để đăng ký tài khoản!!!'],
                ['check' => !$this->validateEmail($email), 'message' => 'Vui lòng nhập đúng định dạng Email!!!'],
                ['check' => !$this->validatePass($password), 'message' => 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm ít nhất 1 chữ cái thường, 1 chữ cái in hoa, 1 chữ số và 1 ký tự đặc biệt'],
                ['check' => $password !== $passwordRe, 'message' => 'Hãy nhập đúng mật khẩu của bạn'],
                ['check' => $this->user->checkUsername($username) > 0, 'message' => 'Tên đăng nhập đã tồn tại'],
                ['check' => $this->user->checkEmail($email) > 0, 'message' => 'Email của bạn đã tồn tại'],
            ];
            $errors = array_filter(array_map(function($validator) {
                return $validator['check'] ? $validator['message'] : null;
            }, $validators));

            if (!empty($errors)) {
                $_SESSION['msg'] = $errors;
                return;
            }

            $data = [
                'username'      => $username,
                'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                'email'         => $email
            ];

            $client = $this->user->insert($data);

            if ($client) {
                $_SESSION['msg'] = ['Bạn đã đăng ký thành công. Vui lòng đăng nhập.'];
            } else {
                $_SESSION['msg'] = ['Việc đăng ký đang có lỗi. Vui lòng thử lại.'];
            }

            header('Location: /login');
            exit();
        }
    }

    /**
     * Hiển thị danh sách người dùng
     * 
     * @return mixed View hiển thị danh sách người dùng
     */
    public function index() {
        $users = $this->user->getAllUser();
        return view('admin.users.user', 
        compact('users')    
        );
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng
     * 
     * @param int $id ID của người dùng cần chỉnh sửa
     * @return mixed View hiển thị form chỉnh sửa
     */
    public function edit($id) {
        $user = $this->user->getUser( $id );
        $detailUser = $this->user->detailUser($id);
        return view('admin.users.user-detail', 
            data: compact('user','detailUser')
        );
    }

    /**
     * Xóa người dùng
     * 
     * @param int $id ID của người dùng cần xóa
     * @return void Chuyển hướng về trang danh sách người dùng
     */
    public function delete($id)
    {
        try {
            $this->user->delete($id);
            $_SESSION["success"] = ["Đã xóa thành công người dùng"];
        } catch (\Exception $e) {
            $_SESSION["errors"] = ["Lỗi không thể xóa được: " . $e->getMessage()];
        }
        header("Location: /admin/contact");
        exit;
    }
}