<?php
include 'database.php';
include 'usuario.php';

$usuarioObj = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action == 'inserir') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        if ($usuarioObj->create($nome, $email)) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o usuário.";
        }
    } elseif ($action == 'remover') {
        $id_usuario = $_POST['id_usuario'];
        if ($usuarioObj->delete($id_usuario)) {
            echo "Usuário removido com sucesso!";
        } else {
            echo "Erro ao remover o usuário.";
        }
    } elseif ($action == 'toggle_status') {
        $id_usuario = $_POST['id_usuario'];
        if ($usuarioObj->toggleStatus($id_usuario)) {
            echo "Status do usuário alterado com sucesso!";
        } else {
            echo "Erro ao alterar o status do usuário.";
        }
    } elseif ($action == 'editar') {
        $id_usuario = $_POST['id_usuario'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        if ($usuarioObj->update($id_usuario, ['nome' => $nome, 'email' => $email])) {
            echo "Usuário editado com sucesso!";
        } else {
            echo "Erro ao editar o usuário.";
        }
    }
}
