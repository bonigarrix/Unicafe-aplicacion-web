<?php
require_once "conexion.php";

if (!isset($_GET['accion'])) {
    header("Location: menu.php");
    exit;
}

$accion = $_GET['accion'];

// Función para subir imagen
function subirImagenMenu($archivo) {
    // Subimos un nivel (..) y entramos a imagenes_menu
    $directorio = "../imagenes_menu/";
    
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombre_archivo = basename($archivo["name"]);
    $tipo_archivo = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    
    // Validar imagen
    if($check = getimagesize($archivo["tmp_name"]) === false) {
        return null;
    }

    // Nombre único
    $nuevo_nombre = "platillo_" . uniqid() . "." . $tipo_archivo;
    $ruta_destino = $directorio . $nuevo_nombre;

    if (move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
        // Guardamos la ruta relativa limpia para la BD
        return "imagenes_menu/" . $nuevo_nombre;
    }
    return null;
}

switch ($accion) {

    // ====== AGREGAR NUEVO PLATILLO ======
    case 'agregar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria = trim($_POST['categoria'] ?? '');
            $nombre    = trim($_POST['nombre'] ?? '');
            $precio    = (float)($_POST['precio'] ?? 0);
            
            // Por defecto cadena vacía para cumplir con NOT NULL de tu tabla
            $ruta_imagen = ""; 
            
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $subido = subirImagenMenu($_FILES['imagen']);
                if ($subido) {
                    $ruta_imagen = $subido;
                }
            }

            if ($categoria === '' || $nombre === '' || $precio <= 0) {
                die("Datos inválidos.");
            }

            $sql = "INSERT INTO tblmenu (vchCategoria, vchNombre, decPrecio, vchImagen)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            // Importante: Pasamos $ruta_imagen que ahora es "" si no hay foto, no NULL
            $stmt->bind_param("ssds", $categoria, $nombre, $precio, $ruta_imagen);
            
            if (!$stmt->execute()) { die("Error: " . $stmt->error); }
            $stmt->close();
        }
        break;

    // ====== ACTUALIZAR ======
    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $id        = (int)$_GET['id'];
            $categoria = trim($_POST['categoria'] ?? '');
            $nombre    = trim($_POST['nombre'] ?? '');
            $precio    = (float)($_POST['precio'] ?? 0);
            
            // Imagen actual (por si no suben una nueva)
            // Aseguramos que si viene vacía sea "" y no null
            $ruta_imagen = $_POST['imagen_actual'] ?? "";

            // Si suben nueva imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $nueva = subirImagenMenu($_FILES['imagen']);
                if($nueva) {
                    $ruta_imagen = $nueva;
                }
            }

            if ($id <= 0 || $categoria === '' || $nombre === '' || $precio <= 0) {
                die("Datos inválidos.");
            }

            $sql = "UPDATE tblmenu SET
                        vchCategoria = ?,
                        vchNombre    = ?,
                        decPrecio    = ?,
                        vchImagen    = ?
                    WHERE intIdPlatillo = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $categoria, $nombre, $precio, $ruta_imagen, $id);
            
            if (!$stmt->execute()) { die("Error: " . $stmt->error); }
            $stmt->close();
        }
        break;

    // ====== ELIMINAR ======
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            // Opcional: Borrar archivo físico
            $res = $conn->query("SELECT vchImagen FROM tblmenu WHERE intIdPlatillo=$id");
            if($row = $res->fetch_assoc()){
                // Solo borramos si no está vacío y existe el archivo
                if(!empty($row['vchImagen']) && file_exists("../".$row['vchImagen'])){
                    unlink("../".$row['vchImagen']);
                }
            }

            $stmt = $conn->prepare("DELETE FROM tblmenu WHERE intIdPlatillo = ?");
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) { die("Error: " . $stmt->error); }
            $stmt->close();
        }
        break;
}

$conn->close();
header("Location: menu.php");
exit;
?>