//Funciones para la cédula
function resetearAlertas() {
  $("#mensajeResultado").html("").hide();
  ocultarPopover();
}

// Función para mostrar mensajes de error
function mostrarError(mensaje) {
  $("#mensajeResultado").html(mensaje);
  mostrarPopover(mensaje);
  resetearAlertas(); // Resetea las alertas
}

// Función para mostrar mensajes de resultado
function mostrarMensajeResultado(mensaje) {
  $("#mensajeResultado").html(mensaje);
  mostrarPopover(mensaje);
  resetearAlertas(); // Resetea las alertas
}

// Función principal de validación
function verificarCedula() {
  resetearAlertas(); // Resetear las alertas antes de comenzar la validación
  var cedula = $("#cedula").val();
  if (cedula.length === 0) {
    // Si la longitud de la cédula es 0, mantén los campos deshabilitados
    deshabilitarCamposExcepto("cedula");
    return true;
  }

  if (validarLongitud(cedula) && validarDigitoVerificador(cedula)) {
    validarEnBaseDeDatos(cedula);
    return true;
  }

  // Si la validación falla, mantén los campos deshabilitados y marca el campo como editado
  deshabilitarCamposExcepto("cedula");
  $("#cedula").data("edited", true);
  return false;
}
// Función para validar la longitud de la cédula
function validarLongitud(cedula) {
  if (cedula.length !== 10) {
    mostrarError("La cédula debe tener 10 dígitos.");
    return false;
  }
  return true;
}
// Función para validar el dígito verificador
function validarDigitoVerificador(cedula) {
  var digitoVerificador = parseInt(cedula.charAt(9));
  var cedulaSinDigitoVerificador = cedula.substring(0, 9);
  var suma = 0;

  for (var i = 0; i < 9; i++) {
    var digito = parseInt(cedulaSinDigitoVerificador.charAt(i));
    var multiplicador = i % 2 === 0 ? 2 : 1;
    var producto = digito * multiplicador;
    suma += producto > 9 ? producto - 9 : producto;
  }

  var residuo = suma % 10;
  var digitoVerificadorEsperado = residuo !== 0 ? 10 - residuo : 0;

  if (digitoVerificador !== digitoVerificadorEsperado) {
    mostrarError("La cédula no es válida.");
    return false;
  }

  return true;
}

