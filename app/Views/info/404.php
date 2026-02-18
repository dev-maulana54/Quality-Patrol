<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="/_sdk/element_sdk.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-20px) rotate(5deg);
            }

            50% {
                transform: translateY(-10px) rotate(0deg);
            }

            75% {
                transform: translateY(-25px) rotate(-5deg);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.3), 0 0 40px rgba(99, 102, 241, 0.1);
            }

            50% {
                box-shadow: 0 0 40px rgba(99, 102, 241, 0.5), 0 0 80px rgba(99, 102, 241, 0.2);
            }
        }

        @keyframes slide-up {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce-in {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes orbit {
            0% {
                transform: rotate(0deg) translateX(120px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(120px) rotate(-360deg);
            }
        }

        @keyframes orbit-reverse {
            0% {
                transform: rotate(0deg) translateX(80px) rotate(0deg);
            }

            100% {
                transform: rotate(-360deg) translateX(80px) rotate(360deg);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .slide-up {
            animation: slide-up 0.8s ease-out forwards;
        }

        .bounce-in {
            animation: bounce-in 1s ease-out forwards;
        }

        .orbit-1 {
            animation: orbit 20s linear infinite;
        }

        .orbit-2 {
            animation: orbit-reverse 15s linear infinite;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="h-full">
    <div id="app-wrapper" class="w-full h-full overflow-auto bg-slate-900 relative">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Gradient Orbs -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl"></div>

            <!-- Orbiting Elements -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <div class="orbit-1">
                    <div class="w-3 h-3 bg-indigo-400 rounded-full"></div>
                </div>
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <div class="orbit-2">
                    <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                </div>
            </div>

            <!-- Floating Stars -->
            <div class="absolute top-20 left-10 text-2xl opacity-50 float-animation">✨</div>
            <div class="absolute top-40 right-20 text-xl opacity-40 float-animation delay-200">⭐</div>
            <div class="absolute bottom-40 left-20 text-lg opacity-30 float-animation delay-300">💫</div>
            <div class="absolute bottom-60 right-10 text-2xl opacity-50 float-animation delay-100">🌟</div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10 flex flex-col items-center justify-center min-h-full px-6 py-12">
            <!-- Illustration -->
            <div class="bounce-in mb-8">
                <div class="relative float-animation">
                    <!-- Astronaut SVG -->
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-2xl">
                        <!-- Space Helmet -->
                        <circle cx="100" cy="85" r="55" fill="url(#helmet-gradient)" stroke="#6366f1" stroke-width="3" />
                        <circle cx="100" cy="85" r="45" fill="#1e1b4b" />
                        <!-- Visor Reflection -->
                        <ellipse cx="85" cy="75" rx="15" ry="20" fill="url(#visor-gradient)" opacity="0.6" />
                        <!-- Face -->
                        <circle cx="90" cy="80" r="5" fill="#fbbf24" />
                        <circle cx="110" cy="80" r="5" fill="#fbbf24" />
                        <path d="M92 95 Q100 102 108 95" stroke="#fbbf24" stroke-width="3" stroke-linecap="round" fill="none" />
                        <!-- Body -->
                        <path d="M60 140 Q60 120 100 115 Q140 120 140 140 L140 170 Q140 180 100 180 Q60 180 60 170 Z" fill="url(#suit-gradient)" stroke="#6366f1" stroke-width="2" />
                        <!-- Oxygen Tank -->
                        <rect x="125" y="125" width="20" height="40" rx="5" fill="#4f46e5" />
                        <rect x="128" y="130" width="4" height="30" rx="2" fill="#818cf8" />
                        <!-- Arms -->
                        <ellipse cx="50" cy="145" rx="15" ry="20" fill="url(#suit-gradient)" stroke="#6366f1" stroke-width="2" />
                        <ellipse cx="150" cy="145" rx="15" ry="20" fill="url(#suit-gradient)" stroke="#6366f1" stroke-width="2" />
                        <!-- Antenna -->
                        <line x1="100" y1="30" x2="100" y2="15" stroke="#6366f1" stroke-width="3" stroke-linecap="round" />
                        <circle cx="100" cy="12" r="5" fill="#ec4899" />

                        <defs>
                            <linearGradient id="helmet-gradient" x1="45" y1="30" x2="155" y2="140">
                                <stop offset="0%" stop-color="#e0e7ff" />
                                <stop offset="100%" stop-color="#c7d2fe" />
                            </linearGradient>
                            <linearGradient id="visor-gradient" x1="70" y1="55" x2="100" y2="95">
                                <stop offset="0%" stop-color="#60a5fa" />
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="suit-gradient" x1="60" y1="115" x2="140" y2="180">
                                <stop offset="0%" stop-color="#e0e7ff" />
                                <stop offset="100%" stop-color="#a5b4fc" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- Floating Elements around astronaut -->
                    <div class="absolute -top-4 -right-4 text-3xl float-animation delay-100">🚀</div>
                    <div class="absolute -bottom-2 -left-6 text-2xl float-animation delay-300">🌙</div>
                    <div class="absolute top-1/2 -right-8 text-xl float-animation delay-200">⚡</div>
                </div>
            </div>

            <!-- Error Code -->
            <div class="slide-up opacity-0 text-center mb-4" style="animation-delay: 0.2s;">
                <h1 id="error-code" class="text-8xl sm:text-9xl font-extrabold gradient-text pulse-glow rounded-3xl px-6 py-2">
                    404
                </h1>
            </div>

            <!-- Title -->
            <div class="slide-up opacity-0 text-center mb-4" style="animation-delay: 0.4s;">
                <h2 id="title-text" class="text-2xl sm:text-3xl font-bold text-white mb-2">
                    Oops! Halaman Tidak Ditemukan
                </h2>
            </div>

            <!-- Description -->
            <div class="slide-up opacity-0 text-center mb-8 max-w-md" style="animation-delay: 0.6s;">
                <p id="description-text" class="text-slate-400 text-base sm:text-lg leading-relaxed">
                    Sepertinya astronaut kami tersesat di luar angkasa. Halaman yang kamu cari mungkin sudah dipindahkan atau tidak tersedia.
                </p>
            </div>

            <!-- Action Button -->
            <div class="slide-up opacity-0" style="animation-delay: 0.8s;">
                <button id="home-button" class="btn-gradient text-white font-semibold px-8 py-4 rounded-2xl text-lg flex items-center gap-3 shadow-xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    <span id="button-text">Kembali ke Beranda</span>
                </button>
            </div>

            <!-- Glass Card with additional info -->
            <div class="slide-up opacity-0 mt-12 glass-card rounded-2xl p-6 max-w-sm w-full" style="animation-delay: 1s;">
                <div class="flex items-center gap-4 text-slate-300">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/20 flex items-center justify-center">
                        <span class="text-2xl">💡</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">Tips</p>
                        <p class="text-xs text-slate-400">Coba periksa URL atau gunakan menu navigasi</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation Hint -->
            <div class="slide-up opacity-0 mt-8 flex gap-6 text-slate-500" style="animation-delay: 1.2s;">
                <button class="flex flex-col items-center gap-1 hover:text-indigo-400 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                    </svg>
                    <span class="text-xs">Cari</span>
                </button>
                <button class="flex flex-col items-center gap-1 hover:text-indigo-400 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <span class="text-xs">Bantuan</span>
                </button>
                <button class="flex flex-col items-center gap-1 hover:text-indigo-400 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    <span class="text-xs">Panduan</span>
                </button>
            </div>
        </main>
    </div>

    <script>
        const defaultConfig = {
            error_code: '404',
            title_text: 'Oops! Halaman Tidak Ditemukan',
            description_text: 'Sepertinya astronaut kami tersesat di luar angkasa. Halaman yang kamu cari mungkin sudah dipindahkan atau tidak tersedia.',
            button_text: 'Kembali ke Beranda',
            background_color: '#0f172a',
            text_color: '#f8fafc',
            accent_color: '#6366f1',
            secondary_color: '#a855f7',
            surface_color: 'rgba(255, 255, 255, 0.1)',
            font_family: 'Outfit',
            font_size: 16
        };

        document.getElementById("home-button").addEventListener("click", function() {
            window.history.back();
        });
        async function onConfigChange(config) {
            // Update text content
            document.getElementById('error-code').textContent = config.error_code || defaultConfig.error_code;
            document.getElementById('title-text').textContent = config.title_text || defaultConfig.title_text;
            document.getElementById('description-text').textContent = config.description_text || defaultConfig.description_text;
            document.getElementById('button-text').textContent = config.button_text || defaultConfig.button_text;

            // Update colors
            const bgColor = config.background_color || defaultConfig.background_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const accentColor = config.accent_color || defaultConfig.accent_color;

            document.getElementById('app-wrapper').style.backgroundColor = bgColor;
            document.getElementById('title-text').style.color = textColor;

            // Update font
            const fontFamily = config.font_family || defaultConfig.font_family;
            const fontSize = config.font_size || defaultConfig.font_size;
            document.body.style.fontFamily = `${fontFamily}, sans-serif`;
            document.getElementById('description-text').style.fontSize = `${fontSize}px`;
        }

        function mapToCapabilities(config) {
            return {
                recolorables: [{
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            window.elementSdk.setConfig({
                                background_color: value
                            });
                        }
                    },
                    {
                        get: () => config.surface_color || defaultConfig.surface_color,
                        set: (value) => {
                            config.surface_color = value;
                            window.elementSdk.setConfig({
                                surface_color: value
                            });
                        }
                    },
                    {
                        get: () => config.text_color || defaultConfig.text_color,
                        set: (value) => {
                            config.text_color = value;
                            window.elementSdk.setConfig({
                                text_color: value
                            });
                        }
                    },
                    {
                        get: () => config.accent_color || defaultConfig.accent_color,
                        set: (value) => {
                            config.accent_color = value;
                            window.elementSdk.setConfig({
                                accent_color: value
                            });
                        }
                    },
                    {
                        get: () => config.secondary_color || defaultConfig.secondary_color,
                        set: (value) => {
                            config.secondary_color = value;
                            window.elementSdk.setConfig({
                                secondary_color: value
                            });
                        }
                    }
                ],
                borderables: [],
                fontEditable: {
                    get: () => config.font_family || defaultConfig.font_family,
                    set: (value) => {
                        config.font_family = value;
                        window.elementSdk.setConfig({
                            font_family: value
                        });
                    }
                },
                fontSizeable: {
                    get: () => config.font_size || defaultConfig.font_size,
                    set: (value) => {
                        config.font_size = value;
                        window.elementSdk.setConfig({
                            font_size: value
                        });
                    }
                }
            };
        }

        function mapToEditPanelValues(config) {
            return new Map([
                ['error_code', config.error_code || defaultConfig.error_code],
                ['title_text', config.title_text || defaultConfig.title_text],
                ['description_text', config.description_text || defaultConfig.description_text],
                ['button_text', config.button_text || defaultConfig.button_text]
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

        // Button click handler
        document.getElementById('home-button').addEventListener('click', function() {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    </script>
</body>

</html>