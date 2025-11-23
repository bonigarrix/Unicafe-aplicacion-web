<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pedidos — Cafetería UTHH</title>
  <link rel="stylesheet" href="estilos.css" /> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="wrapper">
    
    <header class="topbar">
      <div class="topbar__left">
        <span class="avatar" aria-hidden="true">👤</span>
        <a class="login-pill" href="/archivosHTML/login.html"><?php echo $_SESSION['usuario']; ?></a>
      </div>
      <h1 class="title">CAFETERIA UTHH</h1>
      <div></div>
    </header>

    <nav class="nav">
      <div class="nav__wrap">
        <a class="pill" href="../index.php"><span class="ico">🏠</span> HOME</a>
        <a class="pill" href="/archivosPHP/productos.php"><span class="ico">📦</span> PRODUCTOS</a>
        <a class="pill" href="/archivosPHP/menu.php"><span class="ico">🍽️</span> MENÚ</a>
        <a class="pill is-active" href="/archivosPHP/pedidos.php"><span class="ico">🧾</span> PEDIDOS</a>
        <a class="pill" href="/archivosPHP/usuarios.php"><span class="ico">👤</span> REGISTROS</a>
      </div>
    </nav>

    <div class="main-container">
        <div class="card">
            <h2>Historial de Pedidos</h2>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nombre</th>
                            <th>Lista de Pedido</th>
                            <th>Hora de la entrega</th>
                            <th class="text-center">Cantidad</th>
                            <th>Pago Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                        // 1. Incluimos la conexión (Asegúrate de que conexion.php esté en la misma carpeta)
                        include 'conexion.php'; 

                        // 2. Hacemos la consulta SQL
                        $sql = "SELECT * FROM historial_pedidos";
                        
                        // Verificamos que la conexión exista antes de consultar
                        if ($conn) {
                            $result = $conn->query($sql);

                            // 3. Verificamos si hay resultados
                            if ($result && $result->num_rows > 0) {
                                // 4. Recorremos cada fila y la dibujamos
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["nombre_cliente"] . "</td>";
                                    echo "<td>" . $row["detalle_pedido"] . "</td>";
                                    echo "<td>" . $row["hora_entrega"] . "</td>";
                                    echo "<td class='text-center'>" . $row["cantidad"] . "</td>";
                                    echo "<td>$" . $row["total"] . "</td>";
                                    echo "<td class='text-center'>";
                                    // Enlace para eliminar
                                    echo "<a href='eliminar_pedido.php?id=" . $row["id"] . "' class='btn-delete'>Eliminar</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No hay pedidos registrados</td></tr>";
                            }
                            $conn->close();
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Error de conexión a la base de datos</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
            </div>
        </div>
    </div>

    <footer class="footer">
      <p>Universidad Tecnológica de la Huasteca Hidalguense</p>
      <p>&copy; 2025 Cafetería UTHH. Todos los derechos reservados.</p>
      <form action="#contacto.html" method="get">
        <button type="submit" class="btn-contacto">Contáctanos</button>
      </form>
    </footer>
    
  </div>
</body>
</html>