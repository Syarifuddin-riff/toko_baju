<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('dashboard') ?>">
                <div class="sidebar-brand-icon">
                    <img src="<?php echo base_url('assets/img/logo1.png') ?>" alt="Logo Toko" style="width: 95px; height: 95px; object-fit: contain;">
                </div>
                <div class="sidebar-brand-text mx-3">𝑹𝒊𝒇𝒇 𝓢𝓽𝓸𝓻𝓮</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url('') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Kategori
            </div>
            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('kategori/pakaian_wanita') ?>">
                    <i class="fas fa-fw fa-tshirt"></i>
                    <span>Baju Perempuan</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('kategori/pakaian_pria') ?>">
                    <i class="fas fa-fw fa-tshirt"></i>
                    <span>Baju Laki-Laki</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('kategori/pakaian_anak_anak') ?>">
                    <i class="fas fa-fw fa-tshirt"></i>
                    <span>Baju Anak-Anak</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="<?php echo base_url('dashboard/search') ?>" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2" name="keyword">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search" action="<?php echo base_url('dashboard/search') ?>" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2" name="keyword_mobile">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="<?php echo base_url('dashboard/detail_keranjang') ?>" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-shopping-cart fa-fw fa-lg"></i>
                                    <?php $total_items_keranjang = $this->cart->total_items(); ?>
                                    <?php if ($total_items_keranjang > 0) : ?>
                                        <span class="badge badge-danger badge-counter"><?php echo $total_items_keranjang; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <ul class="navbar-nav ml-auto">
                                <?php if ($this->session->userdata('username')){ ?>
                                    <li class="nav-item"><div class="nav-link mr-3">Selamat Datang <?php echo $this->session->userdata('username') ?></div></li>
                                    <li class="nav-item"><?php echo anchor('auth/logout', 'Logout', 'class="nav-link"') ?></li><?php 
                                } else { ?>
                                    <li class="nav-item"><?php echo anchor('auth/login', 'Login', 'class="nav-link"') ?></li><?php } ?>      
                            </ul>

                        </ul> </nav>

                        