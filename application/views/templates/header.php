<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Riff Store - Toko Online</title>

    <link href="<?php echo base_url() ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="<?php echo base_url() ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* CSS untuk badge keranjang (tetap) */
        .badge-counter {
            font-size: 0.8rem !important;
            padding: .35em .6em !important;
            top: 15px !important;
            right: -8px !important;
            z-index: 1; /* Pastikan badge di atas ikon */
        }

        .badge-danger {
            background-color: #ff69b4 !important;
        }

        /* --- CSS UNTUK LAYOUT UMUM DAN FOOTER --- */
        html {
            height: 100%;
        }
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            width: 100%;
            overflow: hidden;
        }

        #content {
            flex-grow: 1;
            padding-bottom: 20px; /* Jarak dari footer, bisa disesuaikan */
        }

        .container-fluid {
            /* Perbesar padding kiri dan kanan untuk memberikan jarak */
            padding-right: 3rem; /* <--- Ganti dari 0.75rem ke 2rem */
            padding-left: 2.3rem;  /* <--- Ganti dari 0.75rem ke 2rem */

            margin-right: auto;
            margin-left: auto;
            width: 100%;
            max-width: 100%;
        }

        /* Style untuk row produk di dashboard */
        .row.text-center.mt-4 {
            justify-content: center !important;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
            margin-top: 1.5rem !important;
        }

        .row.text-center.mt-4 .card {
            margin-left: 0.75rem !important;
            margin-right: 0.75rem !important;
            margin-bottom: 1.5rem !important;
            flex: 0 0 auto;
        }

        /* --- REVISI GAYA FOOTER UTAMA (Satu Baris & Lebih Kecil) --- */
        footer {
            flex-shrink: 0;
            border-top: 1px solid #e3e6f0; /* Garis pemisah */
            background-color: #f8f9fc !important; /* Warna latar belakang footer */
            padding: 0.75rem 0; /* <--- PENTING: Padding vertikal lebih rapat (sekitar 12px) */
            font-size: 0.85rem; /* <--- PENTING: Ukuran font umum untuk footer (sedikit lebih besar dari sebelumnya) */
            color: #6c757d; /* Warna teks yang lembut */
            line-height: 0; /* Menyesuaikan tinggi baris agar teks tidak terlalu tinggi */
        }

        /* Styling untuk elemen di dalam footer */
        footer .container {
            /* padding-right: 0rem; */ /* Jika Anda ingin container rapat ke tepi */
            /* padding-left: 0rem; */  /* Jika Anda ingin container rapat ke tepi */
        }

        /* Untuk teks copyright
        
        */
        .copyright span {
            font-size: 0.8rem;
            color: #858796;
            line-height: 1;
            text-align: left; /* <--- PENTING: Memaksa teks rata kiri */
            flex-grow: 1; /* Biarkan span mengambil ruang yang diperlukan, dorong ikon ke kanan */
        }


        /* Gaya untuk ikon sosial */
        .social-icons {
            margin-left: auto; /* Dorong ikon sosial ke kanan jika ada ruang */
            gap: 10px; /* Jarak antar item flexbox (ikon+teks) */
        }

        .social-icons li {
            margin-bottom: 0; /* Pastikan tidak ada margin bawah pada list item */
            /* mr-3 di HTML masih akan memberikan margin kanan */
        }
        .social-icons li:last-child {
            margin-right: 0 !important; /* Pastikan elemen terakhir tidak punya margin kanan */
        }

        .social-icons i {
            font-size: 1.6rem; /* <--- Ukuran ikon sosial (lebih besar dari sebelumnya) */
            color: #858796;
            transition: color 0.3s ease;
            margin-right: 5px; /* Jarak antara ikon dan teks */
            vertical-align: middle; /* Pastikan ikon sejajar secara vertikal dengan teks */
        }
        .social-icons a {
            color: #6c757d; /* Warna link sosial agar sama dengan teks muted di footer */
            font-size: 1rem; /* <--- Ukuran font teks sosial media, sama dengan font-size footer */
            display: inline-flex; /* Agar ikon dan teks sejajar */
            align-items: center; /* Pusatkan secara vertikal */
            text-decoration: none; /* Hapus underline default link */
        }
        .social-icons a:hover {
            color: #0056b3;
            text-decoration: underline; /* Tambah underline saat hover */
        }
        /* Hover colors for social icons */
        .social-icons a:hover i.fab.fa-facebook-f { color: #3b5998; }
        .social-icons a:hover i.fab.fa-twitter { color: #1da1f2; }
        .social-icons a:hover i.fab.fa-instagram { color: #c32aa3; }
        .social-icons a:hover i.fab.fa-youtube { color: #ff0000; }
        .social-icons a:hover i.fab.fa-whatsapp { color: #25D366; } /* Warna WhatsApp hover */

        /* Tambahan untuk sticky footer di halaman login/registrasi (tetap) */
        .min-vh-100 {
            min-height: 100vh;
        }    footer .container {
        /* padding-right/left bisa dihapus jika ingin lebih rapat ke tepi container Bootstrap */
    }

    /* Untuk teks copyright */
    .copyright span {
        font-size: 0.8rem;
        color: #858796;
        line-height: 1;
        text-align: left; /* <--- PENTING: Memaksa teks rata kiri */
        flex-grow: 1; /* Biarkan span mengambil ruang yang diperlukan, dorong ikon ke kanan */
    }

    /* Gaya untuk ikon sosial */
    .social-icons {
        margin-left: auto; /* Dorong ikon sosial ke kanan jika ada ruang */
        gap: 10px; /* Jarak antar ikon sosial */
        /* Pastikan tidak ada text-align di sini */
    }

    .social-icons li {
        margin-bottom: 0;
        margin-right: 0; /* Pastikan tidak ada margin tambahan di li */
    }
    .social-icons li:last-child {
        margin-right: 0 !important; /* Pastikan elemen terakhir tidak punya margin kanan */
    }

    .social-icons i {
        font-size: 1.1rem;
        color: #858796;
        transition: color 0.3s ease;
        margin-right: 5px;
        vertical-align: middle;
    }
    .social-icons a {
        color: #6c757d;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .social-icons a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    /* Hover colors for social icons */
    .social-icons a:hover i.fab.fa-facebook-f { color: #3b5998; }
    .social-icons a:hover i.fab.fa-twitter { color: #1da1f2; }
    .social-icons a:hover i.fab.fa-instagram { color: #c32aa3; }
    .social-icons a:hover i.fab.fa-youtube { color: #ff0000; }
    .social-icons a:hover i.fab.fa-whatsapp { color: #25D366; }

    /* Tambahan untuk sticky footer di halaman login/registrasi (tetap) */
    .min-vh-100 {
        min-height: 100vh;
    }

    </style>
</head>

