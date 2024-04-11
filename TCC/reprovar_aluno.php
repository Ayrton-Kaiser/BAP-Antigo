<?php
    
    require('config/conexao.php');

    $RA = filter_input(INPUT_GET, 'RA');
    $query_delete = "DELETE FROM aluno WHERE RA='$RA'";
    $query_delete_proj = "DELETE FROM projeto WHERE RA='$RA'";

    $result_alunos = $pdo->prepare($query_delete);
    $result_alunos->execute();
    
    $result_proj = $pdo->prepare($query_delete_proj);
    $result_proj->execute();
    header('Location: alunos_aprovar.php');
?>