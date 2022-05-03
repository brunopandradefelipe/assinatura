<?php
$x509  = $_SERVER['SSL_CLIENT_CERT']
//Obter chave pública
    $pub_key = openssl_pkey_get_public($_SERVER['SSL_CLIENT_CERT']);
    $details = openssl_pkey_get_details($pub_key);
    
//Lendo outros dados do certificado.
        $cert = openssl_x509_read($_SERVER['SSL_CLIENT_CERT']);
        $certData = openssl_x509_parse($cert);
        openssl_x509_free($cert);       

        openssl_x509_export_to_file($x509,'/path/to/client_certificate.cer');
        
        $data  = "String para assinar ";
        
        //Criptografar
        $a_key = openssl_pkey_get_details($pub_key);
        $chunkSize = ceil($a_key['bits'] / 8) - 11;
        $crypttext = "";
        $data = gzcompress($data);
        while ($data) {
            $chunk = substr($data, 0, $chunkSize);
            $data = substr($data, $chunkSize);
            $encrypted = '';
            if (!openssl_public_encrypt($chunk, $encrypted, $pub_key)) {
                die('Failed to encrypt data');
            }
            $crypttext .= $encrypted;
        }
        $crypttext = base64_encode($crypttext); //String criptografada.
        echo $crypttext;
        openssl_pkey_free($pub_key);
        
        //Descriptografar:
        $privateKey = "user.key"; //User private key vc não vai ter acesso à ela.
        $a_key = openssl_pkey_get_details($privateKey);
        $chunkSize = ceil($a_key['bits'] / 8);
        $output = '';
        $encrypted = base64_decode($encrypted);
        while ($encrypted) {
            $chunk = substr($encrypted, 0, $chunkSize);
            $encrypted = substr($encrypted, $chunkSize);
            $decrypted = '';
            if (!openssl_private_decrypt($chunk, $decrypted, $privateKey)) {
                die('Failed to decrypt data');
            }
            $output .= $decrypted;
        }
        openssl_free_key($privateKey);
        echo gzuncompress($output);