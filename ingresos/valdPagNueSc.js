$(document).ready(function () {
  const TIEMPO_ESPERA = 500;
  var timeoutId;
  // Inicializar campo valPen con formato de moneda
  var valPenInput = new AutoNumeric("#valPen", {
    currencySymbol: "$",
    decimalCharacter: ",",
    digitGroupSeparator: ".",
    minimumValue: "0.00",
    maximumValue: "99999999.99",
  });

  // Inicializar campo certApor con formato de moneda
var certAporInput = new AutoNumeric("#certApor", {
  currencySymbol: "$",
  decimalCharacter: ",",
  digitGroupSeparator: ".",
  minimumValue: "0.00",
  maximumValue: "99999999.99",
  readOnly: true,  // Hacer el campo de solo lectura
});

// Inicializar campo fondEst con formato de moneda
var fondEstInput = new AutoNumeric("#fondEst", {
  currencySymbol: "$",
  decimalCharacter: ",",
  digitGroupSeparator: ".",
  minimumValue: "0.00",
  maximumValue: "99999999.99",
  readOnly: true,  // Hacer el campo de solo lectura
});

// Inicializar campo gastAdm con formato de moneda
var gastAdmInput = new AutoNumeric("#gastAdm", {
  currencySymbol: "$",
  decimalCharacter: ",",
  digitGroupSeparator: ".",
  minimumValue: "0.00",
  maximumValue: "99999999.99",
  readOnly: true,  // Hacer el campo de solo lectura
});

  // Función para mostrar mensajes de error en el campo valPag
  function mostrarErrorValPag(mensaje) {
    $("#mensajeValPagError").html(mensaje);
    mostrarPopoverValPag();
  }

  // Función para mostrar el popover en el campo valPag
  function mostrarPopoverValPag() {
    var mensaje = $("#mensajeValPagError").html().trim();

    if (mensaje !== "") {
      // Ocultar popover existente antes de crear uno nuevo
      ocultarPopoverValPag();

      // Agrega esta condición para evitar popover con mensaje vacío
      var popover = new bootstrap.Popover(document.getElementById("valPag"), {
        title: "Mensaje",
        content: mensaje,
        placement: "right",
      });

      popover.show();
      $("#mensajeValPagError").hide();

      setTimeout(function () {
        popover.hide();
      }, 3000);
    }
  }

  // Función para ocultar el popover en el campo valPag
  function ocultarPopoverValPag() {
    var popover = bootstrap.Popover.getInstance(
      document.getElementById("valPag")
    );
    if (popover) {
      popover.dispose(); // Eliminar el popover existente
      $("#valPag").popover("dispose"); // Alternativa para eliminar el popover
    }
  }

  //Fin funciones para calcular el monto sobrante con parámetros
  //Funciones para dato de busqueda
  function mostrarMensaje(elemento, mensaje) {
    if (mensaje.trim() !== "") {
      // Agrega esta línea para asegurarte de que el elemento esté visible
      $(elemento).show();

      var popover = new bootstrap.Popover(elemento, {
        title: "Mensaje",
        content: mensaje,
        placement: "right",
      });

      popover.show();

      setTimeout(function () {
        popover.hide();
      }, 3000);
    }
  }

  function mostrarErrorBuscar(mensaje) {
    mostrarMensaje("#mensajeBuscarError", mensaje);
    limpiarFormulario();
  }

  function manejarEntradaBusqueda() {
    var busqueda = $("#buscar").val();
    valPenInput.clear();
  
    if (busqueda.length >= 3) {
      $.ajax({
        type: "GET",
        url: "buscarNoSc.php",
        data: { buscar: busqueda },
        dataType: "json",
        success: function (data) {
          $("#sugerencias").empty();

          if (data.length > 0) {
            $.each(data, function (index, resultado) {
              var sugerenciaElement = $("<li>")
                .addClass("list-group-item sugerencia")
                .data("idSoc", resultado.pk_sc_id)
                .data("nombre", resultado.sc_nombre)
                .data("apellido", resultado.sc_apellido)
                .data("cedula", resultado.sc_cedula)
                .data("monto", resultado.pg_pend_monto)
                .data("cuentaVal", resultado.cta_sc_saldo)
                .data("tipoSocio", resultado.tp_dsc)
                .data("fondo", resultado.tp_val_fond_estr)
                .data("gastos", resultado.tp_val_gast_adm)
                .text(
                  resultado.sc_nombre +
                    " " +
                    resultado.sc_apellido +
                    " " +
                    resultado.sc_cedula
                );

              sugerenciaElement.on("click", function () {
                var datosSugerencia = $(this).data();
                var idSoc = datosSugerencia.idSoc;
                var nombre = datosSugerencia.nombre;
                var apellido = datosSugerencia.apellido;
                var cedula = datosSugerencia.cedula;
                var fechaActual = new Date().toLocaleDateString();
                var cuentaVal = datosSugerencia.cuentaVal;
                var tipoSocio = datosSugerencia.tipoSocio;
                var montoPendiente = datosSugerencia.monto;
                var fondo = datosSugerencia.fondo;
                var gastos = datosSugerencia.gastos;
                var certificados = montoPendiente-fondo-gastos;
                certificados = parseFloat(certificados.toFixed(2));
                $("#idSoc").val(idSoc);
                habilitarCampos();
                $("#nombre").val(nombre);
                $("#apellido").val(apellido);
                $("#cedula").val(cedula);
                var montoFormateado = parseFloat(montoPendiente);
                $("#tpSoc").val(tipoSocio);
                valPenInput.set(montoFormateado);
                certAporInput.set(certificados);
                fondEstInput.set(fondo);
                gastAdmInput.set(gastos);
                $("#fechaAct").val(fechaActual);
                $("#cuentaVal").val(cuentaVal);
                $("#valPag, #valSob").val("");
                $("#buscar").val("");
                $("#sugerencias").hide();
              });

              $("#sugerencias").append(sugerenciaElement);
            });

            $("#sugerencias").show();
          } else {
            var mensaje = "El dato no existe.";
            mostrarErrorBuscar(mensaje);
            limpiarFormulario();
          }
        },
        error: function (xhr, status, error) {
          console.log("Error en la solicitud AJAX:", status, error);
          alert("Error en la solicitud AJAX: " + status + " - " + error);
        },
      });
    } else {
      limpiarFormulario();
      $("#sugerencias").hide();
    }
  }

  function limpiarTemporizador() {
    clearTimeout(timeoutId);
  }

  // Agrega esta función para validar el formulario antes de enviarlo
  function validarFormulario() {
    if (montoDePago === 0) {
      var mensaje = "El monto a pagar debe ser mayor que cero.";
      console.log ('si llega aqui'+montoDePago);
      mostrarErrorValPag(mensaje);
      valPagInput.set(""); // Limpiar el campo valPag
      console.log (montoDePago);
      valSobInput.set(""); // Limpiar el campo valSob
      return false; // Evitar el envío del formulario
    }

    // Otras validaciones y lógica del formulario aquí

    return true; // Permitir el envío del formulario
  }

  // Modifica la configuración de eventos para incluir la validación antes de enviar el formulario
  function configurarEventos() {
    $("#valPag").on("input", function () {
      limpiarTemporizador();
      timeoutId = setTimeout(function () {
        calcularMontoSobrante(valPagInput.getNumber(), valPenInput.getNumber());
      }, TIEMPO_ESPERA);
    });

    $("#buscar").on("input", function () {
      manejarEntradaBusqueda();
    });

    // Agrega un evento para la validación antes de enviar el formulario
    $("#miFormulario").submit(function (event) {
      if (!validarFormulario()) {
        event.preventDefault(); // Evita el envío del formulario si la validación falla
      }
    });
  }

  // Configurar eventos al cargar el documento
  $(document).ready(function () {
    configurarEventos();
  });
});

// Función para limpiar todo el formulario
function limpiarFormulario() {
  $(
    "#idSoc, #nombre, #apellido, #cedula, #valPen, #fechaAct, #fechaVen, #cuentaVal, #valPag, #valSob"
  ).val("");
  deshabilitarCamposExcepto("buscar", "idSoc");
}

function deshabilitarCamposExcepto(exceptoCampo1, exceptoCampo2) {
  $("#miFormulario :input").each(function () {
    // Deshabilita todos los campos excepto los campos especificados
    var currentId = $(this).attr("id");
    if (currentId !== exceptoCampo1 && currentId !== exceptoCampo2) {
      $(this).prop("disabled", true);
    }
  });
}
function habilitarCampos() {
  // Verificar si el campo idSoc tiene datos
  if ($("#idSoc").val() !== "") {
    $("#miFormulario :input").prop("disabled", false);
  }
}
