<?php 

function verificaAcesso() {
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if(!isset($_SESSION['user'])) {
       echo "Usuário não foi logado";
       exit;
    }
}