<?php
session_start();
require_once __DIR__ . '/conexion.php'; // Necesitamos conexión a BD

// Validación de sesión básica
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.html");
    exit;
}

// OBTENER EL CONTENIDO DE LA BASE DE DATOS
$sql = "SELECT contenido FROM tblconfiguracion WHERE clave = 'aviso_privacidad' LIMIT 1";
$res = $conn->query($sql);
$fila = $res->fetch_assoc();
$contenido_aviso = $fila['contenido'] ?? '<p>No hay información disponible.</p>';

// Verificar si es Admin (1) o Empleado (2) para mostrar botón de editar
$es_staff = (isset($_SESSION['rol_id']) && ($_SESSION['rol_id'] == 1 || $_SESSION['rol_id'] == 2));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Aviso de Privacidad — Cafetería UTHH</title>
  <link rel="stylesheet" href="../archivosCSS/home.css?v=3.5" />
  <link rel="stylesheet" href="../archivosCSS/footer.css?v=3.5" />
  <style>
    .privacy-container {
        max-width: 900px;
        margin: 40px auto;
        background-color: #ffffff;
        padding: 60px 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-align: center;
        border: 1px solid #e0e0e0;
        position: relative; /* Para posicionar el botón de editar */
    }
    .privacy-title { font-size: 2rem; color: #1f1f1f; margin-bottom: 50px; font-weight: normal; }
    
    /* Estilos para el contenido dinámico */
    .privacy-content h3 { font-size: 1.3rem; color: #333; margin-bottom: 15px; text-transform: uppercase; font-weight: 500; margin-top: 40px; }
    .privacy-content p { font-size: 1rem; color: #666; line-height: 1.6; max-width: 80%; margin: 0 auto; }

    body { background-color: #f3efe6; }

    /* Botón de editar flotante */
    .btn-editar-aviso {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: #2A9D8F;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        font-size: 0.9rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .btn-editar-aviso:hover { background-color: #21867a; }
  </style>
</head>
<body>
  <div class="app">
    <header class="topbar">
      <div class="topbar__left">
        <span class="avatar">👤</span>
        <div class="user-dropdown">
            <span class="user-trigger">Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?> ▼</span>
            <div class="dropdown-content">
                <a href="mi_cuenta.php">⚙️ Mi Cuenta</a>
                <a href="logout.php">🚪 Cerrar Sesión</a>
            </div>
        </div>
      </div>
      <h1 class="title">CAFETERIA UTHH</h1>
      <div class="topbar__right"></div>
    </header>

    <nav class="nav">
      <div class="nav__wrap">
        <a class="pill" href="../index.php">HOME 🏠</a>
        <a class="pill" href="productos.php">PRODUCTOS 📦</a>
        <a class="pill" href="menu.php">MENÚ 🍽️</a>
        <a class="pill" href="pedidos.php">PEDIDOS 🧾</a>
        <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1){ ?>
            <a class="pill" href="usuarios.php">REGISTROS 👤</a>
        <?php } ?>
      </div>
    </nav>

    <main class="content">
        <div class="privacy-container">
            <?php if ($es_staff): ?>
                <a href="editar_aviso.php" class="btn-editar-aviso">✏️ Editar Texto</a>
            <?php endif; ?>

            <h2 class="privacy-title">Aviso de Privacidad</h2>

            <div class="privacy-content">
                <?php echo $contenido_aviso; ?>
            </div>
        </div>
    </main>
  </div>

  <footer class="footer">
    <p>Universidad Tecnológica de la Huasteca Hidalguense</p>
    <p>&copy; 2025 Cafetería UTHH. Todos los derechos reservados.</p>

    <div class="footer-links">
      <a href="/unicafe/archivosPHP/aviso_privacidad.php">Aviso de Privacidad</a>
      <span class="separator">|</span>
      <a href="/archivosPHP/terminos.php">Terminos y condiciones</a>
      <span class="separator">|</span>
      <a href="/unicafe/archivosHTML/somosUnicafe.html">Sobre nosotros</a>
    </div>
  </footer>
  <button
    id="btn-voz"
    class="voice-btn"
    aria-label="Escuchar el contenido de la página">
    🔊 Escuchar Contenido
  </button>
  <script src="/archivosJS/lector_voz.js"></script>

  <script src="/archivosJS/accesibilidad.js"></script>

  <div class="accessibility-panel">
    <button id="btn-zoom-in" aria-label="Aumentar tamaño">A+</button>
    <button id="btn-zoom-reset" aria-label="Restablecer tamaño">↺</button>
    <button id="btn-zoom-out" aria-label="Disminuir tamaño">A-</button>

    <button
      id="btn-contrast"
      aria-label="Cambiar modo de color"
      style="margin-top: 5px; border-color: #2a9d8f; color: #2a9d8f">
      🌗
    </button>
  </div>
</body>
</html>