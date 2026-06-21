<?php 

    // core/Upload.php
    class Upload {
        private const TIPOS_PERMITIDOS = ['image/jpeg', 'image/png', 'image/webp'];
        private const TAMANHO_MAXIMO = 2 * 1024 * 1024; // 2mb
        
        public static function salvarImagem(array $arquivo, string $pastaDestino): string|false {
            // Verifica se o upload em si não teve problema técnico
            if ($arquivo['error'] !== UPLOAD_ERR_OK) {
                return false;
            }

            if (!in_array($arquivo['type'], self::TIPOS_PERMITIDOS)) {
                return false;
            }

            if ($arquivo['size'] > self::TAMANHO_MAXIMO) {
                return false;
            }

            $extensao = match ($arquivo['type']) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
            };

            // Gera um nome único pro arquivo, evitando que duas fotos com nome original igual (foto.jpg) se sobrescrevam.
            $nomeArquivo = uniqid('foto_', true) . '.' . $extensao;
            $caminhoCompleto = $pastaDestino . '/' . $nomeArquivo;

            if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                return false;
            }

            return $nomeArquivo;
        }
    }

?>