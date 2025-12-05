<h2>Registro</h2>

<form action="/register" method="post" class="form-auth">
    <div class="form-group">
        <label for="username">Usuario</label>
        <input type="text" name="username" id="username" required>
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div class="form-group">
        <label for="password2">Repite la contraseña</label>
        <input type="password" name="password2" id="password2" required>
    </div>

    <button type="submit">Registrarse</button>
</form>

<p>¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>
