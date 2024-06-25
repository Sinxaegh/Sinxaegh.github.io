function validarFormulario() {
    var respuestasCorrectas = {
        'respuesta1': 'correcta',
        'respuesta2': 'correcta',
        'respuesta3': 'correcta',
        'respuesta4': 'correcta',
        'respuesta5': 'correcta',
        'respuesta6': 'correcta',
        'respuesta7': 'correcta'
    };

    // Contadores para respuestas correctas e incorrectas
    var correctas = 0;
    var incorrectas = 0;

    // Iterar sobre las respuestas
    for (var pregunta in respuestasCorrectas) {
        var opciones = document.getElementsByName(pregunta);
        var respondida = false;
        for (var i = 0; i < opciones.length; i++) {
            if (opciones[i].checked) {
                respondida = true;
                if (opciones[i].value === respuestasCorrectas[pregunta]) {
                    correctas++;
                } else {
                    incorrectas++;
                }
            }
        }
        if (!respondida) {
            incorrectas++;
        }
    }

    // Mostrar el resultado
    var resultado = document.getElementById('resultado');
    resultado.innerHTML = `Respuestas correctas: ${correctas}<br>Respuestas incorrectas: ${incorrectas}<br>`;

    // Mostrar mensaje de error o correcto
    if (incorrectas > 0) {
        resultado.innerHTML += "<div class='mensaje-error'>Algunas respuestas son incorrectas. Inténtalo de nuevo.</div>";
    } else {
        resultado.innerHTML += "<div class='mensaje-correcto'>¡Todas las respuestas son correctas!</div>";
    }
}

var TP = respuestasCorrectas.length;