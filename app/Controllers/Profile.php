<?php
namespace App\Controllers;

use App\Models\user_model;

class Profile extends BaseController
{
    protected $user_model;

    public function __construct()
    {
        $this->user_model = new user_model();
    }

    // Display the profile page
    public function index()
    {
        $session = session();

        if (!$session->get('LoggedIn')) {
            return redirect()->to(base_url('loginpage'));
        }

        $id = $session->get('id');
        $user = $this->user_model->find($id);

        if (!$user) {
            return redirect()->to(base_url('loginpage'));
        }

        $data = [
            'id'           => $user['id'],
            'fullname'     => $user['full_name'] ?? '',
            'username'     => $user['username'] ?? '',
            'email'        => $user['email'] ?? '',
            'mobilenumber' => $user['mobile'] ?? '',
            'role'         => $user['role'] ?? '',
            'status'       => $user['status'] ?? '',
            'profileimage' => $user['profile_image'] ?? ''
        ];

        return view('profile', $data);
    }

    // Update profile details or upload a profile photo
    public function updateprofile()
    {
        $session = session();

        if (!$session->get('LoggedIn')) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please log in again.'
            ])->setStatusCode(401);
        }

        $id = $session->get('id');
        $user = $this->user_model->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'User not found.'
            ])->setStatusCode(404);
        }

        $fullname = trim($this->request->getPost('fullname') ?? '');
        $username = trim($this->request->getPost('username') ?? '');
        $email = trim($this->request->getPost('email') ?? '');
        $mobile = trim($this->request->getPost('mobilenumber') ?? '');
        $password = $this->request->getPost('password') ?? '';

        // Start with an empty update array
        $updateData = [];

        // Update profile details only when details are submitted
        if (
            $fullname !== '' ||
            $username !== '' ||
            $email !== '' ||
            $mobile !== '' ||
            $password !== ''
        ) {
            if (
                $fullname === '' ||
                $username === '' ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                $mobile === ''
            ) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Enter valid profile details.'
                ]);
            }

            // Check whether another user already uses this username or email
            $existingUser = $this->user_model->checkuser(
                $username,
                $email
            );

            if (
                $existingUser &&
                (int) $existingUser['id'] !== (int) $id
            ) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Username or email already exists.'
                ]);
            }

            $updateData = [
                'full_name' => $fullname,
                'username'  => $username,
                'email'     => $email,
                'mobile'    => $mobile
            ];

            if ($password !== '') {
                $updateData['password'] = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );
            }
        }

        // Handle profile image upload
        $image = $this->request->getFile('profileimage');

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {

            if (!$image->isValid() || $image->hasMoved()) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Image upload failed: '
                        . $image->getErrorString()
                ]);
            }

            // Allow JPG, PNG, and WebP images
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!in_array($image->getMimeType(), $allowedTypes, true)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Please upload a JPG, PNG, or WebP image.'
                ]);
            }

            // Maximum image size: 2 MB
            if ($image->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Image size must not exceed 2 MB.'
                ]);
            }

            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;

            if (
                !is_dir($uploadPath) &&
                !mkdir($uploadPath, 0755, true) &&
                !is_dir($uploadPath)
            ) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Could not create the uploads folder.'
                ]);
            }

            try {
                $newName = $image->getRandomName();

                $image->move($uploadPath, $newName);

                // Store the uploaded image filename in the database
                $updateData['profile_image'] = $newName;

            } catch (\Throwable $e) {
                log_message(
                    'error',
                    'Profile image upload failed: ' . $e->getMessage()
                );

                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Unable to save the uploaded image.'
                ]);
            }
        }

        // Do not run an empty database update
        if (empty($updateData)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No profile details or image were provided.'
            ]);
        }

        // Save the updated data
        $success = $this->user_model->updateuser($updateData, $id);

        if (!$success) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Failed to update profile.'
            ]);
        }

        // Fetch the latest saved record
        $updatedUser = $this->user_model->find($id);

        if (!$updatedUser) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to retrieve the updated profile.'
            ]);
        }

        $photo = $updatedUser['profile_image'] ?? '';

        // Update session values
        $session->set([
            'id'           => $id,
            'fullname'     => $updatedUser['full_name'] ?? '',
            'username'     => $updatedUser['username'] ?? '',
            'email'        => $updatedUser['email'] ?? '',
            'mobilenumber' => $updatedUser['mobile'] ?? '',
            'photo'        => $photo
        ]);

        // Return updated data to AJAX
        return $this->response->setJSON([
            'status'       => true,
            'message'      => !empty($updateData['profile_image'])
                ? 'Profile photo updated successfully.'
                : 'Profile updated successfully.',
            'id'           => $id,
            'fullname'     => $updatedUser['full_name'] ?? '',
            'username'     => $updatedUser['username'] ?? '',
            'email'        => $updatedUser['email'] ?? '',
            'mobilenumber' => $updatedUser['mobile'] ?? '',
            'photo'        => $photo
        ]);
    }
}
