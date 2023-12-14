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

  // Inicializar campo valPag con formato de moneda
  var valPagInput = new AutoNumeric("#valPag", {
    currencySymbol: "$",
    decimalCharacter: ",",
    digitGroupSeparator: ".",
    minimumValue: "0.00",
    maximumValue: "99999999.99",
  });
  // Inicializar campo valSob con formato de moneda
  var valSobInput = new AutoNumeric("#valSob", {
    currencySymbol: "$",
    decimalCharacter: ",",
    digitGroupSeparator: ".",
    minimumValue: "0.00",
    maximumValue: "99999999.99", // Limitar el formato al monto pendiente
  });

  // Función para calcular el monto sobrante con parámetros
  // Función para calcular el monto sobrante con parámetros
  function calcularMontoSobrante(montoDePago, montoFormateado) {
    // Verificar que el monto a pagar no sea cero
    if (montoDePago <= 0) {
      // Mostrar mensaje de error en lugar de la alerta
      var mensaje = "El monto a pagar debe ser mayor que cero.";
      mostrarErrorValPag(mensaje);
      valSobInput.set(""); // Limpiar el campo valSob
      return;
    }

    // Verificar que los montos no estén vacíos
    if (!isNaN(montoFormateado) && !isNaN(montoDePago)) {
      // Calcular el monto sobrante
      var montoSobrante = montoFormateado - montoDePago;

      // Asegurarse de que montoSobrante sea un número válido
      if (!isNaN(montoSobrante)) {
        // Actualizar el campo valSob con el formato deseado
        valSobInput.set(montoSobrante);
        ocultarPopoverValPag(); // Ocultar el popover en caso de que se haya mostrado anteriormente
      } else {
        console.error("El monto sobrante no es un número válido.");
      }
    } else {
      // Si alguno de los montos está vacío, puedes manejarlo de acuerdo a tus necesidades
      console.error("Uno de los montos está vacío.");
    }
  }

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
    valSobInput.clear();
    valPenInput.clear();
    valPagInput.clear();
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
                .data("fechaVen", resultado.pg_pend_fech_ven)
                .data("cuentaVal", resultado.cta_sc_saldo)
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
                var montoPendiente = datosSugerencia.monto;
                var fechaActual = new Date().toLocaleDateString();
                var fechaVen = datosSugerencia.fechaVen;
                var cuentaVal = datosSugerencia.cuentaVal;
                var fechaVenFormateada = new Date(fechaVen).toLocaleDateString(
                  "es-ES"
                );

                $("#idSoc").val(idSoc);
                habilitarCampos();

                $("#nombre").val(nombre);
                $("#apellido").val(apellido);
                $("#cedula").val(cedula);

                var montoFormateado = parseFloat(montoPendiente);
                valPenInput.set(montoFormateado);

                $("#fechaAct").val(fechaActual);
                $("#fechaVen").val(fechaVenFormateada);
                $("#cuentaVal").val(cuentaVal);
                $("#valPag, #valSob").val("");

                $("#buscar").val("");
                $("#sugerencias").hide();

                valPagInput.update({
                  maximumValue: montoFormateado.toFixed(2),
                });
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
