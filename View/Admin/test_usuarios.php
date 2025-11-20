<?php
session_start();

// Simular sesión de admin
$_SESSION["ConsecutivoUsuario"] = 1;
$_SESSION["ConsecutivoPerfil"] = 1;

// Usar el archivo correcto de conexión
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shareflix/Model/UtilesModel.php';

echo "<h1>🔍 Prueba de Agregar Película</h1>";
echo "<hr>";

// 1. PROBAR CONEXIÓN
echo "<h2>1️⃣ Probando conexión a la base de datos...</h2>";
$conexion = Conectar();
if($conexion) {
    echo "✅ Conexión exitosa<br><br>";
} else {
    echo "❌ Error de conexión: " . mysqli_connect_error() . "<br><br>";
    exit;
}

// 2. VERIFICAR SI EXISTE LA TABLA
echo "<h2>2️⃣ Verificando tabla 'contenido'...</h2>";
$consulta = "SHOW TABLES LIKE 'contenido'";
$resultado = mysqli_query($conexion, $consulta);
if(mysqli_num_rows($resultado) > 0) {
    echo "✅ La tabla 'contenido' existe<br><br>";
} else {
    echo "❌ La tabla 'contenido' NO existe<br><br>";
    Desconectar($conexion);
    exit;
}

// 3. VER ESTRUCTURA DE LA TABLA
echo "<h2>3️⃣ Estructura de la tabla 'contenido':</h2>";
$consulta = "DESCRIBE contenido";
$resultado = mysqli_query($conexion, $consulta);
echo "<table border='1' cellpadding='5' style='background: white;'>";
echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Default</th></tr>";
while($campo = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    echo "<td>" . $campo['Field'] . "</td>";
    echo "<td>" . $campo['Type'] . "</td>";
    echo "<td>" . $campo['Null'] . "</td>";
    echo "<td>" . ($campo['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table><br><br>";

// 4. VERIFICAR SI EXISTE EL STORED PROCEDURE
echo "<h2>4️⃣ Verificando stored procedure 'AgregarContenido'...</h2>";
$consulta = "SHOW PROCEDURE STATUS WHERE Name = 'AgregarContenido'";
$resultado = mysqli_query($conexion, $consulta);
if(mysqli_num_rows($resultado) > 0) {
    echo "✅ El stored procedure 'AgregarContenido' existe<br>";
    
    // Mostrar la definición
    $consultaDef = "SHOW CREATE PROCEDURE AgregarContenido";
    $resultadoDef = mysqli_query($conexion, $consultaDef);
    if($resultadoDef && mysqli_num_rows($resultadoDef) > 0) {
        $def = mysqli_fetch_assoc($resultadoDef);
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 300px;'>";
        echo htmlspecialchars($def['Create Procedure']);
        echo "</pre><br>";
    }
} else {
    echo "❌ El stored procedure 'AgregarContenido' NO existe<br>";
    echo "⚠️ <strong>Ve al paso 8 para crearlo</strong><br><br>";
}

// 5. PROBAR INSERCIÓN DIRECTA
echo "<h2>5️⃣ Probando inserción DIRECTA (sin stored procedure)...</h2>";
$titulo = "Película de Prueba " . date('H:i:s');
$descripcion = "Esta es una prueba de inserción directa";
$duracion = 120;
$imagen = "test.jpg";
$trailer = "";
$calificacion = "ATP";
$fecha = date('Y-m-d');

$consultaDirecta = "INSERT INTO contenido (titulo, descripcion, duracion, imagen, trailer, calificacionEdad, fechaPublicacion, activo) 
                    VALUES ('$titulo', '$descripcion', $duracion, '$imagen', '$trailer', '$calificacion', '$fecha', 1)";

if(mysqli_query($conexion, $consultaDirecta)) {
    $idInsertado = mysqli_insert_id($conexion);
    echo "✅ Inserción directa EXITOSA<br>";
    echo "📌 ID generado: <strong>$idInsertado</strong><br><br>";
} else {
    echo "❌ Error en inserción directa: " . mysqli_error($conexion) . "<br><br>";
}

// 6. PROBAR CON STORED PROCEDURE (si existe)
// 6. PROBAR CON STORED PROCEDURE (si existe)
echo "<h2>6️⃣ Probando inserción con STORED PROCEDURE...</h2>";
$titulo2 = "Película SP " . date('H:i:s');
$consultaSP = "CALL AgregarContenido('$titulo2', '$descripcion', $duracion, '$imagen', '$trailer', '$calificacion', '$fecha')";

if($resultadoSP = mysqli_query($conexion, $consultaSP)) {
    if(mysqli_num_rows($resultadoSP) > 0) {
        $fila = mysqli_fetch_assoc($resultadoSP);
        echo "✅ Inserción con SP EXITOSA<br>";
        echo "📌 ID generado: <strong>" . $fila['idContenido'] . "</strong><br><br>";
    } else {
        echo "⚠️ SP ejecutado pero no retornó ID<br><br>";
    }
    // Limpiar resultados del stored procedure
    mysqli_free_result($resultadoSP);
    while(mysqli_next_result($conexion)) {
        if($res = mysqli_store_result($conexion)) {
            mysqli_free_result($res);
        }
    }
} else {
    echo "❌ Error con stored procedure: " . mysqli_error($conexion) . "<br><br>";
}

// 7. VERIFICAR REGISTROS INSERTADOS
echo "<h2>7️⃣ Últimos 5 registros en la tabla 'contenido':</h2>";
$consultaUltimos = "SELECT idContenido, titulo, duracion, fechaPublicacion, activo FROM contenido ORDER BY idContenido DESC LIMIT 5";
$resultadoUltimos = mysqli_query($conexion, $consultaUltimos);

if($resultadoUltimos && mysqli_num_rows($resultadoUltimos) > 0) {
    echo "<table border='1' cellpadding='5' style='background: white;'>";
    echo "<tr><th>ID</th><th>Título</th><th>Duración</th><th>Fecha</th><th>Activo</th></tr>";
    while($reg = mysqli_fetch_assoc($resultadoUltimos)) {
        echo "<tr>";
        echo "<td>" . $reg['idContenido'] . "</td>";
        echo "<td>" . $reg['titulo'] . "</td>";
        echo "<td>" . $reg['duracion'] . "</td>";
        echo "<td>" . $reg['fechaPublicacion'] . "</td>";
        echo "<td>" . ($reg['activo'] ? '✅' : '❌') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "⚠️ No hay registros o error: " . mysqli_error($conexion) . "<br><br>";
}

// 8. SCRIPT PARA CREAR EL STORED PROCEDURE
echo "<h2>8️⃣ Script para crear el Stored Procedure</h2>";
echo "<p>Si el stored procedure no existe, copia y pega este código en phpMyAdmin (pestaña SQL):</p>";
echo "<textarea style='width: 100%; height: 300px; font-family: monospace; background: #f5f5f5; padding: 10px;'>";
echo "DELIMITER $$

DROP PROCEDURE IF EXISTS AgregarContenido$$
CREATE PROCEDURE AgregarContenido(
    IN p_titulo VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_duracion INT,
    IN p_imagen VARCHAR(255),
    IN p_trailer VARCHAR(255),
    IN p_calificacion VARCHAR(10),
    IN p_fechaPublicacion DATE
)
BEGIN
    INSERT INTO contenido (
        titulo, 
        descripcion, 
        duracion, 
        imagen, 
        trailer, 
        calificacionEdad, 
        fechaPublicacion,
        activo
    ) VALUES (
        p_titulo,
        p_descripcion,
        p_duracion,
        p_imagen,
        p_trailer,
        p_calificacion,
        p_fechaPublicacion,
        1
    );
    
    SELECT LAST_INSERT_ID() as idContenido;
END$$

DELIMITER ;";
echo "</textarea>";

echo "<hr>";
echo "<h2>✨ Prueba completada</h2>";

Desconectar($conexion);
?>
## Ahora accede a:
```
http://localhost/Shareflix/View/Admin/test_pelicula.php
## Ahora accede a:
```
http://localhost/Shareflix/View/Admin/test_usuarios.php