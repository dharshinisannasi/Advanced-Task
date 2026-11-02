<?php
namespace App\Controllers;
use App\Models\user_model;
class Employeemanagementcontroller extends BaseController {
    public function __construct() {
        $this->user_model = new user_model();
    }
    public function index(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return redirect()->to(base_url('loginpage'));
        }

        return view('employeemanagementview');
    }
     
    public function insertemployeedata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $fullname=$this->request->getPost('fullname');
        $username=$this->request->getPost('username');
        $email=$this->request->getPost('email');
        $mobilenumber=$this->request->getPost('mobilenumber');
        $password=$this->request->getPost('password');
        $confirmpassword=$this->request->getPost("confirmpassword");
        $photo=$this->request->getFile('photo');
        // $createdBy=$this->request->getPost('username');//command after admin login setup
        $createdBy=session()->get('username') ?? ''; //uncommand after admin login setup
        if (empty($fullname)|| empty($username) || empty($email) || empty($mobilenumber) || empty($password) || empty($confirmpassword)){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'All field are required',
            ]);
        }
        if (!$photo || !$photo->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Please upload a valid image'
            ]);
        }
        if ($password !== $confirmpassword) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Password and confirm password do not match'
            ]);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Invalid mail',
            ]);
        }
        $existingUser = $this->user_model->checkuser($username, $email);
        if ($existingUser) {
            if ($existingUser['username'] == $username) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Username already exists'
                ]);
            }

            if ($existingUser['email'] == $email) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Email already exists'
                ]);
            }
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower($photo->getClientExtension());

        if (!in_array($extension, $allowedExtensions, true)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Only JPG, JPEG and PNG images are allowed'
            ]);
        }
        $mimeType = $photo->getMimeType();

        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'The uploaded file must be a JPG, JPEG or PNG image'
            ]);
        }

        if ($photo->getSizeByUnit('mb') > 2) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Image size must not exceed 2 MB'
            ]);
        }
        $photoname=$photo->getRandomName();
        $photo->move(FCPATH . 'uploads', $photoname); //images ->included to the project folder 
        
        $data=[
            'full_name'=>$fullname,
            'username'=>$username,
            'email'=>$email,
            'mobile'=>$mobilenumber,
            'password'=> password_hash($password, PASSWORD_DEFAULT),
            'role'=>'Employee',
            'profile_image'=> $photoname,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by'   => $createdBy,
        ];

        $result= $this->user_model->insertdataofuser($data);
        if ($result){
            return $this->response->setJSON([
                'status'=> true,
                'message'=> 'Registration Successfull',
            ]);
        }
        return $this->response->setJSON([
            'status'=>false,
            'message'=>'Registration Failed',
        ]);
        

    }
    public function selectemployeedata()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $page = (int) ($this->request->getPost('page') ?? 0);
        $limit = $this->request->getPost('limit') ?? 'all';
        $searchuser = $this->request->getPost('searchuser') ?? '';
        $username = $this->request->getPost('username')?? '';
        $status= $this->request->getPost('status')?? '';
        $date= $this->request->getPost('date')?? '';
        $result = $this->user_model->selectemployee($page, $limit,$searchuser,$username,$status,$date);

        return $this->response->setJSON([
            'status' => true,
            'totalrecords' => $result['totalrecords'],
            'totalpages' => $result['totalpages'],
            'limit' => $result['limit'],
            'data' => $result['data']
        ]);
    }
    public function getusernames()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $usernames = $this->user_model->getuserNames();

        return $this->response->setJSON($usernames);
    }
    public function getstatusnames()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $status = $this->user_model->getstatusNames();

        return $this->response->setJSON($status);
    }
    public function getdate()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $date = $this->user_model->getDate();

        return $this->response->setJSON($date);
    }
    public function updateemployeedata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $id=$this->request->getPost('id');
        $fullname=$this->request->getPost('fullname');
        $username=$this->request->getPost('username');
        $mobilenumber=$this->request->getPost('mobilenumber');
        $email=$this->request->getPost('email');
        $status=$this->request->getPost('status');

        $data=[
            'full_name'=> $fullname,
            'username'=> $username,
            'mobile'=> $mobilenumber,
            'status'=> $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if (empty($id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Employee ID is required'
            ]);
        }
        $oldUser = $this->user_model->find($id);

        if (!$oldUser) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Employee not found'
            ]);
        }
        if ($oldUser['email'] != $email) {
            $existingEmail = $this->user_model
                ->where('email', $email)
                ->first();

            if ($existingEmail) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Email already exists'
                ]);
            }
            $data['email'] = $email;
        }
        $existingUsername = $this->user_model
            ->where('username', $username)
            ->where('id !=', $id)
            ->first();

        if ($existingUsername) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Username already exists'
            ]);
        }
        $result=$this->user_model->updateuser($data,$id);

        return $this->response->setJSON([
            'status'=> true,
            'message'=> 'Updated Successfully',
        ]);
    }

    public function deleteemployeedata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $id= $this->request->getPost('id');
        $result= $this->user_model->deleteuser($id);
        return $this->response->setJSON([
            'status'=>true,
            'message'=> 'Deleted Successfully',
        ]);
    }

    public function clearemployeedata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $result=$this->user_model->cleardata();
        return $this->response->setJSON([
            'status'=>true,
            'message'=>'Table deleted successfully',
        ]);
    }
    public function changestatus()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!in_array($status, ['Active', 'Inactive'], true)) {
            return $this->response->setJSON([
                'status' => false
            ]);
        }

        $result = $this->user_model->updateuser(
            ['status' => $status],
            $id
        );

        return $this->response->setJSON([
            'status' => $result
        ]);
    }

    public function exportemployee()
    {
        
        if (!session()->get('LoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/');
        }

        $format = $this->request->getGet('format');

        $searchuser = $this->request->getGet('searchuser') ?? '';
        $username   = $this->request->getGet('username') ?? '';
        $status     = $this->request->getGet('status') ?? '';
        $date       = $this->request->getGet('date') ?? '';

        $employees = $this->user_model->getemployeeforExport(
            $searchuser,
            $username,
            $status,
            $date
        );

        // PDF export
        if ($format === 'pdf') {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);

            $dompdf = new \Dompdf\Dompdf($options);

            $html = '
            <h2 style="text-align:center;">Employee Listing Report</h2>
            <p>Generated Date: ' . date('d-m-Y H:i:s') . '</p>
            <table border="1" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Created By</th>
            </tr>';

            foreach ($employees as $employee) {
                $html .= '<tr>
                    <td>' . esc($employee['id']) . '</td>
                    <td>' . esc($employee['full_name']) . '</td>
                    <td>' . esc($employee['username']) . '</td>
                    <td>' . esc($employee['email']) . '</td>
                    <td>' . esc($employee['mobile']) . '</td>
                    <td>' . esc($employee['status']) . '</td>
                    <td>' . esc($employee['created_at']) . '</td>
                    <td>' . esc($employee['created_by']) . '</td>
                </tr>';
            }

            $html .= '</table>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="Employee_Report.pdf"')
                ->setBody($dompdf->output());
        }

        // CSV export
        if ($format === 'csv') {
            $filename = 'Employee_Report.csv';

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'ID', 'Full Name', 'Username', 'Email',
                'Mobile', 'Status', 'Created At', 'Created By'
            ]);

            foreach ($employees as $employee) {
                fputcsv($output, [
                    $employee['id'],
                    $employee['full_name'],
                    $employee['username'],
                    $employee['email'],
                    $employee['mobile'],
                    $employee['status'],
                    $employee['created_at'],
                    $employee['created_by']
                ]);
            }

            fclose($output);
            exit;
        }

        // Excel export
        if ($format === 'xlsx') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Full Name');
            $sheet->setCellValue('C1', 'Username');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Mobile');
            $sheet->setCellValue('F1', 'Status');
            $sheet->setCellValue('G1', 'Created At');
            $sheet->setCellValue('H1', 'Created By');

            $row = 2;

            foreach ($employees as $employee) {
                $sheet->fromArray([
                    $employee['id'],
                    $employee['full_name'],
                    $employee['username'],
                    $employee['email'],
                    $employee['mobile'],
                    $employee['status'],
                    $employee['created_at'],
                    $employee['created_by']
                ], null, 'A' . $row);

                $row++;
            }

            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            ob_start();
            $writer->save('php://output');
            $file = ob_get_clean();

            return $this->response
                ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->setHeader('Content-Disposition', 'attachment; filename="Employee_Report.xlsx"')
                ->setBody($file);
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

    
}