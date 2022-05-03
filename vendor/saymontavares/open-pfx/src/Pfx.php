<?php

    namespace Saymontavares\OpenPfx;

    class Pfx
    {
        /**
         * Caminho relativo do arquivo PFX
         *
         * @var String Path file
         */
        private $cert_path;

        /**
         * Senha do certificado
         *
         * @var String Senha
         */
        private $cert_pass;

        /**
         * Gera os arquivos à partir do caminho relativo e da senha informada
         *
         * @param String $cert_path Caminho relativo do arquivo PFX
         * @param String $cert_pass Senha do certificado
         * @return Void
         */
        public function __construct(String $cert_path, String $cert_pass)
        {
            if (is_file($cert_path) && !is_dir($cert_path)) {
                $this->cert_path = file_get_contents($cert_path);
                $this->cert_pass = $cert_pass;
            } else {
                throw new \Exception('Erro: Arquivo não encontrado');
            }
        }

        /**
         * Abre o arquivo PFX e extrai as chaves privadas
         *
         * @return Array Retorno uma array com as chaves privadas
         */
        public function read()
        {
            if(!openssl_pkcs12_read($this->cert_path, $keys, $this->cert_pass)) {
                throw new \Exception('Erro: '.openssl_error_string());
            }

            return $keys;
        }

        /**
         * Gera o certificado no formato CER
         *
         * @param String $path Caminho relativo para salvar o arquivo CER, padrão é o diretório atual (OPCIONAL)
         * @param String $filename Nome do arquivo, padrão é 'Cert.cer' (OPCIONAL)
         * @return Bool|Int Retorna false em falhas, caso contrário o tamanho do arquivo em bytes
         */
        public function toCer(String $path = null, String $filename = null)
        {
            $keys = $this->read();
            $filename = empty($filename) ? 'Cert.cer' : trim("{$filename}.cer");
            if (!$file = file_put_contents("{$path}{$filename}", $keys['pkey'].$keys['cert'].(isset($keys['extracerts']) ? implode('', $keys['extracerts']) : null))) {
                throw new \Exception('Erro: Arquivo não pode ser salvo.');
            }

            return $file;
        }

        /**
         * Gera o certificado no formato PEM
         *
         * @param String $path Caminho relativo para salvar o arquivo PEM, padrão é o diretório atual (OPCIONAL)
         * @param String $filename Nome do arquivo, padrão é 'Key.pem' (OPCIONAL)
         * @return Bool|Int Retorna false em falhas, caso contrário o tamanho do arquivo em bytes
         */
        public function toPem(String $path = null, String $filename = null)
        {
            $keys = $this->read();
            $filename = empty($filename) ? 'Key.pem' : trim("{$filename}.pem");
            if (!$file = file_put_contents("{$path}{$filename}", $keys['cert'].(isset($keys['extracerts']) ? implode('', $keys['extracerts']) : null))) {
                throw new \Exception('Erro: Arquivo não pode ser salvo.');
            }

            return $file;
        }
    }
