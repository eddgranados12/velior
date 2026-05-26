<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand-mark">VELIOR</div>

        <h2>
            <i class="fas fa-sign-in-alt"></i>
            Iniciar Sesión
        </h2>

        <p class="subtitle">
            Accede a tu cuenta para continuar.
        </p>

        <form method="POST" action="/velior/admin/login.php?accion=login">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input
                    type="email"
                    class="form-control"
                    id="correo"
                    name="correo"
                    placeholder="tu@email.com"
                    required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input
                    type="password"
                    class="form-control"
                    id="contrasena"
                    name="contrasena"
                    placeholder="Ingresa tu contraseña"
                    required>
            </div>

            <div class="forgot-password-link" style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap;">
                <a href="/velior/admin/login.php?accion=recuperar">¿Olvidaste tu contraseña?</a>
                <a href="/velior/admin/registro.php">No tengo cuenta</a>
                
            </div>


            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>