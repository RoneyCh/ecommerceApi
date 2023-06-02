<?php 

function verificaAcesso() {
    session_start();

    if(!isset($_SESSION['user'])) {
       echo "Usuário não foi logado";
       exit;
    }
}