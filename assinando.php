<?php
require_once('vendor/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

class PDF extends FPDI
{
    private $nome;

    function __construct($nome)
    {
        parent::__construct();
        $this->nome = $nome;
    }

    function Header()
    {
    }

    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-10);
        // Police Arial italique 8
        $this->SetFont('Helvetica', '', 6);
        // Numéro de page
        $textoFooter = "DOCUMENTO ASSINADO DIGITALMENTE POR $this->nome VERIFIQUE O DOCUMENTO EM https://verificador.iti.gov.br";
        $this->Cell(0, 10, $textoFooter, 'T', 0, 'C');
    }
}


// Rotas
$diretorio = getcwd() . '/';
$nomeCertPFX = $diretorio . 'pierre.pfx';
$documentoParaAssinar = $diretorio . '50948.pdf';
$nomeCertCRT = $diretorio . 'teste.pem';
$password = '1234';


// Criando CRT caso não exista
if (!file_exists($nomeCertCRT)) {
    var_dump(shell_exec("openssl pkcs12 -in $nomeCertPFX -out $nomeCertCRT -nodes -passin pass:$password"));
}

$pkcs12 = file_get_contents($nomeCertPFX);

// Pega Conteudo do arquivo
$p = file_get_contents($nomeCertCRT);

// aqui a gente pega o certificado .crt, mas esse cara a gente tem que gerar
$cert = openssl_x509_read($p);
$cert_parsed = openssl_x509_parse($cert, true);

// print_r($cert_parsed);

$nome_cpf = explode(":", $cert_parsed['subject']['CN']);


$res = [];
$openSSL = openssl_pkcs12_read($pkcs12, $res, $password);
if (!$openSSL) {
    throw new ClientException("Error: " . openssl_error_string());
}


// aqui a gente pega o certificado .pfx
if (openssl_pkcs12_read($pkcs12, $cert_info, $password)) {
    // echo "Certificate read\n";
} else {
    echo "Error: Unable to read the cert store.\n";
    exit;
}

//Informações da assinatura - Preencha com os seus dados
$info = array(
    'Name' => 'teste',
    'Location' => 'teste',
    'Reason' => 'teste',
    'ContactInfo' => 'teste',
);

$pdf = new PDF($nome_cpf[0]);
//Importa uma página
$numPages = $pdf->setSourceFile($documentoParaAssinar);
for ($i = 0; $i < $numPages; $i++) {
    # code...
    $pdf->AddPage();
    $tplId = $pdf->importPage($i + 1);
    // $pdf->setSignature('file://'.$cert, 'file://'.realpath($cert), '','', 2, $info);
    $pdf->setSignature($cert_info['cert'], $cert_info['pkey'], '', '', 2, $info);


    $pdf->useTemplate($tplId, 0, 0); //Importa nas medidas originais
    // print a line of text
    $pdf->setSignatureAppearance(10, 10, 10, 10, 1);
}

//Manda o PDF pra download
$pdf->Output('./assinado/laudo.pdf', 'D');

// openssl x509 -outform der -in your-cert.pem -out your-cert.crt

// openssl pkcs12 -in certificado.pfx -out certificado.pem -nodes
