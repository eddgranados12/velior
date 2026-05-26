<div class="login-card">
    <h2><i class="fas fa-key"></i> Recuperar Contraseña</h2>
    <p class="subtitle">Ingresa tu correo para recuperar tu contraseña.</p>

    <form method="POST" action="login.php?accion=token">
        
        <!-- Email input -->
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" placeholder="tu@email.com" required>
        </div>

        <!-- Submit button -->
        <button type="submit" name="recuperar" class="btn btn-login">
            <i class="fas fa-envelope"></i> Recuperar contraseña
        </button>
    </form>

    <!-- Back to login -->
    <div class="text-center mt-4">
        <a href="login.php">← Volver al inicio de sesión</a>
    </div>
</div>