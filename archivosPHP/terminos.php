<?php
include '../archivosPHP/conexion.php';
$sql = "SELECT * FROM tblterminos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Términos y Condiciones – Cafetería UTHH</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../archivosCSS/productos.css" /> <!-- Para el header/footer -->
    <link rel="stylesheet" href="../archivosCSS/footer.css" />
    <link rel="stylesheet" href="../archivosCSS/terminos.css" /> <!-- Estilos específicos -->
</head>

<body>
    <div class="app">
        <!-- TOPBAR (Igual a tus otras páginas) -->
        <header class="topbar">
            <div class="topbar__left">
                <span class="avatar">👤</span>
                <a class="login-pill" href="/archivosHTML/login.html">Iniciar Sesión</a>
            </div>
            <h1 class="title">CAFETERIA UTHH</h1>
            <div class="topbar__right"></div>
        </header>

        <!-- NAV -->
        <nav class="nav">
            <div class="nav__wrap">
                <a class="pill" href="../index.html"><span class="ico">🏠</span> HOME</a>
                <a class="pill" href="/archivosPHP/productos.php"><span class="ico">📦</span> PRODUCTOS</a>
                <a class="pill" href="../archivosHTML/menu.html"><span class="ico">🍽️</span> MENÚ</a>
                <a class="pill" href="../archivosHTML/pedidos.html"><span class="ico">🧾</span> PEDIDOS</a>
            </div>
        </nav>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="content">
            <h2 class="page-title">Términos y condiciones</h2>

            <div class="terms-container">
                <div class="terms-grid">
                    <?php
                    if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                    ?>
                            <div class="term-card">
                                <div class="term-title"><?php echo htmlspecialchars($row['vchTitulo']); ?>:</div>
                                <div class="term-desc">
                                    <?php echo nl2br(htmlspecialchars($row['txtDescripcion'])); ?>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='text-align:center; grid-column: 1/-1;'>No hay términos registrados.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-links" style="display:flex; justify-content:center; gap:20px; margin-bottom:10px; font-size:0.9rem;">
            <!-- Enlace útil para llegar aquí -->
            <a href="terminos.php" style="color:#fff; text-decoration:none;">Términos y condiciones</a>
            <a href="#" style="color:#fff; text-decoration:none;">Somos Unicafe</a>
            <a href="#" style="color:#fff; text-decoration:none;">Aviso de privacidad</a>
        </div>
        <p>Universidad Tecnológica de la Huasteca Hidalguense</p>
        <p>&copy; 2025 Cafetería UTHH. Todos los derechos reservados.</p>
        <form action="#contacto.html" method="get">
            <button type="submit" class="btn-contacto">Contáctanos</button>
        </form>
    </footer>

</body>

</html>
<?php $conn->close(); ?>