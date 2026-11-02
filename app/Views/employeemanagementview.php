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
                <h3 class="text-center mb-3 mt-4">Employee Registration Form</h3>
                <label for="Fullname" class="form-label text-primary">Full Name</label>
                <input type="text" id="Fullname" class="form-control" placeholder="Enter your Fullname">
                <p id="fullnameerror" class="text-danger"></p>
                <label for="Username" class="form-label text-primary">UserName</label>
                <input type="text" id="Username" class="form-control" placeholder="Enter the Username">
                <p id="usernameerror" class="text-danger"></p>
                <label for="Emailaddress" class="form-label text-primary">Email Address</label>
                <input type="email" id="Emailaddress" class="form-control" placeholder="Enter the Email">
                <p id="emailerror" class="text-danger"></p>
                <label for="Mobilenumber" class="form-label text-primary">Mobile Number</label>
                <input type="text" id="Mobilenumber" class="form-control" placeholder="Enter the Mobilenumber">
                <p id="mobilenumbererror" class="text-danger"></p>
                <label for="Password" class="form-label text-primary">Password</label>
                <input type="password" id="Password" class="form-control" placeholder="Enter the Password">
                <p id="passworderror" class="text-danger"></p>
                <label for="Confirmpassword" class="form-label text-primary">Confirm Password</label>
                <input type="password" id="Confirmpassword" class="form-control" placeholder="Re-enter the Password">
                <p id="confirmpassworderror" class="text-danger"></p>
                <label for="Profilephoto" class="form-label text-primary">Upload image</label>
                <input type="file" id="Profilephoto" class="form-control">
                <p id="profileerror" class="text-danger"></p>
                <button type="submit" class="btn btn-primary mt-3 mb-3 form-control" onclick="Register()">Register</button>
            </div>
            <div class="col-9 mt-5">
                <div class="d-flex justify-content-between mb-3">
                    <h2>Dynamic Employee Table</h2>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-danger" onclick="clearemployee()">Clear all</button>
                        <button type="button" class="btn btn-outline-danger" onclick="exportemployee('pdf')">
                            PDF
                        </button>

                        <button type="button" class="btn btn-outline-danger" onclick="exportemployee('csv')">
                            CSV
                        </button>

                        <button type="button" class="btn btn-outline-danger" onclick="exportemployee('xlsx')">
                            Excel
                        </button>
                        <button type="button" class="btn btn-success">Total counts: <span id="totalrecords" ></span></button>
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
                        <select id="userDropdown" class="form-select w-50">
                            <option value="">Select Users</option>
                        </select>
                        <select id="statusDropdown" class="form-select w-50">
                            <option value="">Select Status</option>
                        </select>
                        <select id="dateDropdown" class="form-select w-50">
                            <option value="">Select Date</option>
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
                        <h5 class="modal-title">Edit Employee Details</h5>
                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row ">
                            <div class=" border border-dark-subtle shadow-lg">
                                <div class="bg-primary-subtle">
                                    <h2 class="text-center">Update Employee Details</h2>
                                </div>
                                <div>
                                    <label for="editEmployeeFullname" class="form-label">Fullname</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editEmployeeFullname">
                                </div>
                                <div>
                                    <label for="editEmployeeusername" class="form-label">Username</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editEmployeeusername">
                                </div>
                                <div class="me-2">
                                    <label for="editEmployeemobilenumber" class="form-label">Mobilenumber</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editEmployeemobilenumber">
                                </div>
                                <div class="ms-2">
                                    <label for="editEmployeeemail" class="form-label">Email</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editEmployeeemail">
                                </div>
                                <div class="ms-2">
                                    <label for="editEmployeestatus" class="form-label">status</label>
                                    <input class="border-dark-subtle form-control" type="text" id="editEmployeestatus">
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
        loaduserDropdown();
        loadstatusDropdown();
        loaddateDropdown();
        function Register(){
            var fullname= $("#Fullname").val();
            //console.log(fullname);
            var username= $("#Username").val();
            //console.log(username);
            var email= $("#Emailaddress").val();
            //console.log(email);
            var mobilenumber= $("#Mobilenumber").val();
            //console.log(mobilenumber);
            var password= $("#Password").val();
            //console.log(password);
            var confirmpassword= $("#Confirmpassword").val();
            //console.log(confirmpassword);
            var photo = $("#Profilephoto")[0].files[0];
            //console.log(photo);
            $("#fullnameerror").html("");
            $("#usernameerror").html("");
            $("#emailerror").html("");
            $("#mobilenumbererror").html("");
            $("#passworderror").html("");
            $("#confirmpassworderror").html("");
            $("#profileerror").html("");
            var check=true;
            var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (fullname.trim()===""){
                $("#fullnameerror").html("Field empty");
                check= false;
            }
            if (username.trim()===""){
                $("#usernameerror").html("Field empty");
                check= false;
            }
            if (email.trim()===""){
                $("#emailerror").html("Field empty");
                check= false;
            }
            else if (!pattern.test(email)){
                $("#emailerror").html("Email is invalid");
                check=false;
            }
            if (mobilenumber.trim()===""){
                $("#mobilenumbererror").html("Field empty");
                check= false;
            }
            if (password.trim()===""){
                $("#passworderror").html("Field empty");
                check= false;
            }
            if (confirmpassword.trim()===""){
                $("#confirmpassworderror").html("Field empty");
                check= false;
            }
            else if (password !== confirmpassword) {
                $("#confirmpassworderror").html("Passwords do not match");
                check = false;
            }
            if (!photo){
                $("#profileerror").html("Field empty");
                check= false;
            }
            if (check===false){
                return false;
            }

            var fielddata= new FormData();
            fielddata.append("fullname",fullname);
            fielddata.append("username",username);
            fielddata.append("email",email);
            fielddata.append("password",password);
            fielddata.append("mobilenumber",mobilenumber);
            fielddata.append("confirmpassword",confirmpassword);
            fielddata.append("photo",photo);
            $.ajax({
                url: __baseurl + "insertemployeedata",
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
                        $("#validmessage").html("Registration Completed Successfully");
                        createemployeetable();
                        loaduserDropdown();
                        loadstatusDropdown();
                        loaddateDropdown();
                        showMessagePopup("Success","Employee inserted successfully");
                    }
                }
                
            })
        }
        function editemployeedata(id){
             var employee = employee_details.find(function(employee) {
                return employee.id == id;
            });
            $("#editEmployeeFullname").val(employee.full_name);
            $("#editEmployeeusername").val(employee.username);
            $("#editEmployeemobilenumber").val(employee.mobile);
            $("#editEmployeeemail").val(employee.email);
            $("#editEmployeestatus").val(employee.status);
            $("#updateButton").attr("data-id", id);
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById("editModal")
            ).show();
        }
        function updateemployee(id){
            var id = $("#updateButton").attr("data-id");
            var fullname = $("#editEmployeeFullname").val();
            var username = $("#editEmployeeusername").val();
            var mobilenumber = $("#editEmployeemobilenumber").val();
            var email = $("#editEmployeeemail").val();
            var status = $("#editEmployeestatus").val();
             if (fullname.trim() === "" ||
                username.trim() === "" ||
                mobilenumber.trim() === ""||
                email.trim() === ""||
                status.trim() === ""){
                alert("Please fill all fields");
                return;
            }
            var formData = new FormData();
            formData.append("id", id);
            formData.append("fullname", fullname);
            formData.append("username", username);
            formData.append("mobilenumber", mobilenumber);
            formData.append("email", email);
            formData.append("status", status);
            $.ajax({
                url: __baseurl + "updateemployeedata",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        createemployeetable();
                        loaduserDropdown();
                        loadstatusDropdown();
                        loaddateDropdown();
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
                "Are you sure you want to delete this employee?",
                function () {
                    $.ajax({
                        url: __baseurl+ "deleteemployeedata",
                        type: "POST",
                        data:{
                            id: id,
                        },
                        dataType: "json",
                        success: function(response){
                            if(response.status){
                                showMessagePopup("Success","Employee deleted successfully");
                                createemployeetable();
                                loaduserDropdown();
                                loadstatusDropdown();
                                loaddateDropdown();
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
                "Are you sure you want to delete all employees?",
                function () {
                    $.ajax({
                        url: __baseurl+"clearemployeedata",
                        type: "POST",
                        dataType: "json",
                        success: function(response){
                            if(response.status){
                                createemployeetable();
                                loaduserDropdown();
                                loadstatusDropdown();
                                loaddateDropdown();
                                showMessagePopup("Success","All employees deleted successfully");
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
            var username = $("#userDropdown").val();
            var status= $("#statusDropdown").val();
            var date= $("#dateDropdown").val();
            // console.log(date);
            $.ajax({
                url: __baseurl+ "selectemployeedata",
                type: "POST",
                data: {
                    page: page,
                    limit: limit,
                    searchuser: searchuser,
                    username:username,
                    status: status,
                    date: date,
                },
                dataType: "json",
                success:function(response){
                    // console.log(response);
                    if(response.status){
                        employee_details=response.data;
                        //console.log(response.data);
                        
                        var html = "";
                        html += `<table class="table table-bordered table-striped">`;
                        html += `<tr>`;
                        html +=`<th>#</th>`;
                        html +=`<th>Fullname</th>`;
                        html +=`<th>Username</th>`;
                        html +=`<th>Email</th>`; 
                        html +='<th>Mobilenumber</th>';
                        html +=`<th>Role</th>`;
                        html +=`<th>Status</th>`;
                        html +=`<th>Created_at</th>`;
                        html +=`<th>Created_by</th>`;
                        html +=`<th>Updated_at</th>`;
                        html +=`<th>Action</th>`;
                        html +=`</tr>`;
                        employee_details.forEach((users, index) => {
                            html +=`<tr id="row${users.id}">`;
                            html +=`<td class="${users.id}">${index+1}</td>`;
                            html +=`<td class="${users.id}">${users.full_name}</td>`;
                            html +=`<td class="${users.id}">${users.username}</td>`;
                            html +=`<td class="${users.id}">${users.email}</td>`;
                            html +=`<td class="${users.id}">${users.mobile}</td>`;
                            html +=`<td class="${users.id}">${users.role}</td>`;
                            html +=`<td class="${users.id}">${users.status}</td>`;
                            html +=`<td class="${users.id}">${users.created_at}</td>`;
                            html +=`<td class="${users.id}">${users.created_by}</td>`;
                            html +=`<td class="${users.id}">${users.updated_at}</td>`;
                            html += `<td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning"
                                        onclick="editemployeedata(${users.id})">
                                        Edit
                                    </button>

                                    <button class="btn btn-danger"
                                        onclick="deleteemployee(${users.id})">
                                        Delete
                                    </button>
                                    <input id="hideinput${users.id}" value="0" type="hidden">
                                    <a id="hide${users.id}" class="btn btn-outline-secondary" href="${__baseurl}viewpage/${users.id}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn ${users.status == 'Active' ? 'btn-secondary' : 'btn-success'}"
                                        onclick="changeStatus(${users.id}, '${users.status}')">
                                        ${users.status == 'Active' ? 'Deactivate' : 'Activate'}
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
                "Are you sure you want to " + newStatus.toLowerCase() + " this employee?",
                function () {
                    $.ajax({
                        url: __baseurl + "changestatus",
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
                                    "Employee " + newStatus.toLowerCase() + "d successfully"
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
        function loaduserDropdown() {
            $.ajax({
                url: __baseurl + "getusernames",
                type: "GET",
                dataType: "json",
                success: function(usernames) {
                    $("#userDropdown").html(
                        '<option value="">Select Users</option>'
                    );

                    usernames.forEach(function(users) {
                        $("#userDropdown").append(
                            `<option value="${users.username}">
                                ${users.username}
                            </option>`
                        );
                    });
                }
            });
        }
        function loadstatusDropdown() {
            $.ajax({
                url: __baseurl + "getstatusnames",
                type: "GET",
                dataType: "json",
                success: function(statusnames) {
                    $("#statusDropdown").html(
                        '<option value="">Select Status</option>'
                    );

                    statusnames.forEach(function(users) {
                        $("#statusDropdown").append(
                            `<option value="${users.status}">
                                ${users.status}
                            </option>`
                        );
                    });
                }
            });
        }
        function loaddateDropdown() {
            $.ajax({
                url: __baseurl + "getdate",
                type: "GET",
                dataType: "json",
                success: function(dates) {
                    //console.log(dates);

                    $("#dateDropdown").html(
                        '<option value="">Select Date</option>'
                    );
                    //console.log("Dropdown count:", $("#dateDropdown").length);

                    dates.forEach(function(users) {
                        //console.log(users.created_at);
                        $("#dateDropdown").append(
                            `<option value="${users.created_at}">
                                ${users.created_at}
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
        $("#userDropdown").on("change", function() {
            createemployeetable();
        });
        $("#statusDropdown").on("change", function() {
            createemployeetable();
        });
        $("#dateDropdown").on("change", function() {
            createemployeetable();
        });



        function exportemployee(format) {
            var params = new URLSearchParams();

            params.append('format', format);
            params.append('searchuser', $('#searchuser').val() || '');
            params.append('username', $('#userDropdown').val() || '');
            params.append('status', $('#statusDropdown').val() || '');
            params.append('date', $('#dateDropdown').val() || '');

            var url = __baseurl + 'exportemployee?' + params.toString();

           // console.log("Export URL:", url);

            window.location.href = url;
        }
    </script>
</html>