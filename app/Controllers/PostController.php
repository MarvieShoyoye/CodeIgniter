<?php
namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\RESTful\ResourceController;

class PostsController extends ResourceController {
    protected $modelName = 'App\Models\PostModel';
    protected $format    = 'json';

    public function index() {
        return $this->respond($this->model->findAll());
    }

    public function show($id) {
        $post = $this->model->find($id);
        return $post ? $this->respond($post) : $this->failNotFound('Post not found');
    }

    public function store() {
        $rules = [
            'title'   => 'required',
            'content' => 'required',
            'user_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $postId = $this->model->insert([
            'title'   => $this->request->getVar('title'),
            'content' => $this->request->getVar('content'),
            'user_id' => $this->request->getVar('user_id'),
        ]);

        return $this->respondCreated(['message' => 'Post created', 'postId' => $postId]);
    }

    public function update($id) {
        $post = $this->model->find($id);
        if (!$post) {
            return $this->failNotFound('Post not found');
        }

        $updateData = $this->request->getRawInput();
        $this->model->update($id, $updateData);
        return $this->respondUpdated(['message' => 'Post updated']);
    }

    public function delete($id) {
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['message' => 'Post deleted']);
        }
        return $this->failNotFound('Post not found');
    }
}
