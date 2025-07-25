<body class="bg-gradient-primary"> <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-5 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Form Login</h1>
                                    </div>
                                    <?php echo $this->session->flashdata('pesan') ?>
                                    <form method="post" action="<?php echo base_url('auth/login') ?>" class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Masukkan Username Anda" name="username">
                                            <?php echo form_error('username', '<div class="text-danger small ml-2">','</div'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword" class="sr-only">Masukkan Password Anda</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Masukkan Password Anda" name="password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="togglePassword">
                                                        <i class="fa fa-eye-slash" aria-hidden="true"></i> </span>
                                                </div>
                                            </div>
                                            <?php echo form_error('password', '<div class="text-danger small ml-2">','</div'); ?>
                                        </div>

                                        <button type="submit" class="btn btn-primary form-control">Login</button>

                                    </form>

                                    <hr>
                                    
                                    <div class="text-center">
                                        <a class="small" href="<?php echo base_url('registrasi/index'); ?>">Belum Punya Akun? Daftar!</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="<?php echo base_url() ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/auth_toggle_password.js"></script>
</body>
</html>

