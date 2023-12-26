
function validarFormulario() {
    // Obtener el valor del campo idSoc
    var idSocValue = document.getElementById("idSoc").value;

    // Verificar si el campo idSoc está vacío
    if (idSocValue.trim() === "") {
        // Mostrar un popover con el mensaje de error
        var mensajeErrorBuscar = "Debes buscar un socio para poder continuar.";
        mostrarPopover(mensajeErrorBuscar, document.getElementById("mensajeBuscarAlert"));

        // Evitar que el formulario se envíe
        return false;
    }

    // Obtener el valor del campo capital
    var capitalValue = document.getElementById("capital").value;

    // Convertir el valor a un número
    var capitalNumber = parseFloat(capitalValue.replace("$", ""));

    // Verificar si el capital es igual a 0
    if (capitalNumber === 0) {
        // Mostrar un popover con el mensaje de error
        var mensajeError = "El socio no mantiene saldo disponible en su cuenta.";
        mostrarPopover(mensajeError, document.getElementById("mensajeCapitalError"));

        // Evitar que el formulario se envíe
        return false;
    } else {
        // Si el capital no es 0 y el campo idSoc no está vacío, permitir el envío del formulario
        return true;
    }
}


function mostrarPopover(mensaje, elemento) {
    if (mensaje.trim() !== "") {
        var popover = new bootstrap.Popover(elemento, {
            title: "Mensaje",
            content: mensaje,
            placement: "right",
        });

        popover.show();

        setTimeout(function() {
            popover.hide();
        }, 3000);
    }
}
$(document).ready(function() {
    // Evento que se ejecuta cuando cambia el valor de tpLiqui
    $("#tpLiqui").change(function() {
        // Obtén el valor de tpLiqui
        var tpLiquiValue = $(this).val();
        console.log('Valor seleccionado:', tpLiquiValue);

        // Obtén el valor de idSoc
        var idSocValue = $("#idSoc").val();
        console.log('Valor de idSoc:', idSocValue);

        // Realiza la solicitud AJAX al servidor
        $.ajax({
            url: '../egresos/procesos.php',
            type: 'POST',
            data: {
                tpLiqui: tpLiquiValue,
                idSoc: idSocValue
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                // Actualiza los campos en tu formulario con los datos obtenidos
                $("#multas").val('$' + response.total_multas);
                $("#prest").val('$' + response.sql_prestamos);
                $("#totalR").val('$' + response.total_suma);
                $("#capital").val('$' + response.capital);
                $("#total").val('$' + response.total_pagar);
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la solicitud AJAX:', xhr, status, error);
            }
        });

    });
});