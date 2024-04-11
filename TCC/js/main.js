/*Funções para abrir overlay*/ 

function abrirProjeto() {
    document.getElementById("tela_projeto").style.display = "block"; /*Pega o elemento com a ID "tela_projeto" e muda no estilo (CSS) a característica display para "block" */
}

function fecharProjeto() {
    document.getElementById("tela_projeto").style.display = "none"; /*Pega o elemento com a ID "tela_projeto" e muda no estilo (CSS) a característica display para "none" */
}

function abrirInfoUsuario() {
    document.getElementById("informacoes_usuario").style.display = "block"; /*Pega o elemento com a ID "informacoes_usuario" e muda no estilo (CSS) a característica display para "block" */
}

function fecharInfoUsuario() {
  document.getElementById("informacoes_usuario").style.display = "none"; /*Pega o elemento com a ID "informacoes_usuario" e muda no estilo (CSS) a característica display para "none" */
}

function abrirInfoAluno() {
  document.getElementById("informacoes_aluno").style.display = "block"; /*Pega o elemento com a ID "informacoes_aluno" e muda no estilo (CSS) a característica display para "block" */
}

function fecharInfoAluno() {
  document.getElementById("informacoes_aluno").style.display = "none"; /*Pega o elemento com a ID "informacoes_aluno" e muda no estilo (CSS) a característica display para "none" */
}

function abrirFiltro(){
    document.getElementById("filtro").style.display = "block";
}

function fecharFiltro(){
    document.getElementById("filtro").style.display = "none";
}

  //Mudar o form login

const login_aluno = document.querySelector('.login_aluno');
const login_coord = document.querySelector('.login_coord');
const switchs = document.querySelectorAll('.switch');
const form = document.getElementById("form_login");

let current = 1;

function tab2(){
  form.style.marginLeft = "-100%";
  login_aluno.style.background = "none";
  login_coord.style.background = "#1B998B"
  switchs[current - 1].classList.add("active");
}

function tab1(){
  form.style.marginLeft = "0";
  login_coord.style.background = "none";
  login_aluno.style.background = "#1B998B"
  switchs[current - 1].classList.remove("active");
}

