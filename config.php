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


// Zaman Tüneli Yorum Ayarları
$timeline_commenter = array(
    'is_likes' => 0, // Yorum atılan gönderilerin aynı zamanda beğenilmesini istiyorsanız değeri 1 yapın.
    'interval' => 50, // Her yorum sonrası beklenecek süre (Minimum Saniye: 50)
    'comments' => array('omg', 'cool', 'wow') // Gönderilere atılacak yorumları yazın
);