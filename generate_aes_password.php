<?php
// Pastikan library encryption sudah di autoload atau di load di sini
$CI =& get_instance('');
$CI->load->library('encryption');

// Ganti 'password_admin_baru_anda' dengan password teks biasa yang Anda inginkan untuk admin
$password_to_encrypt = 'Kambing007'; 

$encrypted_password = $CI->encryption->encrypt($password_to_encrypt);

echo $encrypted_password;
?>