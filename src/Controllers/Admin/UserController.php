<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controller;

class UserController extends Controller
{
    private User $user;
    public function __construct()
    {
        $this->user = new User();
    }
    private function role($role) {
        if ($role == 'admin') {
            $_SESSION['msg'] = 'Đã đăng nhập thành công với quyền admin.';
            return true;
        } else {
            $_SESSION['msg'] = 'Đã đăng nhập thành công.';
            return false;
        }
    }
    public function account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Làm sạch và xác thực dữ liệu đầu vào
            $name = htmlspecialchars($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($password)) {
                $_SESSION['msg'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
                return view('elements.login');
            }
    
            // Thực hiện đăng nhập
            $account = $this->user->login($name, $password);
    
            if (empty($account) || count($account) != 1) {
                $_SESSION['msg'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
                return view('elements.login');
            }
            $role = $account[0]['role'];
            // Kiểm tra vai trò của người dùng
            if($this->role($role)) {
                $_SESSION['admin'] = $account[0];
                return view('admin.dashboard');
            } else {
                $_SESSION['user'] = $account[0];
                return view('client.layouts.main');
            }
        }
        return view('elements.login.login');
    }
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    private function validatePass($pass) {
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($passwordRegex, $pass);
    }
    public function signUp()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $passwordRe = htmlspecialchars($_POST['password-re']);
            $email = htmlspecialchars($_POST['email']);

            if (empty($username) || empty($password) || empty($passwordRe) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
                exit();
            }
            if (!$this->validateEmail($email)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đúng định dạng Email']);
                exit();
            }
            if(!$this->validatePass($password)) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm ít nhất 1 chữ cái thường, 1 chữ cái in hoa, 1 chữ số và 1 ký tự đặc biệt']);
                exit();
            }
            if ($password !== $passwordRe) {
                echo json_encode(['success' => false, 'message' => 'Hãy nhập đúng mật khẩu của bạn']);
                exit();
            }

            $data = [
                'name' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'email' => $email
            ];

            $client = $this->user->insert($data);

            if ($client) {
                echo json_encode(['success' => true, 'message' => 'Đăng ký thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi vui lòng đăng ký lại tài khoản']);
            }

            exit();
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }
    public function index() {
        $users = $this->user->getAllUser();
        return view('admin.users.user', 
        compact('users')    
        );
    }
    public function edit($id) {
        $detailUser = $this->user->detailUser($id);
        return view('admin.users.user-detail', 
            compact('detailUser')
        );
    }
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