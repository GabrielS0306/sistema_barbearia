<?php
$dados = json_encode(['email' => 'gabrieldossantosnunes91@gmail.com', 'senha' => 'senha123']);

$ch = curl_init('http://localhost/barbearia/api/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
curl_close($ch);

echo $resposta;