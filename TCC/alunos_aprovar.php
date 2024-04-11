 <?php

session_start();

require('config/conexao.php');
require('config/Sql.php');

$query_alunos = "SELECT nome_aluno, RA, curso FROM aluno WHERE situacao IS NULL";
$result_alunos = $pdo->prepare($query_alunos);
$result_alunos->execute();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/estilo.css" rel="stylesheet" type="text/css" />
    <title>Alunos para Aprovar - B.A.P</title>
  </head>
  <body>
    
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
            <?php echo $_SESSION['nome_coordenador']; ?>
            </p>
          </div>
        </div>
        <div>
          <div class="icon-gif">
            <a href="google.com">
              <img
                class="iconic"
                src="icones/Meu bap.png"
                onmouseover="this.src='icones/Meu bap sem fundo.gif';"
                onmouseout="this.src='icones/Meu bap.png';"
              />
              <p class="letra-sup-es">PROJETOS PARA APROVAR</p>
            </a>
          </div>
        </div>
        <div>
          <div class="icon-gif">
            <a href="google.com">
              <img
                class="iconic"
                width="90px"
                height="90px"
                src="icones/alunos_aprovar.png"
                onmouseover="this.src='icones/alunos_aprovar_sem_fundo.gif';"
                onmouseout="this.src='icones/alunos_aprovar.png';"
              />
              <p class="letra-sup-es">ALUNOS PARA APROVAR</p>
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
      <div class="container-body">
        <header class="search-bar">
          <div class="libarra">
            <div class="container-barra-pesquisa">
              <div class="">
                <form action="" class="barra-pesquisa-form">
                  <input
                    class="input1"
                    type="text"
                    name="pesquisa-text"
                    placeholder="Pesquise aqui algum TCC em específico"
                  />
                  <button type="submit">
                    <img width="30px" height="30px" src="icones/filtro.png" />
                  </button>
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

        <div class="informacoes_usuario" id="informacoes_aluno">
          <div class="informacoes">
            <h2>Aluno</h2>
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
              <p></p>
            </div>
            <div class="info">
              <div class="info-box-cabecalho">Email</div>
              <p></p>
            </div>
            <div class="info">
              <div class="info-box-cabecalho">Instituição</div>
              <p></p>
            </div>
            <div class="btn_aluno_coord">
              <button type="submit">Aprovar</button>
              <button type="submit">Reprovar</button>
          </div>
            <div class="close-btn" onclick="fecharInfoAluno()">X</div>
          </div>
        </div>
        <!--tela informações do aluno para aprovar-->

        <div class="conteudo-coord">
          <div class="conte">Alunos para Aprovar</div>
          <div class="container-documentos">
            <?php
              if(($result_alunos) and ($result_alunos->rowCount() != 0)){
                while($row_alunos = $result_alunos->fetch(PDO::FETCH_ASSOC)){
                  extract($row_alunos);
            ?>
            <?php
              $idDiv = $RA;
            ?>
                <div id="<?php echo $idDiv?>" class="campo">
                  <p><b>Nome Aluno:</b> <?php echo "$nome_aluno"?></p>
                  <p><b>RA:</b> <?php echo "$RA" ?></p>
                  <p><b>Curso:</b> <?php echo "$curso" ?></p>
                  <button class="btn_aprovar">
                    <?php
                      echo "<a href='aprovar_aluno.php?RA=$RA'>Aprovar</a>";
                    ?>
                    </button>
                  <button class="btn_reprovar">
                    <?php
                      echo "<a href='reprovar_aluno.php?RA=$RA'>Reprovar</a>";
                    ?>
                  </button>
                </div>
              <?php
              }
              }else
              {
                echo "<p> Nenhum aluno para analisar";
                }
              ?>
          </div>
        </div>
      </div>
    </div>
    <script src="js/main.js">  </script><!--Script principal-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  </body>
</html>