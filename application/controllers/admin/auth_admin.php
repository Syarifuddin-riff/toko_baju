<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat model_auth untuk otentikasi
        $this->load->model('model_auth');
        // Memuat library form_validation untuk validasi input
        $this->load->library('form_validation');
    }

    public function login()
    {
        // Aturan validasi form login admin
        $this->form_validation->set_rules('username','Username','required',[
            'required'  => 'Username admin wajib diisi!'
        ]);
        // Perbaiki pesan error di sini
        $this->form_validation->set_rules('password','Password','required',[
            'required'  => 'Password admin wajib diisi!'
        ]);

        if ($this->form_validation->run() == FALSE)
        {
            // Jika validasi gagal, tampilkan kembali form login admin
            $this->load->view('templates_admin/header'); // Memuat <head> tag
            $this->load->view('admin/form_login_admin'); // Memuat sisa halaman termasuk <body>, js, dan </body></html>
        } else {
            // Cek login melalui model_auth
            $auth = $this->model_auth->cek_login(); // model_auth->cek_login() sudah kita sesuaikan untuk AES

            // Jika otentikasi gagal atau role_id bukan admin
            if($auth == FALSE || $auth->role_id != '1')
            {
                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Username atau Password Admin Anda Salah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>');
                redirect('admin/auth_admin/login'); // Redirect kembali ke form login admin
            }else {
                // Jika otentikasi berhasil dan role_id adalah admin
                $this->session->set_userdata('username', $auth->username);
                $this->session->set_userdata('role_id', $auth->role_id);
                
                // Redirect ke dashboard admin
                redirect('admin/dashboard_admin');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy(); // Hancurkan sesi
        redirect('admin/auth_admin/login'); // Redirect ke halaman login admin
    }

}