<?php
    
    require('config/conexao.php');
    
    $RA = filter_input(INPUT_GET, 'RA');
    $query_select = "SELECT * FROM aluno WHERE RA='$RA'";
    $query_update = "UPDATE aluno SET situacao=1 WHERE RA='$RA'";

    $result_alunos = $pdo->prepare($query_select);
    $result_alunos->execute();

    $result_update = $pdo->prepare($query_update);
    $result_update->execute();
    header('Location: alunos_aprovar.php');
?>