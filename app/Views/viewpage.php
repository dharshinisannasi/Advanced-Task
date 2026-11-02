<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="card mx-auto shadow-lg mt-5"style="width:500px; height:300px;">
            <h6 id="error"></h6>
            <div class="card-head d-flex justify-content-center gap-5 mt-3">
                <h1>Profile</h2>
                <button type="button" class="btn btn-primary" onclick="edit()">Edit</button>
            </div>
            <div class="card-body d-flex justify-content-around align-items-center">
                <div id="photo">
                    <img id="profileimage"
                        src="<?= base_url('uploads/' . $profileimage) ?>"
                        width="120" height="120"
                        class="rounded-circle">

                    <input type="file" id="newphoto"
                        accept="image/*" hidden>
                </div>
                <div>
                    <h5 id="Fullname">Fullname: <?= esc($fullname)?></h5>
                    <h5 id="Username">Username: <?= esc($username)?></h5>
                    <h5 id="Email">Email: <?= esc($email)?></h5>
                    <h5 id="Mobilenumber"> Mobilenumber:<?= esc($mobilenumber)?></h5>
                    <h5 id="Role">Role: <?= esc($role)?></h5>
                    <h5 id="Status">Status: <?= esc($status)?></h5>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var __baseurl="<?=base_url()?>";
        function edit() {
            $("#newphoto").click();
        }

        $("#newphoto").change(function () {
            var file = this.files[0];

            if (file) {
                var formData = new FormData();
                formData.append("profileimage", file);

                $.ajax({
                    url: __baseurl + "updatephoto/<?= $id ?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",

                    success: function (response) {
                        if (response.status) {
                            $("#profileimage").attr("src",
                                __baseurl + "uploads/" + response.photo + "?t=" + new Date().getTime()
                            );
                        } else {
                            $("#error").html(response.message);
                        }
                    }
                });
            }
        });
    </script>
</html>