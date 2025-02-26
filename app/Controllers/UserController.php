<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsersController extends ResourceController {
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

   public function register()
    {
        $request = service('request');

        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirmPassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ])->setStatusCode(400);
        }

        $userModel = new UserModel();
        $userData = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'password' => password_hash($request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $userModel->save($userData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'User registered successfully.'
        ])->setStatusCode(201);
}


    public function getUser() {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader) {
            return $this->failUnauthorized('Token missing');
        }

        try {
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode(str_replace('Bearer ', '', $authHeader), new Key($key, 'HS256'));
            $user = $this->model->find($decoded->uid);
            return $this->respond($user);
        } catch (\Exception $e) {
            return $this->failUnauthorized('Invalid token');
        }
    }

    public function update($id = null)
{
    $request = service('request');
    $userModel = new \App\Models\UserModel();

    // Find user by ID
    $user = $userModel->find($id);
    if (!$user) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'User not found'
        ])->setStatusCode(404);
    }

    // Get updated data from request
    $data = [
        'name' => $request->getVar('name'),
        'email' => $request->getVar('email')
    ];

    // Update user in the database
    $userModel->update($id, $data);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'User updated successfully',
        'user' => $userModel->find($id)
    ]);
}


    public function logout() {
        return $this->respond(['message' => 'Logout successful']);
    }

    public function deleteUser() {
        $userId = $this->request->getVar('id');
        $this->model->delete($userId);
        return $this->respondDeleted(['message' => 'User deleted successfully']);
    }
}
