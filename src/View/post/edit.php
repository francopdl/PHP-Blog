<h2>Editar post</h2>

<form action="/post/update" method="post" enctype="multipart/form-data" class="form-post">
    <input type="hidden" name="id" value="<?= (int)$post->id ?>">

    <div class="form-group">
        <label for="title">Título</label>
        <input type="text"
               name="title"
               id="title"
               value="<?= htmlspecialchars($post->title) ?>"
               required>
    </div>

    <div class="form-group">
        <label for="content">Contenido</label>
        <textarea name="content" id="content" rows="8" required><?= htmlspecialchars($post->content) ?></textarea>
    </div>

    <?php if ($post->image_path): ?>
        <div class="form-group">
            <p>Imagen actual:</p>
            <img src="<?= htmlspecialchars($post->image_path) ?>"
                 alt=""
                 class="post-thumb">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="image">Nueva imagen (opcional)</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>

    <button type="submit">Guardar cambios</button>
</form>

<form action="/post/delete" method="post"
      onsubmit="return confirm('¿Seguro que quieres borrar este post?');"
      style="margin-top:1rem;">
    <input type="hidden" name="id" value="<?= (int)$post->id ?>">
    <button type="submit" class="btn-danger">Borrar post</button>
</form>

<p><a href="/post?id=<?= (int)$post->id ?>" class="btn-link">← Volver al post</a></p>
