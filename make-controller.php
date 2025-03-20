<?php

if ($argc < 2) {
    die("Usage: php make-controller.php ControllerName\n");
}

$controllerName = ucfirst($argv[1]) . "Controller";
$controllerPath = __DIR__ . "/src/Controllers/{$controllerName}.php";

$template = <<<PHP
<?php
namespace App\Controllers;

class $controllerName {
    public function index() {
        echo "Danh sách dữ liệu.";
    }

    public function create() {
        echo "Form thêm dữ liệu.";
    }

    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    public function edit(\$id) {
        echo "Chỉnh sửa dữ liệu ID: \$id";
    }

    public function update(\$id) {
        echo "Cập nhật dữ liệu ID: \$id";
    }

    public function delete(\$id) {
        echo "Xóa dữ liệu ID: \$id";
    }
}
PHP;

if (!file_exists(__DIR__ . "/src/Controllers")) {
    echo "loi dduoowngf dan";
}

file_put_contents($controllerPath, $template);
echo "Controller {$controllerName} đã được tạo thành công tại {$controllerPath}\n";
