
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; }
        .container { margin-top: 100px; }
        h1 { color: red; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚫 Acceso Denegado</h1>
        <p>No tienes permiso para acceder a esta página.</p>
        <script> console.log("Página actual: <?php echo htmlspecialchars($pagina_actual, ENT_QUOTES, 'UTF-8'); ?>"); </script>

        <a href="/sistransporte/">Volver al inicio</a>
    </div>
</body>
</html>
