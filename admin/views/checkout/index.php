<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Velior</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #faf9f7;
            color: #2c2c2c;
            min-height: 100vh;
        }

        .breadcrumb {
            padding: 20px 8%;
            font-size: 12px;
            letter-spacing: 1.5px;
            color: #999;
            text-transform: uppercase;
        }

        .breadcrumb a {
            color: #9c6644;
            text-decoration: none;
        }

        .breadcrumb span {
            margin: 0 8px;
        }

        /* ── PASOS ── */
        .pasos {
            display: flex;
            justify-content: center;
            gap: 0;
            margin: 20px auto 40px;
            max-width: 500px;
        }

        .paso {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #bbb;
            flex: 1;
            justify-content: center;
        }

        .paso.activo {
            color: #59452C;
        }

        .paso.activo .paso-num {
            background: #59452C;
            color: #F2E8DC;
        }

        .paso-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .paso-linea {
            flex: 1;
            height: 1px;
            background: #e0d9d0;
            max-width: 60px;
        }

        /* ── LAYOUT ── */
        .checkout-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 8% 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 48px;
            align-items: start;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0d9d0;
        }

        /* ── FORMULARIO ── */
        .form-grupo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 8px;
        }

        label .req {
            color: #9c6644;
        }

        input[type="text"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e0d9d0;
            border-radius: 2px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            background: #fff;
            color: #2c2c2c;
            transition: border-color 0.2s;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #9c6644;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── ALERTA ERROR ── */
        .alerta-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 14px 16px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* ── RESUMEN PEDIDO ── */
        .resumen {
            background: #fff;
            border: 1px solid #e0d9d0;
            border-radius: 4px;
            padding: 28px;
            position: sticky;
            top: 20px;
        }

        .resumen h2 {
            font-size: 18px;
        }

        .resumen-item {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f5f0eb;
        }

        .resumen-item:last-of-type {
            border-bottom: none;
        }

        .resumen-img {
            width: 56px;
            height: 68px;
            object-fit: cover;
            border-radius: 3px;
            background: #f0ebe4;
            flex-shrink: 0;
        }

        .resumen-img-placeholder {
            width: 56px;
            height: 68px;
            background: #f0ebe4;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-size: 18px;
            flex-shrink: 0;
        }

        .resumen-info {
            flex: 1;
        }

        .resumen-nombre {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .resumen-qty {
            font-size: 11px;
            color: #999;
            letter-spacing: 1px;
        }

        .resumen-precio {
            font-size: 14px;
            color: #59452C;
            font-weight: 500;
        }

        .resumen-totales {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0d9d0;
        }

        .total-fila {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }

        .total-fila.grande {
            font-size: 16px;
            font-weight: 600;
            color: #2c2c2c;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e0d9d0;
        }

        .total-fila .monto {
            color: #59452C;
        }

        /* ── BOTÓN ── */
        .btn-pagar {
            display: block;
            width: 100%;
            padding: 16px;
            background: #59452C;
            color: #F2E8DC;
            border: none;
            border-radius: 2px;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
            margin-top: 24px;
        }

        .btn-pagar:hover {
            background: #3d2e1a;
        }

        .btn-volver {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9c6644;
            text-decoration: none;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-volver:hover {
            color: #59452C;
        }

        .seguridad {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            font-size: 11px;
            color: #aaa;
            letter-spacing: 1px;
        }

        .seguridad i {
            color: #9c6644;
        }

        @media (max-width: 900px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .resumen {
                position: static;
                order: -1;
            }
        }

        @media (max-width: 500px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php require_once(__DIR__ . "/../../header.php"); ?>

    <div class="breadcrumb">
        <a href="/velior/index.php">Inicio</a>
        <span>›</span>
        <a href="/velior/admin/carrito.php">Carrito</a>
        <span>›</span>
        Checkout
    </div>

    <!-- PASOS -->
    <div class="pasos">
        <div class="paso">
            <div class="paso-num"><i class="fa-solid fa-check" style="font-size:11px"></i></div>
            Carrito
        </div>
        <div class="paso-linea"></div>
        <div class="paso activo">
            <div class="paso-num">2</div>
            Envío
        </div>
        <div class="paso-linea"></div>
        <div class="paso">
            <div class="paso-num">3</div>
            Pago
        </div>
    </div>

    <div class="checkout-container">

        <!-- FORMULARIO DE ENVÍO -->
        <div>
            <h2>Datos de envío</h2>

            <?php if (!empty($_SESSION['error_checkout'])): ?>
                <div class="alerta-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($_SESSION['error_checkout']) ?>
                </div>
                <?php unset($_SESSION['error_checkout']); ?>
            <?php endif; ?>

            <form action="/velior/admin/carrito.php?accion=confirmar" method="POST">

                <div class="form-grupo">
                    <label>Nombre completo <span class="req">*</span></label>
                    <input type="text" name="nombre_completo" value="<?= htmlspecialchars($_SESSION['nombre']) ?>"
                        placeholder="Tu nombre completo" required>
                </div>

                <div class="form-grupo">
                    <label>Dirección de envío <span class="req">*</span></label>
                    <input type="text" name="direccion_envio" placeholder="Calle, número exterior e interior, colonia"
                        required>
                </div>

                <div class="form-row">
                    <div class="form-grupo">
                        <label>Ciudad <span class="req">*</span></label>
                        <input type="text" name="ciudad" placeholder="Ciudad" required>
                    </div>
                    <div class="form-grupo">
                        <label>Estado <span class="req">*</span></label>
                        <input type="text" name="estado" placeholder="Estado" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-grupo">
                        <label>Código postal <span class="req">*</span></label>
                        <input type="text" name="cp" placeholder="00000" maxlength="5" pattern="[0-9]{5}" required>
                    </div>
                    <div class="form-grupo">
                        <label>Teléfono de contacto</label>
                        <input type="tel" name="telefono_contacto" placeholder="10 dígitos">
                    </div>
                </div>

                <div class="form-grupo">
                    <label>Notas adicionales</label>
                    <textarea name="notas" placeholder="Instrucciones especiales para la entrega (opcional)"></textarea>
                </div>

                <button type="submit" class="btn-pagar" id="btn-pagar">
                    <i class="fa-brands fa-mercado-pago" style="margin-right:8px"></i>
                    Continuar al pago
                </button>

                <a href="/velior/admin/carrito.php" class="btn-volver">
                    ← Volver al carrito
                </a>

            </form>
        </div>

        <!-- RESUMEN DEL PEDIDO -->
        <aside class="resumen">
            <h2>Tu pedido</h2>

            <?php foreach ($items as $item): ?>
                <div class="resumen-item">
                    <?php if (!empty($item['imagen_url'])): ?>
                        <img src="/velior/uploads/<?= htmlspecialchars($item['imagen_url']) ?>"
                            alt="<?= htmlspecialchars($item['nombre']) ?>" class="resumen-img">
                    <?php else: ?>
                        <div class="resumen-img-placeholder">
                            <i class="fa-regular fa-image"></i>
                        </div>
                    <?php endif; ?>

                    <div class="resumen-info">
                        <div class="resumen-nombre">
                            <?= htmlspecialchars($item['nombre']) ?>
                        </div>
                        <div class="resumen-qty">Cantidad:
                            <?= $item['cantidad'] ?>
                        </div>
                    </div>

                    <div class="resumen-precio">
                        $
                        <?= number_format($item['subtotal'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="resumen-totales">
                <div class="total-fila">
                    <span>Subtotal</span>
                    <span class="monto">$
                        <?= number_format($total, 2) ?>
                    </span>
                </div>
                <div class="total-fila">
                    <span>Envío</span>
                    <span>Por calcular</span>
                </div>
                <div class="total-fila grande">
                    <span>Total</span>
                    <span class="monto">$
                        <?= number_format($total, 2) ?> MXN
                    </span>
                </div>
            </div>

            <div class="seguridad">
                <i class="fa-solid fa-lock"></i>
                Transacción 100% segura
            </div>
        </aside>

    </div>

    <script>
        // Armar dirección completa al enviar el form
        document.querySelector('form').addEventListener('submit', function (e) {
            const direccion = this.querySelector('[name="direccion_envio"]').value.trim();
            const ciudad = this.querySelector('[name="ciudad"]').value.trim();
            const estado = this.querySelector('[name="estado"]').value.trim();
            const cp = this.querySelector('[name="cp"]').value.trim();

            // Combinar en el campo direccion_envio
            const completa = `${direccion}, ${ciudad}, ${estado}, C.P. ${cp}`;
            this.querySelector('[name="direccion_envio"]').value = completa;

            document.getElementById('btn-pagar').disabled = true;
            document.getElementById('btn-pagar').textContent = 'Procesando...';
        });
    </script>

</body>

</html>