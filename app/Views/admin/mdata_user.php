<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BestTeamV</title><!-- Bootstrap 5 CSS -->
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
        <div style="
            padding: 20px;
            margin: 0 15px 20px 15px;
            background: linear-gradient(135deg, #0d6efd, #0056b3);
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        ">
            <h4 style="
                color: white;
                font-size: 14px;
                font-weight: 600;
                margin: 0 0 4px 0;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            " id="sidebarUserName"><?= $nama; ?> </h4>
            <p style="
                color: rgba(255, 255, 255, 0.8);
                font-size: 11px;
                margin: 0;
                font-weight: 400;
            "><?php if ($role === 1) {
                    echo "Administrator";
                } else if ($role === 2) {
                    echo "Auditor";
                } else {
                    echo "Auditee";
                } ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= base_url('temuan_patrol') ?>" class="menu-item " data-page="patrol">
            <i class="bi bi-search"></i>
            <span>Data Patrol</span>
        </a>
        <a href="<?= base_url('schedule') ?>" class="menu-item" data-page="schedule">
            <i class="bi bi-calendar-check"></i>
            <span>Schedule</span>
        </a>
        <div class="menu-header">
            Master Data
        </div>
        <?php if ($role === 1) : ?>
            <a href="<?= base_url('admin/mdata_user') ?>" class="menu-item active" data-page="user">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>
            <a href="<?= base_url('admin/mdata_department') ?>" class="menu-item" data-page="department">
                <i class="bi bi-building"></i> <span>Departemen</span>
            </a>
        <?php endif; ?>
    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
        <h2 class="page-title" id="dashboardTitle">Master data User</h2><!-- Charts Row 1 -->

        <!-- Data Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data User</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tambahdata">
                        <i class="bi bi-plus-circle"></i> Tambah Data</button>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <table id="auditTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="100">NPK</th>
                            <th width="200">Nama</th>
                            <th>Departemen</th>
                            <th width="200">Seksi</th>
                            <th>Role</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_user as $index => $user) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $user['npk'] ?></td>
                                <td><?= $user['nama'] ?></td><!-- todo : ambil departemen berdasarkan id dept -->
                                <td>
                                    <?php
                                    foreach ($data_dept as $dept) {
                                        if ($dept['id_departement'] == $user['id_departement']) {
                                            echo $dept['departement'];
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- TODO : Ambil seksi berdasarkan id dept nya -->
                                    <?php foreach ($data_seksi as $seksi) {
                                        if ($seksi['id_section'] == $user['id_section']) {
                                            echo $seksi['section'];
                                            break;
                                        }
                                    } ?>
                                </td>
                                <td>
                                    <?php if ($user['role'] == 1) {
                                        echo "Admin";
                                    } elseif ($user['role'] == 2) {
                                        echo "Auditor";
                                    } elseif ($user['role'] == 3) {
                                        echo "Auditee";
                                    } else {
                                        echo "Unknown";
                                    }
                                    ?>

                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-info btnInfo_user" data-bs-toggle="modal" data-bs-target="#modal_info_user" data-id="<?= $user['id'] ?>"><i class="bi bi-pencil-fill"></i></button>
                                    <button type="button" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- Footer -->
    <div class="modal fade" id="modal_tambahdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">

                            <div class="form-group col-md-12 ">
                                <label for="dt_nama" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i> Nama</label>
                                <select class="form-select select2" id="dt_nama" style="width:100%;">
                                    <option value="">-- Pilih Opsi --</option>
                                    <?php foreach ($data_karyawan as $karyawan) : ?>
                                        <option value="<?= $karyawan['npk'] ?>"><?= $karyawan['npk'] ?> - <?= $karyawan['nama'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                          
                            <div class="form-group col-md-6 mt-2">
                                <label for="Role" class="form-label">
                                    <i class="bi bi-building me-1"></i>Departemen</label>
                                <input type="text" class="form-control" id="dept_user" placeholder="Readonly" autocomplete="off" readonly>

                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <label for="Seksi" class="form-label">
                                    <i class="bi bi-diagram-3 me-1"></i>Section </label>
                                <input type="text" class="form-control" id="seksi_user" placeholder="Readonly" autocomplete="off" readonly>

                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="Role" class="form-label">
                                <i class="bi bi-diagram-3 me-1"></i>Role</label>
                            <select class="form-select select2" id="list_role" style="width:100%;">
                                <option value="">-- Pilih Opsi --</option>
                                <option value="1">Admin</option>
                                <option value="2">Auditor</option>
                                <option value="3">Auditee</option>

                            </select>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_sendData"><i class="bi bi-plus-circle"></i> Tambah Data User </button>
                </div>
            </div>
        </div>
    </div>
    <div class="footer" id="footer">
        <p id="footerText">© 2025 Quality Patrol — All rights reserved</p>
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script><!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Select2 core JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="<?= base_url() ?>assets/js/temuan_patrol/mdata_user.js"></script>
    <script>
        $("#auditTable").DataTable({});
        $('#btn_sendData').click(function() {
            // todo : kirim data ke controller admin/sendData
           
            var npk = $('#dt_nama').val();
            var roleUser = $('#list_role').val();

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                 
                    npk_user: npk,
                    role_user: roleUser,
                    keterangan: 'tambah_user'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload(); // muat ulang halaman untuk melihat perubahan
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
        $('#dt_nama').change(function() {
            var selectNPK = $(this).val();


            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    npk: selectNPK,
                    keterangan: 'get_dept_seksi'
                },
                success: function(response) {
                    // Perbarui elemen seksi berdasarkan response.html_seksi
                    $('#dept_user').val(response.nama_dept_user);
                    $('#seksi_user').val(response.nama_seksi_user);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mengambil data seksi.');
                }
            });
        });
    </script>
</body>

</html>