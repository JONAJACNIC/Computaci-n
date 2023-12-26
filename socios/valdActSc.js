function habilitarEdicion(idCampo, mensaje) {
  // Verificar si el campo de cédula está vacío
  if ($("#cedula").val() === "") {
    mostrarPopoverGeneral("Primero debes buscar un socio para continuar");
    return;
  }

  $("#" + idCampo).prop("readonly", false);

  // Mostrar un popover con el mensaje
  $("#" + idCampo).popover({
    title: "Mensaje",
    content: mensaje,
    placement: "top",
    trigger: "manual",
  });

  $("#" + idCampo).popover("show");

  // Ocultar el popover después de unos segundos (opcional)
  setTimeout(function () {
    $("#" + idCampo).popover("hide");
  }, 3000); // 3000 milisegundos = 3 segundos
}

function mostrarPopoverGeneral(mensaje) {
  // Mostrar un popover con el mensaje general en el elemento con ID "mensajeBuscarAlert"
  $("#mensajeBuscarAlert").popover({
    title: "Mensaje",
    content: mensaje,
    placement: "top",
    trigger: "manual",
  });

  $("#mensajeBuscarAlert").popover("show");

  // Ocultar el popover después de unos segundos (opcional)
  setTimeout(function () {
    $("#mensajeBuscarAlert").popover("hide");
  }, 3000); // 3000 milisegundos = 3 segundos
}

function validarFormulario() {
  // Obtener el valor del campo idSoc
  var idSocValue = document.getElementById("idSoc").value;

  // Verificar si el campo idSoc está vacío
  if (idSocValue.trim() === "") {
    // Mostrar un popover con el mensaje de error
    var mensajeErrorBuscar = "Debes buscar un socio para poder continuar.";
    mostrarPopoverGeneral(mensajeErrorBuscar);

    // Evitar que el formulario se envíe
    return false;
  }
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

// Función para validar el correo
function verificarCorreo() {
  resetearAlertasCorreo();

  var correo = $("#correo").val();

  if (correo.length === 0) {
    return;
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

function convertirMayusculas(idCampo) {
    var inputCampo = document.getElementById(idCampo);
    inputCampo.value = inputCampo.value.toUpperCase();
  }

  
// Agrega un manejador de eventos al formulario para el evento submit
$("#miFormulario").on("submit", function (event) {
  // Realiza la validación de los campos
  var fechaValida = validarEdad();
  var correoValido = verificarCorreo($("#correo").val());
  // Si alguno de los campos no es válido, cancela el envío del formulario
  if (!fechaValida || !correoValido) {
    // Evita que el formulario se envíe
    event.preventDefault();
  }
});
