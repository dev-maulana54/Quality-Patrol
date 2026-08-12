/**
 * localStorage_db.js
 * ==================
 * Database terpusat berbasis LocalStorage untuk Quality Patrol App
 * Pengganti backend PHP/CodeIgniter (Models + Controllers)
 *
 * Keys:
 *  qp_users         → array user sistem (auditor, auditee, admin)
 *  qp_employees     → array karyawan (master data dari henkanten)
 *  qp_departments   → array departemen QP
 *  qp_departments_henk → array departemen henkanten
 *  qp_sections      → array seksi / section
 *  qp_schedules     → array jadwal patrol
 *  qp_patrol_data   → array data hasil patrol / temuan
 *  qp_attendance    → array daftar hadir
 *  qp_current_user  → object user yang sedang login (session)
 */

;(function (window) {
    'use strict';

    /* Safe storage: browser dapat memblokir localStorage. */
    function getStorageItem(key) {
        try { return window.localStorage.getItem(key); } catch (e) { return null; }
    }

    function setStorageItem(key, value) {
        try { window.localStorage.setItem(key, value); return true; } catch (e) { return false; }
    }

    function removeStorageItem(key) {
        try { window.localStorage.removeItem(key); return true; } catch (e) { return false; }
    }

    /* Mirror session untuk perpindahan antar halaman HTML pada tab yang sama. */
    function getSessionStorageItem(key) {
        try { return window.sessionStorage.getItem(key); } catch (e) { return null; }
    }

    function setSessionStorageItem(key, value) {
        try { window.sessionStorage.setItem(key, value); return true; } catch (e) { return false; }
    }

    function removeSessionStorageItem(key) {
        try { window.sessionStorage.removeItem(key); return true; } catch (e) { return false; }
    }
    var FILE_SESSION_PREFIX = '__QP_FILE_SESSION__:';

    function getFileSession() {
        if (window.location.protocol !== 'file:') return null;
        try {
            var raw = window.name || '';
            if (raw.indexOf(FILE_SESSION_PREFIX) !== 0) return null;
            var session = JSON.parse(raw.slice(FILE_SESSION_PREFIX.length));
            return session && session.user_id ? session : null;
        } catch (e) {
            return null;
        }
    }

    function setFileSession(session) {
        if (window.location.protocol !== 'file:') return;
        try { window.name = FILE_SESSION_PREFIX + JSON.stringify(session); } catch (e) {}
    }

    function clearFileSession() {
        if (window.location.protocol !== 'file:') return;
        try {
            if ((window.name || '').indexOf(FILE_SESSION_PREFIX) === 0) window.name = '';
        } catch (e) {}
    }
    function canUseStorage() {
        var probeKey = '__qp_storage_probe__';
        try {
            window.localStorage.setItem(probeKey, '1');
            window.localStorage.removeItem(probeKey);
            return true;
        } catch (e) {
            return false;
        }
    }

    /* =========================================================
     * SEED DATA — data awal untuk demo/testing
     * ========================================================= */
    const SEED_DATA = {
        qp_departments: [
            { id_departement: 1, departement: 'Manufacturing', id_departement_henk: 'MFG-01' },
            { id_departement: 2, departement: 'Quality Control', id_departement_henk: 'QC-01' },
            { id_departement: 3, departement: 'Engineering', id_departement_henk: 'ENG-01' },
            { id_departement: 4, departement: 'Maintenance', id_departement_henk: 'MTC-01' },
            { id_departement: 5, departement: 'Safety & Health', id_departement_henk: 'SHE-01' },
            { id_departement: 6, departement: 'Production', id_departement_henk: 'PROD-01' },
            { id_departement: 7, departement: 'Logistic', id_departement_henk: 'LOG-01' },
        ],
        qp_departments_henk: [
            { id_departement: 'MFG-01', departement: 'Manufacturing' },
            { id_departement: 'QC-01',  departement: 'Quality Control' },
            { id_departement: 'ENG-01', departement: 'Engineering' },
            { id_departement: 'MTC-01', departement: 'Maintenance' },
            { id_departement: 'SHE-01', departement: 'Safety & Health' },
            { id_departement: 'PROD-01',departement: 'Production' },
            { id_departement: 'LOG-01', departement: 'Logistic' },
        ],
        qp_sections: [
            { id_section: 1, id_departement: 1, section: 'Assembly' },
            { id_section: 2, id_departement: 1, section: 'Welding' },
            { id_section: 3, id_departement: 2, section: 'Incoming QC' },
            { id_section: 4, id_departement: 2, section: 'Outgoing QC' },
            { id_section: 5, id_departement: 3, section: 'Process Engineering' },
            { id_section: 6, id_departement: 4, section: 'Electrical Maintenance' },
            { id_section: 7, id_departement: 5, section: 'Safety Patrol' },
            { id_section: 8, id_departement: 6, section: 'Line 1' },
            { id_section: 9, id_departement: 6, section: 'Line 2' },
            { id_section: 10, id_departement: 7, section: 'Warehouse' },
        ],
        qp_employees: [
            { npk: '10001', nama: 'Budi Santoso',     id_departement: 1, id_section: 1, jabatan: 'Operator',    email: 'budi.s@cbi.co.id' },
            { npk: '10002', nama: 'Siti Rahayu',      id_departement: 2, id_section: 3, jabatan: 'QC Inspector', email: 'siti.r@cbi.co.id' },
            { npk: '10003', nama: 'Ahmad Fauzi',      id_departement: 3, id_section: 5, jabatan: 'Engineer',     email: 'ahmad.f@cbi.co.id' },
            { npk: '10004', nama: 'Dewi Lestari',     id_departement: 4, id_section: 6, jabatan: 'Technician',   email: 'dewi.l@cbi.co.id' },
            { npk: '10005', nama: 'Eko Prasetyo',     id_departement: 5, id_section: 7, jabatan: 'Safety Officer',email: 'eko.p@cbi.co.id' },
            { npk: '10006', nama: 'Fitri Handayani',  id_departement: 6, id_section: 8, jabatan: 'Supervisor',   email: 'fitri.h@cbi.co.id' },
            { npk: '10007', nama: 'Gunawan Wijaya',   id_departement: 7, id_section: 10,jabatan: 'Warehouse Staff',email:'gunawan.w@cbi.co.id' },
            { npk: '10008', nama: 'Hendra Kusuma',    id_departement: 1, id_section: 2, jabatan: 'Welder',       email: 'hendra.k@cbi.co.id' },
            { npk: '10009', nama: 'Indah Permata',    id_departement: 2, id_section: 4, jabatan: 'QC Staff',     email: 'indah.p@cbi.co.id' },
            { npk: '10010', nama: 'Joko Susilo',      id_departement: 6, id_section: 9, jabatan: 'Operator',     email: 'joko.s@cbi.co.id' },
        ],
        qp_users: [
            { user_id: 1, npk: '10001', nama: 'Budi Santoso',    role: 1, username: 'admin',    password: 'admin123',    id_departement: 1, id_section: 1 },
            { user_id: 2, npk: '10003', nama: 'Ahmad Fauzi',     role: 2, username: 'auditor1', password: 'auditor123',  id_departement: 3, id_section: 5 },
            { user_id: 3, npk: '10005', nama: 'Eko Prasetyo',    role: 2, username: 'auditor2', password: 'auditor123',  id_departement: 5, id_section: 7 },
            { user_id: 4, npk: '10002', nama: 'Siti Rahayu',     role: 3, username: 'auditee1', password: 'auditee123',  id_departement: 2, id_section: 3 },
            { user_id: 5, npk: '10006', nama: 'Fitri Handayani', role: 3, username: 'auditee2', password: 'auditee123',  id_departement: 6, id_section: 8 },
        ],
        qp_schedules: [
            { id_schedule: 1, tanggal: '2025-07-28', shift: 'Pagi',  id_auditor: 2, nama_auditor: 'Ahmad Fauzi',     id_auditee: 4, nama_auditee: 'Siti Rahayu',     id_departement: 2, departement: 'Quality Control', status: 'Belum', keterangan: '' },
            { id_schedule: 2, tanggal: '2025-07-28', shift: 'Siang', id_auditor: 3, nama_auditor: 'Eko Prasetyo',    id_auditee: 5, nama_auditee: 'Fitri Handayani', id_departement: 6, departement: 'Production',      status: 'Selesai', keterangan: 'Sudah diverifikasi' },
            { id_schedule: 3, tanggal: '2025-07-29', shift: 'Pagi',  id_auditor: 2, nama_auditor: 'Ahmad Fauzi',     id_auditee: 5, nama_auditee: 'Fitri Handayani', id_departement: 6, departement: 'Production',      status: 'Belum', keterangan: '' },
            { id_schedule: 4, tanggal: '2025-07-30', shift: 'Pagi',  id_auditor: 3, nama_auditor: 'Eko Prasetyo',    id_auditee: 4, nama_auditee: 'Siti Rahayu',     id_departement: 2, departement: 'Quality Control', status: 'Belum', keterangan: '' },
        ],
        qp_patrol_data: [
            {
                id_patrol: 1, id_schedule: 2, tanggal: '2025-07-28',
                id_auditor: 3, nama_auditor: 'Eko Prasetyo',
                id_auditee: 5, nama_auditee: 'Fitri Handayani',
                id_departement: 6, departement: 'Production',
                temuan: [
                    { id_temuan: 1, kategori: 'Safety', deskripsi: 'APD tidak lengkap di area produksi', foto: '', status: 'Open', tindakan: '', deadline: '2025-08-05' },
                    { id_temuan: 2, kategori: 'Housekeeping', deskripsi: 'Area kerja kotor dan tidak tertata', foto: '', status: 'Close', tindakan: 'Sudah dibersihkan', deadline: '2025-08-01' },
                ],
                status: 'Selesai', ttd_auditor: '', ttd_auditee: ''
            }
        ],
        qp_attendance: [
            { id_attendance: 1, id_schedule: 2, id_user: 3, nama: 'Eko Prasetyo',    role_label: 'Auditor', tanggal: '2025-07-28', jam_masuk: '08:05', status: 'Hadir' },
            { id_attendance: 2, id_schedule: 2, id_user: 5, nama: 'Fitri Handayani', role_label: 'Auditee', tanggal: '2025-07-28', jam_masuk: '08:12', status: 'Hadir' },
        ]
    };

    /* =========================================================
     * INIT — jalankan seed data jika belum ada di localStorage
     * ========================================================= */
    function initSeedData() {
        Object.keys(SEED_DATA).forEach(function (key) {
            if (!getStorageItem(key)) {
                setStorageItem(key, JSON.stringify(SEED_DATA[key]));
            }
        });
        // Pastikan qp_current_user ada
        if (!getStorageItem('qp_current_user')) {
            setStorageItem('qp_current_user', JSON.stringify(null));
        }
    }

    /* =========================================================
     * GENERIC CRUD
     * ========================================================= */

    /**
     * Baca semua record dari sebuah key
     * @param {string} key - localStorage key
     * @returns {Array}
     */
    function readAll(key) {
        try {
            return JSON.parse(getStorageItem(key)) || [];
        } catch (e) {
            return [];
        }
    }

    /**
     * Baca satu record berdasarkan ID field
     * @param {string} key
     * @param {string} idField - nama field primary key
     * @param {*} idValue
     * @returns {object|null}
     */
    function readById(key, idField, idValue) {
        var data = readAll(key);
        return data.find(function (r) { return r[idField] == idValue; }) || null;
    }

    /**
     * Buat record baru, auto-increment ID
     * @param {string} key
     * @param {string} idField
     * @param {object} record
     * @returns {object} record yang baru dibuat (dengan ID)
     */
    function createRecord(key, idField, record) {
        var data = readAll(key);
        // Auto-increment
        var maxId = 0;
        data.forEach(function (r) { if (r[idField] > maxId) maxId = parseInt(r[idField]); });
        record[idField] = maxId + 1;
        data.push(record);
        setStorageItem(key, JSON.stringify(data));
        return record;
    }

    /**
     * Update record berdasarkan ID
     * @param {string} key
     * @param {string} idField
     * @param {*} idValue
     * @param {object} updates - field-field yang diperbarui
     * @returns {boolean}
     */
    function updateRecord(key, idField, idValue, updates) {
        var data = readAll(key);
        var idx = data.findIndex(function (r) { return r[idField] == idValue; });
        if (idx === -1) return false;
        data[idx] = Object.assign({}, data[idx], updates);
        setStorageItem(key, JSON.stringify(data));
        return true;
    }

    /**
     * Hapus record berdasarkan ID
     * @param {string} key
     * @param {string} idField
     * @param {*} idValue
     * @returns {boolean}
     */
    function deleteRecord(key, idField, idValue) {
        var data = readAll(key);
        var filtered = data.filter(function (r) { return r[idField] != idValue; });
        if (filtered.length === data.length) return false;
        setStorageItem(key, JSON.stringify(filtered));
        return true;
    }

    /**
     * Query dengan filter custom
     * @param {string} key
     * @param {function} filterFn - function(record) returns boolean
     * @returns {Array}
     */
    function queryRecords(key, filterFn) {
        return readAll(key).filter(filterFn);
    }

    /* =========================================================
     * AUTH FUNCTIONS
     * ========================================================= */

    /**
     * Login user
     * @param {string} username
     * @param {string} password
     * @returns {{ ok: boolean, msg: string, user?: object }}
     */
    function loginUser(username, password) {
        if (!canUseStorage()) {
            return { ok: false, msg: 'LocalStorage browser tidak tersedia. Aktifkan penyimpanan browser atau buka aplikasi melalui web server.' };
        }
        var users = readAll('qp_users');
        var user = users.find(function (u) {
            return u.username === username && u.password === password;
        });
        if (!user) {
            return { ok: false, msg: 'Username atau password salah.' };
        }
        var session = {
            user_id: user.user_id,
            npk: user.npk,
            nama: user.nama,
            role: user.role,
            role_label: getRoleLabel(user.role),
            username: user.username,
            id_departement: user.id_departement,
            id_section: user.id_section
        };
        var serializedSession = JSON.stringify(session);
        removeStorageItem('qp_logged_out');
        removeSessionStorageItem('qp_logged_out');
        if (!setStorageItem('qp_current_user', serializedSession)) {
            return { ok: false, msg: 'Sesi tidak dapat disimpan di browser. Silakan coba lagi.' };
        }
        if (!getStorageItem('qp_current_user')) {
            return { ok: false, msg: 'Sesi login tidak tersimpan. Silakan coba lagi.' };
        }
        setSessionStorageItem('qp_current_user', serializedSession);
        setFileSession(session);
        return { ok: true, msg: 'Login berhasil.', user: session };
    }

    /**
     * Logout - Menghapus sesi user & menandai status logged out di localStorage
     */
    function logoutUser() {
        removeStorageItem('qp_current_user');
        removeSessionStorageItem('qp_current_user');
        setStorageItem('qp_logged_out', 'true');
        removeSessionStorageItem('__qp_summary_auth_redirect__');
        clearFileSession();
    }

    /**
     * Ambil user yang sedang login
     * @returns {object|null}
     */
    function normalizeRole(role) {
        if (typeof role === 'string') {
            var roleName = role.trim().toLowerCase();
            var roleByName = {
                '1': 1,
                'admin': 1,
                'administrator': 1,
                '2': 2,
                'auditor': 2,
                '3': 3,
                'auditee': 3
            };
            if (Object.prototype.hasOwnProperty.call(roleByName, roleName)) {
                return roleByName[roleName];
            }
        }

        var numericRole = Number(role);
        return isFinite(numericRole) ? numericRole : role;
    }

    function normalizeUser(user) {
        if (!user || typeof user !== 'object') return null;

        var normalized = Object.assign({}, user);
        normalized.user_id = normalized.user_id || normalized.userId || normalized.id || normalized.id_user || normalized.user_row_id;

        // Lengkapi sesi versi lama dari master user. Beberapa versi hanya
        // menyimpan username/NPK atau menyimpan role dengan nama field berbeda.
        var storedUser = readAll('qp_users').find(function (item) {
            return (normalized.user_id && String(item.user_id) === String(normalized.user_id)) ||
                (normalized.username && item.username === normalized.username) ||
                (normalized.npk && String(item.npk) === String(normalized.npk));
        });
        if (storedUser) normalized = Object.assign({}, storedUser, normalized);

        var roleValue = normalized.role || normalized.role_id || normalized.role_name ||
            normalized.active_role || (storedUser && storedUser.role) || normalized.role_label;
        if (roleValue !== undefined && roleValue !== null && roleValue !== '') {
            var resolvedRole = normalizeRole(roleValue);
            var storedRole = storedUser ? normalizeRole(storedUser.role) : null;
            var knownRoles = [1, 2, 3];
            if (knownRoles.indexOf(resolvedRole) === -1 && knownRoles.indexOf(storedRole) !== -1) {
                resolvedRole = storedRole;
            }
            normalized.role = resolvedRole;
        }

        if (!normalized.user_id) return null;
        normalized.role = normalizeRole(normalized.role);
        normalized.role_label = normalized.role_label || getRoleLabel(normalized.role);
        return normalized;
    }

    function getCurrentUser() {
        try {
            var u = null;
            try { u = JSON.parse(getStorageItem('qp_current_user')); } catch (e) {}
            u = normalizeUser(u);
            if (!u) {
                try { u = JSON.parse(getSessionStorageItem('qp_current_user')); } catch (e) {}
            }
            u = normalizeUser(u);
            if (!u) u = normalizeUser(getFileSession());
            if (u) {
                // Sesi valid adalah sumber kebenaran utama; flag logout lama diabaikan.
                removeStorageItem('qp_logged_out');
                removeSessionStorageItem('qp_logged_out');
                return u;
            }

            return null;
        } catch (e) {
            return null;
        }
    }

    /**
     * Cek apakah sudah login, jika tidak redirect ke login
     * @param {string} [redirectPath='../../auth/login.html']
     */
    function requireAuth(redirectPath) {
        var user = getCurrentUser();
        if (!user) {
            window.location.replace(redirectPath || '../../auth/login.html');
        }
        return user;
    }

    /**
     * Cek apakah user memiliki role tertentu
     * @param {number|number[]} allowedRoles
     */
    function requireRole(allowedRoles, redirectPath) {
        var user = requireAuth(redirectPath);
        if (!user) return null;
        var roles = Array.isArray(allowedRoles) ? allowedRoles : [allowedRoles];
        var currentRole = String(normalizeRole(user.role));
        var hasRole = roles.some(function (role) {
            return String(normalizeRole(role)) === currentRole;
        });
        if (!hasRole) {
            alert('Anda tidak memiliki akses ke halaman ini.');
            window.history.back();
            return null;
        }
        return user;
    }

    /**
     * Konversi role number ke label
     * @param {number} role
     * @returns {string}
     */
    function getRoleLabel(role) {
        var labels = { 1: 'Administrator', 2: 'Auditor', 3: 'Auditee' };
        return labels[role] || 'Unknown';
    }

    /* =========================================================
     * NAVIGATION HELPERS
     * ========================================================= */

    var ROUTES = {
        'summary':              '../../users/summary.html',
        'schedule':             '../../users/schedule.html',
        'temuan_patrol/auditor':'../../users/temuan_patrol.html',
        'temuan_patrol/auditee':'../../users/temuan_auditee.html',
        'confirm_audit':        '../../users/confirm_audit.html',
        'daftar_hadir':         '../../users/daftar_hadir.html',
        'admin/mdata_user':     '../../admin/mdata_user.html',
        'admin/mdata_departemen':'../../admin/mdata_departement.html',
        'login':                '../../auth/login.html',
        'mobile/summary':       '../../mobile/summary.html',
        'mobile/data_patrol':   '../../mobile/data_patrol.html',
    };

    function navigate(routeKey) {
        var path = ROUTES[routeKey] || '#';
        window.location.href = path;
    }

    /* =========================================================
     * UI HELPERS
     * ========================================================= */

    /**
     * Render informasi user login ke elemen sidebar
     * - #sidebarUserName → nama user
     * - #sidebarRole     → role user
     * - #profileImg      → avatar
     */
    function renderUserInfo() {
        var user = getCurrentUser();
        if (!user) return;

        var nameEl = document.getElementById('sidebarUserName');
        var roleEl = document.getElementById('sidebarRole');
        var imgEl  = document.getElementById('profileImg');

        if (nameEl) nameEl.textContent = user.nama;
        if (roleEl) roleEl.textContent = user.role_label;
        if (imgEl)  imgEl.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nama) + '&background=0d6efd&color=fff&size=128';
    }

    /**
     * Render sidebar nav — tampilkan/sembunyikan item berdasarkan role
     * Role 1 = Admin: semua menu
     * Role 2 = Auditor: summary, schedule, temuan_patrol
     * Role 3 = Auditee: summary, temuan_auditee
     */
    function renderSidebarRole() {
        var user = getCurrentUser();
        if (!user) return;

        var adminMenus = document.querySelectorAll('[data-role-required="1"]');
        adminMenus.forEach(function (el) {
            el.style.display = (user.role === 1) ? '' : 'none';
        });
    }

    /**
     * Bind logout button
     */
    function bindLogout() {
        var logoutBtns = [document.getElementById('logoutBtn'), document.getElementById('logoutAction')].filter(Boolean);
        logoutBtns.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: 'Apakah Anda yakin ingin keluar dari sistem?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            logoutUser();
                            window.location.href = getRelativePath('auth/login.html');
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin keluar?')) {
                        logoutUser();
                        window.location.href = getRelativePath('auth/login.html');
                    }
                }
            });
        });
    }

    /**
     * Dapatkan path relatif dari lokasi file saat ini ke file target
     * Mencoba mendeteksi kedalaman folder berdasarkan window.location.pathname
     * @param {string} target - misal 'auth/login.html'
     */
    function getRelativePath(target) {
        var path = window.location.pathname;
        // Hitung kedalaman folder dalam path
        var parts = path.split('/').filter(Boolean);
        // Asumsi struktur: /app/Views/{folder}/{file.html}
        var viewsIdx = parts.indexOf('Views');
        if (viewsIdx === -1) viewsIdx = parts.indexOf('views');
        var depth = (viewsIdx !== -1) ? Math.max(parts.length - viewsIdx - 2, 0) : 1;
        var prefix = '';
        for (var i = 0; i < depth; i++) prefix += '../';
        return prefix + target;
    }

    /* =========================================================
     * LOOKUP HELPERS (menggantikan JOIN SQL)
     * ========================================================= */

    /** Ambil nama departemen berdasarkan id_departement */
    function getDeptName(id) {
        var dept = readById('qp_departments', 'id_departement', id);
        return dept ? dept.departement : '-';
    }

    /** Ambil nama seksi berdasarkan id_section */
    function getSectionName(id) {
        var sec = readById('qp_sections', 'id_section', id);
        return sec ? sec.section : '-';
    }

    /** Ambil sections berdasarkan id_departement */
    function getSectionsByDept(id_departement) {
        return queryRecords('qp_sections', function (s) { return s.id_departement == id_departement; });
    }

    /** Ambil karyawan (employee) berdasarkan NPK */
    function getEmployeeByNPK(npk) {
        return readById('qp_employees', 'npk', npk);
    }

    /** Ambil user berdasarkan user_id */
    function getUserById(userId) {
        return readById('qp_users', 'user_id', userId);
    }

    /* =========================================================
     * SCHEDULE HELPERS
     * ========================================================= */

    function getSchedulesByAuditor(auditorId) {
        return queryRecords('qp_schedules', function (s) { return s.id_auditor == auditorId; });
    }

    function getSchedulesByAuditee(auditeeId) {
        return queryRecords('qp_schedules', function (s) { return s.id_auditee == auditeeId; });
    }

    function getSchedulesByMonth(year, month) {
        return queryRecords('qp_schedules', function (s) {
            var d = new Date(s.tanggal);
            return d.getFullYear() === year && (d.getMonth() + 1) === month;
        });
    }

    /* =========================================================
     * PATROL DATA HELPERS
     * ========================================================= */

    function getPatrolBySchedule(scheduleId) {
        return queryRecords('qp_patrol_data', function (p) { return p.id_schedule == scheduleId; });
    }

    function getPatrolByAuditor(auditorId) {
        return queryRecords('qp_patrol_data', function (p) { return p.id_auditor == auditorId; });
    }

    function getPatrolByAuditee(auditeeId) {
        return queryRecords('qp_patrol_data', function (p) { return p.id_auditee == auditeeId; });
    }

    /* =========================================================
     * SUMMARY / STATISTIK HELPERS
     * ========================================================= */

    function getSummaryStats() {
        var patrol = readAll('qp_patrol_data');
        var schedules = readAll('qp_schedules');
        var allTemuan = [];
        patrol.forEach(function (p) {
            if (Array.isArray(p.temuan)) {
                p.temuan.forEach(function (t) { allTemuan.push(t); });
            }
        });
        return {
            total_schedule: schedules.length,
            total_selesai: schedules.filter(function (s) { return s.status === 'Selesai'; }).length,
            total_belum: schedules.filter(function (s) { return s.status === 'Belum'; }).length,
            total_temuan: allTemuan.length,
            temuan_open: allTemuan.filter(function (t) { return t.status === 'Open'; }).length,
            temuan_close: allTemuan.filter(function (t) { return t.status === 'Close'; }).length,
        };
    }

    /* =========================================================
     * RESET (untuk keperluan development/testing)
     * ========================================================= */

    function resetAllData() {
        Object.keys(SEED_DATA).forEach(function (key) {
            removeStorageItem(key);
        });
        removeStorageItem('qp_current_user');
        initSeedData();
    }

    /* =========================================================
     * EXPORT ke window.QPDB
     * ========================================================= */
    window.QPDB = {
        // Init
        init: initSeedData,
        reset: resetAllData,

        // Generic CRUD
        readAll: readAll,
        readById: readById,
        createRecord: createRecord,
        updateRecord: updateRecord,
        deleteRecord: deleteRecord,
        queryRecords: queryRecords,

        // Auth
        loginUser: loginUser,
        logoutUser: logoutUser,
        getCurrentUser: getCurrentUser,
        getStorageItem: getStorageItem,
        setStorageItem: setStorageItem,
        removeStorageItem: removeStorageItem,
        canUseStorage: canUseStorage,
        requireAuth: requireAuth,
        requireRole: requireRole,
        getRoleLabel: getRoleLabel,

        // Navigation
        navigate: navigate,
        getRelativePath: getRelativePath,
        ROUTES: ROUTES,

        // UI Helpers
        renderUserInfo: renderUserInfo,
        renderSidebarRole: renderSidebarRole,
        bindLogout: bindLogout,

        // Lookup Helpers
        getDeptName: getDeptName,
        getSectionName: getSectionName,
        getSectionsByDept: getSectionsByDept,
        getEmployeeByNPK: getEmployeeByNPK,
        getUserById: getUserById,

        // Schedule Helpers
        getSchedulesByAuditor: getSchedulesByAuditor,
        getSchedulesByAuditee: getSchedulesByAuditee,
        getSchedulesByMonth: getSchedulesByMonth,

        // Patrol Helpers
        getPatrolBySchedule: getPatrolBySchedule,
        getPatrolByAuditor: getPatrolByAuditor,
        getPatrolByAuditee: getPatrolByAuditee,

        // Stats
        getSummaryStats: getSummaryStats,
    };

    // Auto-init saat script dimuat
    initSeedData();

    console.log('[QPDB] LocalStorage Database siap. Gunakan window.QPDB untuk akses data.');
    console.log('[QPDB] Akun demo: admin/admin123 | auditor1/auditor123 | auditee1/auditee123');

})(window);
