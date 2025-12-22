<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title><!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"><!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Select2 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 Bootstrap-5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.6.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/css/summary.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/t_patrol.css" rel="stylesheet">

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }
    </style>

    <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
</head>

<body class="light-theme"><!-- Header -->
    <div class="header">
        <div class="header-left"><button class="mobile-toggle" id="mobileToggle"> <i class="bi bi-list"></i> </button>
            <h1 class="logo-text" id="appName">Quality Patrol</h1>
        </div>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle">
                <i class="bi bi-sun-fill"></i>
            </button>
            <div class="profile-dropdown">
                <img src="https://ui-avatars.com/api/?name=User&amp;background=0d6efd&amp;color=fff&amp;size=128" alt="Profile" class="profile-img" id="profileImg">
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="#" class="dropdown-item" id="logoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout </a>
                </div>
            </div>
        </div>
    </div><!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= base_url('temuan_patrol') ?>" class="menu-item " data-page="patrol">
            <i class="bi bi-search"></i>
            <span>Temuan Patrol</span>
        </a>

        <div class="menu-header">
            Master Data
        </div>
        <a href="<?= base_url('admin/mdata_user') ?>" class="menu-item " data-page="user">
            <i class="bi bi-people"></i>
            <span>User</span>
        </a>
        <a href="<?= base_url('admin/mdata_department') ?>" class="menu-item active" data-page="department">
            <i class="bi bi-building"></i> <span>Departemen</span>
        </a>

    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
        <h2 class="page-title" id="dashboardTitle">Master data Departemen dan Seksi</h2><!-- Charts Row 1 -->

        <!-- Data Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Departemen</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tambahdata">
                        <i class="bi bi-plus-circle"></i> Tambah Data</button>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <table id="auditTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama Departemen</th>


                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_dept as $dept) : ?>
                            <tr>
                                <td><?= esc($dept['nama_dept']) ?></td>


                                <td class="text-center">
                                    <button type="button" class="btn btn-info btnInfo_dept" data-bs-toggle="modal" data-bs-target="#modal_info_dept" data-id="<?= $dept['id_dept'] ?>"><i class="bi bi-pencil-fill"></i></button>
                                    <button type="button" class="btn btn-danger btnHapus_dept" data-id="<?= $dept['id_dept'] ?>"><i class="bi bi-trash3-fill"></i> Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- Footer -->
    <div class="footer" id="footer">
        <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="modal_tambahdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Departemen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDept">
                        <div class="form-group">
                            <label for="nama_dept" class="form-label">
                                <i class="bi bi-building me-1"></i> Nama Departemen </label>
                            <input type="text" class="form-control" id="nama_dept" placeholder="Masukkan nama departemen">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_sendData"><i class="bi bi-plus-circle"></i> Tambah Data Departemen </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_info_dept" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Info Departemen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDept_Info">
                        <div class="form-group">
                            <label for="nama_dept" class="form-label">
                                <i class="bi bi-building me-1"></i> Nama Departemen </label>
                            <input type="text" class="form-control" id="nama_dept_info" placeholder="Masukkan nama departemen">
                        </div>
                        <div class="form-group mt-2">
                            <label for="nama_dept" class="form-label">
                                <i class="bi bi-diagram-3 me-1"></i> Nama Seksi </label>
                            <input type="text" class="form-control" id="nama_seksi_info" placeholder="Masukkan nama seksi">
                        </div>
                        <div class="form-group mt-2 text-end ">
                            <button type="button" class="btn btn-success btn-sm btn_tambahSeksi" id="btn_tambahSeksi"><i class="bi bi-plus-circle"></i> Tambah seksi </button>
                        </div>
                    </form>
                    <div class="form-group mt-2">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Seksi</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="btn_updateData"><i class="bi bi-download"></i> Update </button>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script><!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Select2 core JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/mdata_dept.js"></script>

    <script>
        $("#auditTable").DataTable({});
        $('#btn_sendData').click(function() {
            // Ambil nilai input
            var namaDept = $('#nama_dept').val().trim();

            // Validasi sederhana
            if (namaDept === '') {
                alert('Nama departemen tidak boleh kosong!');
                return;
            }

            // Kirim data via AJAX
            $.ajax({
                url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                type: 'POST',
                data: {
                    nama_dept: namaDept,
                    keterangan: 'tambah_dept'
                },
                success: function(response) {
                    alert('Data berhasil dikirim');
                    // reset input jika perlu

                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $('.btnInfo_dept').on('click', function() {
            var deptId = $(this).data('id');
            // console.log(deptId);
            $.ajax({
                url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                type: 'POST',
                data: {
                    id_dept: deptId,
                    keterangan: 'get_dept'
                },
                success: function(response) {

                    $('#nama_dept_info').val(response.nama_dept);
                    $('.btn_tambahSeksi').attr('data-id', response.id_dept);
                    $('#btn_updateData').attr('data-id', response.id_dept);
                    var tbody = $('#modal_info_dept').find('tbody');
                    tbody.html(response.html_seksi);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $(document).on('click', '.btn_tambahSeksi', function() {
            var deptId = $(this).attr('data-id');
            var nama_seksi = $('#nama_seksi_info').val().trim();
            $.ajax({
                url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                type: 'POST',
                data: {
                    id_dept: deptId,
                    keterangan: 'tambah_seksi',
                    nama_seksi: nama_seksi
                },
                success: function(response) {


                    var tbody = $('#modal_info_dept').find('tbody');
                    tbody.html(response.html);
                    $('#nama_seksi_info').val('');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $(document).on('click', '.hapus_seksi', function() {
            var seksiId = $(this).data('id');
            var idDept = $(this).data('iddept');

            $.ajax({
                url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                type: 'POST',
                data: {
                    id_seksi: seksiId,
                    id_dept: idDept,
                    keterangan: 'hapus_seksi',

                },
                success: function(response) {

                    var tbody = $('#modal_info_dept').find('tbody');
                    tbody.html(response.html);
                    $('#nama_seksi_info').val('');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $(document).on('click', '#btn_updateData', function() {
            var id = $(this).data('id');
            var nama_baru_dept = $('#nama_dept_info').val();
            $.ajax({
                url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                type: 'POST',
                data: {

                    id_dept: id,
                    keterangan: 'update_dept',
                    nama_dept: nama_baru_dept

                },
                success: function(response) {

                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $('.btnHapus_dept').on('click', function() {
            var deptId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus departemen ini?')) {
                $.ajax({
                    url: '<?= base_url('admin/sendData') ?>', // ganti dengan URL endpoint kamu
                    type: 'POST',
                    data: {
                        id_dept: deptId,
                        keterangan: 'hapus_dept'
                    },
                    success: function(response) {
                        alert('Departemen berhasil dihapus.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Terjadi kesalahan saat menghapus departemen.');
                    }
                });
            }
        });
    </script>
</body>

</html>