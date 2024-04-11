<?php

session_start();

require('config/conexao.php');

if (isset($_POST['nome']) && isset($_POST['RA']) && isset($_POST['email']) && isset($_POST['titulo']) && isset($_POST['ano']) && isset($_POST['CI']) && isset($_POST['resumo'])){
    if (empty($_POST['nome']) or empty($_POST['RA'])  or empty($_POST['email']) or empty($_POST['titulo']) or empty($_POST['ano']) or empty($_POST['CI']) or empty($_POST['resumo'])){
        $erro_geral = "Todos os campos são obrigatórios!";
    }else{
        $nomes_alunos = limparPost($_POST['nome']);
        $id_ra = limparPost($_POST['RA']);
        $email = limparPost($_POST['email']);
        $titulo = limparPost($_POST['titulo']);
        $ano_produc = limparPost($_POST['ano']);
        $CI = limparPost($_POST['CI']);
        $resumo = limparPost($_POST['resumo']);


        if (isset($_POST['curso']) && !empty($_POST['curso']) && isset($_POST['categoria']) && !empty($_POST['categoria'])){
            $id_curso = limparPost($_POST['curso']);
            $cat = limparPost($_POST['categoria']);
        }

        //filtro do EMAIL pra ver se bate na tabela
        $sql = $pdo->prepare("SELECT email_institucional_aluno FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
        $sql->execute(array($email));
        $info_email = $sql->fetch(PDO::FETCH_ASSOC);

        if($info_email == NULL){
            $erro_email2 = "O email não condiz com seu cadastro! Verefique seu email.";
        }else{
        
            //filtro do ci pra ver se bate na tabela
            $sql = $pdo->prepare("SELECT id_instituicao FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
            $sql->execute(array($email));
            $info_CI = $sql->fetch(PDO::FETCH_ASSOC);
            extract($info_CI);

            if($id_instituicao != $CI){
                $erro_ci = "Instituição não cadastrada! Verifique o código de sua instituição.";
            }else{
                //filtro do nome pra ver se bate na tabela
                $sql = $pdo->prepare("SELECT nome_aluno FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
                $sql->execute(array($email));
                $info_nome = $sql->fetch(PDO::FETCH_ASSOC);
                extract($info_nome);

                if($nome_aluno != $nomes_alunos){
                    $erro_nome2 = "O nome não condiz com seu cadastro! Verefique seu nome.";
                }else{
                    //filtro do ra pra ver se bate na tabela
                    $sql = $pdo->prepare("SELECT RA FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
                    $sql->execute(array($email));
                    $info_ra = $sql->fetch(PDO::FETCH_ASSOC);
                    extract($info_ra);

                    if($RA != $id_ra){
                        $erro_ra2 = "O RA não condiz com seu cadastro! Verifique seu RA.";
                    }else{
                        //filtro do CURSO pra ver se bate na tabela
                        $sql = $pdo->prepare("SELECT curso FROM aluno WHERE email_institucional_aluno=? LIMIT 1");
                        $sql->execute(array($email));
                        $info_curso = $sql->fetch(PDO::FETCH_ASSOC);
                        extract($info_curso);

                        if($curso != $id_curso){
                            $erro_curso2 = "O curso não condiz com seu cadastro! Verefique seu curso.";
                        }
                    }
                }
            } 
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

        //filtro erro pra categoria
        if(!isset($_POST['categoria'])) {
            $erro_cat = "Selecione uma categoria!";
        }
        
        //filtro pro nome
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/", $nomes_alunos)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        //filtro pro título
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/", $titulo)) {
            $erro_tit = "Somente permitido letras e espaços em branco!";
        }

        //filtro pro ra
        if (strlen($id_ra) !== 14){
            $erro_ra = "Número inválido de caracteres!";
        }

        //filtro pro ano
        if($ano_produc < 2000 && $ano_produc > 3024){
            $erro_ano = "Ano inválido, por favor selecione outro ano!";
        }

        //filtro pro numero de caracteres do CI
        if (strlen($CI) !== 3){
            $erro_ci2 = "Número inválido de caracteres!";
        }

        //filtro pro email
        if(!filter_var($filtro == '@etec.sp.gov.br')){
            $erro_email = "Formato de email inválido!";
        }

        if(isset($_FILES['arquivo'])){
            $arquivo = $_FILES['arquivo'];
            print_r($_FILES);
            //var_dump(extract($arquivo));

            if($arquivo['size'] < 1073741824){
                //var_dump($arquivo['size']);    
            }else{
                echo "Arquivo grande demais!";
            }
                
                /*if($arquivo['error']){
                    $erro_falha = "Falha ao enviar arquivo";
                }*/
    
                $pasta = "uploads/";
                $nome_arquivo = $arquivo['name'];
                $name_id_arquivo = uniqid();
                $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    
                if($extensao != "pdf"){
                    $erro_invalido = "Tipo de arquivo inválido";
                }
    
                //$caminho = $pasta . $nome_arquivo . "." . $extensao;
    
                $caminho = $pasta . $nome_arquivo . "." . $extensao;
    
                $enviado = move_uploaded_file($arquivo["tmp_name"], $caminho);
    
            if (!isset($erro_geral) && !isset($erro_nome) && !isset($erro_ra) && !isset($erro_email) && !isset($erro_cat) && !isset($erro_curso) && !isset($erro_ci) && !isset($erro_ci2) && !isset($erro_nome2) && !isset($erro_ra2) && !isset($erro_email2) && !isset($erro_curso2) && !isset($erro_tit) && !isset($erro_ano) /*&& !isset($erro_falha)*/ && !isset($erro_invalido)){
    
                if($enviado){
                    //echo "<p>Arquivo enviado com sucesso! <a href=uploads/tecmobile.pdf.pdf.pdf> Clique aqui </a>";
                    $sql = $pdo->prepare ("INSERT INTO projeto (nomes_integrantes, curso, titulo_projeto, ano_producao, projeto_link, arquivo_pdf, email_institucional_aluno, RA, nome_categoria, resumo, id_instituicao) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $sql->execute(array($nomes_alunos, $curso, $titulo, $ano_produc, $caminho, $caminho, $email, $RA, $cat, $resumo, $CI));
                        
                    header('location: home_aluno.php?result=ok');
    
                }else{
                    echo "falha ao enviar arquivo";
                }
            }

            $sql = $pdo->prepare("SELECT * FROM projeto WHERE projeto_link=?");
            $sql->execute(array($caminho));
            $projeto = $sql->fetch(PDO::FETCH_ASSOC);
        }
    }
}

            

    if (isset($_POST['ano1']) && !empty($_POST['ano1']) && isset($_POST['CI1']) && !empty($_POST['CI1'])){
    
        $ano_produc = limparPost($_POST['ano1']);
        $CI = limparPost($_POST['CI1']);
    
        if (isset($_POST['curso1']) && !empty($_POST['curso1'])){
          $curso = limparPost($_POST['curso1']);
        }
    
        if (isset($_POST['categoria1']) && !empty($_POST['categoria1'])){
          $cat = limparPost($_POST['categoria1']);
        }
    
        if(!isset($_POST['curso1'])) {
          $erro_curso3 = "Selecione um curso!";
        }
    
        if(!isset($_POST['categoria1'])) {
          $erro_cat3 = "Selecione uma categoria!";
        }
    
        if($ano_produc < 2000 && $ano_produc > 3024){
          $erro_ano = "Ano inválido, por favor selecione outro ano!";
        }
    
        if (strlen($CI) !== 3){
            $erro_ci3 = "Número inválido de caracteres!";
        }
    
        $sql = $pdo->prepare("SELECT id_instituicao FROM instituicao WHERE id_instituicao=? LIMIT 1");
        $sql->execute(array($CI));
        $info_CI = $sql->fetch(PDO::FETCH_ASSOC);
        //var_dump($info_CI);

        if($info_CI != false){
            extract($info_CI);
    
            if($id_instituicao != $CI){
                $erro_ci4 = "Instituição não cadastrada! Verifique o código de sua instituição.";
            }
        }else{
            $erro_ci4 = "Instituição não cadastrada! Verifique o código de sua instituição.";
        }
    
        if (!isset($erro_cat3) && !isset($erro_curso3) && !isset($erro_ci3) && !isset($erro_ci4) && !isset($erro_ano)){
    
          /*$ano_produc = limparPost($_POST['ano']);
          $CI = limparPost($_POST['CI']);
          $curso = limparPost($_POST['curso']);
          $cat = limparPost($_POST['categoria']);*/
    
          $sql = $pdo->prepare("SELECT projeto_link FROM projeto WHERE ano_producao=? and id_instituicao=? and curso=? and nome_categoria=?");
          $sql->execute(array($ano_produc, $CI, $curso, $cat));
          $filtro = $sql->fetchAll(PDO::FETCH_ASSOC);
          //var_dump($filtro);
    
        }else{
          //echo "FILTRO NÃO FUNFOU";
        }
      }else{
        //echo "LSLSLSL";
      }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet" type="text/css">
    <title>Inserir Projeto - B.A.P.</title>

</head>
<body>
    <script src="js/main.js"> </script>
    <div class="container-all">
        <div class="sup-es">
            <div>
                <br>
                <p>
                <div class="icon-perfil">
                    <img
                    class="iconic-perfil"
                    width="130px"
                    height="130px"
                    src="icones/user.png"
                    />
                    <p onclick="abrirInfoUsuario()" style="cursor: pointer">
                    <?php echo $_SESSION['nome_aluno']; ?>
                    </p>
                </div>

                <div class="informacoes_usuario" id="informacoes_usuario">
                <div class="informacoes">
                    <h2>Perfil</h2>
                    <div class="icon-perfil">
                    <img
                        class="iconic-perfil"
                        width="130px"
                        height="130px"
                        src="icones/user.png"
                    />
                    </div>
                    <div class="info">
                    <div class="info-box-cabecalho">Nome</div>
                    <p><?php echo $_SESSION['nome_aluno']; ?></p>
                    </div>
                    <div class="info">
                    <div class="info-box-cabecalho">Email</div>
                    <p><?php echo $_SESSION['email_aluno']; ?></p>
                    </div>
                    <div class="info">
                    <div class="info-box-cabecalho">Instituição</div>
                    <p><?php echo $_SESSION['nome_instituicao']; ?></p>
                    </div>
                    <div class="close-btn" onclick="fecharInfoUsuario()">X</div>
                </div>
                </div>
                
            </div>
            <div>
                <div class="icon-gif">
                    <a href="home_aluno.php">
                    <img class="iconic"  src="icones/casa.png" onmouseover="this.src='icones/casa.gif';" onmouseout="this.src='icones/casa.png';" />
                        <p class="letra-sup-es">
                            HOME
                        </p>
                    </a>
                </div>
            </div>
            <div>
                <div class="icon-gif">
                    <a href="meu_projeto.php">
                        <img class="iconic" width="90px" height="90px" src="icones/minha-lista com cor.png" onmouseover="this.src='icones/minha-lista sem fundo.gif';" onmouseout="this.src='icones/minha-lista com cor.png';" />
                        <p class="letra-sup-es">
                            MEUS PROJETOS
                        </p>
                    </a>
                </div>
            </div>
            <div>
                <div class="config">
                        <img width="20px" height="20px" src="icones/configuracoes.png">
                        <p class="letra-sup-es1">Configurações</p>
                </div>
            </div>
        </div>

                <div class="container-body">
                <header class="search-bar">
                    <div class="libarra">
                    <div class="container-barra-pesquisa">
                        <div class="">
                        
                        </div>
                        <div class="libarra">
                        <img class="icon1" src="img/logo.png" />
                        </div>
                    </div>
                    </div>
                    <div class="clear"></div>
                </header>
                <!--fecha header-->

                <div class="informacoes_usuario" id="informacoes_usuario">
                    <div class="informacoes">
                    <h2>Perfil</h2>
                    <div class="icon-perfil">
                        <img
                        class="iconic-perfil"
                        width="130px"
                        height="130px"
                        src="icones/user.png"
                        />
                    </div>
                    <div class="info">
                        <div class="info-box-cabecalho">Nome</div>
                        <p><?php echo $_SESSION['nome_aluno']; ?></p>
                    </div>
                    <div class="info">
                        <div class="info-box-cabecalho">Email</div>
                        <p><?php echo $_SESSION['email_aluno']; ?></p>
                    </div>
                    <div class="info">
                        <div class="info-box-cabecalho">Instituição</div>
                        <p><?php echo $_SESSION['nome_instituicao']; ?></p>
                    </div>
                    <div class="close-btn" onclick="fecharInfoUsuario()">X</div>
                    </div>
                </div>
                <!--tela de informações do usuário-->


            <div class="cadastro">
                <form class="form-inserir" method="post" enctype="multipart/form-data">
                    <fieldset class="container-fieldset">
                        <div class="input-group">
                            <div>
                                <div><p>Nome Completo</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_nome) or isset($erro_nome2)){echo 'class="erro-input"';} ?> type="text" name="nome" placeholder="Digite seu nome aqui!" <?php if(isset($_POST['nome'])){echo "value'".$_POST['nome']."'";} ?> required/></div>
                                <?php if(!empty($erro_nome)) { 
                                echo "<div>".$erro_nome."</div>";
                                } ?>
                                <?php if(!empty($erro_nome2)) { 
                                echo "<div>".$erro_nome2."</div>";
                                } ?>

                                <div><p>RA</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_ra) or isset($erro_ra2)){echo 'class="erro-input"';} ?> type="text" name="RA" placeholder="Digite seu RA aqui!" <?php if(isset($_POST['RA'])){echo "value'".$_POST['RA']."'";} ?> required/></div>
                                <?php if(isset($erro_ra)) { 
                                echo "<div>".$erro_ra."</div>";
                                } ?>
                                <?php if(isset($erro_ra2)) { 
                                echo "<div>".$erro_ra2."</div>";
                                } ?>

                                <div><p>EMAIL Institucional</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_email) or isset($erro_email2) or isset($erro_email_dupli)){echo 'class="erro-input"';} ?> type="email" name="email" placeholder="Digite seu EMAIL aqui!" <?php if(isset($_POST['email'])){echo "value'".$_POST['email']."'";} ?> required/></div>
                                <?php if(isset($erro_email)) { 
                                echo "<div>".$erro_email."</div>";
                                } ?>
                                <?php if(isset($erro_email2)) { 
                                echo "<div>".$erro_email2."</div>";
                                } ?>

                                <div><p>Selecione seu curso</p></div>
                                <div><select  name="curso" class="select-cad" <?php if(isset($erro_geral) or isset($erro_curso) or isset($erro_curso2)){echo 'class="erro-input"';} ?> >
                                        <option disabled selected>Selecione seu curso</option>
                                        <option>Desenvolvimento de Sistemas</option>
                                        <option>Administração</option>
                                        <option>Mecânica</option>
                                </select></div>
                                <?php if(isset($erro_curso)) { 
                                echo "<div>".$erro_curso."</div>";
                                } ?>
                                <?php if(isset($erro_curso2)) { 
                                echo "<div>".$erro_curso2."</div>";
                                } ?>
                            
                                <div><p>Código da Instituição</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_ci) or isset($erro_ci2)){echo 'class="erro-input"';} ?>type="text" name="CI" placeholder="Digite o código da sua instituição aqui!" <?php if(isset($_POST['CI'])){echo "value'".$_POST['CI']."'";} ?>required/></div>
                                <?php if(isset($erro_ci)) { 
                                echo "<div>".$erro_ci."</div>";
                                } ?>
                                <?php if(isset($erro_ci2)) { 
                                echo "<div>".$erro_ci2."</div>";
                                } ?>

                            </div>
                            <div>
                                <div><p>Título</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_tit)){echo 'class="erro-input"';} ?> type="text" name="titulo" placeholder="Digite o título do seu projeto aqui!" <?php if(isset($_POST['titulo'])){echo "value'".$_POST['titulo']."'";} ?> required/></div>
                                <?php if(isset($erro_tit)) { 
                                echo "<div>".$erro_tit."</div>";
                                } ?>

                                <div><p>Ano de produção</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_ano)){echo 'class="erro-input"';} ?> type="number" min="2000" max="3024" name="ano" placeholder="Digite o ano de produção do seu projeto aqui!" <?php if(isset($_POST['ano'])){echo "value'".$_POST['ano']."'";} ?> required/></div>
                                <?php if(isset($erro_ano)) { 
                                echo "<div>".$erro_ano."</div>";
                                } ?>

                                <div><p>Selecione sua categoria</p></div>
                                <div><select name="categoria" class="select-cad" <?php if(isset($erro_geral) or isset($erro_cat)){echo 'class="erro-input"';} ?>>
                                    <option disabled selected>Selecione sua categoria</option>
                                    <option>Site</option>
                                    <option>Jogo</option>
                                    <option>Sistema</option>
                                    <option>Aplicativo</option>
                                </select></div>
                                <?php if(isset($erro_cat)) { 
                                echo "<div>".$erro_cat."</div>";
                                } ?>

                                <div><p>Resumo do Projeto</p></div>
                                <div><input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';} ?> type="text" name="resumo" placeholder="Digite o resumo do seu projeto aqui!" <?php if(isset($_POST['resumo'])){echo "value'".$_POST['resumo']."'";} ?> required/></div>
                                <?php if(isset($erro_resumo)) { 
                                echo "<div>".$erro_resumo."</div>";
                                } ?>

                                <div>
                                    <input id='selecao-arquivo' type='file' name='arquivo' accept=".pdf"><p><label for="selecao-arquivo" class="label-inserir">Selecione o arquivo</labe</p></div>
                                        <!--<div>
                                            <input type="file" class="enviar" name="Projeto" accept=".pdf" required/>
                                        </div>-->
                                </div>
                            </div>
                            <div>
                                <div>
                                    <input type="submit" name="cadastrar" value="Enviar projeto" class="button-inserir" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>

                

            </div>
</body>
</html>