<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .sidebar {
                width: 220px;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
            }
            .main-content {
                margin-left: 220px;
            }
        </style>
    </head>
    <body>
        <?php if ($Role == "Employee") { ?>
            <div class="sidebar mt-3">
                <div class="logo">
                    <i class="bi bi-people-fill"></i>
                    <span>MyPortal</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('dashboardpage') ?>">
                            <i class="bi bi-house-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('employeeproductpage') ?>">
                            <i class="bi bi-box"></i>
                            <span>My Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('profilepage') ?>">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logoutpage') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Log out</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex justify-content-end mt-3 me-3">
                <div class="text-end">
                    <div>
                        <i class="bi bi-calendar3"></i>
                        <?= date("d-m-Y") ?>
                    </div>
                    <div>
                        <i class="bi bi-clock"></i>
                        <?= date("h:i:s A") ?>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <h1 class="text-center">Employee Dashboard</h1>
                <h6 class="text-center">
                    Welcome <?= esc($Username) ?>
                </h6>
                <div class="container col-7 bg-primary-subtle d-flex justify-content-center align-items-center mb-3 p-4">
                    <img
                        class="me-5"
                        src="https://cdn-icons-png.flaticon.com/512/6821/6821002.png"
                        style="height:100px;width:100px;"
                    >
                    <div class="me-5">
                        <h3 class="text-center">Total Products</h3>
                        <h4 class="text-center"><?= esc($product_quantity) ?></h4>
                    </div>
                    <div class="me-5">
                        <h3 class="text-center">Product Count</h3>
                        <h4 class="text-center"><?= esc($product_count) ?></h4>
                    </div>
                </div>
                <div class="d-flex justify-content-around">
                    <div>
                        <h1>My Product Table</h1>
                        <div class="mt-4 " id="employeetable"></div>
                        
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <label for="pageLimit2" class="form-label mb-0">
                                Show:
                            </label>

                            <select id="pageLimit2" class="form-select" style="width: 100px;"
                                    onchange="createtable2(0)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="all">All</option>
                            </select>

                            <span>entries</span>
                        </div>

                    </div>
                    <div>
                        <h1>Product List</h1>
                        <div class="mt-4" id="admintable"></div> 
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <label for="pageLimit3" class="form-label mb-0">
                                Show:
                            </label>

                            <select id="pageLimit3" class="form-select" style="width: 100px;"
                                    onchange="createtable3(0)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="all">All</option>
                            </select>

                            <span>entries</span>
                        </div> 
                    </div>
                </div>
            </div>
            
        <?php } elseif ($Role == "Admin") { ?>
            <div class="sidebar mt-3">
                <div class="logo">
                    <i class="bi bi-people-fill"></i>
                    <span>MyPortal</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('dashboardpage') ?>">
                            <i class="bi bi-house-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('adminproductpage') ?>">
                            <i class="bi bi-box"></i>
                            <span>Admin Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('adminregistrationpage') ?>">
                            <i class="bi bi-box"></i>
                            <span>Admin Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('employeemanagementpage') ?>">
                            <i class="bi bi-person"></i>
                            <span>Employee Management</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('userspage') ?>">
                            <i class="bi bi-box"></i>
                            <span>User Details</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('profilepage') ?>">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logoutpage') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Log out</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex justify-content-end mt-3 me-3">
                <div class="text-end">
                    <div>
                        <i class="bi bi-calendar3"></i>
                        <?= date("d-m-Y") ?>
                    </div>
                    <div>
                        <i class="bi bi-clock"></i>
                        <?= date("h:i:s A") ?>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <h1 class="text-center">Admin Dashboard</h1>
                <h6 class="text-center">
                    Welcome <?= esc($Username) ?>
                </h6>
                <div class="container col-8 bg-primary-subtle d-flex justify-content-center align-items-center mb-3 p-4">
                    <img
                        class="me-5"
                        src="https://cdn-icons-png.flaticon.com/512/6821/6821002.png"
                        style="height:100px;width:100px;"
                    >
                    <div  class="me-5">
                        <h3 class="text-center">Total Products</h3>
                        <h4 class="text-center"><?= esc($product_quantity) ?></h4>
                    </div>
                    <div  class="me-5">
                        <h3 class="text-center">Product Count</h3>
                        <h4 class="text-center"><?= esc($product_count) ?></h4>
                    </div>
                    <div  class="me-5">
                        <h3 class="text-center">Total employee</h3>
                        <h4 class="text-center"><?=  $totalEmployees ?? 0 ?></h4>
                    </div>
                    <div  class="me-5">
                        <h3 class="text-center">Active users</h3>
                        <h4 class="text-center"><?= $activeUsers ?? 0  ?></h4>
                    </div>
                    <div  class="me-5">
                        <h3 class="text-center">Inactive users</h3>
                        <h4 class="text-center"><?= $inactiveUsers ?? 0  ?></h4>
                    </div>
                </div>
                <div class="d-flex justify-content-around">
                    <div class=" mt-4">
                        <h1 class="text-center">Product details</h1>
                        <div id="admintable"></div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <label for="pageLimit1" class="form-label mb-0">
                                Show:
                            </label>

                            <select id="pageLimit1" class="form-select" style="width: 100px;"
                                    onchange="createtable1(0)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="all">All</option>
                            </select>

                            <span>entries</span>
                        </div>
                    </div>
                    <div><?= view('users') ?></div>

                </div>
            </div>
        <?php } ?>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var __baseurl= "<?=base_url()?>"
        //alert("new")
        $(document).ready(function () {
            <?php if ($Role == "Admin") { ?>
                createtable1();
            <?php } elseif ($Role == "Employee") { ?>
                createtable2();
                createtable3();
            <?php } ?>
        });
        function createtable1(page=0){
            var limit = $("#pageLimit1").val();
            // var searchuser = $("#searchuser").val().trim();
            // var category = $("#categoryDropdown").val();
            // var status= $("#statusDropdown").val();
            // var date= $("#dateDropdown").val();
            // var code = $("#productcodeDropdown").val();
            // var name = $("#productDropdown").val();
            // var user=$("#userDropdown").val();
            // console.log(date);
            $.ajax({
                url: __baseurl+ "selectadminproductdata",
                type: "POST",
                data: {
                    page: page,
                    limit: limit,
                    // searchuser: searchuser,
                    // category:category,
                    // status: status,
                    // date: date,
                    // code: code,
                    // name: name,
                    // user: user,
                },
                dataType: "json",
                success:function(response){
                    // console.log(response);
                    if(response.status){
                        product_details=response.data;
                        //console.log(response.data);
                        
                        var html = "";
                        html += `<table class="table table-bordered table-striped">`;
                        html += `<tr>`;
                        html +=`<th>#</th>`;
                        html +=`<th>Product Code</th>`;
                        html +=`<th>Product name</th>`;
                        html +=`<th>Category</th>`; 
                        // html +='<th>Description</th>';
                        html +=`<th>Price</th>`;
                        html +=`<th>Quantity</th>`;
                        // html +='<th>Role</th>';
                        html +=`<th>Status</th>`;
                        // html +=`<th>Created_at</th>`;
                        // html +=`<th>Created_by</th>`;
                        // html +=`<th>Updated_at</th>`;
                        // html +=`<th>Action</th>`;
                        html +=`</tr>`;
                        product_details.forEach((products, index) => {
                            html +=`<tr id="row${products.id}">`;
                            html +=`<td class="${products.id}">${index+1}</td>`;
                            html +=`<td class="${products.id}">${products.product_code}</td>`;
                            html +=`<td class="${products.id}">${products.product_name}</td>`;
                            html +=`<td class="${products.id}">${products.category}</td>`;
                            // html +=`<td class="${products.id}">${products.description}</td>`;
                            html +=`<td class="${products.id}">${products.price}</td>`;
                            html +=`<td class="${products.id}">${products.quantity}</td>`;
                            // html +=`<td class="${products.id}">${products.role}</td>`;
                            html +=`<td class="${products.id}">${products.status}</td>`;
                            // html +=`<td class="${products.id}">${products.created_at}</td>`;
                            // html +=`<td class="${products.id}">${products.created_by}</td>`;
                            // html +=`<td class="${products.id}">${products.updated_at}</td>`;
                            html +=`</tr>`;
                        });
                        html += `</table>`;
                        

                        var totalpages = response.totalpages;
                        $("#totalrecords").html(response.totalrecords);
                        if (limit !== "all") {
                            for (var i = 0; i < totalpages; i++) {
                                html += `
                                    <button
                                        type="button"
                                        class="btn btn-primary me-2 ${i === page ? 'active' : ''}"
                                        onclick="createtable1(${i})">
                                        ${i + 1}
                                    </button>
                                `;
                            }
                        }
                        $("#admintable").html(html);
                        
                    }
                }

            })
        }

       function createtable2(page=0){
            var limit = $("#pageLimit2").val();
            // var searchuser = $("#searchuser").val().trim();
            // var category = $("#categoryDropdown").val();
            // var status= $("#statusDropdown").val();
            // var date= $("#dateDropdown").val();
            // var code = $("#productcodeDropdown").val();
            // var name = $("#productDropdown").val();
            // console.log(date);
            $.ajax({
                url: __baseurl+ "selectemployeeproductdata",
                type: "POST",
                data: {
                    page: page,
                    limit: limit,
                    // searchuser: searchuser,
                    // category:category,
                    // status: status,
                    // date: date,
                    // code: code,
                    // name: name,
                },
                dataType: "json",
                success:function(response){
                    // console.log(response);
                    if(response.status){
                        product_details=response.data;
                        //console.log(response.data);
                        
                        var html = "";
                        html += `<table class="table table-bordered table-striped">`;
                        html += `<tr>`;
                        html +=`<th>#</th>`;
                        html +=`<th>Product Code</th>`;
                        html +=`<th>Product name</th>`;
                        html +=`<th>Category</th>`; 
                        // html +='<th>Description</th>';
                        html +=`<th>Price</th>`;
                        html +=`<th>Quantity</th>`;
                        // html +='<th>Role</th>';
                        html +=`<th>Status</th>`;
                        // html +=`<th>Created_at</th>`;
                        // html +=`<th>Created_by</th>`;
                        // html +=`<th>Updated_at</th>`;
                        // html +=`<th>Action</th>`;
                        html +=`</tr>`;
                        product_details.forEach((products, index) => {
                            html +=`<tr id="row${products.id}">`;
                            html +=`<td class="${products.id}">${index+1}</td>`;
                            html +=`<td class="${products.id}">${products.product_code}</td>`;
                            html +=`<td class="${products.id}">${products.product_name}</td>`;
                            html +=`<td class="${products.id}">${products.category}</td>`;
                            // html +=`<td class="${products.id}">${products.description}</td>`;
                            html +=`<td class="${products.id}">${products.price}</td>`;
                            html +=`<td class="${products.id}">${products.quantity}</td>`;
                            // html +=`<td class="${products.id}">${products.role}</td>`;
                            html +=`<td class="${products.id}">${products.status}</td>`;
                            // html +=`<td class="${products.id}">${products.created_at}</td>`;
                            // html +=`<td class="${products.id}">${products.created_by}</td>`;
                            // html +=`<td class="${products.id}">${products.updated_at}</td>`;
                                
                            html +=`</tr>`;
                        });
                        html += `</table>`;
                        

                        var totalpages = response.totalpages;
                        $("#totalrecords").html(response.totalrecords);
                        if (limit !== "all") {
                            for (var i = 0; i < totalpages; i++) {
                                html += `
                                    <button
                                        type="button"
                                        class="btn btn-primary me-2 ${i === page ? 'active' : ''}"
                                        onclick="createtable2(${i})">
                                        ${i + 1}
                                    </button>
                                `;
                            }
                        }
                        $("#employeetable").html(html);
                        
                    }
                }

            })
        }
        function createtable3(page = 0) {
            var limit = $("#pageLimit3").val();
            // var searchuser = $("#searchuser").val().trim();
            // var category = $("#categoryDropdown").val();
            // var status= $("#statusDropdown").val();
            // var date= $("#dateDropdown").val();
            // var code = $("#productcodeDropdown").val();
            // var name = $("#productDropdown").val();
            // var user=$("#userDropdown").val();
            // console.log(date);
            $.ajax({
                url: __baseurl+ "selectemployeeproductdata",
                type: "POST",
                data: {
                    page: page,
                    limit: limit,
                    // searchuser: searchuser,
                    // category:category,
                    // status: status,
                    // date: date,
                    // code: code,
                    // name: name,
                    // user: user,
                },
                dataType: "json",
                success:function(response){
                    // console.log(response);
                    if(response.status){
                        product_details=response.data;
                        //console.log(response.data);
                        
                        var html = "";
                        html += `<table class="table table-bordered table-striped">`;
                        html += `<tr>`;
                        html +=`<th>#</th>`;
                        html +=`<th>Product Code</th>`;
                        html +=`<th>Product name</th>`;
                        html +=`<th>Category</th>`; 
                        // html +='<th>Description</th>';
                        // html +=`<th>Price</th>`;
                        // html +=`<th>Quantity</th>`;
                        // html +='<th>Role</th>';
                        // html +=`<th>Status</th>`;
                        // html +=`<th>Created_at</th>`;
                        // html +=`<th>Created_by</th>`;
                        // html +=`<th>Updated_at</th>`;
                        // html +=`<th>Action</th>`;
                        html +=`</tr>`;
                        product_details.forEach((products, index) => {
                            html +=`<tr id="row${products.id}">`;
                            html +=`<td class="${products.id}">${index+1}</td>`;
                            html +=`<td class="${products.id}">${products.product_code}</td>`;
                            html +=`<td class="${products.id}">${products.product_name}</td>`;
                            html +=`<td class="${products.id}">${products.category}</td>`;
                            // html +=`<td class="${products.id}">${products.description}</td>`;
                            // html +=`<td class="${products.id}">${products.price}</td>`;
                            // html +=`<td class="${products.id}">${products.quantity}</td>`;
                            // html +=`<td class="${products.id}">${products.role}</td>`;
                            // html +=`<td class="${products.id}">${products.status}</td>`;
                            // html +=`<td class="${products.id}">${products.created_at}</td>`;
                            // html +=`<td class="${products.id}">${products.created_by}</td>`;
                            // html +=`<td class="${products.id}">${products.updated_at}</td>`;
                            html +=`</tr>`;
                        });
                        html += `</table>`;
                        

                        var totalpages = response.totalpages;
                        $("#totalrecords").html(response.totalrecords);
                        if (limit !== "all") {
                            for (var i = 0; i < totalpages; i++) {
                                html += `
                                    <button
                                        type="button"
                                        class="btn btn-primary me-2 ${i === page ? 'active' : ''}"
                                        onclick="createtable3(${i})">
                                        ${i + 1}
                                    </button>
                                `;
                            }
                        }
                        $("#admintable").html(html);
                        
                    }
                }

            })
        }
        

    </script>
</html>