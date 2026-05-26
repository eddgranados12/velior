<?php
// ============================================================
//  PENDING.PHP — Pago en proceso (OXXO, transferencia, etc.)
// ============================================================

require_once(__DIR__ . "/../admin/config.php");

$idPedido = (int) ($_GET['external_reference'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Pendiente — Velior</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fffbeb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 48px 40px;
            text-align: center;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        }

        .icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        h1 {
            color: #d97706;
            font-size: 28px;
            margin-bottom: 8px;
        }

        p {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .info {
            background: #fffbeb;
            border-radius: 10px;
            padding: 16px;
            margin: 24px 0;
        }

        .btn {
            display: inline-block;
            background: #d97706;
            color: #fff;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 8px;
        }

        .btn:hover {
            background: #b45309;
        }

        .nota {
            font-size: 13px;
            color: #888;
            margin-top: 12px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon">⏳</div>
        <h1>Pago en proceso</h1>
        <p>Tu pago está siendo verificado. Esto puede tardar unos minutos.</p>

        <div class="info">
            <?php if ($idPedido > 0): ?>
                <p>📦 Pedido #<?= $idPedido ?></p>
            <?php endif; ?>
            <p>Te notificaremos cuando tu pago sea confirmado.<br>
                Puedes revisar el estado en <strong>Mis pedidos</strong>.</p>
        </div>

        <p class="nota">Si pagaste con OXXO o transferencia, puede tomar hasta 24–72 horas.</p>

        <a href="/velior/index.php" class="btn">Ir al inicio</a>
    </div>
</body>

</html>