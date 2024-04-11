<?php

  session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/estilo.css" rel="stylesheet" type="text/css" />
    <title>Coordenador - B.A.P.</title>
  </head>
  <body>
    <script src="js/main.js">  </script><!--Script principal-->
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
      <!--fecha sup-es-->

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
        <!--fecha header-->

        <div class="projeto_coord" id="tela_projeto">
          <div class="projeto">
            <div class="informacoes-projeto">
              <h2>Título Projeto</h2>
              <div class="integrantes">
                <p>Nome_Integrante 1:</p>
                <p>Nome_Integrante 2:</p>
                <p>Nome_Integrante 3:</p>
              </div>
              <div class="tags">
                <input type="radio" name="tag1" value="tag1" checked />
                <label for="tag1">Tag1</label>
                <input type="radio" name="tag2" value="tag2" checked />
                <label for="tag2">Tag2</label>
                <input type="radio" name="tag3" value="tag3" checked />
                <label for="tag3">Tag3</label>
              </div>
              <div class="sintese">
                <h2>Síntese</h2>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc
                  nunc lorem, bibendum quis erat maximus, tincidunt sagittis
                  sem. Proin facilisis pretium feugiat. Pellentesque habitant
                  morbi tristique senectus et netus et malesuada fames ac turpis
                  egestas. Mauris at ipsum vel justo volutpat dapibus sit amet
                  at augue. Cras non nisi posuere, malesuada dui ut, pharetra
                  erat. Duis pulvinar arcu eu sollicitudin pulvinar.
                </p>
              </div>
              <div class="btn_proj_coord">
                  <button type="submit">Aprovar</button>
                  <button type="submit">Reprovar</button>
              </div>
            </div>
            <div class="close-btn" onclick="fecharProjeto()">X</div>
          </div>
        </div>
        <!--Fecha overlay projeto-->

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
              <p><?php echo $_SESSION['nome_coordenador']; ?></p>
            </div>
            <div class="info">
              <div class="info-box-cabecalho">Email</div>
              <p><?php echo $_SESSION['email_coordenador']; ?></p>
            </div>
            <div class="info">
              <div class="info-box-cabecalho">Instituição</div>
              <p><?php echo $_SESSION['nome_instituicao']; ?></p>
            </div>
            <div class="close-btn" onclick="fecharInfoUsuario()">X</div>
          </div>
        </div>
        <!--tela de informações do usuário-->

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
          <div class="conte">Projetos para Aprovar</div>
          <div class="container-documentos">
            <div class="docu" onclick="abrirProjeto()"></div>
            <div class="docu"></div>
            <div class="docu"></div>
            <div class="docu"></div>
            <div class="docu"></div>
            <div class="docu"></div>
          </div>
        </div>
        <div class="conteudo-coord">
          <div class="conte">Alunos para Aprovar</div>
          <div class="container-documentos">
            <div class="icon-perfil" onclick="abrirInfoAluno()">
              <img
                class="iconic-perfil"
                width="130px"
                height="130px"
                src="icones/user.png"
              />
              <p>Nome_Aluno</p>
              <p>RM:</p>
              <p>Curso:</p>
            </div>
            <div class="icon-perfil">
              <img
                class="iconic-perfil"
                width="130px"
                height="130px"
                src="icones/user.png"
              />
              <p>Nome_Aluno</p>
              <p>RM:</p>
              <p>Curso:</p>
            </div>
            <div class="icon-perfil">
              <img
                class="iconic-perfil"
                width="130px"
                height="130px"
                src="icones/user.png"
              />
              <p>Nome_Aluno</p>
              <p>RM:</p>
              <p>Curso:</p>
            </div>
            <div class="icon-perfil">
              <img
                class="iconic-perfil"
                width="130px"
                height="130px"
                src="icones/user.png"
              />
              <p>Nome_Aluno</p>
              <p>RM:</p>
              <p>Curso:</p>
            </div>
            <div class="icon-perfil">
              <img
                class="iconic-perfil"
                width="130px"
                height="130px"
                src="icones/user.png"
              />
              <p>Nome_Aluno</p>
              <p>RM:</p>
              <p>Curso:</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
