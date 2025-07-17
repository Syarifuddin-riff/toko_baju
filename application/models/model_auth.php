<?php

class Model_auth extends CI_Model{

    public function cek_login()
    {
        $username_input = $this->input->post('username'); // Mengambil username dari form
        $password_input = $this->input->post('password'); // Mengambil password dari form

        // Ambil data user dari database berdasarkan username
        $result = $this->db->where('username', $username_input)
                           ->limit(1)
                           ->get('tb_user');

        if($result->num_rows() > 0 ){
            $user_data = $result->row();
            $stored_encrypted_password = $user_data->password; // Password terenkripsi dari database

            // --- DEKRIPSI AES UNTUK VERIFIKASI ---
            // Pastikan Anda mendekripsi password sebelum membandingkannya
            $decrypted_password = $this->encryption->decrypt($stored_encrypted_password);

            if ($password_input === $decrypted_password) { // Bandingkan password teks biasa
                return $user_data;
            } else {
                return FALSE; // Password tidak cocok
            }
        }else{
            return FALSE; // User tidak ditemukan
        }
    }
}

