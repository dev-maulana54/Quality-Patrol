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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      .topbar-nav { display:flex; align-items:center; gap:6px; margin-left:20px; }
      .topbar-nav-link { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; color:#475569; font-size:13px; font-weight:500; text-decoration:none; transition:all 0.2s ease; }
      .topbar-nav-link:hover, .topbar-nav-link:focus, .topbar-nav-link.active { background-color:#eef2ff; color:#4f46e5; font-weight:600; }
      .dark-theme .topbar-nav-link { color:#cbd5e1; }
      .dark-theme .topbar-nav-link:hover, .dark-theme .topbar-nav-link:focus, .dark-theme .topbar-nav-link.active { background-color:#334155; color:#818cf8; }
      .main-content { margin-left: 0 !important; width: 100% !important; padding: 30px !important; }
      .footer { margin-left: 0 !important; }
    </style>
</head>

<body class="light-theme">
    <!-- Header -->
    <header class="topbar">
      <div class="topbar-left">
        <a class="topbar-brand" href="<?= base_url('summary') ?>" aria-label="Quality Patrol">
          <span class="topbar-mark"><i class="bi bi-shield-check"></i></span>
          <span id="appName">Quality Patrol</span>
        </a>
        <nav class="topbar-nav d-none d-md-flex">
          <a class="topbar-nav-link" href="<?= base_url('summary') ?>"><i class="bi bi-house-door"></i> Home</a>
          <a class="topbar-nav-link" href="<?= base_url('temuan_patrol/daftar_temuan') ?>"><i class="bi bi-list-check"></i> Daftar Temuan</a>
          <div class="dropdown d-inline-block">
            <a class="topbar-nav-link dropdown-toggle active" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-database"></i> Master Data
            </a>
            <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="masterDataDropdown">
              <li><a class="dropdown-item py-2 active fw-semibold" href="<?= base_url('admin/mdata_user') ?>"><i class="bi bi-people me-2 text-primary"></i> User</a></li>
              <li><a class="dropdown-item py-2" href="<?= base_url('admin/mdata_departemen') ?>"><i class="bi bi-building me-2 text-primary"></i> Departemen</a></li>
            </ul>
          </div>
        </nav>
      </div>
      <div class="topbar-actions">
        <div class="topbar-user">
          <span id="topbarUserName" class="topbar-user-name"><?= isset($nama) ? $nama : 'User'; ?></span>
          <div class="profile-dropdown">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode(isset($nama) ? $nama : 'User'); ?>&amp;background=0d6efd&amp;color=fff&amp;size=128" alt="Profil pengguna" class="profile-img" id="profileImg" />
            <div class="dropdown-menu dropdown-menu-end shadow-sm" id="profileDropdown">
              <div class="d-md-none border-bottom pb-2 mb-2 px-2">
                <a class="dropdown-item py-1" href="<?= base_url('summary') ?>"><i class="bi bi-house-door me-2"></i> Home</a>
                <a class="dropdown-item py-1" href="<?= base_url('temuan_patrol/daftar_temuan') ?>"><i class="bi bi-list-check me-2"></i> Daftar Temuan</a>
                <div class="dropdown-header px-0 text-muted fw-bold small mt-1">MASTER DATA</div>
                <a class="dropdown-item py-1 ps-3 active" href="<?= base_url('admin/mdata_user') ?>"><i class="bi bi-people me-2"></i> User</a>
                <a class="dropdown-item py-1 ps-3" href="<?= base_url('admin/mdata_departemen') ?>"><i class="bi bi-building me-2"></i> Departemen</a>
              </div>
              <button class="topbar-menu-action theme-toggle" id="themeToggle" type="button"><i class="bi bi-sun-fill me-2"></i><span>Ubah tema</span></button>
              <a href="javascript:void(0)" class="dropdown-item text-danger fw-semibold" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
          </div>
        </div>
      </div>
    </header>
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
                            <!-- <th>Departemen</th>
                            <th width="200">Seksi</th> -->
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
                                <!-- <td>
                                    <?php
                                    foreach ($data_dept as $dept) {
                                        if ($dept['id_departement'] == $user['id_departement']) {
                                            echo $dept['departement'];
                                            break;
                                        }
                                    }
                                    ?>
                                </td> -->
                                <!-- <td>
                                    
                                    <?php foreach ($data_seksi as $seksi) {
                                        if ($seksi['id_section'] == $user['id_section']) {
                                            echo $seksi['section'];
                                            break;
                                        }
                                    } ?>
                                </td> -->
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

                                    <button type="button" class="btn btn-danger btnhapus_user" data-id="<?= $user['user_id'] ?>"><i class="bi bi-trash3-fill"></i> Hapus</button>
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
        document.querySelectorAll('.submenu-toggle').forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.classList.toggle('open');
            });
        });
        var baseurl = '<?= base_url() ?>';
    </script>
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
        $(document).on('click', '.btnhapus_user', function() {
            var userId = $(this).data('id');
            var doDelete = function() {
                $.ajax({
                    url: '<?= base_url('sendData') ?>',
                    type: 'POST',
                    data: {
                        id_user: userId,
                        keterangan: 'hapus_user'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 1800, showConfirmButton: false }).then(function() {
                                    location.reload();
                                });
                            } else {
                                alert(response.message);
                                location.reload();
                            }
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                            } else {
                                alert('Error: ' + response.message);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menghapus user.' });
                        } else {
                            alert('Terjadi kesalahan saat menghapus user.');
                        }
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus User?',
                    text: 'Apakah Anda yakin ingin menghapus user ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) doDelete();
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus user ini?')) doDelete();
            }
        });

        // Logout listener
        $(document).on('click', '#logoutBtn', function(e) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari aplikasi?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = '<?= base_url('auth/logout') ?>';
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin logout?')) {
                    window.location.href = '<?= base_url('auth/logout') ?>';
                }
            }
        });
    </script>
</body>

</html>