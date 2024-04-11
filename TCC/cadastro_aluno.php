<?php

session_start();

require('config/conexao.php');
require('config/Sql.php');

if (isset($_POST['nome']) && isset($_POST['RA']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['CI'])){
    if (empty($_POST['nome']) or empty($_POST['RA'])  or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['CI'])){
        $erro_geral = "Todos os campos são obrigatórios!";
    }else{

        $nome_aluno = limparPost($_POST['nome']);
        $RA = limparPost($_POST['RA']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);
        $CI = limparPost($_POST['CI']);


        if (isset($_POST['curso']) && !empty($_POST['curso'])){
            $curso = limparPost($_POST['curso']);
        }

        //filtro de email
        $email_ar = ($_POST['email']);
        $arroba = '@';
        $pos = strpos($email_ar, $arroba);
        $filtro = substr($email_ar, $pos); 

        //filtro erro pro curso
        if(!isset($_POST['curso'])) {
            $erro_curso = "Selecione um curso!";
        }
        
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/", $nome_aluno)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        $sql = $pdo->prepare("SELECT RA FROM aluno WHERE RA=? LIMIT 1");
        $sql->execute(array($RA));
        $info_ra = $sql->fetch(PDO::FETCH_ASSOC);

        if($info_ra != NULL){
            $erro_ra2 = "RA já cadastrado! Verifique seu RA.";
        }

        if (strlen($RA) !== 14){
            $erro_ra = "Número inválido de caracteres!";
        }

        $sql = $pdo->prepare("SELECT id_instituicao FROM instituicao WHERE id_instituicao=? LIMIT 1");
        $sql->execute(array($CI));
        $info_CI = $sql->fetch(PDO::FETCH_ASSOC);

        if($info_CI == NULL){
            $erro_ci = "Instituição não cadastrada! Verifique o código de sua instituição.";
        }

        if (strlen($CI) !== 3){
            $erro_ci2 = "Número inválido de caracteres!";
        }

        /*if ($CI != $info_CI){
            $erro_ci = "Número inválido de caracteres!";
        }*/

        if(!filter_var($filtro == '@etec.sp.gov.br')){
            $erro_email = "Formato de email inválido!";
        }

        if(strlen($senha) < 8){
            $erro_senha = "Sua senha deve ter no mínimo 8 caracteres!";
        }
        
        if (!isset($erro_geral) && !isset($erro_nome) && !isset($erro_ra) && !isset($erro_ra2) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_curso) && !isset($erro_ci) && !isset($erro_ci2)){
           
            $sql = $pdo->prepare("SELECT * FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
            $sql->execute(array($email));
            $aluno = $sql->fetchAll(PDO::FETCH_ASSOC);

            $sql = $pdo->prepare("SELECT * FROM coordenador WHERE email_institucional_coordenador=? LIMIT 1");
            $sql->execute(array($email));
            $coordenador = $sql->fetchAll(PDO::FETCH_ASSOC);

            if ($aluno == NULL && $coordenador == NULL){
                $sql = $pdo->prepare ("INSERT INTO aluno VALUES (?,?,?,?,?,?)");
                $sql->execute(array($email,$RA,$nome_aluno,$curso,$CI, null));
                $sql = $pdo->prepare ("INSERT INTO login (senha, email_institucional_aluno) VALUES (?,?)");
                $sql->execute(array($senha_cript, $email));

                header('location: login.php?result=ok');

            }else{
                $erro_email_dupli = "Esse email já está cadastrado, por favor insira outro email!";
            }
           
        }
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet" type="text/css">
    <title>Cadastro do Aluno - B.A.P.</title>

</head>
<body>
    <div class="container-body">
        <header class="cabecalho">
            <div class="libarra">
                <img class="icon" src="img/logo_bap.png">
            <span>
                <button class="button"><a href="index.php">Início</a></button>
                <button class="button"><a href="login.php">Login</a></button>
            </span>
        </div>
        </header>
        <div class="cadastro">
            <form class="form-cadastro" method="post">
                <fieldset>

                <?php if(isset($erro_geral)){ 
                    echo $erro_geral;
                } ?>
                    <div class="input-group">
                        <div><p>Nome Completo</p></div>
                        <div><input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';} ?> type="text" name="nome" placeholder="Digite seu nome aqui!" <?php if(isset($_POST['nome'])){echo "value'".$_POST['nome']."'";} ?> required /></div>
                        <?php if(isset($erro_nome)) { 
                        echo "<div>".$erro_nome."</div>";
                         } ?>

                        <div><p>RA</p></div>
                        <div><input <?php if(isset($erro_geral) or isset($erro_ra) or isset($erro_ra2)){echo 'class="erro-input"';} ?> type="text" name="RA" placeholder="Digite seu RA aqui!" <?php if(isset($_POST['RA'])){echo "value'".$_POST['RA']."'";} ?> required /></div>
                        <?php if(isset($erro_ra)) { 
                        echo "<div>".$erro_ra."</div>";
                        } ?>
                        <?php if(isset($erro_ra2)) { 
                        echo "<div>".$erro_ra2."</div>";
                         } ?>


                        <div><p>Código da Instituição</p></div>
                        <div><input <?php if(isset($erro_geral) or isset($erro_ci) or isset($erro_ci2)){echo 'class="erro-input"';} ?>type="text" name="CI" placeholder="Digite o código da sua instituição aqui!" <?php if(isset($_POST['CI'])){echo "value'".$_POST['CI']."'";} ?>required/></div>
                        <?php if(isset($erro_ci)) { 
                        echo "<div>".$erro_ci."</div>";
                        } ?>
                        <?php if(isset($erro_ci2)) { 
                        echo "<div>".$erro_ci2."</div>";
                        } ?>


                        <div><p>Selecione seu curso</p></div>
                        <div><select name="curso" <?php if(isset($erro_geral) or isset($erro_curso)){echo 'class="erro-input"';} ?> >
                            <option disabled selected>Selecione seu curso</option>
                            <option>Desenvolvimento de Sistemas</option>
                            <option>Administração</option>
                            <option>Mecânica</option>
                        </select></div>
                        <?php if(isset($erro_curso)) { 
                        echo "<div>".$erro_curso."</div>";
                         } ?>

                        <div><p>EMAIL Institucional</p></div>
                        <div><input <?php if(isset($erro_geral) or isset($erro_email)  or isset($erro_email_dupli)){echo 'class="erro-input"';} ?> type="email" name="email" placeholder="Digite seu EMAIL aqui!" <?php if(isset($_POST['email'])){echo "value'".$_POST['email']."'";} ?> required /></div>
                        <?php if(isset($erro_email)) { 
                        echo "<div>".$erro_email."</div>";
                         } ?>
                        <?php if(isset($erro_email_dupli)) { 
                        echo "<div>".$erro_email_dupli."</div>";
                         } ?>

                        <div><p>Senha</p></div>
                        <div><input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';} ?> type="password" name="senha" placeholder="Insira uma senha" <?php if(isset($_POST['senha'])){echo "value'".$_POST['senha']."'";} ?> required /></div>
                        <?php if(isset($erro_senha)) { 
                            echo "<div>".$erro_senha."</div>";
                        } ?>
                    </div>
                    <div>
                        <input type="submit" name="cadastrar" value="Cadastrar" class="button" />
                        <p><a href="login.php">Já possui uma conta? Clique aqui!</a></p>
                    </div>
                </fieldset>
            </form>
        </div>
        <br>
        <p>
        <br>
        <p>
    </div>
</body>
</html>