<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController {
    public function register() {
        $userModel = new UserModel();
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => 'user'
        ];
        $userModel->insert($data);
        return $this->respondCreated(['message' => 'User registered successfully']);
    }
}
