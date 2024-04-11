<?php

  require('config/conexao.php');

  session_start();

  $query_arquivos = "SELECT codigo_projeto, projeto_link FROM projeto ORDER BY codigo_projeto DESC";
  $result_arquivos = $pdo->prepare($query_arquivos);
  $result_arquivos->execute();

  if (isset($_POST['ano']) && !empty($_POST['ano']) && isset($_POST['CI']) && !empty($_POST['CI'])){
    
    $ano_produc = limparPost($_POST['ano']);
    $CI = limparPost($_POST['CI']);

    if (isset($_POST['curso']) && !empty($_POST['curso'])){
      $curso = limparPost($_POST['curso']);
    }

    if (isset($_POST['categoria']) && !empty($_POST['categoria'])){
      $cat = limparPost($_POST['categoria']);
    }

    if(!isset($_POST['curso'])) {
      $erro_curso = "Selecione um curso!";
    }

    if(!isset($_POST['categoria'])) {
      $erro_cat = "Selecione uma categoria!";
    }

    if($ano_produc < 2000 && $ano_produc > 3024){
      $erro_ano = "Ano inválido, por favor selecione outro ano!";
    }

    if (strlen($CI) !== 3){
        $erro_ci2 = "Número inválido de caracteres!";
    }

    $sql = $pdo->prepare("SELECT id_instituicao FROM instituicao WHERE id_instituicao=? LIMIT 1");
    $sql->execute(array($CI));
    $info_CI = $sql->fetch(PDO::FETCH_ASSOC);

    if($info_CI != false){
      extract($info_CI);

      if($id_instituicao != $CI){
          $erro_ci = "Instituição não cadastrada! Verifique o código de sua instituição.";
      }
  }else{
      $erro_ci = "Instituição não cadastrada! Verifique o código de sua instituição.";
  }

    if (!isset($erro_cat) && !isset($erro_curso) && !isset($erro_ci) && !isset($erro_ci2) && !isset($erro_ano)){

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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/estilo.css" rel="stylesheet" type="text/css" />
    <title>Aluno - B.A.P.</title>
  </head>
  <body>
    <script src="js/main.js"> </script><!--Script principal-->
    
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
        </div>
        <div>
          <div class="icon-gif">
            <a href="inserir.php">
              <img
                class="iconic"
                width="90px"
                height="90px"
                src="icones/minha-lista com cor.png"
                onmouseover="this.src='icones/minha-lista sem fundo.gif';"
                onmouseout="this.src='icones/minha-lista com cor.png';"
              />
              <p class="letra-sup-es">UPLOAD</p>
            </a>
          </div>
        </div>
        <div>
          <div class="icon-gif">
            <a href="meu_projeto.php">
              <img
                class="iconic"
                src="icones/Meu bap.png"
                onmouseover="this.src='icones/Meu bap sem fundo.gif';"
                onmouseout="this.src='icones/Meu bap.png';"
              />
              <p class="letra-sup-es">MEUS PROJETOS</p>
            </a>
          </div>
        </div>
        <div>
          <div class="config">
            <img width="20px" height="20px" src="icones/configuracoes.png" />
            <p class="letra-sup-es1">Configurações</p>
          </div>
        </div>
      </div>
      <!--fecha sup-es-->

      <div class="container-body">
        <header class="search-bar">
          <div class="libarra">
            <div class="container-barra-pesquisa">
              <div class="">
                <form action="" class="barra-pesquisa-form">
                  <input
                    autocomplete="off"
                    class="input1"
                    type="text"
                    name="pesquisa-text"
                    placeholder="Pesquise aqui algum TCC em específico"
                  />
                  <p onclick="abrirFiltro()">
                    <img width="30px" height="30px" src="icones/filtro.png" />
                  </p>
                  <button type="submit">
                    <img width="30px" height="30px" src="icones/procurar.png" />
                  </button>
                </form>
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

        <div class="filtro_projeto" id="filtro">
          <div class="filtro">
            <h2>Filtro</h2>
            <form action="" class="form-filtro" method="POST">
              <fieldset class="container-fieldset">
                <div class="input-group">
                  <div>
                    <div><p>Ano de Produção</p></div>
                    <div><input <?php if(isset($erro_ano)){echo 'class="erro-input"';} ?> type="number" name="ano"  placeholder="Digite aqui o ano de produção" required/></div>
                    <?php if(isset($erro_ano)) { 
                      echo "<div>".$erro_ano."</div>";
                    } ?>

                    <div><p>Curso</p></div>
                    <div><select name="curso" class="select-cad" <?php if(isset($erro_curso) or isset($erro_curso2)){echo 'class="erro-input"';} ?> >
                            <option disabled selected>Selecione seu curso</option>
                            <option>Desenvolvimento de Sistemas</option>
                            <option>Administração</option>
                            <option>Mecânica</option>
                      </select>
                    </div>
                    <?php if(isset($erro_curso)) { 
                      echo "<div>".$erro_curso."</div>";
                    } ?>
                  </div>
                  <div>
                    <div><p>Código da Instituição</p></div>
                    <div>
                      <input <?php if(isset($erro_ci) or isset($erro_ci2)){echo 'class="erro-input"';} ?> type="number" name="CI" placeholder="Digite aqui o código da instituição" required/>
                    </div>
                    <?php if(isset($erro_ci)) { 
                      echo "<div>".$erro_ci."</div>";
                    } ?>
                    <?php if(isset($erro_ci2)) { 
                    echo "<div>".$erro_ci2."</div>";
                    } ?>

                    <div><p>Categoria</p></div>
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

                  </div>
                </div>
              </fieldset>
              <div>
                <input type="submit" name="buscar" value="BUSCAR" class="button-inserir" />
              </div>
            </form>
            <div class="close-btn" onclick="fecharFiltro()">X</div>
          </div>
        </div>
        <!--Overlay filtro-->

        <div class="conteudo">
        <!--<div class="container-documentos">-->

        <?php
          if(!empty($filtro)){
        ?>
          <div class="conte">PROJETOS DA SUA BUSCA</div>
          <div class="container-documentos">

                <?php

                $filtro = $pdo->prepare("SELECT id_instituicao, codigo_projeto, projeto_link, titulo_projeto, ano_producao, curso, nomes_integrantes, resumo, nome_categoria FROM projeto WHERE ano_producao=? and id_instituicao=? and curso=? and nome_categoria=?");
                $filtro->execute(array($ano_produc, $CI, $curso, $cat));
                 

                if(($filtro) and ($filtro->rowCount() != 0)){
                  while($row_filtro = $filtro->fetch(PDO::FETCH_ASSOC)){
                  //var_dump($row_filtro);
                  extract($row_filtro);
                ?>
              <div class="box_projeto">
                <p><?php echo "<a href='$projeto_link' target='_blank'>$titulo_projeto</a>"; ?></p>
                <p><?php echo "$nomes_integrantes"; ?></p>
                <p>Instituição: <?php echo "$id_instituicao"; ?></p>
                <p>Ano: </b><?php echo "$ano_producao"; ?></p>
                <p>Curso: </b><?php echo "$curso"; ?></p>
                <p>Categoria: </b><?php echo "$nome_categoria"; ?></p>
                <p><?php echo "$resumo"; ?></p>
              </div>

                <?php 
                  }
                }else{
                  echo "lalalal";
                }
                  $_SESSION['projeto_link'] = $projeto_link;
                ?>
          </div>
        <?php
          }
        ?>

          <div class="conte">BIBLIOTECA</div>
          <div class="container-documentos">
          <?php
              $query_arquivos = "SELECT codigo_projeto, projeto_link, titulo_projeto, ano_producao, curso, nomes_integrantes, resumo, nome_categoria, id_instituicao FROM projeto ORDER BY codigo_projeto DESC";
              $result_arquivos = $pdo->prepare($query_arquivos);
              $result_arquivos->execute();

              if(($result_arquivos) and ($result_arquivos->rowCount() != 0)){
                while($row_arquivo = $result_arquivos->fetch(PDO::FETCH_ASSOC)){
                //var_dump($row_arquivo);
                extract($row_arquivo);
                //echo
                //echo "<a href='$projeto_link' target='_blank'>$projeto_link</a><br><p>";
              ?>

              <div class="box_projeto">
                <p><?php echo "<a href='$projeto_link' target='_blank'>$titulo_projeto</a>"; ?></p>
                <p><?php echo "$nomes_integrantes"; ?></p>
                <p><b>Instituição: </b><?php echo "$id_instituicao"; ?></p>
                <p><b>Ano: </b><?php echo "$ano_producao"; ?></p>
                <p><b>Curso: </b><?php echo "$curso"; ?></p>
                <p><b>Categoria: </b><?php echo "$nome_categoria"; ?></p>
                <p><b>Resumo: </b><?php echo "$resumo"; ?></p>
              </div>

              <?php 
                }
                }else{
                  echo "<p> Nenhum arquivo encontrado";
                }
                $_SESSION['projeto_link'] = $projeto_link;
              ?>
          
          

        </div>
      </div>
    </div>


  </body>
</html>
