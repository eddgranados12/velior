<h2>Mis datos</h2>
<p class="sub">Administra tu información personal y mantén tu cuenta actualizada.</p>

<style>
    .cuenta-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .cuenta-form-group {
        display: flex;
        flex-direction: column;
    }

    .cuenta-form-group.full {
        grid-column: 1 / -1;
    }

    .cuenta-form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f1f1f;
    }

    .cuenta-form-group input,
    .cuenta-form-group textarea {
        border: 1px solid #ddd;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 1rem;
        outline: none;
        transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .cuenta-form-group input:focus,
    .cuenta-form-group textarea:focus {
        border-color: #111;
        box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
    }

    .btn-guardar-cuenta {
        margin-top: 24px;
        background: #111;
        color: white;
        border: none;
        padding: 14px 22px;
        border-radius: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-guardar-cuenta:hover {
        background: #333;
    }

    @media (max-width: 768px) {
        .cuenta-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<form method="POST" action="/velior/admin/cuenta.php?accion=actualizar">
    <div class="cuenta-form-grid">

        <div class="cuenta-form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" required
                   value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>">
        </div>

        <div class="cuenta-form-group">
            <label>Apellido paterno</label>
            <input type="text" name="apellido_paterno" required
                   value="<?php echo htmlspecialchars($usuario['apellido_paterno'] ?? ''); ?>">
        </div>

        <div class="cuenta-form-group">
            <label>Apellido materno</label>
            <input type="text" name="apellido_materno"
                   value="<?php echo htmlspecialchars($usuario['apellido_materno'] ?? ''); ?>">
        </div>

        <div class="cuenta-form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono"
                   value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
        </div>

        <div class="cuenta-form-group full">
            <label>Correo electrónico</label>
            <input type="email" name="correo" required
                   value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>">
        </div>

        <div class="cuenta-form-group full">
            <label>Dirección</label>
            <textarea name="direccion" rows="4"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
        </div>

    </div>

    <button type="submit" class="btn-guardar-cuenta">
        Guardar cambios
    </button>
</form>