<?php 
session_start();

if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
} else {
    echo "Usuário não está logado";
}
