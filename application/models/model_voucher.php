<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_voucher extends CI_Model {

    public function tampil_data_voucher()
    {
        return $this->db->get('tb_voucher');
    }

    public function tambah_voucher($data)
    {
        $this->db->insert('tb_voucher', $data);
    }

    public function edit_voucher($where)
    {
        return $this->db->get_where('tb_voucher', $where);
    }

    public function update_voucher($where, $data)
    {
        $this->db->where($where);
        $this->db->update('tb_voucher', $data);
    }

    public function hapus_voucher($where)
    {
        $this->db->where($where);
        $this->db->delete('tb_voucher');
    }

    public function get_voucher_by_kode($kode_voucher)
    {
        return $this->db->get_where('tb_voucher', ['kode_voucher' => $kode_voucher, 'is_active' => 1])->row();
    }

    public function update_penggunaan_voucher($id_voucher)
    {
        $this->db->set('digunakan', 'digunakan + 1', FALSE);
        $this->db->where('id_voucher', $id_voucher);
        $this->db->update('tb_voucher');
    }
}

