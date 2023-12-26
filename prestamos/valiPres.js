document.addEventListener("DOMContentLoaded", function() {

    function deshabilitarCamposExcepto(exceptoCampo1, exceptoCampo2) {
        $("#miFormulario :input").each(function () {
            // Deshabilita todos los campos excepto los campos especificados
            var currentId = $(this).attr("id");
            if (currentId !== exceptoCampo1 && currentId !== exceptoCampo2) {
                $(this).prop("disabled", true);
            }
        });
    }
    
      
    function habilitarCamposExcepto(exceptoCampo) {
        // Verificar si el campo idSoc tiene datos
        if ($("#idSoc").val() !== "") {
          // Si idSoc tiene datos, habilitar todos los campos excepto el especificado
          $("#miFormulario :input").each(function () {
            if ($(this).attr("id") !== exceptoCampo) {
              $(this).prop("disabled", false);
            }
          });
        }
      }
    


    function todosSeleccionados() {
        var checkboxes = document.querySelectorAll('input[name="doc_pres[]"]');
        return Array.from(checkboxes).every(function (checkbox) {
            return checkbox.checked;
        });
    }

    function actualizarCampos() {
        var checkboxesSeleccionados = todosSeleccionados();
        var cantPresInput = document.getElementById('cantPres');
        var tipPrestSelect = document.getElementById('tipPrest');
        var tiemPagInput = document.getElementById('tiemPag');

        // Si hay checkboxes seleccionados, deshabilita la propiedad 'readonly'
        // Si no hay checkboxes seleccionados, habilita la propiedad 'readonly'
        cantPresInput.readOnly = !checkboxesSeleccionados;
        tipPrestSelect.disabled = !checkboxesSeleccionados;
        tiemPagInput.readOnly = !checkboxesSeleccionados;
    }

    var checkboxes = document.querySelectorAll('input[name="doc_pres[]"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', actualizarCampos);
    });

    // Llama a la función actualizarCampos al cargar la página para establecer el estado inicial
    actualizarCampos();

    var max_value;

    // Obtén el valor máximo al cargar la página
    fetch('../prestamos/procePres.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'getMaxValue=true',
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(result => {
            // Actualiza el placeholder del campo cantPres con el valor máximo
            max_value = result.max_value;
            document.getElementById('cantPres').placeholder = 'Valor máximo ' + result.max_value+'$';
        })
        .catch(error => {
            console.error('Error al obtener el valor máximo:', error);
            // Manejar el error de acuerdo a tus necesidades
        });

    // Event listener al campo de cantidad de préstamo para validar el monto cuando cambie
    var cantPresInput = document.getElementById('cantPres');
    cantPresInput.addEventListener('input', function () {
        // Obtén el valor del campo cantPres
        var montoDeseado = cantPresInput.value;
        console.log(max_value);        
        // Verifica si el montoDeseado excede 6000
        if (montoDeseado >= max_value) {
            alert('No puede exceder el valor máximo de ' + max_value + '$');
            cantPresInput.value="";            
            return; 
        }       

        // Obtén el valor del campo tiemPag
        fetch('procePres.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'cantPres=' + montoDeseado,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(resultados => {
                // Actualiza los elementos en el frontend con los resultados del servidor
            var tiemPagInput = document.getElementById('tiemPag');        
            var rangIntInput = document.getElementById('rangInt');
            var idRango = document.getElementById('idRange');  
            
            
            //limitar valor de ingreso hasta 6000
            //if(resultados.length > 0){
            //    cantPresInput.placeholder = 'valor maximo '+resultados[0].ran_mont_max;              
            //}

            // Asigna los resultados a los campos de entrada
            if (resultados.length > 0) {
                tiemPagInput.placeholder = 'Maximo '+resultados[0].ran_plz_ncto+ ' meses';
                rangIntInput.value = resultados[0].ran_inte_tasa_inter;
                idRango.value = resultados[0].pk_ran_mont_id;
                console.log(idRango+'no existe');
                
                var temporal = resultados[0].ran_plz_ncto; // Usa el valor máximo directamente
                //var temporal = tiemPagInput.value; // Guarda el valor en temporal
                console.log(temporal);
                limitarTiemPag(temporal); // Llama a la función y pasa temporal como argumento               
            } else {
                // Manejar el caso donde no hay resultados
                tiemPagInput.value = '';
                rangIntInput.value = '';
                idRango.value = '';
            }

            // Llama a la función limitarTiemPag cada vez que cambia el valor de tiemPag
    tiemPagInput.addEventListener('input', function() {
        limitarTiemPag(temporal); // Utiliza el valor temporal
    });
            })
            .catch(error => {
                console.error('Error:', error);
                // Manejar el error de acuerdo a tus necesidades
            });
    });


    function limitarTiemPag(temporal) {
        // Obtén los elementos del DOM
        var tiemPagInput = document.getElementById('tiemPag');
        console.log(temporal);
        console.log(tiemPagInput.value);
    
        // Verifica si tiemPag supera el valor de ranPlzNcuotas
        if (tiemPagInput.value !== '' && parseInt(tiemPagInput.value, 10) > temporal) {
            alert('No puede exceder el numero de meses ' + temporal);          
          
            
        }
    } 


    

}); 

