document.addEventListener("DOMContentLoaded", function() {
    function todosSeleccionados() {
        var checkboxes = document.querySelectorAll('input[name="doc_pres[]"]');
        return Array.from(checkboxes).every(function(checkbox) {
            return checkbox.checked;
        });
    }
    function actualizarCampos() {
        var checkboxesSeleccionados = todosSeleccionados();
        document.getElementById('cantPres').disabled = !checkboxesSeleccionados;
        document.getElementById('tipPrest').disabled = !checkboxesSeleccionados;
    }

    var checkboxes = document.querySelectorAll('input[name="doc_pres[]"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', actualizarCampos);
    });

    // Event listener al campo de cantidad de préstamo para validar el monto cuando cambie
var cantPresInput = document.getElementById('cantPres');
cantPresInput.addEventListener('input', function () {
    // Obtén el valor del campo cantPres
    var montoDeseado = cantPresInput.value;
    fetch('procePres.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cantPres=' + montoDeseado,
    })
    .then(response => response.json()) // Ahora esperamos un JSON en lugar de texto
    .then(resultados => {
        // Actualiza los elementos en el frontend con los resultados del servidor
        var tiemPagInput = document.getElementById('tiemPag');
        var rangIntInput = document.getElementById('rangInt');
        var idRango = document.getElementById('idRange');
        // Asigna los resultados a los campos de entrada
        if (resultados.length > 0) {
            var resultado = resultados[0]; // Tomamos el primer resultado (puedes ajustar según tu lógica)
            tiemPagInput.value = resultado.ran_plz_cuota;
            rangIntInput.value = resultado.ran_inte_tasa_inter;
            idRango.value = resultado.pk_ran_mont_id;
        } else {
            // Manejar el caso donde no hay resultados
            tiemPagInput.value = '';
            rangIntInput.value = '';
            idRango.value = '';
        }
    })
});
});

    
