<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SMART FACTORY PORTAL</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            overflow-x: hidden;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #60a5fa22, transparent 25%),
                radial-gradient(circle at bottom right, #fb718522, transparent 25%),
                linear-gradient(135deg,
                    #eef2ff 0%,
                    #fef2f2 50%,
                    #f0fdf4 100%);
        }
        /* GRID */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .4) 1px, transparent 1px);
            background-size: 45px 45px;
            z-index: 0;
            pointer-events: none;
        }
        /* ORB */
        .orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(90px);
            opacity: .45;
            animation: float 8s ease-in-out infinite;
            z-index: 0;
        }
        .orb1 {
            width: 320px;
            height: 320px;
            background: #60a5fa;
            top: -120px;
            left: -120px;
        }
        .orb2 {
            width: 320px;
            height: 320px;
            background: #fb7185;
            bottom: -120px;
            right: -120px;
            animation-delay: 2s;
        }
        .orb3 {
            width: 250px;
            height: 250px;
            background: #34d399;
            top: 40%;
            left: 45%;
            animation-delay: 4s;
        }
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-25px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        /* GLASS */
        .glass {
            background: rgba(255, 255, 255, .55);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .4);
            box-shadow:
                0 10px 40px rgba(0, 0, 0, .06);
        }
        /* CARD */
        .card {
            position: relative;
            overflow: hidden;
            transition: .4s ease;
        }
        .card:hover {
            transform:
                translateY(-12px) scale(1.02);
            box-shadow:
                0 30px 60px rgba(0, 0, 0, .12),
                0 0 25px rgba(255, 255, 255, .35);
        }
        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, .4),
                    transparent);
            opacity: 0;
            transition: .4s ease;
        }
        .card:hover::before {
            opacity: 1;
        }
        /* ICON BACKGROUND */
        .icon-bg {
            position: absolute;
            right: -15px;
            bottom: -15px;
            opacity: .08;
        }
        /* BUTTON */
        .open-btn {
            transition: .3s ease;
        }
        .open-btn:hover {
            transform: scale(1.03);
        }
        /* PULSE */
        .pulse {
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.5);
                opacity: .4;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        /* FLOAT CARD */
        .floating {
            animation: floating 5s ease-in-out infinite;
        }
        @keyframes floating {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        /* BADGE */
        .badge {
            background: rgba(255, 255, 255, .7);
            backdrop-filter: blur(10px);
        }
        /* MARQUEE */
        .marquee-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 20px;
            background: rgba(255, 255, 255, .55);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .5);
            padding: 14px 0;
        }
        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            font-weight: 700;
            color: #374151;
            padding-left: 100%;
            animation: marquee 24s linear infinite;
        }
        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="relative text-gray-800 overflow-hidden h-screen">
    <!-- ORB -->
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>
    <div class="relative z-10 h-screen flex flex-col">
        <!-- HEADER -->
        <header class="max-w-7xl w-full mx-auto px-5 pt-3 pb-2">
            <!-- TOP -->
            <div class="flex items-center justify-between">
                <div class="glass rounded-2xl px-4 py-2 inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 pulse"></span>
                    <span class="font-bold text-xs text-gray-700">
                        ALL SYSTEM OPERATIONAL
                    </span>
                </div>
                <div id="clock" class="glass rounded-2xl px-4 py-2 text-xs font-bold text-gray-700">
                </div>
            </div>
        </header>
        <!-- MAIN -->
        <main class="flex-1 flex flex-col justify-center px-4 -mt-2">
            <!-- HERO -->
            <div class="flex flex-col items-center text-center">
                <!-- WELCOME -->
                <!-- TITLE -->
                <h1 class="text-4xl lg:text-5xl font-black leading-tight">
                    SMART
                    <span class="text-blue-600">
                        FACTORY
                    </span>
                    <span class="text-red-500">
                        PORTAL
                    </span>
                </h1>
                <!-- MARQUEE -->
                <div class="mt-3 overflow-hidden w-full max-w-4xl">
                    <div class="marquee-wrapper py-2">
                        <div class="marquee-content text-xs">
                            🚀 Semua sistem produksi, engineering,
                            monitoring, maintenance, paperless,
                            realtime chart, preventive maintenance,
                            quality control dan operational monitoring
                            dalam satu portal modern yang cepat,
                            realtime dan mudah digunakan.
                        </div>
                    </div>
                </div>
                <!-- STATS -->
                <!-- <div class="mt-4 flex flex-wrap justify-center gap-3">
                <div class="glass rounded-2xl px-4 py-2">
                    <h3 class="text-lg font-black text-blue-600">
                        14
                    </h3>
                    <p class="text-[11px] text-gray-500">
                        Mesin Online
                    </p>
                </div>
                <div class="glass rounded-2xl px-4 py-2">
                    <h3 class="text-lg font-black text-green-600">
                        4
                    </h3>
                    <p class="text-[11px] text-gray-500">
                        System Active
                    </p>
                </div>
                <div class="glass rounded-2xl px-4 py-2">
                    <h3 class="text-lg font-black text-red-500">
                        LIVE
                    </h3>
                    <p class="text-[11px] text-gray-500">
                        Monitoring Data
                    </p>
                </div>
            </div> -->
                <!-- SYSTEM -->
                <div class="relative mt-4 w-full max-w-5xl">
                    <div class="glass rounded-[28px] p-3">
                        <div class="mb-3">
                            <h2 class="text-lg font-black">
                                Pilih sistem yang ingin digunakan
                            </h2>
                        </div>
                        <!-- GRID -->
                        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 items-stretch">
                            <!-- CARD -->
                            <div class="card glass rounded-[22px] p-3 h-full flex flex-col">
                                <i data-lucide="cpu" class="icon-bg w-20 h-20"></i>
                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg">
                                            <i data-lucide="cpu" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="badge px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-700 pulse"></span>
                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black mb-1">
                                        Monitoring Web
                                    </h3>
                                    <p class="text-[11px] text-gray-600 leading-snug mb-3">
                                        Monitoring produksi realtime.
                                    </p>
                                    <a href="<?= base_url('yieldportal/dashboard'); ?>" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-blue-500 to-indigo-500 shadow-lg">
                                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                        OPEN
                                    </a>
                                </div>
                            </div>
                            <!-- CARD -->
                            <div class="card glass rounded-[22px] p-3 h-full flex flex-col">
                                <i data-lucide="file-text" class="icon-bg w-20 h-20"></i>
                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 to-sky-500 flex items-center justify-center shadow-lg">
                                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="badge px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-700 pulse"></span>
                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black mb-1">
                                        PAPERLESS
                                    </h3>
                                    <p class="text-[11px] text-gray-600 leading-snug mb-3">
                                        Form digital tanpa kertas.
                                    </p>
                                    <a href="<?= base_url('portal/paperless'); ?>" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-cyan-500 to-sky-500 shadow-lg">
                                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                        OPEN
                                    </a>
                                </div>
                            </div>
                            <!-- CARD -->
                            <div class="card glass rounded-[22px] p-3 h-full flex flex-col">
                                <i data-lucide="activity" class="icon-bg w-20 h-20"></i>
                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center shadow-lg">
                                            <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="badge px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-700 pulse"></span>
                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black mb-1">
                                        MANSYS
                                    </h3>
                                    <p class="text-[11px] text-gray-600 leading-snug mb-3">
                                        Monitoring Release Produksi & Warehouse.
                                    </p>
                                    <a href="http://cpi.berbek:8000/" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-red-500 to-orange-500 shadow-lg">
                                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                        OPEN
                                    </a>
                                </div>
                            </div>
                            <!-- CARD -->
                            <div class="card glass rounded-[22px] p-3 h-full flex flex-col">
                                <i data-lucide="wrench" class="icon-bg w-20 h-20"></i>
                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                                            <i data-lucide="wrench" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="badge px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-700 pulse"></span>
                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black mb-1">
                                        MAINTENANCE
                                    </h3>
                                    <p class="text-[11px] text-gray-600 leading-snug mb-3">
                                        Preventive maintenance mesin<br>
                                        Autonomous Maintenance<br>
                                        Sparepart<br>
                                        New & Repair Part
                                    </p>
                                    <a href="<?= base_url('portal/maintenance'); ?>" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-amber-500 to-orange-500 shadow-lg">
                                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                        OPEN
                                    </a>
                                </div>
                            </div>
                            <!-- CARD SANITASI -->
                            <!-- <div class="card glass rounded-[22px] p-3 h-full flex flex-col">
                                <i data-lucide="spray-can" class="icon-bg w-20 h-20"></i>
                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg">
                                            <i data-lucide="spray-can" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="badge px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-700 pulse"></span>
                                            <span class="text-[10px] font-bold text-green-700">ONLINE</span>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black mb-1">SANITASI</h3>
                                    <p class="text-[11px] text-gray-600 leading-snug mb-3">
                                        Monitoring dan pengelolaan sanitasi produksi.
                                    </p>
                                    <a href="<?= base_url('portal/sanitasi'); ?>" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-emerald-500 to-teal-500 shadow-lg">
                                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                        OPEN
                                    </a>
                                </div>
                            </div> -->
                        </div>
                    </div>
        </main>
        <!-- FOOTER -->
        <footer class="pb-2 text-center">
            <p class="text-[10px] text-gray-500">
                © 2026 PT. Charoen Pokphand Indonesia
            </p>
        </footer>
    </div>
    <script>
        lucide.createIcons();
        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const time = now.toLocaleTimeString('id-ID');
            document.getElementById('clock').innerHTML =
                `
        <div>${date}</div>
        <div class="text-blue-600">${time}</div>
        `;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>