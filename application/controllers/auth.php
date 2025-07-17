<?php

class Auth extends CI_Controller{

    public function login()
    {
        $this->form_validation->set_rules('username','Username','required',[
            'required'  => 'Username wajib di isi!'
        ]);
        $this->form_validation->set_rules('password','Password','required',[
            'required'  => 'Password wajib di isi!' // Perbaiki pesan error di sini
        ]);
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/header');
            $this->load->view('form_login');
        }else{
            $auth = $this->model_auth->cek_login(); // model_auth->cek_login() sudah disesuaikan untuk AES

            if($auth == FALSE)
            {
                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Username atau Password Anda Salah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>');
                redirect('auth/login');
            }else {
                // Cek role_id setelah berhasil otentikasi
                if ($auth->role_id == '1') { // Jika admin mencoba login dari form user
                    $this->session->set_flashdata('pesan',
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Admin harus login melalui halaman login Admin.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>');
                    redirect('admin/auth_admin/login'); // Arahkan ke form login admin
                } else if ($auth->role_id == '2') { // Jika user biasa
                    $this->session->set_userdata('username',$auth->username);
                    $this->session->set_userdata('role_id',$auth->role_id);
                    redirect('welcome'); // Redirect user biasa ke halaman welcome
                } else { // Role ID tidak dikenal
                    $this->session->set_flashdata('pesan',
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Role pengguna tidak dikenal.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>');
                    redirect('auth/login');
                }
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login'); // Logout user biasa akan kembali ke login user biasa
    }

}