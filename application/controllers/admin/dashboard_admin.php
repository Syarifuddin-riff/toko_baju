<?php

class Dashboard_admin extends CI_Controller{
    public function __construct(){
        parent::__construct();

        if($this->session->userdata('role_id') != '1')
        {
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Anda Belum Login!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>');
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Ambil data statistik dari model
        $data['total_pendapatan'] = $this->model_invoice->get_total_pendapatan();
        $data['total_pesanan'] = $this->model_invoice->get_total_pesanan();
        $data['total_produk_terjual'] = $this->model_invoice->get_total_produk_terjual();

        // Pastikan nilai null menjadi 0 jika tidak ada data
        $data['total_pendapatan'] = $data['total_pendapatan'] ? $data['total_pendapatan'] : 0;
        $data['total_produk_terjual'] = $data['total_produk_terjual'] ? $data['total_produk_terjual'] : 0;

        // --- Bagian baru untuk data grafik ---
        $penjualan_per_produk = $this->model_invoice->get_penjualan_per_produk();

        $labels = []; // Untuk nama produk (label di sumbu X)
        $data_values = []; // Untuk jumlah terjual (nilai di sumbu Y)

        if (!empty($penjualan_per_produk)) {
            foreach ($penjualan_per_produk as $row) {
                $labels[] = $row['nama_brg'];
                $data_values[] = (int)$row['total_terjual']; // Pastikan ini integer
            }
        }
        
        $data['chart_labels'] = json_encode($labels); // Ubah ke format JSON untuk JS
        $data['chart_data_values'] = json_encode($data_values); // Ubah ke format JSON untuk JS
        // --- Akhir bagian baru untuk data grafik ---

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/dashboard', $data); // Kirim data statistik dan data grafik ke view
        $this->load->view('templates_admin/footer');
    }
}

