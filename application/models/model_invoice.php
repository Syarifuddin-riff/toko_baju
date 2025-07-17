<?php

class Model_invoice extends CI_Model{
    // Modifikasi method index() dengan transaksi database
    public function index($nama, $alamat, $no_telp, $jasa_pengiriman, $metode_pembayaran, $final_total_after_diskon, $diskon_nominal, $ongkos_kirim, $final_grand_total_pembayaran, $latitude_pengiriman, $longitude_pengiriman)
    {
        date_default_timezone_set('Asia/Jakarta');
        
        // --- Memulai Transaksi Database ---
        $this->db->trans_begin();

        $invoice = array (
            'nama'              => $nama,
            'alamat'            => $alamat,
            'tgl_pesan'         => date('Y-m-d H:i:s'),
            'batas_bayar'       => date('Y-m-d H:i:s', mktime( date ('H'),date('i'),date('s'),date('m'),date('d') + 1,date('Y'))),
            'no_telp'           => $no_telp,
            'jasa_pengiriman'   => $jasa_pengiriman,
            'metode_pembayaran' => $metode_pembayaran,
            'total_bayar'       => $final_total_after_diskon, // Total belanja setelah diskon voucher
            'diskon_nominal'    => $diskon_nominal,
            'latitude_pengiriman'  => $latitude_pengiriman, // Koordinat dari Maps
            'longitude_pengiriman' => $longitude_pengiriman, // Koordinat dari Maps
            'ongkos_kirim'      => $ongkos_kirim, // Ongkos kirim
            'grand_total_final' => $final_grand_total_pembayaran // Grand total akhir termasuk ongkir dan diskon
        );

        // 1. Insert ke tabel tb_invoice
        $this->db->insert('tb_invoice', $invoice);
        $id_invoice = $this->db->insert_id();

        // Penting: Periksa status transaksi setelah insert pertama
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback(); // Batalkan semua jika insert invoice gagal
            log_message('error', 'Model_invoice: Transaksi gagal insert tb_invoice. DB Error: ' . $this->db->error()['message']);
            return FALSE;
        }

        // 2. Loop dan insert setiap item ke tb_pesanan & update stok
        foreach ($this->cart->contents() as $item)
        {
            $data_pesanan_item = array(
                'id_invoice'        => $id_invoice,
                'id_brg'            => $item['id'],
                'nama_brg'          => $item['name'],
                'jumlah'            => $item['qty'],
                'harga'             => $item['price'],
            );
            $this->db->insert('tb_pesanan', $data_pesanan_item);

            // Periksa status transaksi setelah insert item pesanan
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback(); // Batalkan semua jika insert item gagal
                log_message('error', 'Model_invoice: Transaksi gagal insert item ' . $item['name'] . ' for invoice ' . $id_invoice . '. DB Error: ' . $this->db->error()['message']);
                return FALSE;
            }

            // Kurangi stok barang setelah pesanan dibuat
            $this->db->set('stok', 'stok - ' . (int)$item['qty'], FALSE);
            $this->db->where('id_brg', $item['id']);
            $this->db->update('tb_barang');

            // Periksa status transaksi setelah update stok
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback(); // Batalkan semua jika update stok gagal
                log_message('error', 'Model_invoice: Transaksi gagal update stok for item ' . $item['name'] . '. DB Error: ' . $this->db->error()['message']);
                return FALSE;
            }
        }
        
        // --- Mengakhiri Transaksi ---
        // Jika semua operasi di atas berhasil dan trans_status masih TRUE, lakukan commit
        if ($this->db->trans_status() === FALSE) {
            // Seharusnya tidak tercapai jika semua cek di atas berfungsi, tapi sebagai fallback
            $this->db->trans_rollback();
            log_message('error', 'Model_invoice: Transaksi gagal di akhir proses, melakukan rollback.');
            return FALSE;
        } else {
            $this->db->trans_commit(); // Semua berhasil, simpan perubahan permanen
            return TRUE;
        }
    }

    public function tampil_data()
    {
        $result = $this->db->get('tb_invoice');
        if($result->num_rows() > 0){
            return $result->result();
        }else {
            return false;
        }
    }

        public function ambil_id_invoice($id_invoice)
    {
        $result = $this->db->where('id', $id_invoice)->limit(1)->get('tb_invoice');
        if($result->num_rows() > 0){
            return $result->row();
        }else {
            return false;
        }
    }

    public function ambil_id_pesanan($id_invoice)
    {
        $result = $this->db->where('id_invoice', $id_invoice)->get('tb_pesanan');
        if($result->num_rows() > 0){
            return $result->result();
        }else {
            return false;
        }
    }


    //membuat fitur statistik penjualan
    public function get_total_pendapatan()
    {
        // Menghitung total pendapatan dari semua pesanan
        // Bergabung dengan tb_pesanan untuk mendapatkan harga dan jumlah
        $this->db->select('SUM(tb_pesanan.jumlah * tb_pesanan.harga) as total_pendapatan');
        $this->db->from('tb_invoice');
        $this->db->join('tb_pesanan', 'tb_pesanan.id_invoice = tb_invoice.id');
        $query = $this->db->get();
        return $query->row()->total_pendapatan;
    }

    public function get_total_pesanan()
    {
        // Menghitung total jumlah invoice (pesanan)
        return $this->db->count_all('tb_invoice');
    }

    public function get_total_produk_terjual()
    {
        // Menghitung total jumlah produk yang terjual dari semua pesanan
        $this->db->select('SUM(jumlah) as total_produk_terjual');
        $this->db->from('tb_pesanan');
        $query = $this->db->get();
        return $query->row()->total_produk_terjual;
    }


    public function get_penjualan_per_produk()
    {
        // Mengambil nama produk dan total jumlah terjual untuk setiap produk
        $this->db->select('nama_brg, SUM(jumlah) as total_terjual');
        $this->db->from('tb_pesanan');
        $this->db->group_by('nama_brg'); // Kelompokkan berdasarkan nama produk
        $this->db->order_by('total_terjual', 'DESC'); // Urutkan dari yang paling banyak terjual
        $this->db->limit(10); // Ambil 10 produk teratas, bisa disesuaikan
        $query = $this->db->get();
        return $query->result_array(); // Kembalikan dalam bentuk array asosiatif
    }
}

