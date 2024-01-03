$(document).ready(function() {
    // Mostrar el modal al hacer clic en el botón Desembolso
    $('.btnDesembolso').on('click', function() {
        var idLiquidacion = $(this).data('id');
        // Configurar el modal para enviar el ID de la liquidación
        var egreVal = parseFloat($(this).closest('tr').find('td:eq(5)').text()); // Obtener el valor desde la columna correspondiente
var tipoEgreso = $(this).closest('tr').find('td:eq(4)').text(); // Obtener el tipo de egreso desde la columna correspondiente

// Realizar las validaciones
var mensajeValidacion = '';

// Validar si egreVal es mayor que el saldo en la cuenta
if (egreVal > parseFloat($('#tpCuenta').val())) {
    mensajeValidacion = 'No hay fondos suficientes en la caja.';
}

// Validar si egreVal es mayor que el saldo en la cuenta y tipoEgreso es 'Fallecimiento'
if (egreVal > parseFloat($('#tpCuenta').val()) && tipoEgreso === 'Fallecimiento') {
    mensajeValidacion = 'Fondos de la caja no disponibles. Puede tomarse de la cuenta de gastos de desgravamen.';
}

// Mostrar un mensaje de validación si está presente
if (mensajeValidacion !== '') {
    alert(mensajeValidacion);
    return;
}

        $('#btnConfirmarDesembolso').data('idLiquidacion', idLiquidacion);
        // Configurar el mensaje del modal
        $('#confirmModal .modal-body').text('¿Estás seguro de realizar el desembolso para la liquidación N° ' + idLiquidacion + '?');
        // Mostrar el modal
        $('#confirmModal').modal('show');
    });
    // Manejar el clic en el botón Sí en el modal
    $('#btnConfirmarDesembolso').on('click', function() {
        var idLiquidacion = $(this).data('idLiquidacion');
        // Obtener la fecha del campo fechaDes
        var fechaDes = $('#fechaDes').val();
// Almacenar la fecha global
fechaDesGlobal = fechaDes;
        // Enviar la solicitud AJAX para realizar el desembolso
        $.ajax({
            type: 'POST',
            url: 'DesemLiquidaciones.php', // Reemplaza 'tu_script.php' con la ruta correcta a tu script PHP
            data: {
                btnDesembolso: true,
                idLiquidacion: idLiquidacion,
                fechaDes: fechaDesGlobal
            },
            dataType: 'json',
            success: function(response) {
                // Manejar la respuesta del servidor
                if (response.success) {
                    alert(response.message); // Puedes usar una modalidad diferente para mostrar mensajes
                    // Eliminar la fila de la tabla
                    $('#row_' + idLiquidacion).remove();
                } else {
                    alert('Error al realizar el desembolso.');
                }
                // Cerrar el modal después de la respuesta del servidor
                $('#confirmModal').modal('hide');
            },
            error: function() {
                alert('Error en la solicitud AJAX.');
            }
        });
    });
});

function sumaLiqui() {
    var checkboxes = document.querySelectorAll(".check-item");
    var totalInput = document.getElementById("valTot");

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            // Recalcular la suma al cambiar un checkbox
            var currentSum = 0;

            checkboxes.forEach(function(cb) {
                if (cb.checked) {
                    currentSum += parseFloat(cb.dataset.valor);
                }
            });

            totalInput.value = currentSum;
        });
    });
}

document.addEventListener("DOMContentLoaded", sumaLiqui);