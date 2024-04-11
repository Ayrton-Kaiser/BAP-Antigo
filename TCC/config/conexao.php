<?php

$modo = 'local';

if($modo == 'local'){
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "banco_bap";
}

try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*echo "valeu valeu";*/
}catch(PDOException $erro){
    /*echo "Falha ao se conectar com o banco!";*/
}

function limparPost($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

?>