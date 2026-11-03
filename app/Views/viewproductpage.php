<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Product</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }

        .product-card {
            width: 100%;
            max-width: 650px;
            margin: 60px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e7ebf2;
        }

        .product-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #edf0f5;
        }

        .product-body {
            padding: 30px;
        }

        #productimage {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #eaf2ff;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        #productimage:hover {
            transform: scale(1.05);
        }

        .image-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
        }

        .product-details h6 {
            margin-bottom: 15px;
            overflow-wrap: anywhere;
        }

        @media (max-width: 575px) {
            .product-card {
                margin: 25px 12px;
                width: auto;
            }

            .product-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<?php
    $photo = !empty($productimage)
        ? base_url('uploads/' . $productimage)
        : '';
?>

<div class="card product-card shadow-sm">

    <div class="product-header">
        <h2 class="mb-0">
            <i class="bi bi-box-seam text-primary me-2"></i>
            Product Details
        </h2>
    </div>

    <div class="product-body">
        <div class="row align-items-center g-4">

            <div class="col-md-5 text-center">

                <img
                    id="productimage"
                    src="<?= esc($photo) ?>"
                    alt="Product image"
                    title="Click to change product image"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22130%22 height=%22130%22 viewBox=%220 0 130 130%22%3E%3Crect width=%22130%22 height=%22130%22 rx=%2265%22 fill=%22%23e8eef8%22/%3E%3Ctext x=%2265%22 y=%2278%22 font-size=%2245%22 text-anchor=%22middle%22 fill=%22%23647791%22%3E%3F%3C/text%3E%3C/svg%3E';">

                <input
                    type="file"
                    id="newphoto"
                    accept="image/jpeg,image/png,image/webp"
                    hidden>

                <div class="image-hint">
                    <i class="bi bi-camera me-1"></i>
                    Click image to change
                </div>

            </div>

            <div class="col-md-7 product-details">

                <h6>
                    <span class="text-secondary">Product Name:</span>
                    <?= esc($productname) ?>
                </h6>

                <h6>
                    <span class="text-secondary">Product Code:</span>
                    <?= esc($productcode) ?>
                </h6>

                <h6>
                    <span class="text-secondary">Category:</span>
                    <?= esc($category) ?>
                </h6>

                <h6>
                    <span class="text-secondary">Price:</span>
                    <?= esc($price) ?>
                </h6>

                <h6>
                    <span class="text-secondary">Quantity:</span>
                    <?= esc($quantity) ?>
                </h6>

                <h6>
                    <span class="text-secondary">Status:</span>
                    <?= esc($status) ?>
                </h6>

            </div>
        </div>
    </div>
</div>

<!-- Popup for upload status -->
<div class="modal fade" id="uploadModal" tabindex="-1"
     aria-labelledby="uploadModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3">

            <div class="modal-body">
                <i id="popupIcon" class="bi bi-check-circle-fill text-success fs-1"></i>

                <h5 id="uploadModalLabel" class="mt-3">Success</h5>

                <p id="uploadMessage" class="text-secondary mb-0"></p>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-primary"
                        data-bs-dismiss="modal">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {

    const baseUrl = "<?= base_url() ?>";
    const oldPhoto = <?= json_encode($photo) ?>;

    let previewURL = null;

    // Display a Bootstrap popup instead of an alert
    function showPopup(success, message) {

        $("#popupIcon")
            .removeClass("bi-check-circle-fill bi-x-circle-fill text-success text-danger")
            .addClass(
                success
                    ? "bi-check-circle-fill text-success"
                    : "bi-x-circle-fill text-danger"
            );

        $("#uploadModalLabel").text(success ? "Success" : "Upload Failed");
        $("#uploadMessage").text(message);

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("uploadModal")
        ).show();
    }

    // Click the product image to open the file picker
    $("#productimage").on("click", function () {
        $("#newphoto").click();
    });

    // Upload immediately when a new image is selected
    $("#newphoto").on("change", function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!allowedTypes.includes(file.type)) {
            showPopup(false, "Please select a JPG, PNG, or WebP image.");
            $(this).val("");
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            showPopup(false, "Image size must not exceed 2 MB.");
            $(this).val("");
            return;
        }

        const formData = new FormData();
        formData.append("product_image", file);

        previewURL = URL.createObjectURL(file);

        // Show the new image immediately
        $("#productimage").attr("src", previewURL);

        $.ajax({
            url: baseUrl + "updateproduct/<?= esc((string) $id) ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (response) {

                if (response.status) {

                    const savedPhoto =
                        baseUrl + "uploads/" +
                        encodeURIComponent(response.photo) +
                        "?t=" + Date.now();

                    $("#productimage").attr("src", savedPhoto);

                    showPopup(
                        true,
                        response.message || "Product image updated successfully."
                    );

                } else {

                    $("#productimage").attr("src", oldPhoto);

                    showPopup(
                        false,
                        response.message || "Unable to update the product image."
                    );
                }
            },

            error: function (xhr) {

                $("#productimage").attr("src", oldPhoto);

                const response = xhr.responseJSON || {};

                showPopup(
                    false,
                    response.message || "Image upload failed. Please try again."
                );
            },

            complete: function () {

                $("#newphoto").val("");

                if (previewURL) {
                    URL.revokeObjectURL(previewURL);
                    previewURL = null;
                }
            }
        });
    });

});
</script>

</body>
</html>