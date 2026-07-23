<!DOCTYPE html>
<html lang="en" class="h-100">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?= base_url('assets/img/Prod-title.png'); ?>" type="image/x-icon">
    <title>Login - Monitoring CPI Berbek</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet"
    type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css');?>">
    <style type="text/css">
        .cpi-logo-login {
            width: 400px;
            margin: 0 auto;
            position: relative;
            transform: translateY(-50%);
            top: 50%;
        }

        .cpi-logo-login h5 {
            color: #d71c0c
        }

        .cpi-logo-login img {
            width: 100%;
        }

        .login-container {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .login-form:before {
            content: '';
            width: 1px;
            height: 100%;
            background-color: #0d6efd;
            position: absolute;
            left: 0;
            top: 0;
        }

        @media (min-width:769px) and (max-width: 992px) {
            .login-form:before {
                display: none;
            }

            .logo-mobile {
                display: block !important;
                width: 250px;
                margin: 0 auto 20px auto;
            }
        }

        @media (max-width:768px) {
            .login-form:before {
                display: none;
            }

            .logo-mobile {
                display: block !important;
                width: 100%;
            }
        }

        #bg-animate {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0
        }

        #bg-animate canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .position-relative .form-control {
            padding-right: 40px;
            /* beri ruang untuk ikon */
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #3a3b45;
        }

        .form-control-user {
            color: #000 !important;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div id="bg-animate"></div>

    <div class="container login-container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5 p-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="cpi-logo-login">
                                    <img src="<?= base_url('assets/img/login.png')?>">
                                    <h5 class="text-center pt-4">Silahkan masuk menggunakan akun anda!</h5>
                                </div>

                            </div>
                            <div class="col-lg-6 login-form">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="logo-mobile mb-4 d-none">
                                            <img src="<?= base_url('assets/img/Prod-title.png');?>" class="w-100">
                                        </div>
                                        <h1 class="h4 text-primary mb-4">Production System<br>CPI Berbek</h1>
                                        <?php if($this->session->flashdata('error_msg')): ?>
                                            <div class="alert alert-danger">
                                                <?= $this->session->flashdata('error_msg') ?>
                                            </div>
                                            <br>
                                        <?php endif ?>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input type="text" name="username"
                                            class="form-control form-control-user <?= form_error('username') ? 'invalid' : '' ?>"
                                            value="<?= set_value('username'); ?>"
                                            placeholder="Enter Username...">
                                            <div
                                            class="invalid-feedback <?= !empty(form_error('username')) ? 'd-block':'';?>">
                                            <?= form_error('username') ?>
                                        </div>
                                    </div>
                                    <div class="form-group position-relative">
                                        <input type="password" name="password" id="password"
                                        class="form-control form-control-user <?= form_error('password') ? 'invalid' : '' ?>"
                                        placeholder="Password">
                                        <i class="fas fa-eye-slash input-icon" id="togglePassword"></i>
                                        <div
                                        class="invalid-feedback <?= !empty(form_error('password')) ? 'd-block':'';?>">
                                        <?= form_error('password') ?>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Login
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        VANTA.NET({
                el: "#bg-animate", // ID elemen target
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x37cfff,
                backgroundColor: 0x5f,

            });
    });
</script>
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        var passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        }
    });
</script>
</body>

</html>