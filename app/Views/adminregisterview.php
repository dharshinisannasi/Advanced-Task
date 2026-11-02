<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="container mx-auto col-3 mt-3 shadow-lg bg-body-secondary">
            <h5 class="text-success text-center" id="validmessage"></h5>
            <h5 class="text-danger text-center" id="invalidmessage"></h5>
            <h1 class="text-center mb-3 mt-4">Admin Registration Form</h1>
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
            <h6 class="text-center">Already having an account <span class="ms-3"><a href="loginpage">Login</a></span></h6>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var __baseurl= "<?=base_url() ?>";
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
           // console.log(confirmpassword);
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
                url: __baseurl + "insertadmindata",
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
                    }
                }
                
            })
        }


        
    </script>
</html>