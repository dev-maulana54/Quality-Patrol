/*
 * Penyimpanan sederhana untuk versi HTML.
 * Tidak bergantung pada session PHP atau API backend.
 */
(function (window) {
    'use strict';

    var AUTH_KEY = 'qp_current_user';
    var FILE_SESSION_PREFIX = '__QUALITY_PATROL_FILE_SESSION__:';
    var URL_SESSION_PREFIX = '#qp_session=';

    var DEFAULT_DATA = {
        qp_users: [
            { user_id: 1, npk: '10001', nama: 'Budi Santoso', role: 1, username: 'admin', password: 'admin123', id_departement: 1, id_section: 1 },
            { user_id: 2, npk: '10003', nama: 'Ahmad Fauzi', role: 2, username: 'auditor1', password: 'auditor123', id_departement: 3, id_section: 5 },
            { user_id: 3, npk: '10005', nama: 'Eko Prasetyo', role: 2, username: 'auditor2', password: 'auditor123', id_departement: 5, id_section: 7 },
            { user_id: 4, npk: '10002', nama: 'Siti Rahayu', role: 3, username: 'auditee1', password: 'auditee123', id_departement: 2, id_section: 3 },
            { user_id: 5, npk: '10006', nama: 'Fitri Handayani', role: 3, username: 'auditee2', password: 'auditee123', id_departement: 6, id_section: 8 }
        ],
        qp_departments: [
            { id_departement: 1, departement: 'Manufacturing', id_departement_henk: 'MFG-01' },
            { id_departement: 2, departement: 'Quality Control', id_departement_henk: 'QC-01' },
            { id_departement: 3, departement: 'Engineering', id_departement_henk: 'ENG-01' },
            { id_departement: 4, departement: 'Maintenance', id_departement_henk: 'MTC-01' },
            { id_departement: 5, departement: 'Safety & Health', id_departement_henk: 'SHE-01' },
            { id_departement: 6, departement: 'Production', id_departement_henk: 'PROD-01' },
            { id_departement: 7, departement: 'Logistic', id_departement_henk: 'LOG-01' }
        ],
        qp_departments_henk: [
            { id_departement: 'MFG-01', departement: 'Manufacturing' },
            { id_departement: 'QC-01', departement: 'Quality Control' },
            { id_departement: 'ENG-01', departement: 'Engineering' },
            { id_departement: 'MTC-01', departement: 'Maintenance' },
            { id_departement: 'SHE-01', departement: 'Safety & Health' },
            { id_departement: 'PROD-01', departement: 'Production' },
            { id_departement: 'LOG-01', departement: 'Logistic' }
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
            { id_section: 10, id_departement: 7, section: 'Warehouse' }
        ],
        qp_employees: [
            { npk: '10001', nama: 'Budi Santoso', id_departement: 1, id_section: 1, jabatan: 'Operator' },
            { npk: '10002', nama: 'Siti Rahayu', id_departement: 2, id_section: 3, jabatan: 'QC Inspector' },
            { npk: '10003', nama: 'Ahmad Fauzi', id_departement: 3, id_section: 5, jabatan: 'Engineer' },
            { npk: '10004', nama: 'Dewi Lestari', id_departement: 4, id_section: 6, jabatan: 'Technician' },
            { npk: '10005', nama: 'Eko Prasetyo', id_departement: 5, id_section: 7, jabatan: 'Safety Officer' },
            { npk: '10006', nama: 'Fitri Handayani', id_departement: 6, id_section: 8, jabatan: 'Supervisor' },
            { npk: '10007', nama: 'Gunawan Wijaya', id_departement: 7, id_section: 10, jabatan: 'Warehouse Staff' },
            { npk: '10008', nama: 'Hendra Kusuma', id_departement: 1, id_section: 2, jabatan: 'Welder' },
            { npk: '10009', nama: 'Indah Permata', id_departement: 2, id_section: 4, jabatan: 'QC Staff' },
            { npk: '10010', nama: 'Joko Susilo', id_departement: 6, id_section: 9, jabatan: 'Operator' }
        ],
        qp_schedules: [],
        qp_patrol_data: []
    };

    function read(key, fallback) {
        try {
            var value = window.localStorage.getItem(key);
            return value === null ? (fallback === undefined ? null : fallback) : JSON.parse(value);
        } catch (error) {
            return fallback === undefined ? null : fallback;
        }
    }

    function write(key, value) {
        try {
            window.localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (error) {
            return false;
        }
    }

    function readFileSession() {
        if (window.location.protocol !== 'file:') return null;
        try {
            var raw = window.name || '';
            if (raw.indexOf(FILE_SESSION_PREFIX) !== 0) return null;
            return JSON.parse(raw.slice(FILE_SESSION_PREFIX.length));
        } catch (error) {
            return null;
        }
    }

    function writeFileSession(user) {
        if (window.location.protocol !== 'file:') return;
        try { window.name = FILE_SESSION_PREFIX + JSON.stringify(user); } catch (error) {}
    }

    function clearFileSession() {
        if (window.location.protocol !== 'file:') return;
        try {
            if ((window.name || '').indexOf(FILE_SESSION_PREFIX) === 0) window.name = '';
        } catch (error) {}
    }

    function logoutRequested() {
        try {
            return new URLSearchParams(window.location.search).get('logged_out') === '1';
        } catch (error) {
            return false;
        }
    }

    function removeLogoutMarker() {
        try {
            var url = new URL(window.location.href);
            url.searchParams.delete('logged_out');
            window.history.replaceState({}, document.title, url.href);
        } catch (error) {}
    }

    function clearLocalSession() {
        try {
            window.localStorage.removeItem(AUTH_KEY);
            window.sessionStorage.removeItem(AUTH_KEY);
        } catch (error) {}
        clearFileSession();
    }

    function encodeSession(user) {
        return encodeURIComponent(JSON.stringify(user));
    }

    function consumeUrlSession() {
        if (window.location.hash.indexOf(URL_SESSION_PREFIX) !== 0) return null;
        try {
            var encoded = window.location.hash.slice(URL_SESSION_PREFIX.length);
            var user = JSON.parse(decodeURIComponent(encoded));
            if (user && user.user_id) {
                write(AUTH_KEY, user);
                window.history.replaceState({}, document.title, window.location.href.split('#')[0]);
                return user;
            }
        } catch (error) {}
        return null;
    }

    function isFilePage() {
        return window.location.protocol === 'file:';
    }

    function redirectAfterLogin(defaultPath) {
        var target = defaultPath;
        try {
            var requested = new URLSearchParams(window.location.search).get('return');
            if (requested && requested.indexOf('/app/Views/') !== -1) target = requested;
        } catch (error) {}

        var user = currentUser();
        if (user && isFilePage()) target += URL_SESSION_PREFIX + encodeSession(user);
        window.location.replace(target);
    }

    function initialize() {
        Object.keys(DEFAULT_DATA).forEach(function (key) {
            if (window.localStorage.getItem(key) === null) write(key, DEFAULT_DATA[key]);
        });
    }

    function all(key) {
        var data = read(key, []);
        return Array.isArray(data) ? data : [];
    }

    function roleNumber(role) {
        var value = String(role === undefined || role === null ? '' : role).trim().toLowerCase();
        var roles = { admin: 1, administrator: 1, auditor: 2, auditee: 3 };
        return roles[value] || Number(value) || 0;
    }

    function roleLabel(role) {
        return ({ 1: 'Administrator', 2: 'Auditor', 3: 'Auditee' })[roleNumber(role)] || 'Unknown';
    }

    function currentUser() {
        // Penanda ini dikirim oleh tombol logout. Wajib diproses sebelum
        // membaca storage agar sesi lama milik login.html ikut dibersihkan.
        if (logoutRequested()) {
            clearLocalSession();
            removeLogoutMarker();
            return null;
        }

        var user = consumeUrlSession() || read(AUTH_KEY, null);
        // file:// dapat memiliki storage terpisah per file pada browser tertentu.
        // Sesi tetap ditulis ke localStorage setiap halaman; window.name hanya
        // menjadi jembatan saat navigasi masih berada di tab yang sama.
        if (!user) {
            user = readFileSession();
            if (user) write(AUTH_KEY, user);
        }
        if (!user || typeof user !== 'object' || !user.user_id) return null;
        user.role = roleNumber(user.role || user.role_id || user.role_name || user.role_label);
        user.role_label = user.role_label || roleLabel(user.role);
        writeFileSession(user);
        return user;
    }

    function login(username, password) {
        initialize();
        var user = all('qp_users').find(function (item) {
            return item.username === username && item.password === password;
        });
        if (!user) return { ok: false, message: 'Username atau password salah.' };

        var session = {
            user_id: user.user_id,
            npk: user.npk,
            nama: user.nama,
            username: user.username,
            role: roleNumber(user.role),
            role_label: roleLabel(user.role),
            id_departement: user.id_departement,
            id_section: user.id_section
        };
        if (!write(AUTH_KEY, session)) return { ok: false, message: 'Sesi login tidak dapat disimpan.' };
        writeFileSession(session);
        return { ok: true, user: session };
    }

    function logout() {
        clearLocalSession();
        try { window.localStorage.removeItem('qp_logged_out'); } catch (error) {}
        return !read(AUTH_KEY, null);
    }

    function requireLogin(redirectPath) {
        var user = currentUser();
        if (!user) {
            var loginPath = redirectPath || '../auth/login.html';
            if (isFilePage()) {
                loginPath += (loginPath.indexOf('?') === -1 ? '?' : '&') +
                    'return=' + encodeURIComponent(window.location.href.split('#')[0]);
            }
            window.location.replace(loginPath);
        }
        return user;
    }

    function requireAdmin(redirectPath, deniedPath) {
        var user = requireLogin(redirectPath);
        if (!user) return null;
        if (roleNumber(user.role) !== 1) {
            window.alert('Anda tidak memiliki akses ke halaman ini.');
            window.location.replace(deniedPath || '../users/summary.html');
            return null;
        }
        return user;
    }

    function create(key, idField, record) {
        var data = all(key);
        var maxId = data.reduce(function (max, item) {
            return Math.max(max, Number(item[idField]) || 0);
        }, 0);
        record[idField] = maxId + 1;
        data.push(record);
        write(key, data);
        return record;
    }

    function remove(key, idField, id) {
        var data = all(key);
        var filtered = data.filter(function (item) { return String(item[idField]) !== String(id); });
        if (filtered.length === data.length) return false;
        write(key, filtered);
        return true;
    }

    function employeeByNpk(npk) {
        return all('qp_employees').find(function (item) { return String(item.npk) === String(npk); }) || null;
    }

    function departmentName(id) {
        var item = all('qp_departments').find(function (dept) { return String(dept.id_departement) === String(id); });
        return item ? item.departement : '';
    }

    function sectionName(id) {
        var item = all('qp_sections').find(function (section) { return String(section.id_section) === String(id); });
        return item ? item.section : '';
    }

    function sectionsByDepartment(id) {
        return all('qp_sections').filter(function (section) {
            return String(section.id_departement) === String(id);
        });
    }

    initialize();
    window.LocalStorageApp = {
        read: read,
        all: all,
        write: write,
        create: create,
        remove: remove,
        currentUser: currentUser,
        login: login,
        logout: logout,
        requireLogin: requireLogin,
        redirectAfterLogin: redirectAfterLogin,
        requireAdmin: requireAdmin,
        roleNumber: roleNumber,
        roleLabel: roleLabel,
        employeeByNpk: employeeByNpk,
        departmentName: departmentName,
        sectionName: sectionName,
        sectionsByDepartment: sectionsByDepartment
    };
})(window);
