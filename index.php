<?php
if(isset($_POST['correo']) && isset($_POST['password'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Datos de conexión a la base de datos
    $host = 'localhost'; // El servidor donde se encuentra la base de datos
    $usuario = 'root'; // El nombre de usuario de la base de datos
    $contrasena = ''; // La contraseña de la base de datos
    $base_de_datos = 'pear'; // El nombre de la base de datos que deseas utilizar

    // Crear una conexión
    $conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL para evitar la inyección de SQL
    $consulta = $conexion->prepare("SELECT Correo, Pass FROM peartabla WHERE Correo = ? AND Pass = ?");
    $consulta->bind_param("ss", $correo, $password);
    $consulta->execute();
    $consulta->store_result();

    // Verificar si el usuario existe
    if ($consulta->num_rows > 0) {
        // Si la autenticación es exitosa, configuramos el código de estado 200
        http_response_code(200);
    } else {
        // Si la autenticación falla, configuramos el código de estado 401
        http_response_code(401);
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="indexstyle.css">
    <meta content="text/html; UTF-8" http-equiv=Content-Type>
</head>
<body>
    <div class="container">
        <div class="content">
            <script>
                function validarFormulario() {
                    // Obtener los campos del formulario
                    var correo = document.getElementById("mail").value;
                    var password = document.getElementById("pass").value;
                    var error = document.getElementById("resultado");

                    // Verificar si algún campo está vacío
                    if (correo === "" || password === "") {
                        error.textContent = "Por favor, completa todos los campos del formulario.";
                        error.style.display = "block";
                        error.style.color = "Red";    
                    } 
                    else
                    {
                        error.style.display = "none";
                        // Enviar la solicitud con los datos como parámetro
                        enviarDatos(correo, password);
                    }
                }

                function enviarDatos(correo, password) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status === 200) {
                // Si el código de estado es 200, la autenticación es exitosa
                window.location.href = "main.html";
            } else if (this.status === 401) {
                // Si el código de estado es 401, la autenticación falla
                var error = document.getElementById("resultado");
                error.textContent = "Correo / Contraseña no existe";
                error.style.display = "block";
                error.style.color = "Red";
            }
        }
    };

    xhttp.open("POST", "index.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("correo=" + encodeURIComponent(correo) + "&password=" + encodeURIComponent(password));
}
            </script>
            <form method="post" id="login">
                <label for="mail">Ingresa tu correo electrónico:</label>
                <input type="email" id="mail">
                <label for="pass">Ingresa tu contraseña:</label>
                <input type="password" id="pass">
                <input type="button" value="Iniciar sesión" onclick='validarFormulario()'>
                <div id="resultado"></div>
                <a href="registro.php">¿No tienes una cuenta? ¡Registrate aquí!</a>
            </form>
        </div>
    </div>
</body>
</html>