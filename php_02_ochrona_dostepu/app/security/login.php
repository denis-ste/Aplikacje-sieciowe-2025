<?php
require_once dirname(__FILE__).'/../../config.php';

function getParamsLogin(&$form){
    $form['login'] = $_REQUEST['login'] ?? null;
    $form['pass']  = $_REQUEST['pass']  ?? null;
}

function validateLogin(&$form,&$messages){
    if (!isset($form['login'], $form['pass'])) return false;

    if ($form['login'] === '') $messages[] = 'Nie podano loginu';
    if ($form['pass']  === '') $messages[] = 'Nie podano hasła';
    if ($messages) return false;

    if ($form['login'] === 'manager' && $form['pass'] === 'manager') {
        session_start(); $_SESSION['role'] = 'manager'; $_SESSION['login']='manager'; return true;
    }
    if ($form['login'] === 'user' && $form['pass'] === 'user') {
        session_start(); $_SESSION['role'] = 'user'; $_SESSION['login']='user'; return true;
    }

    $messages[] = 'Niepoprawny login lub hasło';
    return false;
}

$form = []; $messages = [];
getParamsLogin($form);

if (!validateLogin($form,$messages)) {
    include _ROOT_PATH.'/app/security/login_view.php';
} else {
    header("Location: "._APP_URL."/app/menu.php");
    exit();
}
