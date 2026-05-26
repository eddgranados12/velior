<div class="login-card">
    <h2><i class="fas fa-lock"></i> Restablecer Contraseña</h2>
    <p class="subtitle">Ingresa tu nueva contraseña.</p>

    <form method="POST" action="login.php?accion=cambiar">

        <!-- Campos ocultos -->
        <input type="hidden" name="correo" value="<?php echo htmlspecialchars($_GET['correo']); ?>">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

        <!-- Nueva contraseña -->
        <div class="mb-3">
            <label for="nueva" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" name="nueva" required>
        </div>

        <!-- Confirmar contraseña -->
        <div class="mb-3">
            <label for="confirmar" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" name="confirmar" required>
        </div>

        <!-- Botón -->
        <button type="submit" name="cambiar" class="btn btn-login">
            <i class="fas fa-save"></i> Cambiar contraseña
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="login.php">← Volver al login</a>
    </div>
</div>