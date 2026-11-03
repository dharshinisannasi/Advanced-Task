
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
            color: #253047;
        }

        .profile-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .profile-card {
            width: 100%;
            max-width: 720px;
            background: #fff;
            border: 1px solid #e7ebf2;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(28, 43, 65, 0.09);
            overflow: hidden;
        }

        .profile-header {
            padding: 24px 30px;
            border-bottom: 1px solid #edf0f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .profile-header h2 {
            font-size: 25px;
            font-weight: 700;
            margin: 0;
        }

        .profile-content {
            padding: 35px;
        }

        .profile-photo-section {
            text-align: center;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #edf3ff;
            box-shadow: 0 0 0 1px #dce5f5;
            cursor: pointer;
        }

        .profile-name {
            margin-top: 16px;
            font-size: 19px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .photo-hint {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
        }

        .profile-role {
            display: inline-block;
            margin-top: 7px;
            padding: 5px 13px;
            border-radius: 20px;
            background: #eaf2ff;
            color: #175cd3;
            font-size: 12px;
            font-weight: 600;
        }

        .details-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #64748b;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #f0f2f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f5ff;
            color: #2864dc;
            font-size: 17px;
        }

        .detail-label {
            color: #718096;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #16a34a;
            border-radius: 50%;
            margin-right: 6px;
        }

        .edit-btn {
            border-radius: 9px;
            padding: 9px 18px;
            font-weight: 600;
        }

        .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            background: #f7f9fd;
            border-bottom: 1px solid #edf0f5;
            padding: 20px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .form-control {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #86aefb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .popup-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        @media (max-width: 575px) {
            .profile-header {
                padding: 20px;
            }

            .profile-content {
                padding: 24px 20px;
            }

            .profile-photo {
                width: 120px;
                height: 120px;
            }

            .profile-header h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<?php
    $photo = !empty($profileimage)
        ? base_url('uploads/' . $profileimage)
        : '';
?>

<div class="profile-wrapper">
    <div class="profile-card">

        <div class="profile-header">
            <div>
                <h2>
                    <i class="bi bi-person-circle me-2 text-primary"></i>
                    My Profile
                </h2>
                <small class="text-secondary">
                    Manage your personal information
                </small>
            </div>

            <button type="button"
                    class="btn btn-primary edit-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Profile
            </button>
        </div>

        <div class="profile-content">
            <div class="row align-items-center g-4">

                <div class="col-md-5 profile-photo-section">

                    <img src="<?= esc($photo) ?>"
                         id="profilePhoto"
                         class="profile-photo"
                         alt="Profile photo"
                         title="Click to change profile photo"
                         onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22 viewBox=%220 0 150 150%22%3E%3Crect width=%22150%22 height=%22150%22 rx=%2275%22 fill=%22%23e8eef8%22/%3E%3Ctext x=%2275%22 y=%2290%22 font-size=%2255%22 text-anchor=%22middle%22 fill=%22%23647791%22%3E%3F%3C/text%3E%3C/svg%3E';">

                    <input type="file"
                           id="profileimage"
                           name="profileimage"
                           accept=".jpg,.jpeg,.png,.webp"
                           hidden>

                    <div class="profile-name">
                        <?= esc($fullname) ?>
                    </div>

                    <span class="photo-hint">
                        <i class="bi bi-camera me-1"></i>
                        Click the image to change your photo
                    </span>

                    <span class="profile-role">
                        <i class="bi bi-person-badge me-1"></i>
                        <?= esc($role) ?>
                    </span>
                </div>

                <div class="col-md-7">
                    <div class="details-title">
                        PERSONAL INFORMATION
                    </div>

                    <div class="detail-row">
                        <div class="detail-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value"><?= esc($fullname) ?></div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-icon">
                            <i class="bi bi-at"></i>
                        </div>
                        <div>
                            <div class="detail-label">Username</div>
                            <div class="detail-value"><?= esc($username) ?></div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div class="detail-label">Email Address</div>
                            <div class="detail-value"><?= esc($email) ?></div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <div class="detail-label">Mobile Number</div>
                            <div class="detail-value"><?= esc($mobilenumber) ?></div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="detail-label">Account Status</div>
                            <div class="detail-value">
                                <?php if (strtolower($status) === 'active'): ?>
                                    <span class="status-dot"></span>
                                <?php endif; ?>
                                <?= esc($status) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade"
     id="editProfileModal"
     tabindex="-1"
     aria-labelledby="editProfileLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="editProfileLabel">
                        <i class="bi bi-person-gear me-2 text-primary"></i>
                        Edit Profile
                    </h5>
                    <small class="text-secondary">
                        Update your details below
                    </small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form id="profileForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text"
                               name="fullname"
                               id="fullname"
                               class="form-control"
                               value="<?= esc($fullname) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control"
                               value="<?= esc($username) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               value="<?= esc($email) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="mobilenumber" class="form-label">Mobile Number</label>
                        <input type="text"
                               name="mobilenumber"
                               id="mobilenumber"
                               class="form-control"
                               value="<?= esc($mobilenumber) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password
                            <span class="text-secondary">(optional)</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               autocomplete="new-password"
                               placeholder="Leave blank to keep current password">
                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="saveProfileBtn">
                        <i class="bi bi-check2-circle me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap popup for success/error messages -->
<div class="modal fade"
     id="photoPopup"
     tabindex="-1"
     aria-labelledby="photoPopupTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-3">
            <div class="modal-body">

                <div id="photoPopupIcon" class="popup-icon text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h5 id="photoPopupTitle" class="fw-bold">Success</h5>

                <p id="photoPopupMessage" class="text-secondary mb-3">
                    Profile photo updated successfully.
                </p>

                <button type="button"
                        class="btn btn-primary px-4"
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

    const savedPhoto = $("#profilePhoto").attr("src");

    // Display a Bootstrap popup without using alert()
    function showPopup(success, message) {
        if (success) {
            $("#photoPopupIcon")
                .removeClass("text-danger")
                .addClass("text-success")
                .html('<i class="bi bi-check-circle-fill"></i>');

            $("#photoPopupTitle").text("Success");
        } else {
            $("#photoPopupIcon")
                .removeClass("text-success")
                .addClass("text-danger")
                .html('<i class="bi bi-x-circle-fill"></i>');

            $("#photoPopupTitle").text("Unable to update");
        }

        $("#photoPopupMessage").text(message);

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("photoPopup")
        ).show();
    }

    // Click the profile photo to open the file picker
    $("#profilePhoto").on("click", function () {
        $("#profileimage").trigger("click");
    });

    // Upload and save the photo immediately after selection
    $("#profileimage").on("change", function () {

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
            this.value = "";
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            showPopup(false, "Image size must not exceed 2 MB.");
            this.value = "";
            return;
        }

        // Keep the old image in case the upload fails
        const oldPhoto = $("#profilePhoto").attr("src");
        const previewURL = URL.createObjectURL(file);

        // Preview immediately
        $("#profilePhoto").attr("src", previewURL);

        const formData = new FormData();
        formData.append("profileimage", file);

        $("#profilePhoto").css("opacity", "0.6");

        $.ajax({
            url: "<?= base_url('updateprofile') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (response) {

                if (response.status) {

                    // Use the filename returned by the controller
                    if (response.photo) {
                        const newPhoto =
                            "<?= base_url('uploads/') ?>" +
                            encodeURIComponent(response.photo) +
                            "?t=" + Date.now();

                        $("#profilePhoto").attr("src", newPhoto);
                    }

                    showPopup(
                        true,
                        response.message || "Profile photo updated successfully."
                    );

                } else {

                    $("#profilePhoto").attr("src", oldPhoto);

                    showPopup(
                        false,
                        response.message || "Unable to update profile photo."
                    );
                }
            },

            error: function (xhr) {

                $("#profilePhoto").attr("src", oldPhoto);

                const response = xhr.responseJSON || {};

                showPopup(
                    false,
                    response.message || "Image upload failed. Please try again."
                );
            },

            complete: function () {
                $("#profilePhoto").css("opacity", "1");
                $("#profileimage").val("");
                URL.revokeObjectURL(previewURL);
            }
        });
    });

    // Save text details separately using the Save Changes button
    $("#profileForm").on("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(this);
        const saveButton = $("#saveProfileBtn");

        saveButton.prop("disabled", true)
            .html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
            );

        $.ajax({
            url: "<?= base_url('updateprofile') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (response) {

                if (response.status) {

                    $(".profile-name").text(response.fullname);

                    $(".detail-row").eq(0)
                        .find(".detail-value")
                        .text(response.fullname);

                    $(".detail-row").eq(1)
                        .find(".detail-value")
                        .text(response.username);

                    $(".detail-row").eq(2)
                        .find(".detail-value")
                        .text(response.email);

                    $(".detail-row").eq(3)
                        .find(".detail-value")
                        .text(response.mobilenumber);

                    showPopup(
                        true,
                        response.message || "Profile details updated successfully."
                    );

                    $("#password").val("");

                    // Update the modal input values after saving
                    $("#fullname").val(response.fullname);
                    $("#username").val(response.username);
                    $("#email").val(response.email);
                    $("#mobilenumber").val(response.mobilenumber);

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById("editProfileModal")
                    ).hide();

                } else {
                    showPopup(
                        false,
                        response.message || "Unable to update profile details."
                    );
                }
            },

            error: function (xhr) {
                const response = xhr.responseJSON || {};

                showPopup(
                    false,
                    response.message || "Something went wrong. Please try again."
                );
            },

            complete: function () {
                saveButton.prop("disabled", false)
                    .html('<i class="bi bi-check2-circle me-1"></i> Save Changes');
            }
        });
    });

});
</script>

</body>
</html>
