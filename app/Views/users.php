
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h1 class="text-center">Users Details</h1>
    <div class="d-flex align-items-center gap-2 mt-3 mb-3">
        <label for="pageLimit">Show:</label>

        <select id="pageLimit" class="form-select" style="width: 100px;"
                onchange="createtable(0)">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="all">All</option>
        </select>

        <span>entries</span>
    </div>
    <div id="table"></div>
</div>

<script>
    var __baseurl = "<?= base_url() ?>";
    createtable();
    function createtable(page=0){
        var limit = $("#pageLimit").val();
        $.ajax({
            url: __baseurl+ "selectuserdata",
            type: "POST",
            data: {
                page: page,
                limit: limit,
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
                    // html +=`<th>Fullname</th>`;
                    html +=`<th>Username</th>`;
                    html +=`<th>Email</th>`; 
                    // html +='<th>Mobilenumber</th>';
                    html +=`<th>Role</th>`;
                    html +=`<th>Status</th>`;
                    // html +=`<th>Created_at</th>`;
                    // html +=`<th>Created_by</th>`;
                    // html +=`<th>Updated_at</th>`;
                    // html +=`<th>Action</th>`;
                    html +=`</tr>`;
                    employee_details.forEach((users, index) => {
                        html +=`<tr id="row${users.id}">`;
                        html +=`<td class="${users.id}">${index+1}</td>`;
                        //html +=`<td class="${users.id}">${users.full_name}</td>`;
                        html +=`<td class="${users.id}">${users.username}</td>`;
                        html +=`<td class="${users.id}">${users.email}</td>`;
                        // html +=`<td class="${users.id}">${users.mobile}</td>`;
                        html +=`<td class="${users.id}">${users.role}</td>`;
                        html +=`<td class="${users.id}">${users.status}</td>`;
                        // html +=`<td class="${users.id}">${users.created_at}</td>`;
                        // html +=`<td class="${users.id}">${users.created_by}</td>`;
                        // html +=`<td class="${users.id}">${users.updated_at}</td>`;
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
                                    onclick="createtable(${i})">
                                    ${i + 1}
                                </button>
                            `;
                        }
                    }
                    $("#table").html(html);
                    
                }
            }

        })
    }
</script>

</body>
</html>
