
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        #profileimage {
            cursor: pointer;
            object-fit: cover;
        }

        #profileimage:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="card mx-auto shadow-lg mt-5" style="width: 500px; min-height: 300px;">
        
        <h6 id="error" class="text-center text-danger mt-2"></h6>

        <div class="card-header d-flex justify-content-center mt-3">
            <h1>Profile</h1>
        </div>

        <div class="card-body d-flex justify-content-around align-items-center">
            
            <div id="photo">
                <!-- Click the image to choose a new photo -->
                <img
                    id="profileimage"
                    src="<?= base_url('uploads/' . $profileimage) ?>"
                    width="120"
                    height="120"
                    class="rounded-circle"
                    title="Click to change profile photo"
                    alt="Profile Image"
                >

                <input
                    type="file"
                    id="newphoto"
                    accept="image/*"
                    hidden
                >
            </div>

            <div>
                <h5>Fullname: <?= esc($fullname) ?></h5>
                <h5>Username: <?= esc($username) ?></h5>
                <h5>Email: <?= esc($email) ?></h5>
                <h5>Mobilenumber: <?= esc($mobilenumber) ?></h5>
                <h5>Role: <?= esc($role) ?></h5>
                <h5>Status: <?= esc($status) ?></h5>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var __baseurl = "<?= base_url() ?>/";

        // Clicking the image opens the file picker
        $("#profileimage").on("click", function () {
            $("#newphoto").click();
        });

        // Upload the selected image automatically
        $("#newphoto").on("change", function () {
            var file = this.files[0];

            if (file) {
                var formData = new FormData();
                formData.append("profileimage", file);

                $("#error").html("Uploading photo...");

                $.ajax({
                    url: __baseurl + "updatephoto/<?= $id ?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",

                    success: function (response) {
                        if (response.status) {
                            $("#profileimage").attr(
                                "src",
                                __baseurl + "uploads/" +
                                response.photo + "?t=" + new Date().getTime()
                            );

                            $("#error")
                                .removeClass("text-danger")
                                .addClass("text-success")
                                .html("Profile photo updated successfully!");
                        } else {
                            $("#error")
                                .removeClass("text-success")
                                .addClass("text-danger")
                                .html(response.message);
                        }
                    },

                    error: function () {
                        $("#error")
                            .removeClass("text-success")
                            .addClass("text-danger")
                            .html("Photo upload failed. Please try again.");
                    }
                });
            }
        });
    </script>
</body>
</html>
