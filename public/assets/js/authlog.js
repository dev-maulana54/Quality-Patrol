// Default Config
        const defaultConfig = {
            brand_name: "PlayStation",
            login_title: "Selamat Datang",
            email_label: "Email",
            password_label: "Password",
            login_button: "Masuk",
            forgot_password_text: "Lupa password?",

        };

        // Animasi PlayStation Icons
        const canvas = document.getElementById('animation-canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class PlayStationIcon {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 20 + 15;
                this.speedX = (Math.random() - 0.5) * 0.5;
                this.speedY = (Math.random() - 0.5) * 0.5;
                this.type = Math.floor(Math.random() * 4); // 0: circle, 1: cross, 2: triangle, 3: square
                this.opacity = Math.random() * 0.3 + 0.1;
                this.rotation = Math.random() * Math.PI * 2;
                this.rotationSpeed = (Math.random() - 0.5) * 0.02;

                // Warna PlayStation
                const colors = ['#0070cc', '#e60012', '#6cbb3c', '#f5a623'];
                this.color = colors[this.type];
            }

            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                this.rotation += this.rotationSpeed;

                // Bounce dari tepi
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }

            draw() {
                ctx.save();
                ctx.translate(this.x, this.y);
                ctx.rotate(this.rotation);
                ctx.globalAlpha = this.opacity;
                ctx.strokeStyle = this.color;
                ctx.lineWidth = 3;

                switch (this.type) {
                    case 0: // Circle (O)
                        ctx.beginPath();
                        ctx.arc(0, 0, this.size / 2, 0, Math.PI * 2);
                        ctx.stroke();
                        break;
                    case 1: // Cross (X)
                        ctx.beginPath();
                        ctx.moveTo(-this.size / 2, -this.size / 2);
                        ctx.lineTo(this.size / 2, this.size / 2);
                        ctx.moveTo(this.size / 2, -this.size / 2);
                        ctx.lineTo(-this.size / 2, this.size / 2);
                        ctx.stroke();
                        break;
                    case 2: // Triangle
                        ctx.beginPath();
                        ctx.moveTo(0, -this.size / 2);
                        ctx.lineTo(this.size / 2, this.size / 2);
                        ctx.lineTo(-this.size / 2, this.size / 2);
                        ctx.closePath();
                        ctx.stroke();
                        break;
                    case 3: // Square
                        ctx.strokeRect(-this.size / 2, -this.size / 2, this.size, this.size);
                        break;
                }

                ctx.restore();
            }
        }

        // Buat array icons
        const icons = [];
        const iconCount = window.innerWidth < 768 ? 8 : 12;

        for (let i = 0; i < iconCount; i++) {
            icons.push(new PlayStationIcon());
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            icons.forEach(icon => {
                icon.update();
                icon.draw();
            });

            requestAnimationFrame(animate);
        }

        animate();

        // Resize canvas
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });

        // Toggle Theme
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        themeToggle.addEventListener('click', () => {
            if (body.classList.contains('light-theme')) {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                themeIcon.textContent = '☀️';
            } else {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                themeIcon.textContent = '🌙';
            }
        });

        // Validasi & Alert
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const alertContainer = document.getElementById('alertContainer');

        function showAlert(message, type) {
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            // Auto hide setelah 5 detik
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alertContainer.innerHTML = '', 150);
                }
            }, 5000);
        }

        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            // Validasi
            if (!email || !password) {
                showAlert('Mohon isi semua field yang diperlukan!', 'danger');
                return;
            }

            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Format email tidak valid!', 'danger');
                return;
            }

            // Simulasi login (contoh: email: user@example.com, password: 12345)
            if (email === 'user@example.com' && password === '12345') {
                showAlert('Login berhasil! Selamat datang kembali.', 'success');
                // Reset form
                loginForm.reset();
            } else {
                showAlert('Email atau password salah. Silakan coba lagi.', 'danger');
            }
        });

        // Prevent default link behavior
        document.getElementById('forgotPassword').addEventListener('click', (e) => {
            e.preventDefault();
            showAlert('Fitur lupa password akan segera hadir!', 'success');
        });

        document.getElementById('registerLink').addEventListener('click', (e) => {
            e.preventDefault();
            showAlert('Fitur registrasi akan segera hadir!', 'success');
        });

        // Element SDK Implementation
        async function onConfigChange(config) {
            document.getElementById('brandName').textContent = config.brand_name || defaultConfig.brand_name;
            document.getElementById('loginTitle').textContent = config.login_title || defaultConfig.login_title;
            document.getElementById('emailLabel').textContent = config.email_label || defaultConfig.email_label;
            document.getElementById('passwordLabel').textContent = config.password_label || defaultConfig.password_label;
            document.getElementById('loginButton').textContent = config.login_button || defaultConfig.login_button;
            document.getElementById('forgotPassword').textContent = config.forgot_password_text || defaultConfig.forgot_password_text;
            document.getElementById('registerLink').textContent = config.register_text || defaultConfig.register_text;
        }

        function mapToCapabilities(config) {
            return {
                recolorables: [],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            };
        }

        function mapToEditPanelValues(config) {
            return new Map([
                ["brand_name", config.brand_name || defaultConfig.brand_name],
                ["login_title", config.login_title || defaultConfig.login_title],
                ["email_label", config.email_label || defaultConfig.email_label],
                ["password_label", config.password_label || defaultConfig.password_label],
                ["login_button", config.login_button || defaultConfig.login_button],
                ["forgot_password_text", config.forgot_password_text || defaultConfig.forgot_password_text],
                ["register_text", config.register_text || defaultConfig.register_text]
            ]);
        }

        // Initialize SDK
        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities,
                mapToEditPanelValues
            });
        }