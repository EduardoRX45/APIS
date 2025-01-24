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

// Manejo de la carga de la imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "imgg/"; // Carpeta para imágenes de productos
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    // Verificar si la carpeta para imágenes QR existe
    $qr_dir = "imgg/qr/";
    if (!is_dir($qr_dir)) {
        mkdir($qr_dir, 0777, true); // Crear la carpeta con permisos de escritura
    }

    // Guardar la imagen principal del producto
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $nombre = $_POST['nombre'];
        $precio1 = $_POST['precio1'];
        $precio2 = $_POST['precio2'];

        // Procesar la imagen QR si se ha cargado
        $imagen_qr = null;
        if (!empty($_FILES["imagen_qr"]["name"])) {
            $qr_file = $qr_dir . basename($_FILES["imagen_qr"]["name"]);
            if (move_uploaded_file($_FILES["imagen_qr"]["tmp_name"], $qr_file)) {
                $imagen_qr = $qr_file; // Guardar la ruta de la imagen QR
            } else {
                echo "<script>alert('Error al mover la imagen QR');</script>";
            }
        }

        // Insertar o actualizar producto
        if (isset($_POST['id']) && $_POST['id'] != "") {
            $id = $_POST['id'];
            $sql = "UPDATE productos SET nombre='$nombre', precio1='$precio1', precio2='$precio2', imagen='$target_file'";
            if ($imagen_qr) {
                $sql .= ", imagen_qr='$imagen_qr'";
            }
            $sql .= " WHERE id='$id'";
        } else {
            $sql = "INSERT INTO productos (nombre, precio1, precio2, imagen, imagen_qr) VALUES ('$nombre', '$precio1', '$precio2', '$target_file', '$imagen_qr')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registro exitoso.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error al cargar la imagen del producto.');</script>";
    }
}





// Obtener productos para mostrar en el formulario
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estlilo admin.css">
    <title>Administración de Productos</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Administración de Productos</h1>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                <label>Nombre:</label>
                <input type="text" name="nombre" required placeholder="Ingrese el nombre">
                <label>Precio 1:</label>
                <input type="text" name="precio1" required placeholder="Ingrese el precio 1">
                <label>Precio 2:</label>
                <input type="text" name="precio2" required placeholder="Ingrese el precio 2">
                <label>Imagen:</label>
                <input type="file" name="imagen" accept="image/*" required>
                <!-- Nuevo campo para cargar la imagen QR -->
                <label>Imagen QR:</label>
                <input type="file" name="imagen_qr" accept="image/*">

                

                <button type="submit" class="login-btn">Guardar Producto</button>
            </form>

            <h2>Lista de Productos</h2>
            <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio 1</th>
            <th>Precio 2</th>
            <th>Imagen</th>
            <th>Imagen QR</th> <!-- Nueva columna para la imagen QR -->
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['precio1']; ?></td>
                <td><?php echo $row['precio2']; ?></td>
                <td><img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" width="100"></td>

                <!-- Mostrar la imagen QR si existe -->
                <td>
                    <?php if (!empty($row['imagen_qr'])): ?>
                        <img src="<?php echo $row['imagen_qr']; ?>" alt="QR <?php echo $row['nombre']; ?>" width="100">
                    <?php else: ?>
                        <p>No disponible</p>
                    <?php endif; ?>
                </td>

                <td>
                    <button onclick="editProduct(<?php echo $row['id']; ?>, '<?php echo $row['nombre']; ?>', '<?php echo $row['precio1']; ?>', '<?php echo $row['precio2']; ?>', '<?php echo $row['imagen']; ?>', '<?php echo $row['imagen_qr']; ?>')" class="btn-1">Editar</button>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-del">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


            <!-- Botón para regresar al menú -->
            <button onclick="window.location.href='menu_add.php';" class="btn-1">Regresar al Menú</button>
        </div>
    </div>

    <script>
    function editProduct(id, nombre, precio1, precio2, imagen, imagen_qr) {
        document.querySelector('[name="id"]').value = id;
        document.querySelector('[name="nombre"]').value = nombre;
        document.querySelector('[name="precio1"]').value = precio1;
        document.querySelector('[name="precio2"]').value = precio2;
        alert("La imagen actual es: " + imagen + "\nLa imagen QR actual es: " + imagen_qr);
    }
    </script>

</body>
</html>