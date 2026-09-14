<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SMART FACTORY PORTAL</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* =========================================================
           BASE
        ========================================================= */

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at top left, #60a5fa22, transparent 25%),
                radial-gradient(circle at bottom right, #fb718522, transparent 25%),
                linear-gradient(135deg,
                    #eef2ff 0%,
                    #fef2f2 50%,
                    #f0fdf4 100%);
        }

        /* =========================================================
           BACKGROUND GRID
        ========================================================= */

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;

            background-image:
                linear-gradient(rgba(255, 255, 255, 0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.4) 1px, transparent 1px);

            background-size: 45px 45px;
        }

        /* =========================================================
           FLOATING ORBS
        ========================================================= */

        .orb {
            position: absolute;
            z-index: 0;

            border-radius: 9999px;
            filter: blur(90px);
            opacity: 0.45;

            animation: orb-float 8s ease-in-out infinite;
        }

        .orb1 {
            top: -120px;
            left: -120px;
            width: 320px;
            height: 320px;
            background: #60a5fa;
        }

        .orb2 {
            right: -120px;
            bottom: -120px;
            width: 320px;
            height: 320px;
            background: #fb7185;
            animation-delay: 2s;
        }

        .orb3 {
            top: 40%;
            left: 45%;
            width: 250px;
            height: 250px;
            background: #34d399;
            animation-delay: 4s;
        }

        @keyframes orb-float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-25px);
            }
        }

        /* =========================================================
           GLASS
        ========================================================= */

        .glass {
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        /* =========================================================
           CARD
        ========================================================= */

        .card {
            position: relative;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .card:hover {
            transform: translateY(-12px) scale(1.02);

            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.12),
                0 0 25px rgba(255, 255, 255, 0.35);
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;

            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.4),
                    transparent);

            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        /* =========================================================
           ICON BACKGROUND
        ========================================================= */

        .icon-bg {
            position: absolute;
            right: -15px;
            bottom: -15px;
            opacity: 0.08;
        }

        /* =========================================================
           BUTTON
        ========================================================= */

        .open-btn {
            transition: transform 0.3s ease;
        }

        .open-btn:hover {
            transform: scale(1.03);
        }

        /* =========================================================
           PULSE
        ========================================================= */

        .pulse {
            animation: pulse-effect 1.8s infinite;
        }

        @keyframes pulse-effect {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.4;
            }
        }

        /* =========================================================
           BADGE
        ========================================================= */

        .badge {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* =========================================================
           MARQUEE
        ========================================================= */

        .marquee-wrapper {
            width: 100%;
            overflow: hidden;

            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;

            background: rgba(255, 255, 255, 0.55);

            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .marquee-content {
            display: inline-block;

            padding-left: 100%;

            white-space: nowrap;
            font-weight: 700;
            color: #374151;

            animation: marquee 24s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="relative h-screen overflow-hidden text-gray-800">

    <!-- Background Orbs -->
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

    <div class="relative z-10 flex h-screen flex-col">

        <!-- =====================================================
             HEADER
        ====================================================== -->

        <header class="mx-auto w-full max-w-7xl px-5 pb-2 pt-3">
            <div class="flex items-center justify-between">

                <!-- System Status -->
                <div class="glass inline-flex items-center gap-2 rounded-2xl px-4 py-2">
                    <span class="pulse h-2 w-2 rounded-full bg-green-500"></span>

                    <span class="text-xs font-bold text-gray-700">
                        ALL SYSTEM OPERATIONAL
                    </span>
                </div>

                <!-- Clock -->
                <div id="clock" class="glass rounded-2xl px-4 py-2 text-xs font-bold text-gray-700"></div>

            </div>
        </header>

        <!-- =====================================================
             MAIN
        ====================================================== -->

        <main class="-mt-2 flex flex-1 flex-col justify-center px-4">

            <!-- Hero -->
            <section class="flex flex-col items-center text-center">

                <!-- Title -->
                <h1 class="text-4xl font-black leading-tight lg:text-5xl">
                    SMART
                    <span class="text-blue-600">FACTORY</span>
                    <span class="text-red-500">PORTAL</span>
                </h1>

                <!-- Marquee -->
                <div class="mt-3 w-full max-w-4xl overflow-hidden">
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

                <!-- System Selection -->
                <div class="relative mt-4 w-full max-w-5xl">
                    <div class="glass rounded-[28px] p-3">

                        <div class="mb-3">
                            <h2 class="text-lg font-black">
                                Pilih sistem yang ingin digunakan
                            </h2>
                        </div>

                        <!-- System Grid -->
                        <div class="grid grid-cols-2 items-stretch gap-3 lg:grid-cols-3 xl:grid-cols-5">

                            <!-- =================================================
                                 MONITORING WEB
                            ================================================== -->

                            <div class="card glass flex h-full flex-col rounded-[22px] p-3">
                                <i data-lucide="cpu" class="icon-bg h-20 w-20"></i>

                                <div class="relative z-10 flex h-full flex-col">

                                    <div class="mb-3 flex items-center justify-between">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 shadow-lg">
                                            <i data-lucide="cpu" class="h-5 w-5 text-white"></i>
                                        </div>

                                        <div class="badge flex items-center gap-1 rounded-full px-2 py-1">
                                            <span class="pulse h-2 w-2 rounded-full bg-green-700"></span>

                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="mb-1 text-base font-black">
                                        Monitoring Web
                                    </h3>

                                    <p class="mb-3 text-[11px] leading-snug text-gray-600">
                                        Monitoring produksi realtime.
                                    </p>

                                    <a href="<?= base_url('yield/'); ?>"
                                        class="open-btn mt-auto flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-500 to-orange-500 py-2 text-xs font-bold text-white shadow-lg">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        OPEN
                                    </a>

                                </div>
                            </div>

                            <!-- =================================================
                                 MANSYS
                            ================================================== -->

                            <div class="card glass flex h-full flex-col rounded-[22px] p-3">
                                <i data-lucide="activity" class="icon-bg h-20 w-20"></i>

                                <div class="relative z-10 flex h-full flex-col">

                                    <div class="mb-3 flex items-center justify-between">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-orange-500 shadow-lg">
                                            <i data-lucide="activity" class="h-5 w-5 text-white"></i>
                                        </div>

                                        <div class="badge flex items-center gap-1 rounded-full px-2 py-1">
                                            <span class="pulse h-2 w-2 rounded-full bg-green-700"></span>

                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="mb-1 text-base font-black">
                                        MANSYS
                                    </h3>

                                    <p class="mb-3 text-[11px] leading-snug text-gray-600">
                                        Monitoring Release Produksi &amp; Warehouse.
                                    </p>

                                    <a href="http://prod.io:8000/"
                                        class="open-btn mt-auto flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-pink-500 to-orange-500 py-2 text-xs font-bold text-white shadow-lg">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        OPEN
                                    </a>

                                </div>
                            </div>

                            <!-- =================================================
                                 MAINTENANCE
                            ================================================== -->

                            <div class="card glass flex h-full flex-col rounded-[22px] p-3">
                                <i data-lucide="wrench" class="icon-bg h-20 w-20"></i>

                                <div class="relative z-10 flex h-full flex-col">

                                    <div class="mb-3 flex items-center justify-between">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg">
                                            <i data-lucide="wrench" class="h-5 w-5 text-white"></i>
                                        </div>

                                        <div class="badge flex items-center gap-1 rounded-full px-2 py-1">
                                            <span class="pulse h-2 w-2 rounded-full bg-green-700"></span>

                                            <span class="text-[10px] font-bold text-green-700">
                                                ONLINE
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="mb-1 text-base font-black">
                                        MAINTENANCE
                                    </h3>

                                    <p class="mb-3 text-[11px] leading-snug text-gray-600">
                                        Preventive maintenance mesin<br>
                                        Autonomous Maintenance<br>
                                        Sparepart<br>
                                        New &amp; Repair Part
                                    </p>

                                    <a href="<?= base_url('maintenance'); ?>"
                                        class="open-btn mt-auto flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 to-green-500 py-2 text-xs font-bold text-white shadow-lg">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        OPEN
                                    </a>

                                </div>
                            </div>

                            <!-- =================================================
                                 PAPERLESS
                            ================================================== -->

                            <div class="card glass flex h-full flex-col rounded-[22px] p-3">
                                <i data-lucide="file-text" class="icon-bg h-20 w-20"></i>

                                <div class="relative z-10 flex h-full flex-col">

                                    <div class="mb-3 flex items-center justify-between">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-sky-500 shadow-lg">
                                            <i data-lucide="file-text" class="h-5 w-5 text-white"></i>
                                        </div>

                                        <div class="badge flex items-center gap-1 rounded-full px-2 py-1">
                                            <span class="h-2 w-2 rounded-full bg-red-700"></span>

                                            <span class="text-[10px] font-bold text-red-700">
                                                COMING SOON
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="mb-1 text-base font-black">
                                        PAPERLESS
                                    </h3>

                                    <p class="mb-3 text-[11px] leading-snug text-gray-600">
                                        Form digital tanpa kertas.
                                    </p>

                                    <a href="<?= base_url('paperless'); ?>"
                                        class="open-btn mt-auto flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-green-500 to-sky-500 py-2 text-xs font-bold text-white shadow-lg">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        OPEN
                                    </a>

                                </div>
                            </div>

                            <!-- =================================================
                                 MONITORING PACKING
                            ================================================== -->

                            <div class="card glass flex h-full flex-col rounded-[22px] p-3">
                                <i data-lucide="package" class="icon-bg h-20 w-20"></i>

                                <div class="relative z-10 flex h-full flex-col">

                                    <div class="mb-3 flex items-center justify-between">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg">
                                            <i data-lucide="package" class="h-5 w-5 text-white"></i>
                                        </div>

                                        <div class="badge flex items-center gap-1 rounded-full px-2 py-1">
                                            <span class="h-2 w-2 rounded-full bg-red-700"></span>

                                            <span class="text-[10px] font-bold text-red-700">
                                                COMING SOON
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="mb-1 text-base font-black">
                                        Monitoring Packing
                                    </h3>

                                    <p class="mb-3 text-[11px] leading-snug text-gray-600">
                                        Monitoring dan pengelolaan produktifitas packing.
                                    </p>

                                    <a href="<?= base_url('drystore/dashboard'); ?>"
                                        class="open-btn mt-auto flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-sky-500 to-green-500 py-2 text-xs font-bold text-white shadow-lg">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        OPEN
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </main>

        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer class="pb-2 text-center">
            <p class="text-[10px] text-gray-500">
                © 2026 PT. Charoen Pokphand Indonesia
            </p>
        </footer>

    </div>

    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script>
        lucide.createIcons();

        const clockElement = document.getElementById('clock');

        function updateClock() {
            const now = new Date();

            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            const time = now.toLocaleTimeString('id-ID');

            clockElement.innerHTML = `
                <div>${date}</div>
                <div class="text-blue-600">${time}</div>
            `;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>

</body>

</html>