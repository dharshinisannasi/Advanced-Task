<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="container col-4 m-5 mx-auto">
            <h5 id="validuser" class="text-center text-danger"></h5>
            <h5 id="invaliduser" class="text-center text-danger"></h5>
            <h1 class="text-center text-primary">Login Page</h1>
            <label for="loginusername" class="form-label text-primary">Username</label>
            <input type="text" id="loginusername" class="form-control" placeholder="enter your username">
            <p id="loginusererror" class="text-danger"></p>
            <label for="loginpassword" class="form-label text-primary">Password</label>
            <input type="password" id="loginpassword" class="form-control" placeholder="enter your password">
            <p id="loginpassworderror" class="text-danger"></p>
            <button type="submit" class="form-control btn btn-primary mt-3" onclick="loginuser()">Log In</button>
            <h6 class="text-center mt-2">Create a new account <span><a href="registrationpage">sign up</a></span></h6>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loginuser(){
            var __baseurl="<?=base_url()?>"
            var username= $("#loginusername").val();
            //console.log(username);
            var password=$("#loginpassword").val();
            //console.log(password);
            $("#loginusererror").html("");
            $("#loginpassworderror").html("");
            var check= true;
            if (username.trim()===""){
                $("#loginusererror").html("Field empty");
                check= false;
            }
            if (password.trim()===""){
                $("#loginpassworderror").html("Field empty");
                check= false;
            }
            if (check===false){
                return false;
            }
            $.ajax({
                url: __baseurl + "getloginuser",
                type: "POST",
                data: {
                    username,
                    password
                },
                dataType: "json",
                success:function(response){
                    if(response.status){
                        console.log(response.status);
                        $("#invaliduser").html("");
                        $("#validuser").html(response.message);
                        setTimeout(function() {
                            window.location.href = "dashboardpage";
                        },1000 );
                    }
                    else{
                        $("#validuser").html("");
                        $("#invaliduser").html(response.message);
                    }
                }
            });
        }
    </script>
</html>