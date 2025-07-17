<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct(){
        parent::__construct();  
        
        // Memuat model_voucher di sini
        $this->load->model('model_voucher'); 

        if($this->session->userdata('role_id') != '2')
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
        $data['barang'] = $this->model_barang->tampil_data()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }
    
    public function tambah_ke_keranjang($id)
    {
        $barang = $this->model_barang->find($id);

        $data = array(
            'id'    => $barang->id_brg,
            'qty'   => 1,
            'price' => $barang->harga,
            'name'  => $barang->nama_brg,
            
        );
    
        $this->cart->insert($data);
        redirect ('welcome');
    }

    // --- PERBAIKAN PENTING DI FUNGSI detail_keranjang() ---
    public function detail_keranjang()
    {
        // Mengambil isi keranjang dari library cart
        $data['keranjang'] = $this->cart->contents(); 

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('keranjang', $data); // Mengirim data keranjang ke view
        $this->load->view('templates/footer');
    }
    // --- AKHIR PERBAIKAN ---

    public function hapus_keranjang()
    {
        $this->cart->destroy();
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Semua produk di keranjang Anda telah dihapus!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
        redirect('dashboard/detail_keranjang'); // Redirect kembali ke halaman keranjang
    }

    // --- METHOD UNTUK MENGURANGI JUMLAH ATAU MENGHAPUS ITEM PER ITEM ---
    public function hapus_item_keranjang($rowid)
    {
        // Pastikan rowid valid
        if (!empty($rowid)) {
            // Dapatkan detail item dari keranjang berdasarkan rowid
            $item = $this->cart->get_item($rowid);

            if ($item) {
                // Jika jumlah produk lebih dari 1, kurangi 1
                if ($item['qty'] > 1) {
                    $data = array(
                        'rowid' => $rowid,
                        'qty'   => $item['qty'] - 1 // Kurangi jumlahnya 1
                    );
                    $this->cart->update($data); // Update keranjang
                    $this->session->set_flashdata('pesan',
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Jumlah produk "' . $item['name'] . '" berhasil dikurangi 1.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>');
                } else {
                    // Jika jumlah produk hanya 1, hapus item sepenuhnya
                    $this->cart->remove($rowid);
                    $this->session->set_flashdata('pesan',
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Produk "' . $item['name'] . '" berhasil dihapus dari keranjang!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>');
                }
            } else {
                // Item tidak ditemukan di keranjang
                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Gagal menghapus produk: Item tidak ditemukan di keranjang.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>');
            }
        } else {
            // rowid tidak valid
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal menghapus produk: ID item tidak valid.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
        }
        redirect('dashboard/detail_keranjang'); // Redirect kembali ke halaman keranjang
    }
    // --- AKHIR MODIFIKASI METHOD ---

    public function pembayaran()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('pembayaran');
        $this->load->view('templates/footer');
    }

    public function cek_voucher()
    {
        $kode_voucher = $this->input->post('kode_voucher');
        $response = ['status' => 'error', 'message' => 'Voucher tidak valid.'];

        if (empty($kode_voucher)) {
            $response['message'] = 'Kode voucher tidak boleh kosong.';
            echo json_encode($response);
            return;
        }

        $voucher = $this->model_voucher->get_voucher_by_kode($kode_voucher);

        if ($voucher) {
            $now = new DateTime();
            if ($voucher->kadaluarsa && new DateTime($voucher->kadaluarsa) < $now) {
                $response['message'] = 'Voucher sudah kadaluarsa.';
            } 
            else if ($voucher->batas_penggunaan !== NULL && $voucher->digunakan >= $voucher->batas_penggunaan) {
                $response['message'] = 'Voucher sudah mencapai batas penggunaan.';
            }
            else {
                $response['status'] = 'success';
                $response['message'] = 'Voucher valid!';
                $response['voucher'] = $voucher;
            }
        } else {
            $response['message'] = 'Kode voucher tidak ditemukan atau tidak aktif.';
        }

        echo json_encode($response);
    }

    public function proses_pesanan() {
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim', [
            'required' => 'Nama lengkap wajib diisi!', 'trim' => 'Nama lengkap tidak boleh mengandung spasi di awal/akhir.'
        ]);
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required|trim', [
            'required' => 'Alamat lengkap wajib diisi!', 'trim' => 'Alamat lengkap tidak boleh mengandung spasi di awal/akhir.'
        ]);
        // --- PERUBAHAN DI SINI: Membuat Latitude & Longitude OPSIONAL ---
        $this->form_validation->set_rules('latitude_pengiriman', 'Latitude Pengiriman', 'numeric', [ // Hapus 'required' dan 'callback_not_zero_latitude'
            'numeric' => 'Format lokasi (latitude) tidak valid.'
        ]);
        $this->form_validation->set_rules('longitude_pengiriman', 'Longitude Pengiriman', 'numeric', [ // Hapus 'required' dan 'callback_not_zero_longitude'
            'numeric' => 'Format lokasi (longitude) tidak valid.'
        ]);
        // --- AKHIR PERUBAHAN ---
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|numeric|trim', [
            'required' => 'Nomor telepon wajib diisi!', 'numeric' => 'Nomor telepon harus berupa angka!', 'trim' => 'Nomor telepon tidak boleh mengandung spasi di awal/akhir.'
        ]);
        $this->form_validation->set_rules('jasa_pengiriman', 'Jasa Pengiriman', 'required', [
            'required' => 'Jasa pengiriman wajib dipilih!'
        ]);
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required', [
            'required' => 'Metode pembayaran wajib dipilih!'
        ]);
        // Validasi ongkos_kirim
        // --- PERUBAHAN DI SINI: Membuat Ongkos Kirim OPSIONAL ---
        $this->form_validation->set_rules('ongkos_kirim', 'Ongkos Kirim', 'numeric|greater_than_equal_to[0]', [ // Hapus 'required' dan 'callback_ongkir_not_zero'
            'numeric' => 'Format ongkos kirim tidak valid.', 'greater_than_equal_to' => 'Ongkos kirim tidak boleh negatif.'
        ]);
        // --- AKHIR PERUBAHAN ---
        // Validasi final_grand_total_pembayaran
        $this->form_validation->set_rules('final_grand_total_pembayaran', 'Total Pembayaran', 'required|numeric|greater_than_equal_to[0]', [
            'required' => 'Total pembayaran belum terhitung!', 'numeric' => 'Format total pembayaran tidak valid.', 'greater_than_equal_to' => 'Total pembayaran tidak boleh negatif.'
        ]);


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Silakan perbaiki kesalahan pada input pembayaran Anda.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            $this->load->view('proses_pesanan');
            $this->load->view('templates/footer');
        } else {
            // ... (ambil data dari post) ...
            $nama                       = $this->input->post('nama');
            $alamat                     = $this->input->post('alamat');
            $latitude_pengiriman        = $this->input->post('latitude_pengiriman');
            $longitude_pengiriman       = $this->input->post('longitude_pengiriman');
            // Jika latitude/longitude kosong, set ke NULL agar masuk ke database sebagai NULL
            if (empty($latitude_pengiriman)) $latitude_pengiriman = NULL;
            if (empty($longitude_pengiriman)) $longitude_pengiriman = NULL;
            
            $no_telp                    = $this->input->post('no_telp');
            $jasa_pengiriman            = $this->input->post('jasa_pengiriman');
            $metode_pembayaran          = $this->input->post('metode_pembayaran');
            $final_total                = $this->input->post('final_total_hidden');
            $diskon_nominal             = $this->input->post('diskon_nominal_hidden');
            $applied_voucher_id         = $this->input->post('applied_voucher_id');
            $ongkos_kirim               = $this->input->post('ongkos_kirim');
            // Jika ongkos_kirim kosong, set ke 0.00
            if (empty($ongkos_kirim)) $ongkos_kirim = 0.00;

            $final_grand_total_pembayaran = $this->input->post('final_grand_total_pembayaran');

            // ... (lanjutkan pemrosesan ke model_invoice->index()) ...
            $is_processed = $this->model_invoice->index(
                $nama, 
                $alamat, 
                $no_telp, 
                $jasa_pengiriman, 
                $metode_pembayaran,
                $final_total,
                $diskon_nominal,
                $ongkos_kirim, 
                $final_grand_total_pembayaran, 
                $latitude_pengiriman, 
                $longitude_pengiriman
            );


            if($is_processed){ // <-- Ini akan TRUE hanya jika transaksi berhasil di commit
                // ... (jika berhasil: update voucher, hancurkan keranjang, set flashdata sukses, redirect ke proses_pesanan view) ...
                if (!empty($applied_voucher_id)) {
                    $this->model_voucher->update_penggunaan_voucher($applied_voucher_id);
                }
                $this->cart->destroy();
                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Selamat! Pesanan Anda telah berhasil diproses.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>');
                redirect('dashboard/proses_pesanan');
            } else { // <-- Ini akan dieksekusi jika transaksi di model_invoice gagal (return FALSE)
                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Maaf, Pesanan Anda Gagal di Proses. Silakan coba lagi.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>');
                redirect('dashboard/pembayaran'); // Kembali ke pembayaran jika gagal
            }
        }
    }

    public function detail($id_brg) {
        $data['barang'] = $this->model_barang->detail_brg($id_brg);
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            $this->load->view('detail_barang',$data);
            $this->load->view('templates/footer');
    }


        public function search()
    {
        // Ambil keyword dari form desktop atau mobile
        $keyword = $this->input->post('keyword');
        if (empty($keyword)) { // Jika keyword dari desktop kosong, coba ambil dari mobile
            $keyword = $this->input->post('keyword_mobile');
        }

        if (empty($keyword)) {
            // Jika keyword masih kosong, redirect kembali atau tampilkan pesan
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Silakan masukkan kata kunci pencarian!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            redirect('welcome'); // Redirect ke halaman utama jika tidak ada keyword
        }

        $data['barang'] = $this->model_barang->get_keyword($keyword); // Panggil model_barang untuk mencari
        $data['search_keyword'] = $keyword; // Simpan keyword untuk ditampilkan di view

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        // Kita akan menggunakan view dashboard untuk menampilkan hasil pencarian
        // Ini akan terlihat mirip dengan halaman utama, tapi dengan hasil filter
        $this->load->view('dashboard', $data); 
        $this->load->view('templates/footer');
    }

}

