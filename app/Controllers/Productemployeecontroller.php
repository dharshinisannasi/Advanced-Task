<?php
namespace App\Controllers;
use App\Models\product_model;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class Productemployeecontroller extends BaseController {
    public function __construct() {
        $this->product_model = new product_model();
    }
    public function index(){
         if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return redirect()->to(base_url('loginpage'));
        }

        return view('productemployeeview');
    }
     
    public function insertemployeeproductdata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
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
                'message' => 'Price must be non-negative and quantity must be a non-negative integer'
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
        
        $photoname = $photo->getRandomName();
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

        $result= $this->product_model->insertemployeeproduct($data);
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
    public function selectemployeeproductdata()
    {   
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
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
        $result = $this->product_model->selectemployeeproduct($page, $limit,$searchuser,$name,$status,$date,$code,$category);
       
        return $this->response->setJSON([
            'status' => true,
            'totalrecords' => $result['totalrecords'],
            'totalpages' => $result['totalpages'],
            'limit' => $result['limit'],
            'data' => $result['data']
        ]);
    }
    public function getproduct()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $createdBy=session()->get('username') ?? '';
        $product = $this->product_model->getProduct($createdBy);

        return $this->response->setJSON($product);
    }
    public function getproductcodes()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $createdBy=session()->get('username') ?? '';
        $productcodes = $this->product_model->getProductcode($createdBy);

        return $this->response->setJSON($productcodes);
    }
    public function getcategory()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $createdBy=session()->get('username') ?? '';
        $category = $this->product_model->getCategory($createdBy);

        return $this->response->setJSON($category);
    }
    public function getstatus()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $createdBy=session()->get('username') ?? '';
        $status = $this->product_model->getstatusNames($createdBy);

        return $this->response->setJSON($status);
    }
    public function getdates()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $createdBy=session()->get('username') ?? '';
        $date = $this->product_model->getDate($createdBy);
        
        return $this->response->setJSON($date);
    }
    public function updateproductdata(){
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
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
        
        
        $username = session()->get('username');
        if (empty($id) || empty($username)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID or session is missing'
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
        $result = $this->product_model->updateproduct($data, $id, $username);


        return $this->response->setJSON([
            'status'  => $result,
            'message' => $result ? 'Updated successfully' : 'Update failed'
        ]);
    }

    public function deleteproductdata()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $id = $this->request->getPost('id');
        $username = session()->get('username');

        if (empty($id) || empty($username)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Product ID or session is missing'
            ]);
        }

        $result = $this->product_model->deleteproduct($id, $username);

        return $this->response->setJSON([
            'status'  => $result,
            'message' => $result ? 'Deleted successfully' : 'Delete failed'
        ]);
    }

    public function clearproductdata()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }
        $username = session()->get('username');

        if (empty($username)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Session is missing'
            ]);
        }

        $result = $this->product_model->clearproduct($username);

        return $this->response->setJSON([
            'status'  => $result,
            'message' => $result ? 'Products cleared successfully' : 'Clear failed'
        ]);
    }
    public function changeandupdatestatus()
    {
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
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
        $username = session()->get('username');

        if (empty($id) || empty($username) ||
            !in_array($status, ['Active', 'Inactive'], true)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid product ID or status'
            ]);
        }

        $result = $this->product_model->updateproduct(
            ['status' => $status],
            $id,
            $username
        );

        return $this->response->setJSON([
            'status'  => $result,
            'message' => $result ? 'Status updated successfully' : 'Status update failed'
        ]);
    }

    public function exportemployeeproducts()
    {
        // Check employee login
        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Employee'
        ) {
            return redirect()->to(base_url('loginpage'));
        }

        // Get filters from URL
        $searchuser = $this->request->getGet('searchuser') ?? '';
        $name       = $this->request->getGet('name') ?? '';
        $status     = $this->request->getGet('status') ?? '';
        $date       = $this->request->getGet('date') ?? '';
        $code       = $this->request->getGet('code') ?? '';
        $category   = $this->request->getGet('category') ?? '';

        $username = session()->get('username');

        // Get all matching products without pagination
        $products = $this->product_model->getemployeeproductsforexport(
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

        $filename = 'Employee_Products_' . date('Y-m-d');

        // CSV EXPORT
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
                'Created By'
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
                    $product['created_by']
                ]);
            }

            rewind($file);
            $csvData = stream_get_contents($file);
            fclose($file);

            return $this->response
                ->download($filename . '.csv', $csvData)
                ->setFileName($filename . '.csv');
        }

        // PDF EXPORT
        if ($format === 'pdf') {

            $html = '<h2 style="text-align:center;">Employee Product Report</h2>';
            $html .= '<p>Generated Date: ' . date('d-m-Y H:i:s') . '</p>';

            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            $html .= '<tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Product Image</th>
            </tr>';

            foreach ($products as $product) {

                $imageHtml = 'No Image';

                $imagePath = FCPATH . 'uploads/' . $product['product_image'];

                if (
                    !empty($product['product_image']) &&
                    file_exists($imagePath)
                ) {
                    $imageType = mime_content_type($imagePath);
                    $imageData = base64_encode(file_get_contents($imagePath));

                    $imageHtml = '<img src="data:' . $imageType .
                        ';base64,' . $imageData .
                        '" width="60" height="50">';
                }

                $html .= '<tr>
                    <td>' . esc($product['product_code']) . '</td>
                    <td>' . esc($product['product_name']) . '</td>
                    <td>' . esc($product['category']) . '</td>
                    <td>' . esc($product['price']) . '</td>
                    <td>' . esc($product['quantity']) . '</td>
                    <td>' . esc($product['status']) . '</td>
                    <td>' . $imageHtml . '</td>
                </tr>';
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
        // EXCEL EXPORT
        if ($format === 'xlsx') {

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Employee Product Report');
            $sheet->setCellValue('A2', 'Generated Date: ' . date('d-m-Y H:i:s'));

            $headers = [
                'Product Code',
                'Product Name',
                'Category',
                'Description',
                'Price',
                'Quantity',
                'Status',
                'Created Date',
                'Created By',
                'Product Image'
            ];

            $sheet->fromArray($headers, null, 'A4');

            $row = 5;

            foreach ($products as $product) {

                $sheet->fromArray([
                    $product['product_code'],
                    $product['product_name'],
                    $product['category'],
                    $product['description'],
                    $product['price'],
                    $product['quantity'],
                    $product['status'],
                    $product['created_at'],
                    $product['created_by']
                ], null, 'A' . $row);

                $imagePath = FCPATH . 'uploads/' . $product['product_image'];

                if (
                    !empty($product['product_image']) &&
                    file_exists($imagePath)
                ) {
                    $drawing = new Drawing();
                    $drawing->setPath($imagePath);
                    $drawing->setCoordinates('J' . $row);
                    $drawing->setHeight(50);
                    $drawing->setWorksheet($sheet);
                }

                $sheet->getRowDimension($row)->setRowHeight(40);

                $row++;
            }

            $sheet->getColumnDimension('J')->setWidth(18);

            $writer = new Xlsx($spreadsheet);

            ob_start();
            $writer->save('php://output');
            $excelData = ob_get_clean();

            return $this->response
                ->download($filename . '.xlsx', $excelData)
                ->setFileName($filename . '.xlsx');
        }
    }
}