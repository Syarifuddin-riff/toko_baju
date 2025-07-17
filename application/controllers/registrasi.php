<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {

    public function index()
    {
        // Pastikan Anda meload library form_validation dan encryption di autoload.php
        // $this->load->library('form_validation'); // Sudah di autoload
        // $this->load->library('encryption'); // Sudah di autoload

        $this->form_validation->set_rules('nama','Nama','required', [
            'required'  => 'Nama wajib di isi!'
        ]);
        $this->form_validation->set_rules('username','Username','required|is_unique[tb_user.username]', [
            'required'  => 'Username wajib di isi!',
            'is_unique' => 'Username ini sudah terdaftar!'
        ]);
        $this->form_validation->set_rules('password_1','Password','required|matches[password_2]|min_length[6]', [
            'required'  => 'Password wajib di isi!',
            'matches'   => 'Password tidak cocok!',
            'min_length'=> 'Password minimal 6 karakter!'
        ]);
        $this->form_validation->set_rules('password_2','Password','required|matches[password_1]');

        if($this->form_validation->run() == FALSE){
            $this->load->view('templates/header'); // Memuat <head> tag
            $this->load->view('registrasi'); // Memuat sisa halaman termasuk <body>, js, dan </body></html>
            //$this->load->view('templates/footer');
        } else {
            $raw_password = $this->input->post('password_1');
            
            // --- Menggunakan Enkripsi AES (sesuai permintaan tugas Anda) ---
            $encrypted_password = $this->encryption->encrypt($raw_password); 

            $data = array(
                'id'        => '', // Biarkan kosong jika auto-increment
                'nama'      => $this->input->post('nama'),
                'username'  => $this->input->post('username'),
                'password'  => $encrypted_password, // Simpan password yang sudah dienkripsi AES
                'role_id'   => 2, // Default untuk registrasi user biasa
            );

            // Jika Anda ingin mendaftarkan admin melalui form ini,
            // Anda HARUS mengubah 'role_id' di atas menjadi 1 SEMENTARA SAJA,
            // lalu kembalikan ke 2 setelah berhasil mendaftar admin.
            // Contoh: $data['role_id'] = 1; // Untuk membuat akun admin

            $this->db->insert('tb_user',$data);
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Akun Anda berhasil didaftarkan! Silahkan Login.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            redirect('auth/login');
        }
    }
}



