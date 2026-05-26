<?php
// ============================================================
//  SUCCESS.PHP — El usuario regresa aquí después de pagar
//  IMPORTANTE: No actualices la BD aquí, el webhook ya lo hizo
// ============================================================

require_once(__DIR__ . "/../admin/config.php");
require_once(__DIR__ . "/../admin/sistema.class.php");

$externalRef = $_GET['external_reference'] ?? null;
$paymentId = $_GET['payment_id'] ?? null;
$idPedido = (int) $externalRef;

$pedido = null;
if ($idPedido > 0) {
    $app = new Sistema();
    $db = $app->db();
    $stmt = $db->prepare("
        SELECT p.total, p.estado, p.estado_pago,
               t.payment_id, t.card_last_four, t.installments, t.payment_method_id
        FROM pedido p
        LEFT JOIN tranasaccion_pago t ON t.id_pedido = p.id_pedido
        WHERE p.id_pedido = :id_pedido
        ORDER BY t.fecha_creacion DESC LIMIT 1
    ");
    $stmt->execute([':id_pedido' => $idPedido]);
    $pedido = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso — Velior</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0fdf4;
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
            color: #16a34a;
            font-size: 28px;
            margin-bottom: 8px;
        }

        p {
            color: #555;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .info {
            background: #f0fdf4;
            border-radius: 10px;
            padding: 16px;
            margin: 24px 0;
            text-align: left;
        }

        .info p {
            margin: 6px 0;
            color: #333;
        }

        .info strong {
            color: #16a34a;
        }

        .btn {
            display: inline-block;
            background: #16a34a;
            color: #fff;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 8px;
        }

        .btn:hover {
            background: #15803d;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon">✅</div>
        <h1>¡Pago exitoso!</h1>
        <p>Tu pedido ha sido confirmado y está siendo procesado.</p>

        <?php if ($pedido): ?>
            <div class="info">
                <p>📦 <strong>Pedido #<?= $idPedido ?></strong></p>
                <p>💰 Total: <strong>$<?= number_format($pedido['total'], 2) ?>     <?= MONEDA ?></strong></p>
                <?php if (!empty($pedido['payment_id'])): ?>
                    <p>🔑 Pago ID: <?= htmlspecialchars($pedido['payment_id']) ?></p>
                <?php endif; ?>
                <?php if (!empty($pedido['card_last_four'])): ?>
                    <p>🃏 Tarjeta terminada en: <strong><?= $pedido['card_last_four'] ?></strong></p>
                <?php endif; ?>
                <?php if (!empty($pedido['installments']) && $pedido['installments'] > 1): ?>
                    <p>📅 Pagado en <?= $pedido['installments'] ?> mensualidades</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="/velior/index.php" class="btn">Ir al inicio</a>
    </div>
</body>

</html>