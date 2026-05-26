<?php
// ============================================================
//  FAILURE.PHP — Pago rechazado o cancelado
// ============================================================

require_once(__DIR__ . "/../admin/config.php");

$idPedido = (int) ($_GET['external_reference'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Fallido — Velior</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fef2f2;
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
            color: #dc2626;
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
            background: #fef2f2;
            border-radius: 10px;
            padding: 16px;
            margin: 24px 0;
            text-align: left;
        }

        .info li {
            color: #555;
            margin: 6px 0 6px 20px;
            font-size: 14px;
        }

        .btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .btn {
            display: inline-block;
            background: #dc2626;
            color: #fff;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn:hover {
            background: #b91c1c;
        }

        .btn.sec {
            background: #fff;
            color: #dc2626;
            border: 2px solid #dc2626;
        }

        .btn.sec:hover {
            background: #fef2f2;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon">❌</div>
        <h1>Pago no completado</h1>
        <p>Tu pago fue rechazado o cancelado.<br>No se realizó ningún cargo.</p>

        <div class="info">
            <p style="font-weight:600;color:#333;margin-bottom:8px;">Posibles causas:</p>
            <ul>
                <li>Fondos insuficientes en la tarjeta</li>
                <li>Datos de tarjeta incorrectos</li>
                <li>La tarjeta no permite compras en línea</li>
                <li>Pago cancelado por el usuario</li>
            </ul>
        </div>

        <div class="btns">
            <?php if ($idPedido > 0): ?>
                <a href="/velior/pagos/crear_preferencia.php?id_pedido=<?= $idPedido ?>" class="btn">
                    Intentar de nuevo
                </a>
            <?php endif; ?>
            <a href="/velior/index.php" class="btn sec">Ir al inicio</a>
        </div>
    </div>
</body>

</html>