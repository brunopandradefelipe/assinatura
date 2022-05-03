# open-pfx
[![License: GPL 3.0](https://img.shields.io/badge/License-GPL-red.svg)](https://opensource.org/licenses/GPL-3.0)
[![Latest Stable Version](http://poser.pugx.org/saymontavares/open-pfx/v)](https://packagist.org/packages/saymontavares/open-pfx) [![Total Downloads](http://poser.pugx.org/saymontavares/open-pfx/downloads)](https://packagist.org/packages/saymontavares/open-pfx) [![Latest Unstable Version](http://poser.pugx.org/saymontavares/open-pfx/v/unstable)](https://packagist.org/packages/saymontavares/open-pfx) [![License](http://poser.pugx.org/saymontavares/open-pfx/license)](https://packagist.org/packages/saymontavares/open-pfx) [![PHP Version Require](http://poser.pugx.org/saymontavares/open-pfx/require/php)](https://packagist.org/packages/saymontavares/open-pfx)

Classe para gerar os arquivos privados à partir do arquivo PFX criptografado com senha.

### Table of Contents

- [Visão geral](#visão-geral)
- [Built](#built-with)
- [Como usar](#como-usar)
- [Contact](#contact)

### Visão geral
Informando o arquivo PFX com a senha, a classe fica reponsável por gerar o arquivo .cer e/ou .pem, é possível definir o diretório e o nome dos arquivos que serão salvos.

### Built With
- [PHP 7+](https://www.php.net/)

### Como usar
Instale o pacote no seu projeto e siga o exemplo:
```bash
$ composer install saymontavares/open-pfx
```
```php
require_once 'vendor/autoload.php';

use Saymontavares\OpenPfx\Pfx;

$cert = 'CERTIFICADO.pfx';
$cert_password = 'SENHA';

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
```

## Contact

- GitHub [@saymontavares](https://github.com/saymontavares)

Thank You! developed by **Saymon Tavares**.
