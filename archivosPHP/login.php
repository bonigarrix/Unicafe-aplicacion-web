<?php
session_start();
require_once __DIR__ . '/conexion.php'; // misma carpeta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario']  ?? '');
    $password = trim($_POST['password'] ?? '');

    $sql  = "SELECT intIdUsuario, vchNombres, vchCorreo, vchPassword, intIdRol
            FROM tblusuario WHERE vchCorreo = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        // Si el hash es sospechosamente corto, avisa (posible columna truncada)
        if (strlen($fila['vchPassword']) < 55) {
            echo "<script>alert('Tu contraseña es muy corta, porfavor introduce una con un mayor numero de caracteres.'); window.location='../login.html';</script>";
            exit;
        }

        if (password_verify($password, $fila['vchPassword'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $fila['vchNombres'];
            $_SESSION['rol_id']  = $fila['intIdRol'];
            $_SESSION['usuario_id'] = $fila['intIdUsuario'];
            header("Location: ../archivosPHP/index.php");
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='../archivosHTML/login.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='../archivosHTML/login.html';</script>";
        exit;
    }
}

if (isset($stmt) && $stmt instanceof mysqli_stmt) { $stmt->close(); }
if (isset($conn) && $conn instanceof mysqli) { $conn->close(); }
