<?php
// Kullanıcı Bilgileriniz
$account = array(
    'username' => '', // Kullanıcı adınız
    'password' => '' // Şifreniz
);


// Kullanıcı Bilgisi Boş/Dolu Kontrolü
if($account['username'] == '' || $account['password'] == '')
{
    die("Please put config.php file in your username and password.");
}


// Zaman Tüneli Beğeni Ayarları
$timeline_liker = array(
    'interval' => 20 // Her beğeni sonrası beklenecek süre (Minimum Saniye: 20)
);