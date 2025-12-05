<h2>Iniciar sesión</h2>

<form action="/login" method="post" class="form-auth">
    <div class="form-group">
        <label for="username">Usuario</label>
        <input type="text" name="username" id="username" required>
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>
    </div>

    <button type="submit">Entrar</button>
</form>

<p>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
