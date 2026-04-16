<?php
class EmailConfig {

    // 📧 SMTP (Gmail / Production)
    public $default = array(
        'transport' => 'Smtp',
        'from' => array('shop@yourdomain.com' => 'MyShop'),
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'timeout' => 30,
        'tls' => true,
        'username' => 'your@gmail.com',
        'password' => 'app-specific-password', // Use Google App Password
        'client' => null,
        'log' => false,
        'charset' => 'utf-8',
        'headerCharset' => 'utf-8',
    );

    // 📩 Local Mail (for testing)
    public $local = array(
        'transport' => 'Mail',
        'from' => array('shop@localhost' => 'MyShop'),
        'charset' => 'utf-8',
        'headerCharset' => 'utf-8',
    );
}