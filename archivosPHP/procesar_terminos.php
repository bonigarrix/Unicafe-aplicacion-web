<?php
include 'conexion.php';

if (!isset($_GET['accion'])) { header("Location: gestion_terminos.php"); exit; }

$accion = $_GET['accion'];

switch ($accion) {
    case 'agregar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $desc = $_POST['descripcion'];
            $stmt = $conn->prepare("INSERT INTO tblterminos (vchTitulo, txtDescripcion) VALUES (?, ?)");
            $stmt->bind_param("ss", $titulo, $desc);
            $stmt->execute();
            $stmt->close();
        }
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $titulo = $_POST['titulo'];
            $desc = $_POST['descripcion'];
            $stmt = $conn->prepare("UPDATE tblterminos SET vchTitulo=?, txtDescripcion=? WHERE intIdTermino=?");
            $stmt->bind_param("ssi", $titulo, $desc, $id);
            $stmt->execute();
            $stmt->close();
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("DELETE FROM tblterminos WHERE intIdTermino=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        break;
}

$conn->close();
header("Location: gestion_terminos.php");
exit;
?>