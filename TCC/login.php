<?php
    session_start();

    require('config/conexao.php');

    if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha']) ){
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha); 

        //filtro pra email_aluno 
        $sql = $pdo->prepare("SELECT email_institucional_aluno FROM login WHERE email_institucional_aluno=? and senha=? LIMIT 1");
        $sql->execute(array($email, $senha_cript));
        $aluno = $sql->fetch(PDO::FETCH_ASSOC);

        //filtro pra email_coord
        $sql = $pdo->prepare("SELECT email_institucional_coordenador FROM login WHERE email_institucional_coordenador=? and senha=? LIMIT 1");
        $sql->execute(array($email, $senha_cript));
        $coordenador = $sql->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($coordenador)){
            //visivel para o projeto inteiro
            $_SESSION['email_coordenador']=$email;

            $sql = $pdo->prepare("SELECT nome_coordenador FROM coordenador WHERE email_institucional_coordenador=? LIMIT 1");
            $sql->execute(array($email));
            $info_nome_coord = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_nome_coord as $key => $nome_coordenador) {
                # code...
                $_SESSION['nome_coordenador']=$nome_coordenador;
            }

            $sql = $pdo->prepare("SELECT id_instituicao FROM coordenador WHERE email_institucional_coordenador=? LIMIT 1");
            $sql->execute(array($email));
            $info_CI_coord = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_CI_coord as $key => $CI_coordenador) {
                # code...
                //$_SESSION['CI_coordenador']=$CI_coordenador;
            }
            
            $sql = $pdo->prepare("SELECT nome_instituicao FROM instituicao WHERE id_instituicao=? LIMIT 1");
            $sql->execute(array($CI_coordenador));
            $info_nome_instituicao = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_nome_instituicao as $key => $nome_inst) {
                # code...
                $_SESSION['nome_instituicao']=$nome_inst;
            }
        }

        if (!empty($aluno)){
            //visivel para o projeto inteiro
            $_SESSION['email_aluno']=$email;

            $sql = $pdo->prepare("SELECT nome_aluno FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
            $sql->execute(array($email));
            $info_nome_aluno = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_nome_aluno as $key => $nome_aluno) {
                # code...
                $_SESSION['nome_aluno']=$nome_aluno;
            }

            $sql = $pdo->prepare("SELECT id_instituicao FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
            $sql->execute(array($email));
            $info_CI_aluno = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_CI_aluno as $key => $CI_aluno) {
                # code...
                //$_SESSION['CI_coordenador']=$CI_coordenador;
            }
            
            $sql = $pdo->prepare("SELECT nome_instituicao FROM instituicao WHERE id_instituicao=? LIMIT 1");
            $sql->execute(array($CI_aluno));
            $info_nome_instituicao = $sql->fetch(PDO::FETCH_ASSOC);
            foreach ($info_nome_instituicao as $key => $nome_inst) {
                # code...
                $_SESSION['nome_instituicao']=$nome_inst;
            }
        }

        /*var_dump($usuario);
        var_dump($aluno);
        var_dump($coordenador);*/

        //filtro de email
        $email_ar = ($_POST['email']);
        $arroba = '@';
        $pos = strpos($email_ar, $arroba);
        $filtro = substr($email_ar, $pos); 

        //filtro pro email
        if(!filter_var($filtro == '@etec.sp.gov.br')){
            $erro_email = "Formato de email inválido!";
        }

        if(strlen($senha) < 8){
            $erro_senha = "Sua senha deve ter no mínimo 8 caracteres!";
        }

        if ($coordenador == NULL && $aluno == NULL){
            $erro_login = "Usuário e/ou senha incorretos!";
        }elseif(!empty($coordenador)){
            header('location: home_coordenador.php?result=ok');
        }else{
            $_SESSION['email_institucional_aluno'] = $email;
            header('location: home_aluno.php?result=ok');
        }


        /*if('email_institucional_aluno' == NULL){
            header('location: home_aluno.php');
                }if($email = )

            /*}else{
                $erro_login = "Por favor confirme seu cadastro no seu e-mail cadastrado!";
            }        
    
        }else{
            $erro_login = "Usuário e/ou senha incorretos!";
        }*/
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet" type="text/css">
    <title>Login - B.A.P.</title>
</head>
<body>
    <div class="container-body">
        <header class="cabecalho">
            <div class="libarra">
                <img class="icon" src="img/logo_bap.png">
            <span>
                <button class="button">Início</button>
                <button class="button">Cadastro</button>
            </span>
        </div>
        </header>
        <div class="form-login">
            <form class="login" method="post">
                <fieldset class="container-fieldset">
                    <div class="input-group">
                        <div>

                        <?php if(isset($erro_login)){
                        echo $erro_login;
                        } ?>

                            <div><p> Email institucional: </p></div>
                            <div>
                                <input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input"';} ?> type="email" name="email" placeholder="Digite seu email" <?php if(isset($_POST['email'])){echo "value'".$_POST['email']."'";} ?> required />
                            </div>
                            <?php if(isset($erro_email)) { 
                            echo "<div>".$erro_email."</div>";
                            } ?>


                        </div>
                        <div>
                            <div><p> Senha: </p></div>
                            <div>
                                <input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';} ?> type="password" name="senha" placeholder="Digite sua senha" <?php if(isset($_POST['senha'])){echo "value'".$_POST['senha']."'";} ?> required />
                            </div>
                            <?php if(isset($erro_senha)) { 
                            echo "<div>".$erro_senha."</div>";
                            } ?>

                        </div>
                        <div>
                            <div>
                                <a href="esqueci.php">Esqueceu a senha? </a>
                            </div>
                            <div>
                            <button class="button">Login</button>
                            <div>
                                <a href="cadastro.php">Ainda não tenho cadastro</a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</html>