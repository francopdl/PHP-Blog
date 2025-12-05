<?php

$isOwner = !empty($_SESSION['user_id']) && $_SESSION['user_id'] === $post->user_id;
$isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
?>

<article class="post-single">
    <h2><?= htmlspecialchars($post->title) ?></h2>

    <?php if (!empty($post->image_path)): ?>
        <img src="<?= htmlspecialchars($post->image_path) ?>"
             alt="Imagen de <?= htmlspecialchars($post->title) ?>"
             class="post-image">
    <?php endif; ?>

    <div class="post-content">
        <?= nl2br(htmlspecialchars($post->content)) ?>
    </div>

    <?php if ($isOwner || $isAdmin): ?>
        <div class="post-actions">
            <a href="/post/edit?id=<?= (int)$post->id ?>" class="btn-link">Editar</a>

            <form action="/post/delete" method="post"
                  style="display:inline;"
                  onsubmit="return confirm('¿Seguro que quieres borrar este post?');">
                <input type="hidden" name="id" value="<?= (int)$post->id ?>">
                <button type="submit" class="btn-danger">Borrar</button>
            </form>
        </div>
    <?php endif; ?>
</article>

<p><a href="/" class="btn-link">← Volver al listado</a></p>
