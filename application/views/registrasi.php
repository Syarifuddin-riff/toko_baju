<body class="bg-gradient-primary"> <div class="container">

        <div class="card o-hidden border-0 shadow-lg col-lg-6 my-5 mx-auto">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Form Daftar Akun!</h1>
                            </div>
                            <form method="post" action="<?php echo base_url('registrasi/index') ?>" class="user">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Nama Anda" name="nama">
                                    <?php echo form_error('nama', '<div class="text-danger small">', '</div>') ?>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Username Anda" name="username">
                                    <?php echo form_error('username', '<div class="text-danger small">', '</div>') ?>
                                </div>
                                <div class="form-group row">


                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label for="registerPassword1" class="sr-only">Password</label> <div class="input-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="registerPassword1" placeholder="Password" name="password_1"> <div class="input-group-append">
                                            <span class="input-group-text" id="togglePassword1">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <?php echo form_error('password_1', '<div class="text-danger small">', '</div>') ?>
                                </div>


                                    <div class="col-sm-6">
                                        <label for="exampleRepeatPassword" class="sr-only">Ulangi Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleRepeatPassword" placeholder="Ulangi Password" name="password_2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="togglePassword2">
                                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <?php echo form_error('password_2', '<div class="text-danger small">', '</div>') ?>
                                    </div>

                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block" >Daftar</button>

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="<?php echo base_url('auth/login') ?>">Sudah Punya Akun? Login!</a>
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