<?php
// Iniciar sesión si no se ha iniciado aún
session_start();

// Incluir archivo de conexión
require 'Conectar.php';

// Inicializar $resultados como un array vacío
$resultados = [];

// Consulta combinada de clientes
$consulta = $conexion->prepare("SELECT id_cliente AS id, nombre_cliente, telefono, ruc FROM cliente");
$consulta->execute();

// Manejo de errores en la consulta
if (!$consulta) {
    die("Error en la consulta: " . $conexion->error);
}

// Obtener resultados de la consulta
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
    
    <!-- Estilos CSS -->
    <style>
        /* Estilos generales para la página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a2a3a; /* Fondo oscuro */
            margin: 0;
            padding: 0;
            color: #ecf0f1; /* Texto en color claro */
        }

        h1 {
            text-align: center;
            color: #e67e22; /* Naranja */
            font-size: 2em;
            margin-bottom: 20px;
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

        /* Contenido principal */
        .content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s ease;
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
            border: 1px solid #555; /* Bordes más oscuros */
        }

        table th {
            background-color: #2c3e50; /* Fondo gris oscuro para encabezados */
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #34495e; /* Fondo gris claro para filas pares */
        }

        table tr:hover {
            background-color: #e67e22; /* Naranja para resaltar filas al pasar el ratón */
            color: white;
        }

        /* Estilo de los botones */
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
            background-color: #d35400; /* Naranja oscuro en hover */
        }

        /* Estilos del input de búsqueda */
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-input {
            padding: 10px;
            width: 50%;
            max-width: 400px;
            border: 1px solid #555; /* Borde gris oscuro */
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #e67e22; /* Borde naranja cuando el input está activo */
            outline: none;
        }
    </style>
</head>
<body>
    <!-- Botón para abrir el menú -->
    <button class="menu-toggle" onclick="toggleMenu()">☰ Menú</button>

    <!-- Menú Lateral -->
    <div class="sidebar">
        <a href="Inicio.html" class="sidebar-btn">Inicio</a>
        <a href="Proveedores.php" class="sidebar-btn">Proveedores</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <h1>Registro de Clientes</h1>
        
        <!-- Botón para nuevo registro -->
        <div>
            <a class="btn" href="insertar_cliente.php">Nuevo Registro</a>
        </div>

        <!-- Mensaje de éxito -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p>{$_SESSION['mensaje']}</p>";
            unset($_SESSION['mensaje']); // Limpiar mensaje de la sesión
        }
        ?>

        <!-- Barra de búsqueda -->
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" onkeyup="filtrarTabla()" placeholder="Buscar cliente por nombre, teléfono o RUC">
        </div>

        <!-- Tabla de clientes -->
        <h2>Tabla de Registros</h2>
        <table id="clientesTable">
            <thead>
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombre del Cliente</th>
                    <th>Teléfono</th>
                    <th>RUC</th>
                    <th>Ficha del Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($resultados)): ?>
                    <tr><td colspan="5">No hay registros disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($resultados as $fila): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['id']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($fila['ruc']); ?></td>
                        <td>
                            <a class="btn" href="detalle_servicio.php?id=<?php echo $fila['id']; ?>">Ficha del Cliente</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts de interacción -->
    <script>
        // Función para abrir/cerrar el menú lateral
        function toggleMenu() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
            
            var content = document.querySelector('.content');
            content.style.marginLeft = sidebar.classList.contains('open') ? '250px' : '0';
        }

        // Función para filtrar la tabla según el input de búsqueda
        function filtrarTabla() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("clientesTable");
            tr = table.getElementsByTagName("tr");

            // Iterar sobre todas las filas de la tabla (comenzando desde la segunda fila)
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                let encontrado = false;

                // Verificar si el término de búsqueda se encuentra en alguna de las columnas (nombre, teléfono o RUC)
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            encontrado = true;
                            break;
                        }
                    }
                }

                // Mostrar u ocultar la fila según si se encuentra una coincidencia
                tr[i].style.display = encontrado ? "" : "none";
            }
        }
    </script>
</body>
</html>
