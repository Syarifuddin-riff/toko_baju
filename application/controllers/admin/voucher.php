<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model_voucher jika belum di autoload
        $this->load->model('model_voucher');

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
        $data['voucher'] = $this->model_voucher->tampil_data_voucher()->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/data_voucher', $data); // View baru untuk data voucher
        $this->load->view('templates_admin/footer');
    }

    public function tambah_aksi()
    {
        $this->form_validation->set_rules('kode_voucher', 'Kode Voucher', 'required|is_unique[tb_voucher.kode_voucher]', [
            'required'  => 'Kode Voucher wajib diisi!',
            'is_unique' => 'Kode Voucher sudah ada!'
        ]);
        $this->form_validation->set_rules('nilai_diskon', 'Nilai Diskon', 'required|numeric|greater_than[0]', [
            'required'      => 'Nilai Diskon wajib diisi!',
            'numeric'       => 'Nilai Diskon harus angka!',
            'greater_than'  => 'Nilai Diskon harus lebih dari 0!'
        ]);
        $this->form_validation->set_rules('tipe_diskon', 'Tipe Diskon', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->index(); // Kembali ke halaman index untuk menampilkan error
        }
        else
        {
            $kadaluarsa_input = $this->input->post('kadaluarsa');
            $batas_penggunaan_input = $this->input->post('batas_penggunaan');

            $data = array(
                'kode_voucher'      => $this->input->post('kode_voucher'),
                'nilai_diskon'      => $this->input->post('nilai_diskon'),
                'tipe_diskon'       => $this->input->post('tipe_diskon'),
                'kadaluarsa'        => !empty($kadaluarsa_input) ? date('Y-m-d H:i:s', strtotime($kadaluarsa_input)) : NULL,
                'batas_penggunaan'  => !empty($batas_penggunaan_input) ? (int)$batas_penggunaan_input : NULL,
                'is_active'         => $this->input->post('is_active') ? 1 : 0
            );

            $this->model_voucher->tambah_voucher($data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">Voucher berhasil ditambahkan!</div>');
            redirect('admin/voucher/index');
        }
    }

    public function edit($id_voucher)
    {
        $where = array('id_voucher' => $id_voucher);
        $data['voucher'] = $this->model_voucher->edit_voucher($where)->row();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/edit_voucher', $data); // View baru untuk edit voucher
        $this->load->view('templates_admin/footer');
    }

    public function update()
    {
        $id_voucher = $this->input->post('id_voucher');
        $this->form_validation->set_rules('kode_voucher', 'Kode Voucher', 'required', [
            'required'  => 'Kode Voucher wajib diisi!'
        ]);
        $this->form_validation->set_rules('nilai_diskon', 'Nilai Diskon', 'required|numeric|greater_than[0]', [
            'required'      => 'Nilai Diskon wajib diisi!',
            'numeric'       => 'Nilai Diskon harus angka!',
            'greater_than'  => 'Nilai Diskon harus lebih dari 0!'
        ]);
        $this->form_validation->set_rules('tipe_diskon', 'Tipe Diskon', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id_voucher); // Kembali ke form edit jika validasi gagal
        }
        else
        {
            $kadaluarsa_input = $this->input->post('kadaluarsa');
            $batas_penggunaan_input = $this->input->post('batas_penggunaan');

            $data = array(
                'kode_voucher'      => $this->input->post('kode_voucher'),
                'nilai_diskon'      => $this->input->post('nilai_diskon'),
                'tipe_diskon'       => $this->input->post('tipe_diskon'),
                'kadaluarsa'        => !empty($kadaluarsa_input) ? date('Y-m-d H:i:s', strtotime($kadaluarsa_input)) : NULL,
                'batas_penggunaan'  => !empty($batas_penggunaan_input) ? (int)$batas_penggunaan_input : NULL,
                'is_active'         => $this->input->post('is_active') ? 1 : 0
            );

            $where = array('id_voucher' => $id_voucher);
            $this->model_voucher->update_voucher($where, $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">Voucher berhasil diperbarui!</div>');
            redirect('admin/voucher/index');
        }
    }

    public function hapus($id_voucher)
    {
        $where = array('id_voucher' => $id_voucher);
        $this->model_voucher->hapus_voucher($where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">Voucher berhasil dihapus!</div>');
        redirect('admin/voucher/index');
    }
}

