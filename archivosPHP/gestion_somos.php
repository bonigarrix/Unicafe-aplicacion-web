<?php
session_start();
require_once 'conexion.php';

// Seguridad: Solo Admin
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../index.php"); exit;
}

// Consultar lista
$res = $conn->query("SELECT * FROM tblsomos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Gestión Somos Unicafe</title>
  <link rel="stylesheet" href="../archivosCSS/layout.css?v=999.1">
  <link rel="stylesheet" href="../archivosCSS/registro.css">
  
  <style>
    .item-list { max-width: 800px; margin: 30px auto; }
    .item-row { 
        background: #fff; border: 1px solid #ddd; padding: 15px; 
        margin-bottom: 15px; border-radius: 8px; display: flex; gap: 20px; align-items: center;
    }
    .item-img { width: 100px; height: 80px; object-fit: cover; background: #eee; border-radius: 4px; }
    .item-text { flex: 1; }
    .btn-del { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
  </style>
</head>
<body>
<div class="app">
    <?php include 'header.php'; ?>
    <?php include 'barra_navegacion.php'; ?>

    <main class="content">
        <div class="form-container">
            <h2>Agregar Nueva Sección</h2>
            <form action="procesar_somos.php?accion=agregar" method="post" enctype="multipart/form-data">
                <div class="form-grid" style="grid-template-columns: 1fr;">
                    <div class="form-column">
                        <div class="form-row">
                            <label>Descripción / Historia</label>
                            <textarea name="descripcion" required style="width:100%; height:100px; padding:8px;"></textarea>
                        </div>
                        <div class="form-row">
                            <label>Imagen (Opcional)</label>
                            <input type="file" name="imagen" accept="image/*">
                        </div>
                        <div class="actions">
                            <button class="btn-action btn-add" type="submit">Agregar Sección</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="item-list">
            <h3>Secciones Actuales</h3>
            <?php while ($row = $res->fetch_assoc()): ?>
                <div class="item-row">
                    <?php if(!empty($row['vchImagen'])): ?>
                        <img src="../<?php echo $row['vchImagen']; ?>" class="item-img">
                    <?php else: ?>
                        <div class="item-img" style="display:flex; align-items:center; justify-content:center; font-size:20px;">📷</div>
                    <?php endif; ?>
                    
                    <div class="item-text">
                        <?php echo nl2br(htmlspecialchars($row['txtDescripcion'])); ?>
                    </div>
                    
                    <a href="procesar_somos.php?accion=eliminar&id=<?php echo $row['intIdSomos']; ?>" 
                       class="btn-del" onclick="return confirm('¿Borrar esta sección?');">Eliminar</a>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</div>
</body>
</html>