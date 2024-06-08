<?php
if(isset($_POST['correo']) && isset($_POST['password'])) {
    $mail = $_POST["correo"];
    $pass = $_POST["password"];

    // Datos de conexión a la base de datos
    $host = 'localhost'; // El servidor donde se encuentra la base de datos
    $usuario = 'root'; // El nombre de usuario de la base de datos
    $contrasena = ''; // La contraseña de la base de datos
    $bd = 'pear'; // El nombre de la base de datos que deseas utilizar

    // Crear una conexión
    $conexion = new mysqli($host, $usuario, $contrasena, $bd);
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL para evitar la inyección de SQL
    $consulta = $conexion->prepare("SELECT Correo FROM peartabla WHERE Correo = ?");
    $consulta->bind_param("s", $mail);
    $consulta->execute();
    $consulta->store_result();

    // Verificar si el usuario existe
    if ($consulta->num_rows > 0) {
        http_response_code(401);
    } else {
        $consulta = $conexion->prepare("INSERT INTO peartabla (Correo, Pass) VALUES (?, ?)");
        $consulta->bind_param("ss", $mail, $pass);
        $consulta->execute();
    }

    // Cerrar la conexión
    $conexion->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registrate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="indexstyle.css">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>
<body>
    <div class="container">
        <div class="content">
            <script>
                function enviarDatos(correo, pass) {
                    var xhttp = new XMLHttpRequest();

                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4) {
                            var error = document.getElementById('mensaje-error');
                            if (this.status === 401) {
                                error.textContent = "Correo ya registrado";
                                error.style.display = "block";
                                error.style.color = "red";
                            } else {
                                error.textContent = "Registrado con éxito!";
                                error.style.display = "block";
                                error.style.color = "green";
                                window.location.href = "main.html";
                            }
                        }
                    };

                    xhttp.open("POST", "registro.php", true);
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhttp.send("correo=" + encodeURIComponent(correo) + "&password=" + encodeURIComponent(pass));
                }

                function validarFormulario() {
                    // Obtener los campos del formulario
                    var correo = document.getElementById("mail").value;
                    var correoconfirmacion = document.getElementById("mailconfirm").value;
                    var pass = document.getElementById("pass").value;
                    var passconfirmacion = document.getElementById("passconfirm").value;
                    var mensajeError = document.getElementById('mensaje-error');

                    // Verificar si algún campo está vacío
                    if (correo === "" || pass === "" || correoconfirmacion === "" || passconfirmacion === "") {
                        mensajeError.textContent = 'Por favor, completa todos los campos del formulario.';
                        mensajeError.style.display = "block";
                        mensajeError.style.color = "red";
                        return false; // Detener el envío del formulario
                    }

                    if (correo !== correoconfirmacion) {
                        mensajeError.textContent = 'Correo / Confirmación no son iguales';
                        mensajeError.style.display = "block";
                        mensajeError.style.color = "red";
                        return false;
                    }

                    if (pass !== passconfirmacion) {
                        mensajeError.textContent = 'Contraseña / Confirmación no son iguales';
                        mensajeError.style.display = "block";
                        mensajeError.style.color = "red";
                        return false;
                    }

                    // Expresión regular para validar el formato del correo electrónico
                    var formatoCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                    // Verificar si el correo electrónico tiene un formato válido
                    if (!formatoCorreo.test(correo)) {
                        mensajeError.textContent = 'El correo electrónico no es válido';
                        mensajeError.style.display = "block";
                        mensajeError.style.color = "red";
                        return false;
                    }

                    // Si pasa todas las validaciones, enviar los datos
                    enviarDatos(correo, pass);
                }
            </script>
            <form method="post" id="registro" onsubmit="return validarFormulario()">
                <label for="mail">Ingresa el correo electronico para registrarte:</label>
                <input type="email" id="mail" required>
                <label for="mailconfirm">Confirma el correo:</label>
                <input type="email" id="mailconfirm" required>
                <label for="pass">Ingresa la contraseña a la que estar&aacute; ligada la cuenta:</label>
                <input type="password" id="pass" required>
                <label for="passconfirm">Confirma la contraseña:</label>
                <input type="password" id="passconfirm" required><br>
                <span id="mensaje-error" style="color: red; display: none;"></span>
                <input type="submit" value="Confirmar Registro"><br>
            </form>
        </div>
    </div>
</body>
</html>