// Función para realizar la validación en la base de datos
function validarEnBaseDeDatos(cedula) {
  $.ajax({
    type: "POST",
    url: "valdSc.php",
    data: { action: "verificarCedulaExistente", cedula: cedula },
    dataType: "json",
    success: function (response) {
      if ("message" in response) {
        if (
          response.message ===
          "Ya es socio. Por favor, verifica la información."
        ) {
          // Es socio, deshabilita campos y marca el campo como editado
          deshabilitarCamposExcepto("cedula");
          $("#cedula").data("edited", true);
        } else {
          // La cédula no existe en la base de datos, habilita campos
          habilitarCamposExcepto("cedula");
          $("#cedula").data("edited", true);
        }

        mostrarMensajeResultado(response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", status, error);
    },
  });
}

// Función para mostrar el popover
function mostrarPopover(mensaje) {
  if (mensaje.trim() !== "") {
    // Agrega esta condición para evitar popover con mensaje vacío
    var popover = new bootstrap.Popover(document.getElementById("cedula"), {
      title: "Mensaje",
      content: mensaje,
      placement: "right",
    });

    popover.show();
    $("#mensajeResultado").hide();

    setTimeout(function () {
      popover.hide();
    }, 3000);
  }
}

// Agregar un evento al campo de cédula para limpiar el mensaje al editar
$("#cedula").on("input", function () {
  resetearAlertas();
});

// Agregar un evento al campo de cédula para resetear las alertas al interactuar
$("#cedula").on("focus", function () {
  resetearAlertas();
});

// Agregar un evento al campo de cédula para verificar al perder el foco
$("#cedula").on("blur", function () {
  verificarCedula();
});
/// Agregar un evento para manejar la edición del campo de cédula
$("#cedula").on("input", function () {
  // Si el campo de cédula se está editando nuevamente, deshabilita los campos
  if ($(this).data("edited")) {
    deshabilitarCamposExcepto("cedula");
    $(this).data("edited", false);
  }
});

function ocultarPopover() {
  var popover = new bootstrap.Popover(document.getElementById("cedula"));
  popover.hide();
}

// Función para resetear las alertas y contenido relacionado al mensaje
function resetearAlertas() {
  $("#mensajeError").html("").hide();
  ocultarPopover();
}

// Función para mostrar mensajes de error
function mostrarError(mensaje) {
  $("#mensajeResultado").html(mensaje);
  mostrarPopover(mensaje);
  resetearAlertas(); // Resetea las alertas
}

// Función para mostrar mensajes de resultado
function mostrarMensajeResultado(mensaje) {
  $("#mensajeResultado").html(mensaje);
  mostrarPopover(mensaje);
  resetearAlertas(); // Resetea las alertas
}

function deshabilitarCamposExcepto(exceptoCampo) {
  $("#miFormulario :input").each(function () {
    // Deshabilita todos los campos excepto el campo especificado
    if ($(this).attr("id") !== exceptoCampo) {
      $(this).prop("disabled", true);
    }
  });
}

function habilitarCamposExcepto(exceptoCampo) {
  $("#miFormulario :input").each(function () {
    // Habilita todos los campos excepto el campo especificado
    if ($(this).attr("id") !== exceptoCampo) {
      $(this).prop("disabled", false);
    }
  });
}
// fin funciones para la cedula

// Funciónes para validar la fecha de nacimiento
function validarEdad() {
  resetearAlertasF(); // Resetear las alertas antes de comenzar la validación

  var fechaNacimiento = document.getElementById("fechaNac").value;
  var fechaNac = new Date(fechaNacimiento);
  var hoy = new Date();
  var edad = hoy.getFullYear() - fechaNac.getFullYear();
  var mes = hoy.getMonth() - fechaNac.getMonth();

  if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
    edad--;
  }

  var mensajeError = "Debe ser mayor de edad.";
  if (edad < 18) {
    mostrarErrorF(mensajeError);
    return false;
  }
  return true;
}

// Función para mostrar el popover
function mostrarPopoverF(mensaje) {
  var popover = new bootstrap.Popover(document.getElementById("fechaNac"), {
    title: "Mensaje",
    content: mensaje,
    placement: "right",
  });

  popover.show();

  setTimeout(function () {
    popover.hide();
  }, 3000);
}

// Agregar un evento al campo de fecha de nacimiento para limpiar el mensaje al cambiar la fecha
$("#fechaNac").on("change", function () {
  resetearAlertasF();
});

// Agregar un evento al campo de fecha de nacimiento para resetear las alertas al interactuar
$("#fechaNac").on("focus", function () {
  resetearAlertasF();
});

// Agregar un evento al campo de fecha de nacimiento para verificar al perder el foco
$("#fechaNac").on("blur", function () {
  validarEdad();
});

function ocultarPopoverF() {
  var popover = new bootstrap.Popover(document.getElementById("fechaNac"));
  popover.hide();
}

// Función para resetear las alertas y contenido relacionado al mensaje
function resetearAlertasF() {
  $("#mensajeError").html("").hide();
  ocultarPopoverF();
}

// Función para mostrar mensajes de error
function mostrarErrorF(mensaje) {
  $("#mensajeError").html(mensaje);
  mostrarPopoverF(mensaje);
  resetearAlertasF(); // Resetea las alertas
}

// Función para mostrar mensajes de resultado
function mostrarMensajeResultadoF(mensaje) {
  $("#mensajeError").html(mensaje);
  mostrarPopoverF(mensaje);
  resetearAlertasF(); // Resetea las alertas
}

//fin fuciones fecha de nacimiento

// Función para el correo
function verificarCorreo() {
  resetearAlertasCorreo();

  var correo = $("#correo").val();

  if (correo.length === 0) {
  console.log("Correo vacío, permitiendo envío.");
    // El campo de correo está vacío, no hacemos ninguna validación
    return true;
  }

  if (!validarCorreo(correo)) {
    var mensajeError =
      "El correo electrónico no es válido. Asegúrate de incluir el símbolo '@'.";
    mostrarErrorCorreo(mensajeError);
    return false;
  }
  return true;
}

// Función para validar el correo
function validarCorreo(correo) {
  // Expresión regular para validar el formato del correo electrónico
  var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Verificar que el correo contenga "@" y cumpla con el formato
  return correo.includes("@") && regexCorreo.test(correo);
}

// Función para mostrar el popover de correo
function mostrarPopoverCorreo(mensaje) {
  var popoverCorreo = new bootstrap.Popover(document.getElementById("correo"), {
    title: "Mensaje",
    content: mensaje,
    placement: "right",
  });

  popoverCorreo.show();

  setTimeout(function () {
    popoverCorreo.hide();
  }, 3000);
}

// Función para ocultar el popover de correo
function ocultarPopoverCorreo() {
  var popoverCorreo = new bootstrap.Popover(document.getElementById("correo"));
  popoverCorreo.hide();
}

// Función para mostrar mensajes de error de correo
function mostrarErrorCorreo(mensaje) {
  $("#mensajeErrorCorreo").html(mensaje);
  mostrarPopoverCorreo(mensaje);
  resetearAlertasCorreo(); // Resetea las alertas
}
// Función para resetear las alertas y contenido relacionado al mensaje de correo
function resetearAlertasCorreo() {
  $("#mensajeErrorCorreo").html("").hide();
  ocultarPopoverCorreo();
}

// Función para mostrar mensajes de resultado
function mostrarMensajeResultadoCorreo(mensaje) {
  $("#mensajeErrorCorreo").html(mensaje);
  mostrarPopoverCorreo(mensaje);
  resetearAlertasCorreo(); // Resetea las alertas
}

// Agregar eventos al campo de correo
$("#correo").on("blur", function () {
  verificarCorreo();
});

//fin funciones para correo

function soloNumeros(e) {
  // Obtener el evento y la tecla presionada
  var evento = e || window.event;
  var codigoCaracter = evento.charCode || evento.keyCode;

  // Permitir solo números
  if (codigoCaracter < 48 || codigoCaracter > 57) {
    if (evento.preventDefault) {
      evento.preventDefault();
    } else {
      evento.returnValue = false;
    }
  }
}

function soloLetras(event) {
  // Obtener el evento y la tecla presionada
  var evento = event || window.event;
  var codigoCaracter = evento.charCode || evento.keyCode;

  // Permitir solo letras, incluyendo letras con tilde y diacríticos
  if (
    (codigoCaracter >= 65 && codigoCaracter <= 90) || // Mayúsculas
    (codigoCaracter >= 97 && codigoCaracter <= 122) || // Minúsculas
    codigoCaracter === 32 || // Espacio en blanco
    (codigoCaracter >= 192 && codigoCaracter <= 687) || // Letras con tilde y diacríticos
    codigoCaracter === 775 // Acento diacrítico
  ) {
    return true; // Permitir la tecla
  } else {
    if (evento.preventDefault) {
      evento.preventDefault();
    } else {
      evento.returnValue = false;
    }
  }
}

function convertirMayusculas(idCampo) {
  var inputCampo = document.getElementById(idCampo);
  inputCampo.value = inputCampo.value.toUpperCase();
}

// Agrega un manejador de eventos al formulario para el evento submit
$("#miFormulario").on("submit", function (event) {
  // Realiza la validación de los campos
  var cedulaValida = verificarCedula();
  var fechaValida = validarEdad();
  var correoValido = verificarCorreo(); // Agrega la validación del correo
  // Si alguno de los campos no es válido, cancela el envío del formulario
  if (!cedulaValida || !fechaValida || !correoValido) {
    // Evita que el formulario se envíe
    event.preventDefault();
  }
});
