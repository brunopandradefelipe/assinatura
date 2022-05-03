<?php 
require_once 'vendor/autoload.php';

use Saymontavares\OpenPfx\Pfx;

$cert = 'alan.pfx';
$cert_password = '1234';

try {
    $open = new Pfx($cert, $cert_password);
    // chaves privadas
    $keys = $open->read();
    echo "<pre>";
    print_r ($keys);
    echo "</pre>";

    // certificado .cer será salvo no diretório 'certs/' com o nome 'certificado-cer.cer'
    if ($open->toCer('certs/', 'certificado-cer') !== false) echo "arquivo .CER gerado<br>";

    // certificado .pem será salvo na raiz
    if ($open->toPem() !== false) echo "arquivo .PEM gerado";
} catch (Exception $e) {
    echo 'Exceção capturada: ',  $e->getMessage(), "\n";
}

// openssl x509 -outform der -in your-cert.pem -out your-cert.crt