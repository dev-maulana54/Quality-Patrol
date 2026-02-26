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

    <link href="<?= base_url() ?>assets/css/summary.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/t_patrol.css" rel="stylesheet">

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }

        /* ==== Submenu container ==== */
        .menu-item.has-submenu {
            flex-direction: column;
            align-items: stretch;
        }

        /* ==== Toggle row (biar sama kayak menu-item lain) ==== */
        .menu-item.submenu-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        /* kiri: icon + text sejajar */
        .menu-item.submenu-toggle .menu-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* icon ukuran konsisten (opsional) */
        .menu-item.submenu-toggle i,
        .submenu-item i {
            font-size: 18px;
            width: 22px;
            /* bikin icon kolomnya rata */
            text-align: center;
        }

        /* chevron di kanan */
        .chevron {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        /* open state */
        .menu-item.has-submenu.open .chevron {
            transform: rotate(180deg);
        }

        /* ==== Submenu items ==== */
        .submenu {
            display: none;
            flex-direction: column;
            padding-left: 42px;
            /* indent rapi */
            margin-top: 6px;
            gap: 6px;
        }

        .menu-item.has-submenu.open .submenu {
            display: flex;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            text-decoration: none;
            color: #ddd;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.08);
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
            "><?= $role ?></p>
        </div>
        <a href="<?= base_url('summary') ?>" class="menu-item " data-page="dashboard">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <div class="menu-item has-submenu">
            <div class="menu-item submenu-toggle">
                <div class="menu-left">
                    <i class="bi bi-search"></i>
                    <span>Data Patrol</span>
                </div>
                <i class="bi bi-chevron-down chevron"></i>
            </div>

            <div class="submenu">
                <a href="<?= base_url('temuan_patrol/auditor') ?>" class="submenu-item">
                    <i class="bi bi-person-badge"></i>
                    <span>Data Auditor</span>
                </a>
                <a href="<?= base_url('temuan_patrol/auditee') ?>" class="submenu-item">
                    <i class="bi bi-person-check"></i>
                    <span>Data Auditee</span>
                </a>
            </div>
        </div>
        <a href="<?= base_url('schedule') ?>" class="menu-item" data-page="schedule">
            <i class="bi bi-calendar-check"></i>
            <span>Schedule</span>
        </a>
        <div class="menu-header">
            Master Data
        </div>
        <?php if ($role === 'Administrator') : ?>
            <a href="<?= base_url('admin/mdata_user') ?>" class="menu-item " data-page="user">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>
            <a href="<?= base_url('admin/mdata_departemen') ?>" class="menu-item active" data-page="departemen">
                <i class="bi bi-building"></i>
                <span>Departemen</span>
            </a>

        <?php endif; ?>
    </div><!-- Main Content -->
    <div class="main-content" id="mainContent">
        <h2 class="page-title" id="dashboardTitle">Master data User</h2><!-- Charts Row 1 -->

        <!-- Data Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Departemen Quality Patrol</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tambahdata">
                        <i class="bi bi-plus-circle"></i> Tambah Data</button>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <table id="auditTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Departement</th>
                            <th>ID Dept Henkanten</th>
                            <th width="100">Section</th>

                            <th width="150"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_dept as $dd) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $dd['departement'] ?></td>
                                <td><?= $dd['id_departement_henk'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn_lihatsection" data-id="<?= $dd['id_departement'] ?>"><i class="bi bi-eye"></i> Lihat</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn_editdept" data-id="<?= $dd['id_departement'] ?>"><i class="bi bi-pencil-square"></i> </button>
                                    <button type="button" class="btn btn-danger btn_hapusdept" data-id="<?= $dd['id_departement'] ?>"><i class="bi bi-trash"></i> </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-card">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Departemen Henkaten</h3>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <table id="auditTable2" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Dept</th>
                            <th>Departement</th>
                            <th width="100">Section</th>

                            <th width="150"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_dept_henk as $ddh) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $ddh['id_departement'] ?></td>
                                <td><?= $ddh['departement'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn_lihatsection2" data-id="<?= $ddh['id_departement'] ?>"><i class="bi bi-eye"></i> Lihat</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn_hapusdept" data-id="<?= $ddh['id_departement'] ?>"><i class="bi bi-trash"></i> </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-card">
            <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Master Data Karyawan (Henkaten)</h3>
                </div>

            </div>
            <div class="table-responsive mt-3">
                <table id="auditTable3" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NPK</th>
                            <th>Nama</th>
                            <th width="100">ID Departement</th>
                            <th width="100">ID Section</th>
                            <th width="100">Jabatan</th>
                            <th width="100">Email</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_karyawan as $dkh) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $dkh['npk'] ?></td>
                                <td><?= $dkh['nama'] ?></td>
                                <td><?= $dkh['id_departement'] ?></td>
                                <td><?= $dkh['id_section'] ?></td>
                                <td><?= $dkh['jabatan'] ?></td>
                                <td><?= $dkh['email'] ?></td>

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
    <div class="modal fade" id="modal_lihatsection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Section</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-header d-flex justify-content-end align-items-center">

                        <button type="button" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Data
                        </button>
                    </div>

                    <div class="row mt-2">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Section</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_section1">


                            </tbody>
                        </table>

                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_sendData"><i class="bi bi-plus-circle"></i> Tambah Data User </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit_dept" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Departemen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="row mt-2">
                        <div class="form-group">
                            <label for="Role" class="form-label">
                                <i class="bi bi-building me-1"></i>Departemen
                            </label>
                            <select class="form-select select2" id="edit_data_dept" style="width:100%;">
                                <option value="">-- Pilih Opsi --</option>


                            </select>

                        </div>

                    </div>



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
        document.querySelectorAll('.submenu-toggle').forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.classList.toggle('open');
            });
        });
        var baseurl = '<?= base_url() ?>';
    </script>
    <script>
        $("#auditTable").DataTable({});
        $("#auditTable2").DataTable({});
        $("#auditTable3").DataTable({});
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
        $('.btn_lihatsection').click(function() {
            $('#modal_lihatsection').modal('show');
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    id_dept: id,
                    keterangan: 'get_detail_dept_qp'
                },
                success: function(response) {
                    // Perbarui elemen seksi berdasarkan response.html_seksi
                    $('#tbody_section1').html(response.data);

                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mengambil data seksi.');
                }
            });
        });
        $(document).on('click', '.btn_section_edit', function() {
            var id = $(this).data('id');
            alert(id);
        });
        $('.btn_editdept').click(function() {
            $('#modal_edit_dept').modal('show');
            var id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('sendData') ?>',
                type: 'POST',
                data: {
                    npk: selectNPK,
                    keterangan: 'get_detail_dept'
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
        $('.btnhapus_user').click(function() {
            var userId = $(this).data('id');
            console.log('Menghapus user dengan ID:', userId);
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: {
                        id_user: userId,
                        keterangan: 'hapus_user'
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
                        alert('Terjadi kesalahan saat menghapus user.');
                    }
                });
            }
        });
    </script>
</body>

</html>