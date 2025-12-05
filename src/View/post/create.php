<h2>Crear nuevo post</h2>

<form action="/post/store" method="post" enctype="multipart/form-data" class="form-post">
    <div class="form-group">
        <label for="title">Título</label>
        <input type="text" name="title" id="title" required>
    </div>

    <div class="form-group">
        <label for="content">Contenido</label>
        <textarea name="content" id="content" rows="8" required></textarea>
    </div>

    <div class="form-group">
        <label for="image">Imagen (opcional)</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>

    <button type="submit">Publicar</button>
</form>

<p><a href="/" class="btn-link">← Volver al listado</a></p>
