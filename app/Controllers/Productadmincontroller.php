<?php
namespace App\Controllers;
use App\Models\product_model;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class Productadmincontroller extends BaseController {
    public function __construct() {
        $this->product_model = new product_model();
    }
    public function index(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return redirect()->to(base_url('loginpage'));
        }

        return view('productadminview');
    }
     
    public function insertadminproductdata(){
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
        $productcode=$this->request->getPost('productcode');
        $productname=$this->request->getPost('productname');
        $category=$this->request->getPost('category');
        $description=$this->request->getPost('description');
        $price=$this->request->getPost('price');
        $quantity=$this->request->getPost('quantity');
        $status=$this->request->getPost("status");
        $photo=$this->request->getFile('photo');
        $createdBy=session()->get('username') ?? '';
        if (
            trim((string) $productcode) === '' ||
            trim((string) $productname) === '' ||
            trim((string) $category) === '' ||
            trim((string) $description) === '' ||
            trim((string) $price) === '' ||
            trim((string) $quantity) === '' ||
            trim((string) $status) === ''
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'All fields are required'
            ]);
        }
        if (
            !is_numeric($price) || $price < 0 ||
            filter_var($quantity, FILTER_VALIDATE_INT) === false ||
            $quantity < 0
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Enter a valid price and a non-negative whole-number quantity'
            ]);
        }
        if (!$photo || !$photo->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Please upload a valid image'
            ]);
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
        if ($this->product_model->checkProductCode($productcode)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product code already exists'
            ]);
        }
    
        $photoname=$photo->getRandomName();
        if (!$photo->move(FCPATH . 'uploads', $photoname)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Image upload failed'
            ]);
        }
        $data=[
            'product_code'=>$productcode,
            'product_name'=>$productname,
            'category'=>$category,
            'description'=>$description,
            'price'=>$price,
            'quantity'=> $quantity,
            'status'=>$status,
            'role' => session()->get('role'),
            'product_image'=> $photoname,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by'   => $createdBy,
        ];

        $result= $this->product_model->insertadminproduct($data);
        if ($result){
            return $this->response->setJSON([
                'status'=> true,
                'message'=> 'Inserted Successfully',
            ]);
        }
        return $this->response->setJSON([
            'status'=>false,
            'message'=>'Registration Failed',
        ]);
        

    }
    public function selectadminproductdata()
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
        $name = $this->request->getPost('name')?? '';
        $status= $this->request->getPost('status')?? '';
        $date= $this->request->getPost('date')?? '';
        $code= $this->request->getPost('code')?? '';
        $category= $this->request->getPost('category')?? '';
        $username = $this->request->getPost('user') ?? '';
        $result = $this->product_model->selectadminproduct($page, $limit,$searchuser,$name,$status,$date,$code,$category,$username);
        
        return $this->response->setJSON([
            'status' => true,
            'totalrecords' => $result['totalrecords'],
            'totalpages' => $result['totalpages'],
            'limit' => $result['limit'],
            'data' => $result['data']
        ]);
    }
    public function getallproduct()
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
        $product = $this->product_model->getallProduct();

        return $this->response->setJSON($product);
    }
    public function getallproductcodes()
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
        $productcodes = $this->product_model->getallProductcode();

        return $this->response->setJSON($productcodes);
    }
    public function getallcategory()
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
        $category = $this->product_model->getallCategory();

        return $this->response->setJSON($category);
    }
    public function getallstatus()
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
        $status = $this->product_model->getallstatusNames();

        return $this->response->setJSON($status);
    }
    public function getalldates()
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
        $date = $this->product_model->getallDate();
        
        return $this->response->setJSON($date);
    }
    public function getallusers()
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
        $user = $this->product_model->getallUsers();
        
        return $this->response->setJSON($user);
    }
    public function updateallproductdata(){
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
        $productcode=$this->request->getPost('productcode');
        $productname=$this->request->getPost('productname');
        $category=$this->request->getPost('category');
        $description=$this->request->getPost('description');
        $price=$this->request->getPost('price');
        $quantity=$this->request->getPost('quantity');
        $status=$this->request->getPost('status');

        $data=[
            'product_code'=> $productcode,
            'product_name'=> $productname,
            'category'=> $category,
            'description'=> $description,
            'price'=> $price,
            'quantity'=> $quantity,
            'status'=> $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if (empty($id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID is required'
            ]);
        }
        
        if (
            !is_numeric($price) || $price < 0 ||
            filter_var($quantity, FILTER_VALIDATE_INT) === false ||
            $quantity < 0
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid price or quantity'
            ]);
        }
        $result = $this->product_model->updateallproduct($data, $id);

        return $this->response->setJSON([
            'status'  => $result,
            'message' => $result ? 'Updated successfully' : 'Update failed'
        ]);
    }

    public function deleteallproductdata()
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

        if (empty($id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID is required'
            ]);
        }

        $result = $this->product_model->deleteallproduct($id);

        return $this->response->setJSON([
            'status' => $result,
            'message' => $result ? 'Deleted successfully' : 'Delete failed'
        ]);
    }

    public function clearallproductdata()
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
        $result = $this->product_model->clearallproduct();

        return $this->response->setJSON([
            'status' => $result,
            'message' => $result ? 'Products cleared successfully' : 'Clear failed'
        ]);
    }
    public function changeandupdateallstatus()
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

        if (
            empty($id) ||
            !in_array($status, ['Active', 'Inactive'], true)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid product ID or status'
            ]);
        }

        $result = $this->product_model->updateallproduct(
            ['status' => $status],
            $id
        );

        return $this->response->setJSON([
            'status' => $result
        ]);
    }
    

    public function exportadminproducts()
    {
        // Check Admin session
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setBody('Unauthorized access');
        }

        // Get filter values
        $searchuser = $this->request->getGet('searchuser') ?? '';
        $name       = $this->request->getGet('name') ?? '';
        $status     = $this->request->getGet('status') ?? '';
        $date       = $this->request->getGet('date') ?? '';
        $code       = $this->request->getGet('code') ?? '';
        $category   = $this->request->getGet('category') ?? '';
        $username   = $this->request->getGet('user') ?? '';

        // Get filtered product records
        $products = $this->product_model->getadminproductsforexport(
            $searchuser,
            $name,
            $status,
            $date,
            $code,
            $category,
            $username
        );

        if (empty($products)) {
            return $this->response
                ->setStatusCode(404)
                ->setBody('No product records found to export.');
        }

        $format = strtolower($this->request->getGet('format') ?? '');

        $filename = 'Admin_Products_' . date('Y-m-d');


        // =========================
        // CSV EXPORT
        // =========================

        if ($format === 'csv') {

            $file = fopen('php://temp', 'w+');

            fputcsv($file, [
                'Product Code',
                'Product Name',
                'Category',
                'Description',
                'Price',
                'Quantity',
                'Status',
                'Created Date',
                'Created By',
                'Role',
                'Image'
            ]);

            foreach ($products as $product) {

                fputcsv($file, [
                    $product['product_code'],
                    $product['product_name'],
                    $product['category'],
                    $product['description'],
                    $product['price'],
                    $product['quantity'],
                    $product['status'],
                    $product['created_at'],
                    $product['created_by'],
                    $product['role'],
                    $product['product_image']
                ]);
            }

            rewind($file);

            $csvData = stream_get_contents($file);

            fclose($file);

            return $this->response
                ->download($filename . '.csv', $csvData)
                ->setFileName($filename . '.csv');
        }


        // =========================
        // PDF EXPORT
        // =========================

        if ($format === 'pdf') {

            $html = '<h2 style="text-align:center;">Admin Product Report</h2>';

            $html .= '<p>Generated Date: ' . date('d-m-Y H:i:s') . '</p>';

            $html .= '
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Role</th>
                    <th>Image</th>
                </tr>
            ';

            foreach ($products as $product) {

                $imageHtml = '';

                $imagePath = FCPATH . 'uploads/' . $product['product_image'];

                if (
                    !empty($product['product_image']) &&
                    file_exists($imagePath)
                ) {

                    $imageData = base64_encode(file_get_contents($imagePath));

                    $mimeType = mime_content_type($imagePath);

                    $imageHtml = '<img src="data:' . $mimeType .
                        ';base64,' . $imageData .
                        '" width="50" height="50">';
                }

                $html .= '
                <tr>
                    <td>' . esc($product['product_code']) . '</td>
                    <td>' . esc($product['product_name']) . '</td>
                    <td>' . esc($product['category']) . '</td>
                    <td>' . esc($product['price']) . '</td>
                    <td>' . esc($product['quantity']) . '</td>
                    <td>' . esc($product['status']) . '</td>
                    <td>' . esc($product['created_by']) . '</td>
                    <td>' . esc($product['role']) . '</td>
                    <td>' . $imageHtml . '</td>
                </tr>
                ';
            }

            $html .= '</table>';

            $options = new Options();

            $options->set('isRemoteEnabled', false);

            $dompdf = new Dompdf($options);

            $dompdf->loadHtml($html);

            $dompdf->setPaper('A4', 'landscape');

            $dompdf->render();

            $pdfData = $dompdf->output();

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader(
                    'Content-Disposition',
                    'attachment; filename="' . $filename . '.pdf"'
                )
                ->setBody($pdfData);
        }


        // =========================
        // EXCEL EXPORT
        // =========================

        if ($format === 'xlsx') {

            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Admin Product Report');

            $sheet->setCellValue(
                'A2',
                'Generated Date: ' . date('d-m-Y H:i:s')
            );

            // Excel headings
            $headers = [
                'Product Code',
                'Product Name',
                'Image',
                'Category',
                'Description',
                'Price',
                'Quantity',
                'Status',
                'Created Date',
                'Created By',
                'Role'
            ];

            $sheet->fromArray($headers, null, 'A4');

            $row = 5;

            foreach ($products as $product) {

                // Insert product details
                $sheet->fromArray([
                    $product['product_code'],
                    $product['product_name'],
                    '',
                    $product['category'],
                    $product['description'],
                    $product['price'],
                    $product['quantity'],
                    $product['status'],
                    $product['created_at'],
                    $product['created_by'],
                    $product['role']
                ], null, 'A' . $row);

                // Get image path
                $imagePath = FCPATH . 'uploads/' . $product['product_image'];

                // Insert image into Excel
                if (
                    !empty($product['product_image']) &&
                    file_exists($imagePath)
                ) {

                    $drawing = new Drawing();

                    $drawing->setPath($imagePath);

                    $drawing->setCoordinates('C' . $row);

                    $drawing->setHeight(50);

                    $drawing->setWorksheet($sheet);
                }

                // Adjust row height
                $sheet->getRowDimension($row)->setRowHeight(40);

                $row++;
            }

            // Adjust column widths
            $sheet->getColumnDimension('A')->setWidth(18);
            $sheet->getColumnDimension('B')->setWidth(22);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(18);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(12);
            $sheet->getColumnDimension('H')->setWidth(15);

            // Generate Excel file
            $writer = new Xlsx($spreadsheet);

            ob_start();

            $writer->save('php://output');

            $excelData = ob_get_clean();

            return $this->response
                ->setHeader(
                    'Content-Type',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                )
                ->setHeader(
                    'Content-Disposition',
                    'attachment; filename="' . $filename . '.xlsx"'
                )
                ->setBody($excelData);
        }


        // Invalid format
        return $this->response
            ->setStatusCode(400)
            ->setBody('Invalid export format.');
    }
    
    
}