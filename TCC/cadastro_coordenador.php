<?php
require('config/conexao.php');
require('config/Sql.php');

if (isset($_POST['nome']) && isset($_POST['CI']) && isset($_POST['email']) && isset($_POST['CC']) && isset($_POST['senha'])){
    if (empty($_POST['nome']) or empty($_POST['CI']) or empty($_POST['email']) or empty($_POST['CC']) or empty($_POST['senha'])){
        $erro_geral = "Todos os campos são obrigatórios!";
    }else{
        $nome_coord = limparPost($_POST['nome']);
        $CI = limparPost($_POST['CI']);
        $email = limparPost($_POST['email']);
        $CC = limparPost($_POST['CC']);
        $area = limparPost($_POST['area']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);

        //filtro de email
        $email_ar = ($_POST['email']);
        $arroba = '@';
        $pos = strpos($email_ar, $arroba);
        $filtro = substr($email_ar, $pos); 

        //filtro da area
        if(!isset($_POST['area'])) {
            $erro_curso = "Selecione uma área!";
        }

        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/", $nome_coord)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
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

        if(!filter_var($filtro == '@etec.sp.gov.br')){
            $erro_email = "Formato de email inválido!";
        }

        $sql = $pdo->prepare("SELECT id_coordenador FROM coordenador WHERE id_coordenador=? LIMIT 1");
        $sql->execute(array($CC));
        $info_CC = $sql->fetch(PDO::FETCH_ASSOC);

        if($info_CC != NULL){
            $erro_CC2 = "Código do Coordenador já cadastrado! Verifique seu Código Coordenador.";
        }

        if (strlen($CC) !== 14){
            $erro_cc = "Número inválido de caracteres!";
        }

        if(strlen($senha) < 8){
            $erro_senha = "Sua senha deve ter no mínimo 8 caracteres!";
        }

        if (!isset($erro_geral) && !isset($erro_nome) && !isset($erro_ci) && !isset($erro_ci2) && !isset($erro_email) && !isset($erro_cc) && !isset($erro_CC2) && !isset($erro_area) && !isset($erro_senha)){
           
            $sql = $pdo->prepare("SELECT * FROM coordenador WHERE email_institucional_coordenador=? LIMIT 1");
            $sql->execute(array($email));
            $coordenador = $sql->fetchAll(PDO::FETCH_ASSOC);

            $sql = $pdo->prepare("SELECT * FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
            $sql->execute(array($email));
            $aluno = $sql->fetchAll(PDO::FETCH_ASSOC);

            if ($coordenador == NULL && $aluno == NULL){
                $sql = $pdo->prepare ("INSERT INTO coordenador VALUES (?,?,?,?,?)");
                $sql->execute(array($email,$CC,$CI,$nome_coord,$area));
                $sql = $pdo->prepare ("INSERT INTO login (senha, email_institucional_coordenador) VALUES (?,?)");
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
    <title>Cadastro do Coordenador - B.A.P.</title>

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
                <fieldset class="container-fieldset">

                <?php if(isset($erro_geral)){
                    echo $erro_geral;
                } ?>

                    <div class="input-group">
                        <div>
                            <div><p>Nome Completo</p></div>
                            <div><input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';} ?>type="text" name="nome" placeholder="Digite seu nome aqui!" <?php if(isset($_POST['nome'])){echo "value'".$_POST['nome']."'";} ?>required /></div>
                            <?php if(isset($erro_nome)) { 
                            echo "<div>".$erro_nome."</div>";
                            } ?>
                            
                            <div><p>Código da Instituição</p></div>
                            <div><input <?php if(isset($erro_geral) or isset($erro_ci) or isset($erro_ci2)){echo 'class="erro-input"';} ?>type="text" name="CI" placeholder="Digite o código da sua instituição aqui!" <?php if(isset($_POST['CI'])){echo "value'".$_POST['CI']."'";} ?>required/></div>
                            <?php if(isset($erro_ci)) { 
                            echo "<div>".$erro_ci."</div>";
                            } ?>
                            <?php if(isset($erro_ci2)) { 
                            echo "<div>".$erro_ci2."</div>";
                            } ?>

                            <div><p>EMAIL Institucional</p></div>
                            <div><input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input"';} ?>type="email" name="email" placeholder="Digite seu EMAIL aqui!" <?php if(isset($_POST['email'])){echo "value'".$_POST['email']."'";} ?>required/></div>
                            <?php if(isset($erro_email)) { 
                            echo "<div>".$erro_email."</div>";
                            } ?>
                        </div>
                        <div>
                            <div><p>Código do Coordenador</p></div>
                            <div><input <?php if(isset($erro_geral) or isset($erro_cc)){echo 'class="erro-input"';} ?>type="text" name="CC" placeholder="Digite seu código aqui!" <?php if(isset($_POST['CC'])){echo "value'".$_POST['CC']."'";} ?>required/></div>
                            <?php if(isset($erro_cc)) { 
                            echo "<div>".$erro_cc."</div>";
                            } ?>

                            <div><p>Selecione sua área</p></div>
                            <div><select <?php if(isset($erro_geral) or isset($erro_area)){echo 'class="erro-input"';} ?>name="area" class="select-cad">
                                <option value="NULL" disabled selected>Selecione sua área</option>
                                <option>Desenvolvimento de Sistemas</option>
                                <option>Administração</option>
                                <option>Mecânica</option>
                            </select></div>
                            <?php if(isset($erro_area)) { 
                            echo "<div>".$erro_area."</div>";
                            } ?>
                            
                            <div><p>Senha</p></div>
                            <div><input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';} ?>type="password" name="senha" placeholder="Insira uma senha"  <?php if(isset($_POST['senha'])){echo "value'".$_POST['senha']."'";} ?>required/></div>
                            <?php if(isset($erro_senha)) { 
                            echo "<div>".$erro_senha."</div>";
                            } ?>
                        </div>
                    </div>
                    <div>
                        <input type="submit" name="cadastrar" value="Cadastrar" class="button" />
                        <p>Já possui uma conta?<a href=""> Clique aqui!</a></p>
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