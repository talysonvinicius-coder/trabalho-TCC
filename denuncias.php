<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Denúncias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .denuncias-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .denuncia-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            border: 1px solid #404040;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .denuncia-card:hover {
            border-color: #1db954;
            box-shadow: 0 8px 24px rgba(29, 185, 84, 0.2);
        }

        .denuncia-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #404040;
        }

        .denuncia-id {
            font-size: 0.9rem;
            color: #b3b3b3;
            font-weight: 600;
        }

        .badge-motivo {
            background: #e22c38;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .comentario-denunciado {
            background: rgba(29, 185, 84, 0.1);
            border-left: 4px solid #1db954;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .usuario-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .usuario-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1db954, #1ed760);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .usuario-details {
            flex: 1;
        }

        .usuario-nome {
            font-weight: 600;
            color: #fff;
            display: block;
            margin-bottom: 4px;
        }

        .usuario-email {
            font-size: 0.85rem;
            color: #b3b3b3;
        }

        .conteudo-comentario {
            color: #e0e0e0;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .data-info {
            font-size: 0.8rem;
            color: #b3b3b3;
            margin-bottom: 15px;
        }

        .motivo-denuncia {
            background: rgba(226, 44, 56, 0.1);
            border-left: 4px solid #e22c38;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .motivo-titulo {
            font-weight: 600;
            color: #ff6b6b;
            margin-bottom: 5px;
        }

        .motivo-texto {
            color: #e0e0e0;
            font-size: 0.95rem;
        }

        .acoes-denuncia {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding-top: 15px;
            border-top: 1px solid #404040;
        }

        .btn-aceitar {
            background: #1db954;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 24px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-aceitar:hover {
            background: #1ed760;
            transform: translateY(-2px);
            color: white;
        }

        .btn-rejeitar {
            background: #e22c38;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 24px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-rejeitar:hover {
            background: #ff4d54;
            transform: translateY(-2px);
            color: white;
        }

        .sem-denuncias {
            text-align: center;
            padding: 60px 20px;
            color: #b3b3b3;
        }

        .sem-denuncias i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #1db954;
        }

        .sem-denuncias h3 {
            color: #fff;
            margin-bottom: 10px;
        }

        .filtro-denuncias {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn-filtro {
            background: transparent;
            border: 1px solid #404040;
            color: #b3b3b3;
            padding: 8px 16px;
            border-radius: 24px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-filtro:hover,
        .btn-filtro.ativo {
            background: #1db954;
            border-color: #1db954;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendente {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status-aceita {
            background: rgba(29, 185, 84, 0.2);
            color: #1db954;
        }

        .status-rejeitada {
            background: rgba(226, 44, 56, 0.2);
            color: #e22c38;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1db954;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            display: none;
        }

        .toast-notification.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-flag"></i>
                <h2 style="margin: 0; margin-left: 10px; font-size: 1.5rem;">Denúncias</h2>
            </div>
            <div class="user-controls">
                <span class="badge-upgrade"><i class="fas fa-shield-alt me-1"></i>Admin</span>
            </div>
        </header>

        <div class="denuncias-container">
            <div class="filtro-denuncias">
                <button class="btn-filtro ativo" onclick="filtrarDenuncias('pendentes')">
                    <i class="fas fa-hourglass-half me-2"></i>Pendentes
                </button>
                <button class="btn-filtro" onclick="filtrarDenuncias('aceitas')">
                    <i class="fas fa-check me-2"></i>Aceitas
                </button>
                <button class="btn-filtro" onclick="filtrarDenuncias('rejeitadas')">
                    <i class="fas fa-times me-2"></i>Rejeitadas
                </button>
                <button class="btn-filtro" onclick="filtrarDenuncias('todas')">
                    <i class="fas fa-list me-2"></i>Todas
                </button>
            </div>

            <div id="denuncias-list">
                <?php
                    require_once __DIR__ . '/model/dao/Conexao.php';
                    
                    try {
                        $pdo = Conexao::getConexao();
                        
                        // Buscar denúncias com status pendente
                        $query = "
                            SELECT 
                                d.id,
                                d.motivo,
                                d.status,
                                d.data_denuncia,
                                c.conteudo,
                                c.data_comentario,
                                u_comentario.id as usuario_comentario_id,
                                u_comentario.nome as usuario_comentario_nome,
                                l.email as usuario_email,
                                u_denuncia.nome as usuario_denuncia_nome
                            FROM denuncias d
                            INNER JOIN comentarios c ON d.comentario_id = c.id
                            INNER JOIN usuario u_comentario ON c.usuario_id = u_comentario.id
                            INNER JOIN login l ON u_comentario.id = l.usuario_id
                            INNER JOIN usuario u_denuncia ON d.usuario_id = u_denuncia.id
                            WHERE d.status = 'Pendente'
                            ORDER BY d.data_denuncia DESC
                        ";
                        
                        $result = $pdo->query($query);
                        $denuncias = $result->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($denuncias) > 0) {
                            foreach ($denuncias as $denuncia) {
                                $primeiraLetra = strtoupper(substr($denuncia['usuario_comentario_nome'], 0, 1));
                                $dataFormatada = date('d/m/Y H:i', strtotime($denuncia['data_denuncia']));
                                ?>
                                <div class="denuncia-card" data-status="<?php echo htmlspecialchars($denuncia['status']); ?>">
                                    <div class="denuncia-header">
                                        <div>
                                            <span class="denuncia-id">ID: #<?php echo $denuncia['id']; ?></span>
                                            <span class="status-badge status-pendente ms-3">
                                                <i class="fas fa-clock me-1"></i>Pendente
                                            </span>
                                        </div>
                                        <div class="badge-motivo">
                                            <i class="fas fa-flag me-1"></i><?php echo htmlspecialchars($denuncia['motivo'] ?? 'Sem motivo'); ?>
                                        </div>
                                    </div>

                                    <div class="comentario-denunciado">
                                        <div class="usuario-info">
                                            <div class="usuario-avatar"><?php echo $primeiraLetra; ?></div>
                                            <div class="usuario-details">
                                                <span class="usuario-nome"><?php echo htmlspecialchars($denuncia['usuario_comentario_nome']); ?></span>
                                                <span class="usuario-email"><?php echo htmlspecialchars($denuncia['usuario_email']); ?></span>
                                            </div>
                                        </div>
                                        <div class="conteudo-comentario">
                                            <?php echo htmlspecialchars($denuncia['conteudo']); ?>
                                        </div>
                                        <div class="data-info">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Comentado em: <?php echo date('d/m/Y H:i', strtotime($denuncia['data_comentario'])); ?>
                                        </div>
                                    </div>

                                    <div class="motivo-denuncia">
                                        <div class="motivo-titulo">
                                            <i class="fas fa-info-circle me-2"></i>Motivo da Denúncia
                                        </div>
                                        <div class="motivo-texto">
                                            <?php echo htmlspecialchars($denuncia['motivo'] ?? 'Sem detalhes'); ?>
                                        </div>
                                    </div>

                                    <div class="data-info">
                                        <i class="fas fa-user me-2"></i>
                                        Denunciado por: <strong><?php echo htmlspecialchars($denuncia['usuario_denuncia_nome'] ?? 'Anônimo'); ?></strong>
                                        <br>
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Data da denúncia: <strong><?php echo $dataFormatada; ?></strong>
                                    </div>

                                    <div class="acoes-denuncia">
                                        <button class="btn-rejeitar" onclick="rejeitarDenuncia(<?php echo $denuncia['id']; ?>)">
                                            <i class="fas fa-times"></i>Rejeitar
                                        </button>
                                        <button class="btn-aceitar" onclick="aceitarDenuncia(<?php echo $denuncia['id']; ?>, <?php echo $denuncia['usuario_comentario_id']; ?>)">
                                            <i class="fas fa-check"></i>Aceitar
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="sem-denuncias">
                                <i class="fas fa-check-circle"></i>
                                <h3>Nenhuma denúncia pendente</h3>
                                <p>Não há denúncias aguardando revisão no momento.</p>
                            </div>
                            <?php
                        }
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">Erro ao buscar denúncias: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                ?>
            </div>
        </div>
    </main>

    <div class="toast-notification" id="toast">
        <i class="fas fa-check-circle me-2"></i>
        <span id="toast-message">Ação realizada com sucesso</span>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        function aceitarDenuncia(denunciaId, usuarioComentarioId) {
            if (confirm('Tem certeza que deseja aceitar esta denúncia? O comentário será removido.')) {
                fetch('api/denuncias/aceitar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        denuncia_id: denunciaId,
                        usuario_comentario_id: usuarioComentarioId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        mostrarNotificacao('Denúncia aceita com sucesso!', 'sucesso');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarNotificacao('Erro ao aceitar denúncia: ' + data.mensagem, 'erro');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    mostrarNotificacao('Erro ao processar ação', 'erro');
                });
            }
        }

        function rejeitarDenuncia(denunciaId) {
            if (confirm('Tem certeza que deseja rejeitar esta denúncia?')) {
                fetch('api/denuncias/rejeitar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        denuncia_id: denunciaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        mostrarNotificacao('Denúncia rejeitada com sucesso!', 'sucesso');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarNotificacao('Erro ao rejeitar denúncia: ' + data.mensagem, 'erro');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    mostrarNotificacao('Erro ao processar ação', 'erro');
                });
            }
        }

        function filtrarDenuncias(filtro) {
            // Atualizar botões ativos
            document.querySelectorAll('.btn-filtro').forEach(btn => {
                btn.classList.remove('ativo');
            });
            event.target.closest('.btn-filtro').classList.add('ativo');

            // Aqui você pode adicionar lógica para filtrar
            // Por enquanto, vamos apenas recarregar com um parâmetro
            window.location.href = '?filtro=' + filtro;
        }

        function mostrarNotificacao(mensagem, tipo = 'sucesso') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = mensagem;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
