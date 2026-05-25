<?php require_once(__DIR__ . "/../header.php"); ?>

<style>
    .cuenta-page {
        background: #f9f7f4;
        min-height: 100vh;
        padding: 50px 8%;
    }

    .cuenta-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        align-items: start;
    }

    .cuenta-card,
    .cuenta-content {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .cuenta-sidebar {
        padding: 30px 24px;
    }

    .cuenta-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #111;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .cuenta-nombre {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f1f1f;
        margin-bottom: 4px;
    }

    .cuenta-correo {
        font-size: 0.95rem;
        color: #777;
        margin-bottom: 24px;
    }

    .cuenta-menu {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cuenta-menu a {
        text-decoration: none;
        padding: 14px 16px;
        border-radius: 14px;
        color: #1f1f1f;
        background: #f7f7f7;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .cuenta-menu a:hover,
    .cuenta-menu a.activo {
        background: #111;
        color: white;
    }

    .cuenta-content {
        padding: 35px;
    }

    .cuenta-content h2 {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #1f1f1f;
    }

    .cuenta-content p.sub {
        color: #666;
        margin-bottom: 28px;
    }

    .alert-cuenta {
        padding: 14px 18px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success { background: #eaf7ee; color: #1f6b3a; }
    .alert-danger { background: #fdecec; color: #a12727; }

    @media (max-width: 992px) {
        .cuenta-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="cuenta-page">
    <div class="cuenta-wrapper">

        <!-- SIDEBAR -->
        <div class="cuenta-card">
            <div class="cuenta-sidebar">
                <div class="cuenta-avatar">
                    <?php echo strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)); ?>
                </div>

                <div class="cuenta-nombre">
                    <?php echo htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido_paterno'] ?? '')); ?>
                </div>

                <div class="cuenta-correo">
                    <?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>
                </div>

                <div class="cuenta-menu">
                    <a href="/velior/mi_cuenta.php?seccion=datos" class="<?php echo ($seccion === 'datos') ? 'activo' : ''; ?>">
                        Mis datos
                    </a>

                    <a href="/velior/mi_cuenta.php?seccion=pedidos" class="<?php echo ($seccion === 'pedidos') ? 'activo' : ''; ?>">
                        Mis pedidos
                    </a>

                    <a href="/velior/mi_cuenta.php?seccion=direcciones" class="<?php echo ($seccion === 'direcciones') ? 'activo' : ''; ?>">
                        Mis direcciones
                    </a>

                    <a href="/velior/mi_cuenta.php?seccion=favoritos" class="<?php echo ($seccion === 'favoritos') ? 'activo' : ''; ?>">
                        Favoritos
                    </a>

                    <a href="/velior/admin/login.php?accion=logout">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="cuenta-content">
            <?php if (isset($_SESSION['cuenta_alerta'])): ?>
                <div class="alert-cuenta alert-<?php echo $_SESSION['cuenta_alerta']['tipo']; ?>">
                    <?php echo htmlspecialchars($_SESSION['cuenta_alerta']['mensaje']); ?>
                </div>
                <?php unset($_SESSION['cuenta_alerta']); ?>
            <?php endif; ?>

            <?php
            switch ($seccion) {
                case 'pedidos':
                    require(__DIR__ . "/seccion_pedidos.php");
                    break;

                case 'direcciones':
                    require(__DIR__ . "/seccion_direcciones.php");
                    break;

                case 'favoritos':
                    require(__DIR__ . "/seccion_favoritos.php");
                    break;

                case 'datos':
                default:
                    require(__DIR__ . "/seccion_datos.php");
                    break;
            }
            ?>
        </div>
    </div>
</section>

<?php require_once(__DIR__ . "/../footer.php"); ?>