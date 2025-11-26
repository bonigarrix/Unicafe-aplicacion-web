<?php
session_start();

// --- 1. LOGICA DE CONEXIÓN Y SESIÓN ---

// Definimos si el usuario está logueado o no
$usuario_logueado = isset($_SESSION['usuario']);
$es_admin = isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
$es_empleado = isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2;
$es_cliente = isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 3;

// Conexión a la BD (Ajustamos la ruta porque este archivo está en la RAÍZ)
// Buscamos conexion.php dentro de la carpeta archivosPHP
require_once 'archivosPHP/conexion.php';

// --- 2. CONSULTAS A LA BASE DE DATOS (Para todos: Visitantes y Usuarios) ---

// A. MENÚ (Platillos preparados - 4 Aleatorios)
$sql_menu = "SELECT * FROM tblmenu ORDER BY RAND() LIMIT 4";
$res_menu = $conn->query($sql_menu);

// B. PRODUCTOS (Snacks/Bebidas - 3 Aleatorios)
$sql_productos = "SELECT * FROM tblproductos ORDER BY RAND() LIMIT 3";
$res_productos = $conn->query($sql_productos);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cafetería UTHH</title>
  
  <link rel="stylesheet" href="archivosCSS/layout.css?v=999.2" />

  
  <style>
    /* --- ESTILOS LOCALES --- */
    
    /* Placeholder para cuando no hay imagen */
    .no-image-placeholder {
        width: 100%; height: 100%;
        background-color: #efe3cf; color: #8a633b;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 24px; text-align: center;
    }
    
    /* Ajuste de imágenes */
    .mini__img img, .special__img img {
        width: 100%; height: 100%; object-fit: cover;
    }
    
    /* Tarjetas Clicables */
    .card-link {
        text-decoration: none; color: inherit; display: block;
        transition: transform 0.2s ease;
    }
    .card-link:hover { transform: scale(1.03); cursor: pointer; }

    /* Hero Banner (Bienvenida) */
    .hero-section {
        /* Imagen de fondo con filtro café */
        background: linear-gradient(rgba(118, 84, 51, 0.8), rgba(118, 84, 51, 0.8)), url('https://images.pexels.com/photos/2956954/pexels-photo-2956954.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
        background-size: cover;
        background-position: center;
        color: white;
        text-align: center;
        padding: 60px 20px;
        margin-bottom: 30px;
    }
    .hero-title { font-size: 2.5em; margin-bottom: 10px; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
    .hero-subtitle { font-size: 1.1em; margin-bottom: 25px; opacity: 0.95; }
    
    .btn-hero {
        background-color: #c59b42; color: white; padding: 10px 25px; text-decoration: none;
        border-radius: 25px; font-weight: bold; display: inline-block; transition: background 0.3s;
    }
    .btn-hero:hover { background-color: #a88b7d; }
  </style>
</head>

<body>
  <div class="app">
    
    <?php include 'archivosPHP/header.php'; ?>
    
    <nav class="nav">
      <div class="nav__wrap">
        <?php include 'archivosPHP/barra_navegacion.php'; ?>
      </div>
    </nav>

    <main class="content" style="padding-top: 0;">
      
      <div class="hero-section">
          <h2 class="hero-title">¡Bienvenido a Cafetería UTHH!</h2>
          <p class="hero-subtitle">Los mejores platillos y el café más fresco del campus.</p>
          <?php if (!$usuario_logueado): ?>
            <a href="archivosPHP/Registro.php" class="btn-hero">¡Regístrate Aquí!</a>
          <?php endif; ?>
      </div>

      <div class="container">
        
        <section>
          <h2 class="section-title">Menú del Día</h2>
          <div class="menu-card">
            <div class="menu-grid">
              
              <?php if ($res_menu && $res_menu->num_rows > 0): ?>
                  <?php while ($row = $res_menu->fetch_assoc()): ?>
                      <?php 
                          // Ajuste de ruta: Como estamos en raíz, NO usamos "../"
                          // La ruta en BD es "imagenes_menu/foto.jpg", eso sirve directo desde raíz
                          $ruta_img = !empty($row['vchImagen']) ? $row['vchImagen'] : ""; 
                      ?>
                      
                      <a href="archivosPHP/menu.php" class="card-link">
                          <article class="mini">
                            <div class="mini__img">
                              <?php if (!empty($ruta_img) && file_exists($ruta_img)): ?>
                                 <img src="<?php echo $ruta_img; ?>" alt="<?php echo htmlspecialchars($row['vchNombre']); ?>">
                              <?php else: ?>
                                 <div class="no-image-placeholder">
                                    <?php echo strtoupper(substr($row['vchNombre'], 0, 1)); ?>
                                 </div>
                              <?php endif; ?>
                            </div>
                            <ul class="mini__lines">
                              <li style="font-weight:bold;"><?php echo htmlspecialchars($row['vchNombre']); ?></li>
                              <li><?php echo htmlspecialchars($row['vchCategoria']); ?></li>
                              <li class="price">$<?php echo number_format($row['decPrecio'], 2); ?></li>
                            </ul>
                          </article>
                      </a>
                  <?php endwhile; ?>
              <?php else: ?>
                  <p style="padding: 20px; text-align:center;">Hoy todo se acabó temprano. ¡Vuelve mañana!</p>
              <?php endif; ?>

            </div>
          </div>
        </section>

        <section>
          <h2 class="section-title">Productos Disponibles</h2>
          <div class="specials-grid">
            
            <?php if ($res_productos && $res_productos->num_rows > 0): ?>
                <?php while ($prod = $res_productos->fetch_assoc()): ?>
                    <?php 
                        // Ajuste de ruta: Igual, desde raíz no usamos "../"
                        $ruta_img_prod = !empty($prod['vchImagen']) ? $prod['vchImagen'] : ""; 
                    ?>
                    
                    <a href="archivosPHP/productos.php" class="card-link">
                        <article class="special">
                          <div class="special__img">
                              <?php if (!empty($ruta_img_prod) && file_exists($ruta_img_prod)): ?>
                                 <img src="<?php echo $ruta_img_prod; ?>" alt="<?php echo htmlspecialchars($prod['vchNombre']); ?>">
                              <?php else: ?>
                                 <div class="no-image-placeholder">
                                    <?php echo strtoupper(substr($prod['vchNombre'], 0, 1)); ?>
                                 </div>
                              <?php endif; ?>
                          </div>
                          <ul class="special__lines">
                            <li style="font-weight:bold; font-size:1.1em;"><?php echo htmlspecialchars($prod['vchNombre']); ?></li>
                            <li style="font-size:0.9em; color:#666;">
                                <?php echo htmlspecialchars($prod['vchDescripcion']); ?>
                            </li>
                            <li class="price">$<?php echo number_format($prod['decPrecioVenta'], 2); ?></li>
                          </ul>
                        </article>
                    </a>
                    
                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding: 20px;">No hay productos destacados.</p>
            <?php endif; ?>

          </div>
        </section><br><br>
      </div>
    </main>
  </div>
  
 <?php include "archivosPHP/footer.php"; ?>
</body>
</html>
<?php 
if(isset($conn)) $conn->close(); 
?>