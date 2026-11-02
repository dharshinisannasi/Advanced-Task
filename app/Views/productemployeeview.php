<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body class="overflow-x-hidden">
        <div class="row">
            <div class="col-2 m-5 shadow-lg bg-body-secondary" style="height:700px;">
                <h5 class="text-success text-center" id="validmessage"></h5>
                <h5 class="text-danger text-center" id="invalidmessage"></h5>
                <h3 class="text-center mb-3 mt-4">Product Form</h3>
                <label for="Productcode" class="form-label text-primary">Product Code</label>
                <input type="text" id="Productcode" class="form-control" placeholder="Enter the Productcode">
                <p id="Productcodeerror" class="text-danger"></p>
                <label for="Productname" class="form-label text-primary">Product Name</label>
                <input type="text" id="Productname" class="form-control" placeholder="Enter the Productname">
                <p id="Productnameerror" class="text-danger"></p>
                
                <label for="Category" class="form-label text-primary">Category</label>
                <input type="text" id="Category" class="form-control" placeholder="Enter the Category">
                <p id="Categoryerror" class="text-danger"></p>
                <label for="Description" class="form-label text-primary">Description</label>
                <textarea id="Description" class="form-control" placeholder="Enter the Description"></textarea>
                <p id="Descriptionerror" class="text-danger"></p>
                <label for="Price" class="form-label text-primary">Price</label>
                <input type="text" id="Price" class="form-control" placeholder="Enter the Price">
                <p id="Priceerror" class="text-danger"></p>
                <label for="Quantity" class="form-label text-primary">Quantity</label>
                <input type="text" id="Quantity" class="form-control" placeholder="Enter the Quantity">
                <p id="Quantityerror" class="text-danger"></p>
                <label for="Status" class="form-label text-primary">Status</label>
                <input type="text" id="Status" class="form-control" placeholder="Enter the Status">
                <p id="Statuserror" class="text-danger"></p>
                <label for="Productphoto" class="form-label text-primary">Upload image</label>
                <input type="file" id="Productphoto" class="form-control">
                <p id="Productphotoerror" class="text-danger"></p>
                <button type="submit" class="btn btn-primary mt-3 mb-3 form-control" onclick="Register()">Register</button>
            </div>
            <div class="col-9 mt-5">
                <div class="d-flex justify-content-between mb-3">
                    <h2>Dynamic Employee Product Table</h2>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger" onclick="clearemployee()">Clear all</button>

                        <button type="button" class="btn btn-outline-danger" onclick="exportProducts('pdf')">PDF</button>

                        <button type="button" class="btn btn-outline-danger" onclick="exportProducts('csv')">CSV</button>

                        <button type="button" class="btn btn-outline-danger" onclick="exportProducts('xlsx')">Excel</button>

                        <button type="button" class="btn btn-success">
                            Total counts: <span id="totalrecords"></span>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-5 justify-content-between ">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <label for="pageLimit" class="form-label mb-0">
                            Show:
                        </label>

                        <select id="pageLimit" class="form-select" style="width: 100px;"
                                onchange="createemployeetable(0)">
                            <option value="all" selected>All</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>

                        <span>entries</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <select id="categoryDropdown" class="form-select w-50">
                            <option value="">Select Category</option>
                        </select>
                        <select id="statusDropdown" class="form-select w-50">
                            <option value="">Select Status</option>
                        </select>
                        <select id="dateDropdown" class="form-select w-50">
                            <option value="">Select Date</option>
                        </select>
                         <select id="productcodeDropdown" class="form-select w-50">
                            <option value="">Select Productcode</option>
                        </select>
                        <select id="productDropdown" class="form-select w-50">
                            <option value="">Select product</option>
                        </select>
                        <input type="text"
                            id="searchuser"
                            class="form-control w-50"
                            placeholder="Search Something">

                        <button type="button" id="searchBtn" class="btn btn-primary">
                            Search
                        </button>
                    </div>
                </div>
                <div id="employeetable"></div>
            </div>
        </div>
         <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee Product Details</h5>
                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row ">
                            <div class=" border border-dark-subtle shadow-lg">
                                <div class="bg-primary-subtle">
                                    <h2 class="text-center">Update Employee Product Details</h2>
                                </div>
                                <div>
                                    <label for="editProductcode" class="form-label">Productcode</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editProductcode">
                                </div>
                                <div>
                                    <label for="editProductname" class="form-label">Productname</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editProductname">
                                </div>
                                
                                <div>
                                    <label for="editCategory" class="form-label">Category</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editCategory">
                                </div>
                                <div>
                                    <label for="editDescription" class="form-label">Description</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editDescription">
                                </div>
                                <div class="me-2">
                                    <label for="editPrice" class="form-label">Price</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editPrice">
                                </div>
                                <div class="ms-2">
                                    <label for="editQuantity" class="form-label">Quantity</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editQuantity">
                                </div>
                                <div class="ms-2">
                                    <label for="editStatus" class="form-label">status</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editStatus">
                                </div>
                                <div class="mt-3 mb-3 ms-2">
                                    <button id="updateButton" type="submit" class="btn btn-primary  px-5 form-control"  onclick="updateemployee()">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmTitle">
                            Confirm Action
                        </h5>
                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="confirmMessage">
                        Are you sure?
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button"
                                class="btn btn-primary"
                                id="confirmOk">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let confirmCallback = null;
        function showConfirmPopup(title, message, callback) {
            document.getElementById("confirmTitle").textContent = title;
            document.getElementById("confirmMessage").textContent = message;
            confirmCallback = callback;
            const modalElement = document.getElementById("confirmModal");
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();
        }
        function showMessagePopup(title, message) {
            $("#confirmTitle").text(title);
            $("#confirmMessage").text(message);
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById("confirmModal")
            ).show();
            $("#confirmOk").off("click.message").on("click.message", function () {
                bootstrap.Modal.getInstance(
                    document.getElementById("confirmModal")
                ).hide();
            });
        }
        document.getElementById("confirmOk").addEventListener("click", function () {
            const callback = confirmCallback;
            confirmCallback = null;
            const modal = bootstrap.Modal.getOrCreateInstance(
                document.getElementById("confirmModal")
            );
            modal.hide();
            if (callback) {
                callback();
            }
        });
        var __baseurl= "<?=base_url() ?>";
        var employee_details=[];
        createemployeetable();
        loadcategoryDropdown();
        loadstatusDropdown();
        loaddateDropdown();
        loadproductDropdown();
        loadproductcodeDropdown();
        function Register(){
            var productcode= $("#Productcode").val();
            //console.log(productcode);
            var productname= $("#Productname").val();
            //console.log(productname);
            var category= $("#Category").val();
            //console.log(category);
            var description= $("#Description").val();
            //console.log(description);
            var price= $("#Price").val();
            //console.log(price);
            var quantity= $("#Quantity").val();
            //console.log(quantity);
            var status= $("#Status").val();
            //console.log(status);
            var photo = $("#Productphoto")[0].files[0];
            //console.log(photo);
            $("#Productcodeerror").html("");
            $("#Productnameerror").html("");
            $("#Categoryerror").html("");
            $("#Descriptionerror").html("");
            $("#Priceerror").html("");
            $("#Quantityerror").html("");
            $("#Statuserror").html("");
            $("#Productphotoerror").html("");
            var check=true;
            if (productcode.trim()===""){
                $("#Productcodeerror").html("Field empty");
                check= false;
            }
            if (productname.trim()===""){
                $("#Productnameerror").html("Field empty");
                check= false;
            }
            if (category.trim()===""){
                $("#Categoryerror").html("Field empty");
                check= false;
            }
            if (description.trim()===""){
                $("#Descriptionerror").html("Field empty");
                check= false;
            }
            if (price.trim()===""){
                $("#Priceerror").html("Field empty");
                check= false;
            }
            if (quantity.trim()===""){
                $("#Quantityerror").html("Field empty");
                check= false;
            }
            if (status.trim()===""){
                $("#Statuserror").html("Field empty");
                check= false;
            }
            if (!photo){
                $("#Productphotoerror").html("Field empty");
                check= false;
            }
            if (check===false){
                return false;
            }

            var fielddata= new FormData();
            fielddata.append("productcode",productcode);
            fielddata.append("productname",productname);
            fielddata.append("category",category);
            fielddata.append("description",description);
            fielddata.append("price",price);
            fielddata.append("quantity",quantity);
            fielddata.append("status",status);
            fielddata.append("photo",photo);
            $.ajax({
                url: __baseurl + "insertemployeeproductdata",
                type: "POST",
                data: fielddata,
                contentType:false,
                processData:false,
                dataType: "json",
                success: function(response){
                    
                    if (response.status == false) {
                        $("#validmessage").html("");
                        $("#invalidmessage").html(response.message);
                    } else {
                        $("#invalidmessage").html("");
                        $("#validmessage").html("Product inserted Successfully");
                        createemployeetable();
                        loadcategoryDropdown();
                        loadstatusDropdown();
                        loaddateDropdown();
                        loadproductDropdown();
                        loadproductcodeDropdown();
                        showMessagePopup("Success","Product inserted Successfully");
                    }
                }
                
            })
        }
        function editemployeedata(id){
             var product = product_details.find(function(product) {
                return product.id == id;
            });
            $("#editProductcode").val(product.product_code);
            $("#editProductname").val(product.product_name);
            $("#editCategory").val(product.category);
            $("#editDescription").val(product.description);
            $("#editPrice").val(product.price);
            $("#editQuantity").val(product.quantity);
            $("#editStatus").val(product.status);
            $("#updateButton").attr("data-id", id);
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById("editModal")
            ).show();
        }
        function updateemployee(id){
            var id = $("#updateButton").attr("data-id");
            var productcode = $("#editProductcode").val();
            var productname = $("#editProductname").val();
            var category = $("#editCategory").val();
            var description = $("#editDescription").val();
            var price = $("#editPrice").val();
            var quantity= $("#editQuantity").val();
            var status = $("#editStatus").val();
             if ( productcode.trim() === "" ||
                productname.trim() === "" ||
                category.trim() === ""||
                description.trim() === ""||price.trim() === ""||quantity.trim() === ""||
                status.trim() === ""){
                alert("Please fill all fields");
                return;
            }
            var formData = new FormData();
            formData.append("id", id);
            formData.append("productcode", productcode);
            formData.append("productname", productname);
            formData.append("category",category);
            formData.append("description", description);
            formData.append("price", price);
            formData.append("quantity", quantity);
            formData.append("status", status);
            $.ajax({
                url: __baseurl + "updateproductdata",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        createemployeetable();
                        loadcategoryDropdown();
                        loadstatusDropdown();
                        loaddateDropdown();
                        loadproductDropdown();
                        loadproductcodeDropdown();
                        bootstrap.Modal.getOrCreateInstance(
                            document.getElementById("editModal")
                        ).hide();
                        showMessagePopup("Success","Updated successfully")
                    } else {
                        showMessagePopup("error","not updated");
                    }
                }
            });
        }
        function deleteemployee(id){
            showConfirmPopup(
                "Confirm Delete",
                "Are you sure you want to delete this Product?",
                function () {
                    $.ajax({
                        url: __baseurl+ "deleteproductdata",
                        type: "POST",
                        data:{
                            id: id,
                        },
                        dataType: "json",
                        success: function(response){
                            if(response.status){
                                showMessagePopup("Success","Product deleted successfully");
                                createemployeetable();
                                loadcategoryDropdown();
                                loadstatusDropdown();
                                loaddateDropdown();
                                loadproductDropdown();
                                loadproductcodeDropdown();
                            }
                            else{
                               showMessagePopup("error","Not deleted");
                            }
                        }
                    })
                }
            );
        }
        function clearemployee(){
            showConfirmPopup(
                "Confirm Delete",
                "Are you sure you want to delete all products?",
                function () {
                    $.ajax({
                        url: __baseurl+"clearproductdata",
                        type: "POST",
                        dataType: "json",
                        success: function(response){
                            if(response.status){
                                createemployeetable();
                                loadcategoryDropdown();
                                loadstatusDropdown();
                                loaddateDropdown();
                                loadproductDropdown();
                                loadproductcodeDropdown();
                                showMessagePopup("Success","All products deleted successfully");
                            }
                            else{
                               showMessagePopup("error","Not deleted");
                            }
                        }
                    })
                }
            );
        }
        function createemployeetable(page=0){
            var limit = $("#pageLimit").val();
            var searchuser = $("#searchuser").val().trim();
            var category = $("#categoryDropdown").val();
            var status= $("#statusDropdown").val();
            var date= $("#dateDropdown").val();
            var code = $("#productcodeDropdown").val();
            var name = $("#productDropdown").val();
            // console.log(date);
            $.ajax({
                url: __baseurl+ "selectemployeeproductdata",
                type: "POST",
                data: {
                    page: page,
                    limit: limit,
                    searchuser: searchuser,
                    category:category,
                    status: status,
                    date: date,
                    code: code,
                    name: name,
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
                        html +='<th>Description</th>';
                        html +=`<th>Price</th>`;
                        html +=`<th>Quantity</th>`;
                        html +='<th>Role</th>';
                        html +=`<th>Status</th>`;
                        html +=`<th>Created_at</th>`;
                        html +=`<th>Created_by</th>`;
                        html +=`<th>Updated_at</th>`;
                        html +=`<th>Action</th>`;
                        html +=`</tr>`;
                        product_details.forEach((products, index) => {
                            html +=`<tr id="row${products.id}">`;
                            html +=`<td class="${products.id}">${index+1}</td>`;
                            html +=`<td class="${products.id}">${products.product_code}</td>`;
                            html +=`<td class="${products.id}">${products.product_name}</td>`;
                            html +=`<td class="${products.id}">${products.category}</td>`;
                            html +=`<td class="${products.id}">${products.description}</td>`;
                            html +=`<td class="${products.id}">${products.price}</td>`;
                            html +=`<td class="${products.id}">${products.quantity}</td>`;
                            html +=`<td class="${products.id}">${products.role}</td>`;
                            html +=`<td class="${products.id}">${products.status}</td>`;
                            html +=`<td class="${products.id}">${products.created_at}</td>`;
                            html +=`<td class="${products.id}">${products.created_by}</td>`;
                            html +=`<td class="${products.id}">${products.updated_at}</td>`;
                            html += `<td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning"
                                        onclick="editemployeedata(${products.id})">
                                        Edit
                                    </button>

                                    <button class="btn btn-danger"
                                        onclick="deleteemployee(${products.id})">
                                        Delete
                                    </button>
                                    <input id="hideinput${products.id}" value="0" type="hidden">
                                    <a id="hide${products.id}" class="btn btn-outline-secondary" href="${__baseurl}viewproductpage/${products.id}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn ${products.status == 'Active' ? 'btn-secondary' : 'btn-success'}"
                                        onclick="changeStatus(${products.id}, '${products.status}')">
                                        ${products.status == 'Active' ? 'Deactivate' : 'Activate'}
                                    </button>
                                </div>
                            </td>`;
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
                                        onclick="createemployeetable(${i})">
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
        function changeStatus(id, status) {
            var newStatus = status == "Active" ? "Inactive" : "Active";

            showConfirmPopup(
                "Confirm Status Change",
                "Are you sure you want to " + newStatus.toLowerCase() + " this Product?",
                function () {
                    $.ajax({
                        url: __baseurl + "changeandupdatestatus",
                        type: "POST",
                        data: {
                            id: id,
                            status: newStatus
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.status) {
                                createemployeetable();
                                showMessagePopup(
                                    "Success",
                                    "Product status updated successfully"
                                );
                            } else {
                                showMessagePopup("Error", "Status update failed");
                            }
                        },
                        error: function () {
                            showMessagePopup("Error", "Something went wrong");
                        }
                    });
                }
            );
        }
        function loadproductcodeDropdown() {
            $.ajax({
                url: __baseurl + "getproductcodes",
                type: "GET",
                dataType: "json",
                success: function(productcodes) {
                    $("#productcodeDropdown").html(
                        '<option value="">Select Product Code</option>'
                    );

                    productcodes.forEach(function(products) {
                        $("#productcodeDropdown").append(
                            `<option value="${products.product_code}">
                                ${products.product_code}
                            </option>`
                        );
                    });
                }
            });
        }
        function loadstatusDropdown() {
            $.ajax({
                url: __baseurl + "getstatus",
                type: "GET",
                dataType: "json",
                success: function(statusnames) {
                    $("#statusDropdown").html(
                        '<option value="">Select Status</option>'
                    );

                    statusnames.forEach(function(products) {
                        $("#statusDropdown").append(
                            `<option value="${products.status}">
                                ${products.status}
                            </option>`
                        );
                    });
                }
            });
        }
        function loadcategoryDropdown() {
            $.ajax({
                url: __baseurl + "getcategory",
                type: "GET",
                dataType: "json",
                success: function(category) {
                    $("#categoryDropdown").html(
                        '<option value="">Select Category</option>'
                    );

                    category.forEach(function(products) {
                        $("#categoryDropdown").append(
                            `<option value="${products.category}">
                                ${products.category}
                            </option>`
                        );
                    });
                }
            });
        }
        function loadproductDropdown() {
            $.ajax({
                url: __baseurl + "getproduct",
                type: "GET",
                dataType: "json",
                success: function(product) {
                    $("#productDropdown").html(
                        '<option value="">Select Product</option>'
                    );

                    product.forEach(function(products) {
                        $("#productDropdown").append(
                            `<option value="${products.product_name}">
                                ${products.product_name}
                            </option>`
                        );
                    });
                }
            });
        }
        function loaddateDropdown() {
            $.ajax({
                url: __baseurl + "getdates",
                type: "GET",
                dataType: "json",
                success: function(dates) {
                    //console.log(dates);

                    $("#dateDropdown").html(
                        '<option value="">Select Date</option>'
                    );
                    //console.log("Dropdown count:", $("#dateDropdown").length);

                    dates.forEach(function(products) {
                        //console.log(products.created_at);
                        $("#dateDropdown").append(
                            `<option value="${products.created_at}">
                                ${products.created_at}
                            </option>`
                        );
                    });
                    //console.log($("#dateDropdown").html());
                }
            });
        }
        

        $("#searchBtn").on("click", function() {
            createemployeetable();
        });
        $("#productcodeDropdown").on("change", function() {
            createemployeetable();
        });
        $("#statusDropdown").on("change", function() {
            createemployeetable();
        });
        $("#dateDropdown").on("change", function() {
            createemployeetable();
        });
        $("#categoryDropdown").on("change", function() {
            createemployeetable();
        });
        $("#productDropdown").on("change", function() {
            createemployeetable();
        });


        function exportProducts(format) {

            var searchuser = $("#searchuser").val().trim();
            var category = $("#categoryDropdown").val();
            var status = $("#statusDropdown").val();
            var date = $("#dateDropdown").val();
            var code = $("#productcodeDropdown").val();
            var name = $("#productDropdown").val();

            var params = new URLSearchParams({
                format: format,
                searchuser: searchuser,
                category: category,
                status: status,
                date: date,
                code: code,
                name: name
            });

            window.location.href =
                __baseurl + "exportemployeeproducts?" + params.toString();
        }

        
    </script>
</html>