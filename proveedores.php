<?php
// Iniciar sesión si no se ha iniciado aún
session_start();

// Incluir tu archivo de conexión
require 'Conectar.php';

// Consulta combinada de proveedores
$consulta = $conexion->prepare("SELECT ID_Proveedores AS id, Nombre_Producto, Telefono, Ruc FROM proveedores");
$consulta->execute();

// Manejo de errores en la consulta
if ($consulta->errorCode() != '00000') {
    die("Error en la consulta: " . $consulta->errorInfo()[2]);
}

// Obtener resultados de la consulta
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Proveedores</title>
    <style>
        /* Estilos generales para el body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a2a3a; /* Fondo oscuro */
            color: #ecf0f1; /* Texto claro */
            margin: 0;
            padding: 0;
        }

        /* Menú lateral */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #34495e; /* Gris oscuro */
            color: white;
            padding-top: 50px;
            text-align: center;
            transition: left 0.3s ease;
        }

        .sidebar.open {
            left: 0;
        }

        .sidebar-btn {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: white;
            background-color: #2c3e50; /* Gris oscuro */
            margin: 10px 0;
            border-radius: 5px;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }

        .sidebar-btn:hover {
            background-color: #e67e22; /* Naranja en hover */
        }

        /* Estilo para el botón del menú */
        .menu-toggle {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #e67e22; /* Naranja */
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            z-index: 1;
        }

        /* Contenedor principal */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        /* Título principal */
        h1 {
            text-align: center;
            color: #e67e22; /* Naranja */
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Estilo para los botones */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e67e22; /* Naranja */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #d35400; /* Naranja oscuro */
        }

        /* Contenedor de la búsqueda */
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        /* Estilos para el input de búsqueda */
        .search-input {
            padding: 10px;
            width: 50%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #e67e22;
            outline: none;
        }

        /* Estilos de la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #34495e;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #2c3e50;
        }

        table tr:hover {
            background-color: #1a252f;
        }

        /* Mensaje de éxito */
        p {
            text-align: center;
            color: #e67e22;
            font-weight: bold;
            background-color: #34495e;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #e67e22;
        }

    </style>
</head>
<body>

<!-- Botón para abrir el menú lateral -->
<button class="menu-toggle" onclick="toggleMenu()">☰ Menú</button>

<!-- Menú Lateral -->
<div class="sidebar">
    <a href="Inicio.html" class="sidebar-btn">Inicio</a>
    <a href="Clientes.php" class="sidebar-btn">Clientes</a>
</div>

<!-- Contenedor principal -->
<div class="container">
    <h1>Registro de Proveedores</h1>

    <div>
        <?php
        // Botones para insertar nuevo registro y cerrar sesión
        echo "<a class='btn' href='insertar_proveedores.php'>Nuevo Registro</a>";
        ?>
    </div>

    <div class="search-container">
        <!-- Barra de búsqueda -->
        <input type="text" id="searchInput" class="search-input" onkeyup="filtrarTabla()" placeholder="Buscar en la tabla...">
    </div>

    <div>
        <?php
        // Mostrar mensaje de éxito si existe en la sesión
        if (isset($_SESSION['mensaje'])) {
            echo "<p>{$_SESSION['mensaje']}</p>";
            unset($_SESSION['mensaje']); // Limpiar mensaje de la sesión
        }
        ?>

        <h2>Tabla de Registros</h2>
        <table id="clientesTable">
            <thead>
                <tr>
                    <th>ID Proveedor</th>
                    <th>Nombre del Producto</th>
                    <th>Teléfono</th>
                    <th>RUC</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($resultados)): ?>
                    <tr><td colspan="4">No hay registros disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($resultados as $fila): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['id']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Nombre_Producto']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Ruc']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Función para filtrar la tabla según lo que se ingresa en el campo de búsqueda
    function filtrarTabla() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput"); // Obtener el campo de búsqueda
        filter = input.value.toUpperCase(); // Convertir lo ingresado a mayúsculas
        table = document.getElementById("clientesTable"); // Obtener la tabla
        tr = table.getElementsByTagName("tr"); // Obtener todas las filas de la tabla

        // Iterar sobre todas las filas de la tabla (comenzando desde la segunda fila, ya que la primera es el encabezado)
        for (i = 1; i < tr.length; i++) { // Comenzamos en 1 para evitar el encabezado
            td = tr[i].getElementsByTagName("td"); // Obtener las celdas de la fila
            var filaVisible = false; // Variable para verificar si la fila debe mostrarse o no

            // Iterar sobre cada celda
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText; // Obtener el texto de la celda
                    if (txtValue.toUpperCase().indexOf(filter) > -1) { // Verificar si hay coincidencia
                        filaVisible = true;
                        break;
                    }
                }
            }

            // Mostrar u ocultar la fila
            if (filaVisible) {
                tr[i].style.display = ""; // Mostrar fila
            } else {
                tr[i].style.display = "none"; // Ocultar fila
            }
        }
    }

    // Función para abrir y cerrar el menú lateral
    function toggleMenu() {
        document.querySelector('.sidebar').classList.toggle('open');
        document.querySelector('.container').style.marginLeft = document.querySelector('.sidebar').classList.contains('open') ? '250px' : '0';
    }
</script>

</body>
</html>
