<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminModel;

class AdminController extends ResourceController
{
    public function createAdmin()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[admins.email]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $adminModel = new AdminModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $adminModel->save($data);
        return $this->respondCreated(['status' => 'success', 'message' => 'Admin created successfully']);
    }

    public function login()
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->where('email', $this->request->getPost('email'))->first();

        if (!$admin || !password_verify($this->request->getPost('password'), $admin['password'])) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        return $this->respond(['status' => 'success', 'message' => 'Login successful']);
    }

    public function getAdmins()
    {
        $adminModel = new AdminModel();
        return $this->respond(['status' => 'success', 'admins' => $adminModel->findAll()]);
    }

    public function viewAdmin($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            return $this->respond(['status' => 'error', 'message' => 'Admin not found'], 404);
        }

        return $this->respond(['status' => 'success', 'admin' => $admin]);
    }

    public function updateAdmin($id)
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            return $this->respond(['status' => 'error', 'message' => 'Admin not found'], 404);
        }

        $data = $this->request->getRawInput();
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $adminModel->update($id, $data);
        return $this->respond(['status' => 'success', 'message' => 'Admin updated successfully']);
    }

    public function deleteAdmin($id)
    {
        $adminModel = new AdminModel();
        if (!$adminModel->find($id)) {
            return $this->respond(['status' => 'error', 'message' => 'Admin not found'], 404);
        }

        $adminModel->delete($id);
        return $this->respondDeleted(['status' => 'success', 'message' => 'Admin deleted successfully']);
    }
}
