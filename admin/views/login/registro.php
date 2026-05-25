<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand-mark">VELIOR</div>

        <h2>
            <i class="fas fa-user-plus"></i>
            Crear cuenta
        </h2>

        <p class="subtitle">
            Regístrate para guardar favoritos, comprar y administrar tu cuenta.
        </p>

        <form method="POST" action="/velior/admin/registro.php?accion=guardar">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" name="apellido_paterno" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" class="form-control" name="apellido_materno">
            </div>

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="correo" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono">
            </div>

            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <textarea class="form-control" name="direccion" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" name="confirmar_contrasena" required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-user-plus"></i>
                Crear cuenta
            </button>
        </form>

        <div class="forgot-password-link" style="margin-top: 18px; text-align:center;">
            <a href="/velior/admin/login.php?accion=login">Ya tengo cuenta</a>
        </div>
    </div>
</div>