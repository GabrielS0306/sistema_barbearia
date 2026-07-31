<?php

    // core/Pix.php

    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;

    class Pix {
        private string $chave;
        private string $nome;
        private string $cidade;
        private string $descricao;

        public function __construct() {
            $cfg             = require __DIR__ . '/../config/pix.php';
            $this->chave     = $cfg['chave'];
            $this->nome      = $cfg['nome'];
            $this->cidade    = $cfg['cidade'];
            $this->descricao = $cfg['descricao'];
        }

        public function gerarCopiaCola(float $valor): string {
            $valor = number_format($valor, 2, '.', '');

            $payload = $this->campo('00', '01') 
                . $this->campo('26',            
                    $this->campo('00', 'BR.GOV.BCB.PIX')
                    . $this->campo('01', $this->chave)
                    . $this->campo('02', $this->descricao)
                )
                . $this->campo('52', '0000')      
                . $this->campo('53', '986')      
                . $this->campo('54', $valor)     
                . $this->campo('58', 'BR')        
                . $this->campo('59', substr($this->nome, 0, 25))
                . $this->campo('60', substr($this->cidade, 0, 15))
                . $this->campo('62',              
                    $this->campo('05', '***')
                )
                . '6304';                      

            return $payload . $this->crc16($payload);
        }

        public function gerarQrCode(float $valor): string {
            $copiaCola = $this->gerarCopiaCola($valor);

            $options = new QROptions([
                'outputType'  => 'png',
                'scale'       => 6,
                'imageBase64' => true,
            ]);

            return (new QRCode($options))->render($copiaCola);
        }

        private function campo(string $id, string $valor): string {
            $tamanho = str_pad(strlen($valor), 2, '0', STR_PAD_LEFT);
            return $id . $tamanho . $valor;
        }

        private function crc16(string $payload): string {
            $crc  = 0xFFFF;
            $poly = 0x1021;

            for ($i = 0; $i < strlen($payload); $i++) {
                $crc ^= (ord($payload[$i]) << 8);
                
                for ($j = 0; $j < 8; $j++) {
                    if ($crc & 0x8000) {
                        $crc = (($crc << 1) & 0xFFFF) ^ $poly;
                    } else {
                        $crc = ($crc << 1) & 0xFFFF;
                    }
                }
            }

            return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
        }
    }

?>