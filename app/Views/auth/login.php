<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="<?= base_url() ?>assets/css/uicss.css" rel="stylesheet">
    <script src="/_sdk/element_sdk.js"></script>
    <style>
        body {
            overflow-y: auto;
        }
    </style>
    <style>
        /* @view-transition {
            navigation: auto;
        } */
    </style>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
    <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
</head>

<body class="light-theme"><!-- Canvas untuk animasi PlayStation icons -->
    <canvas id="animation-canvas"></canvas><!-- Theme Toggle --> <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme" style="border: 1px solid #7b7979ff;"> <span id="themeIcon">🌙</span> </button> <!-- Main Container -->
    <div class="main-container">
        <div class="card login-card">
            <div class="card-body"><!-- Brand Logo -->
                <div class="brand-logo text-center">
                    <img src="<?= base_url() ?>assets/cbi.png" class="img-fluid mx-auto d-block w-25">
                    <h1 id="brandName" class="mt-2">QUALITY PATROL</h1>
                </div><!-- Form Title -->

                <div id="alertContainer"></div><!-- Login Form -->
                <form id="loginForm" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label" id="usernameLabel">Username</label>
                        <input type="text" class="form-control" id="username" required aria-required="true" aria-describedby="usernameHelp">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label" id="passwordLabel">Password</label>
                        <input type="password" class="form-control" id="password" required aria-required="true" aria-describedby="passwordHelp">
                    </div>
                    <button type="button" class="btn btn-login" id="loginButton">Masuk</button>


                </form><!-- Links -->

            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/authlog.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#loginButton').on('click', function() {
            const username = $('#username').val().trim();
            const password = $('#password').val().trim();

            if (username === '' || password === '') {
                showAlert('Harap isi semua bidang.', 'danger');
                return;
            }

            const $btn = $('#loginButton');
            $btn.prop('disabled', true);
            $btn.html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mohon Tunggu ...'
            );

            $.ajax({
                url: '<?= base_url('CrudController/authLogin') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: username,
                    password: password
                },
                success: function(response) {
                    if (response.ok === true) {
                        window.location.href = '<?= base_url('summary') ?>';

                    } else {
                        showAlert(response.msg, 'danger');
                        resetButton();
                    }
                },
                error: function(xhr) {
                    let res = xhr.responseJSON || {};
                    showAlert(res.msg || 'Terjadi kesalahan pada server', 'danger');
                    resetButton();
                }
            });

            function resetButton() {
                $btn.prop('disabled', false);
                $btn.html('Login');
            }


            // $.ajax({
            //     url: '<?= base_url('CrudController/authLogin') ?>',
            //     type: 'POST',
            //     data: { username: username, password: password },
            //     success: function (response) {
            //         if (!response.ok) {
            //         showAlert(response.msg, 'danger');
            //         return;
            //         }

            //         // jika perlu pilih role
            //         if (response.need_role_select === true) {
            //             const options = (response.roles || [])
            //                 .map(r => `<option value="${r.id}">${r.name}</option>`)
            //                 .join('');

            //             Swal.fire({
            //                 title: 'Pilih Role',
            //                 html: `
            //                 <select id="swal-role" class="swal2-input">
            //                     <option value="">-- pilih role --</option>
            //                     ${options}
            //                 </select>
            //                 `,
            //                 showCancelButton: true,
            //                 confirmButtonText: 'Lanjut',
            //                 cancelButtonText: 'Batal',
            //                 focusConfirm: false,
            //                 preConfirm: () => {
            //                 const val = $('#swal-role').val();
            //                 if (!val) {
            //                     Swal.showValidationMessage('Role wajib dipilih!');
            //                     return false;
            //                 }
            //                 return val;
            //             }
            //             }).then((result) => {
            //                 if (!result.isConfirmed) return;

            //                 $.ajax({
            //                 url: '<?= base_url('CrudController/setActiveRole') ?>',
            //                 type: 'POST',
            //                 data: { role_id: result.value },
            //                 success: function (res2) {
            //                     if (res2.ok) {
            //                     showAlert('Login berhasil', 'success');
            //                     window.location.href = res2.redirect || '<?= base_url('summary') ?>';
            //                     } else {
            //                     showAlert(res2.msg || 'Gagal set role', 'danger');
            //                     }
            //                 },
            //                 error: function (xhr) {
            //                     let res = xhr.responseJSON || {};
            //                     showAlert(res.msg || 'Terjadi kesalahan saat set role', 'danger');
            //                 }
            //                 });
            //             });

            //             return;
            //         }

            //         // role cuma 1 -> langsung redirect
            //         showAlert(response.msg, 'success');
            //         window.location.href = response.redirect || '<?= base_url('summary') ?>';
            //     },
            //     error: function (xhr) {
            //         let res = xhr.responseJSON || {};
            //         showAlert(res.msg || 'Terjadi kesalahan pada server', 'danger');
            //     }
            // });
        });
    </script>
</body>

</html>