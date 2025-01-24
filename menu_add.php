<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <link rel="stylesheet" href="estilo login.css">
</head>
<body>
    <div class="main-container">
        <!-- Menú de navegación en la parte izquierda -->
        <div class="menu">
            <h2>Bienvenido a Rulfoos Administrador  <?php echo $_SESSION['usuario']; ?></h2>
            <button onclick="window.location.href='admin.php';"  class="btn-1">Administrar Productos</button>
            <button onclick="window.location.href='admin_promociones.php';" class="btn-1">Administrar Promociones</button>
            <button onclick="window.location.href='nuevo usuario.php';" class="btn-1">Dar de alta nuevo usuario</button>
           
            <br>
            <a href="logout.php" class="btn-1">Cerrar sesión</a>
        </div>
        
        <!-- Contenido o espacio libre al lado derecho del menú -->
        <div class="content">
            <!-- Aquí puede ir contenido adicional si es necesario -->
        </div>
    </div>
</body>
</html>

