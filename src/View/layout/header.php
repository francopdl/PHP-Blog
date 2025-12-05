<?php

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Blog MVC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1><a href="/" style="text-decoration:none;color:inherit;">Mi Blog MVC</a></h1>
        <nav>
            <a href="/">Inicio</a>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="/post/create">Nuevo post</a>
                <span>Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/logout">Cerrar sesión</a>
            <?php else: ?>
                <a href="/login">Iniciar sesión</a>
                <a href="/register">Registrarse</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