$(document).ready(function() {
    // Variable para controlar si el modal ya ha sido abierto
    var modalAbierto = false;

    // Manejar el evento blur del campo cantPres
    $("#cantPres").blur(function() {
        // Obtener el valor del campo cantPres
        var cantPresValue = parseInt($(this).val());
        console.log(cantPresValue);
        // Obtener el valor del campo valCuenta
        var valCuentaValue = parseInt($("#valCuenta").val());
        console.log(valCuentaValue);
        // Verificar si el valor es el triple del valor en valCuenta
        if (cantPresValue > valCuentaValue * 3 && !modalAbierto) {
            // Abrir el modal solo si el valor es mayor que tres veces el valor de valCuenta y el modal no está abierto
            $("#mGarante").modal("show");
            modalAbierto = true; // Actualizar el estado del modal
        }
    });

    // Manejar el evento submit del formulario
    $("#miFormulario").submit(function(e) {
        // Obtener el valor del campo cantPres
        var cantPresValue = parseInt($("#cantPres").val());
        // Obtener el valor del campo valCuenta
        var valCuentaValue = parseInt($("#valCuenta").val());

        // Verificar si el valor es mayor que tres veces el valor de valCuenta y el modal no está abierto
        if (cantPresValue > valCuentaValue * 3 && !modalAbierto) {
            // Abrir el modal solo si el valor es mayor que tres veces el valor de valCuenta y el modal no está abierto
            e.preventDefault(); // Evitar que se envíe el formulario
            $("#mGarante").modal("show");
            modalAbierto = true; // Actualizar el estado del modal
        }
    });
});

// script Garante
$(document).ready(function() {
    $('#cedulam').on('blur', function() {
        // Restaurar el estado del modal al cerrar el modal
        $("#mGarante").on("hidden.bs.modal", function () {
            modalAbierto = false;
        });

        var cedula = $(this).val();

        // Realiza la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: '../prestamos/ConsultaGarante.php',
            data: {
                cedula: cedula
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    // Maneja el error si lo hay
                    $('#mensajeResultado').text(response.error);
                } else {
                    // Rellena los campos con los datos del garante
                    $('#nombreG').val(response.nombre);
                    $('#apellidoG').val(response.apellido);
                    $('#idSocio').val(response.id);
                    $('#valG').val(response.cta_saldo);
                    valG
                    var con;
                    con = response.cta_saldo;
                    console.log('llega aqui:' + con);
                    // Verifica el saldo del garante
                    if (response.cta_saldo === 0) {
                        alert('El garante no puede tener un saldo de 0. No puede ser garante.');
                        // También puedes desactivar el botón de enviar formulario u otras acciones aquí
                    }

                    $('#mensajeResultado').text('');
                }
            },
            error: function() {
                // Maneja los errores de la solicitud AJAX
                $('#mensajeResultado').text('Error en la solicitud AJAX.');
            }
        });
    });
});


//enviar modal 

// Espera a que el documento esté listo
$(document).ready(function() {
    // Maneja el clic en el botón "Aceptar" del modal
    $('#mGarante').on('click', '#btnAceptar', function() {
        // Captura los valores del modal
        var cedulaGarante = $('#cedulam').val();
        var nombreGarante = $('#nombreG').val();
        var apellidoGarante = $('#apellidoG').val();

        // Inserta los valores en el formulario
        $('#cedulaGaranteInput').val(cedulaGarante);
        $('#nombreGaranteInput').val(nombreGarante);
        $('#apellidoGaranteInput').val(apellidoGarante);

        // Muestra la sección de datos del garante
        $('#datosGaranteContainer').show();

        // Cierra el modal
        $('#mGarante').modal('hide');
    });
});