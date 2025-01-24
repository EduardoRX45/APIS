<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto según tu configuración
$password = ""; // Cambia esto según tu configuración
$dbname = "gool"; // Cambia esto por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener productos para mostrar en la tienda
$result = $conn->query("SELECT * FROM productos");

// Función para obtener todas las promociones
function obtenerPromociones($conn) {
    $sql = "SELECT * FROM promociones";
    $resultado = mysqli_query($conn, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

$promociones = obtenerPromociones($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="estilo pag rulfoos php.css">
    <link rel="stylesheet" href="estilo pagar rulfoos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="as_voz_estilo.css">
</head>
<body>
    <a href="https://api.whatsapp.com/send?phone=7294697480" class="btn-wsp" target="_blank">
        <i class="fa fa-whatsapp icono"></i>
    </a>
    
    <header>
        <div class="menu container">
            <a href="#" class="logo">RULFOOS</a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt=""></label>
            <nav class="navbar">
                <ul>
                    <li><a href="inicio rulfoos.php">Inicio</a></li>
                    <li><a href="Servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="Productos rulfoos php.php">Productos</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                </ul>
            </nav>
            
            <div>
                <ul>
                    <li class="submenu">
                        <img src="images/car.svg" id="img-carrito" alt="Carrito">
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a href="#" id="vaciar-carrito" class="btn-3">Vaciar Carrito</a>
                            <li><a href="carrito rulfoos php.php" class="btn-4">Ir a pagar</a></li>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="servicios-container">
        <h2>Productos</h2>
        <p>Verifica tus artículos antes de proceder al pago para asegurarte de que tienes todo lo que necesitas.</p>
    </section>
    
    <section class="servicios-container">
        <section class="contenedor">
            <div class="contenedor-items">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="item">
                        <span class="titulo-item"><?php echo $row['nombre']; ?></span>
                        <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="img-item">
                        <span class="precio-item">$<?php echo $row['precio1']; ?></span>
                        
                        <!-- Botón "ver 3D" -->
                        <?php if (!empty($row['url_3d'])): ?>
                            <a href="<?= htmlspecialchars($row['url_3d']) ?>" class="btn-1" target="_blank">Ver 3D</a>
                        <?php endif; ?>
                        
                        <!-- Botón QR para mostrar la imagen en modal -->
                        <?php if (!empty($row['imagen_qr'])): ?>
                            <button class="btn-qr" onclick="mostrarQR('<?= htmlspecialchars($row['imagen_qr']) ?>')">QR</button>
                        <?php endif; ?>
                        
                        <button class="boton-item">Agregar al Carrito</button>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="carrito" id="carrito">
                <div class="header-carrito">
                    <h2>Tu Carrito</h2>
                </div>

                <div class="carrito-items">
                    <!-- Este div está vacío al inicio, los productos se agregarán dinámicamente -->
                </div>
                <div class="carrito-total">
                    <div class="fila">
                        <strong class="ttl">Tu Total</strong>
                        <span class="carrito-precio-total">
                            $0,00  <!-- Cambia este valor dinámicamente según los productos en el carrito -->
                        </span>
                    </div>
                    <button class="btn-pagar">Pagar <i class="fa-solid fa-bag-shopping"></i> </button>
                </div>
            </div>
        </section>
    </section>
    
    <!-- Modal para mostrar el código QR -->
    <div id="qrModal" class="modal">
        <span class="close" onclick="cerrarQR()">&times;</span>
        <img class="modal-content" id="qrImage" alt="Código QR">
    </div>

    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="funciones java php.js"></script>
    <script src="pagar rulfoos.js"></script>
    <script src="funcion asistente voz.js"></script>

    <!-- JavaScript para abrir y cerrar el modal -->
    <script>
        function mostrarQR(imagenQR) {
            const modal = document.getElementById("qrModal");
            const qrImage = document.getElementById("qrImage");

            qrImage.src = imagenQR;
            modal.style.display = "block";
        }

        function cerrarQR() {
            const modal = document.getElementById("qrModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("qrModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
    
    <div id="voice-control" onclick="startVoiceNavigation()">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/microphone.png" class="btn-vz" alt="Microphone Icon">
    </div>

</body>
<footer class="footer-container">
    <div class="link">
        <p> 
            &copy; 2024 Tienda de Accesorios RULFOOS
        </p>
    </div>
</footer>
</html>
