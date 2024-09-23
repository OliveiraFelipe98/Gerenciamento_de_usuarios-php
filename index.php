<?php
include 'database.php';
include 'usuario.php';

// Criar instância da classe Usuario
$usuarioObj = new Usuario($pdo);

// Recuperar todos os usuários
$usuarios = $usuarioObj->all();

if ($usuarios === null) {
    echo "Erro ao recuperar usuários.";
    $usuarios = []; // Inicializa como um array vazio para evitar o erro no foreach
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Gerenciamento de Usuários</h2>

        <div class="d-flex justify-content-center mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Cadastrar Usuário</button>
        </div>

        <!-- Tabela de usuários -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= $user['id_usuario'] ?></td>
                        <td><?= $user['nome'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['status'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm editBtn" data-id_usuario="<?= $user['id_usuario'] ?>" data-nome="<?= $user['nome'] ?>" data-email="<?= $user['email'] ?>">Editar</button>
                            <button class="btn btn-danger btn-sm removeBtn" data-id_usuario="<?= $user['id_usuario'] ?>">Remover</button>
                            <button class="btn btn-secondary btn-sm toggleStatusBtn" data-id_usuario="<?= $user['id_usuario'] ?>">Ativar/Inativar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para cadastrar usuário -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Cadastrar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar usuário -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="edit_id_usuario" name="id_usuario">
                        <div class="mb-3">
                            <label for="edit_nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="edit_nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Função para enviar dados de inserção de usuário via AJAX
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault(); // Evita o envio padrão do formulário

                var nome = $('#nome').val();
                var email = $('#email').val();

                $.ajax({
                    url: 'process.php',
                    method: 'POST',
                    data: { action: 'inserir', nome: nome, email: email },
                    success: function(response) {
                        alert(response); // Exibe a resposta do servidor
                        location.reload(); // Atualiza a página para refletir as mudanças
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Loga o erro no console
                        alert("Ocorreu um erro ao cadastrar o usuário.");
                    }
                });
            });

            // Função para remover usuário via AJAX
            $('.removeBtn').on('click', function() {
                var id_usuario = $(this).data('id_usuario');

                if (confirm("Deseja realmente remover este usuário?")) {
                    $.ajax({
                        url: 'process.php',
                        method: 'POST',
                        data: { action: 'remover', id_usuario: id_usuario },
                        success: function(response) {
                            alert(response);
                            location.reload(); // Atualiza a página para refletir as mudanças
                        }
                    });
                }
            });

            // Função para ativar/inativar usuário via AJAX
            $('.toggleStatusBtn').on('click', function() {
                var id_usuario = $(this).data('id_usuario');

                $.ajax({
                    url: 'process.php',
                    method: 'POST',
                    data: { action: 'toggle_status', id_usuario: id_usuario },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Atualiza a página para refletir as mudanças
                    }
                });
            });

            // Função para abrir o modal de edição e preencher os campos
            $('.editBtn').on('click', function() {
                var id_usuario = $(this).data('id_usuario');
                var nome = $(this).data('nome');
                var email = $(this).data('email');

                $('#edit_id_usuario').val(id_usuario);
                $('#edit_nome').val(nome);
                $('#edit_email').val(email);

                $('#editUserModal').modal('show'); // Abre o modal de edição
            });

            // Função para enviar os dados de edição via AJAX
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                var id_usuario = $('#edit_id_usuario').val();
                var nome = $('#edit_nome').val();
                var email = $('#edit_email').val();

                $.ajax({
                    url: 'process.php',
                    method: 'POST',
                    data: { action: 'editar', id_usuario: id_usuario, nome: nome, email: email },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Atualiza a página para refletir as mudanças
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert("Ocorreu um erro ao editar o usuário.");
                    }
                });
            });
        });
    </script>
</body>
</html>
