<h2>Últimas publicaciones</h2>

<?php if (empty($posts)): ?>
    <p>No hay publicaciones todavía.</p>
<?php else: ?>
    <div class="post-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <h3>
                    <a href="/post?id=<?= (int)$post->id ?>">
                        <?= htmlspecialchars($post->title) ?>
                    </a>
                </h3>

                <?php if ($post->image_path): ?>
                    <a href="/post?id=<?= (int)$post->id ?>">
                        <img src="<?= htmlspecialchars($post->image_path) ?>"
                             alt="Imagen de <?= htmlspecialchars($post->title) ?>"
                             class="post-thumb">
                    </a>
                <?php endif; ?>

                <p>
                    <?= nl2br(htmlspecialchars(substr($post->content, 0, 200))) ?>
                    <?php if (strlen($post->content) > 200): ?>...<?php endif; ?>
                </p>

                <a href="/post?id=<?= (int)$post->id ?>" class="btn-link">Leer más</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
