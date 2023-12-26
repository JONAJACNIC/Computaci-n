document.addEventListener("DOMContentLoaded", function () {
  // Obtener todos los checkboxes por su nombre
  var checkboxes = document.getElementsByName("Pago[]");

  // Variable para almacenar el índice del último checkbox seleccionado
  var ultimoSeleccionado = -1;

  // Agregar un evento de clic a cada checkbox
  checkboxes.forEach(function (checkbox, index) {
    checkbox.addEventListener("change", function () {
      // Verificar si el checkbox se seleccionó
      if (checkbox.checked) {
        // Verificar si se está seleccionando en orden
        if (index === ultimoSeleccionado + 1) {
          // Actualizar el índice del último checkbox seleccionado
          ultimoSeleccionado = index;
          // Calcular el total al seleccionar/deseleccionar un checkbox
          calcularTotal();
        } else {
          // Desmarcar el checkbox si no se selecciona en orden
          checkbox.checked = false;
        }
      } else {
        // Actualizar el índice del último checkbox seleccionado al deseleccionar
        ultimoSeleccionado = index - 1;
        // Calcular el total al seleccionar/deseleccionar un checkbox
        calcularTotal();
      }

      // Actualizar las fechas seleccionadas en el campo oculto
      actualizarClavesPrimariasSeleccionadas();
    });
  });

 
  function calcularTotal() {
    // Obtener todos los checkboxes seleccionados
    var checkboxesSeleccionados = document.querySelectorAll('input[name="Pago[]"]:checked');

    // Reiniciar las variables antes de calcular
    var sumaCert = 0;
    var sumaFond = 0;
    var sumaTotal = 0;

    // Iterar sobre los checkboxes seleccionados
    checkboxesSeleccionados.forEach(function (checkbox) {
        // Obtener la fila correspondiente a este checkbox
        var fila = checkbox.closest("tr");
        // Obtener el valor de la columna "Detalle Certificado" de la fila
        var detalleCert = parseFloat(fila.cells[0].textContent);
        var detalleFond = parseFloat(fila.cells[1].textContent);
        var detalleAport = parseFloat(fila.cells[3].textContent);
        // Sumar el valor total de certificados
        sumaCert += detalleCert;
        // Sumar el valor total de fondo estratégico
        sumaFond += detalleFond;
        // Sumar el valor total del aporte
        sumaTotal += detalleAport;
    });

    // Obtener el valor ingresado en el campo "adiCert"
    var adiCertValor = parseFloat(document.getElementById("adiCert").value) || 0;
    // Obtener el valor ingresado en el campo "adiFond"
    var adiFondValor = parseFloat(document.getElementById("adiFond").value) || 0;

    // Validar si hay datos en adiCert o adiFond antes de sumarlos al total
    if (adiCertValor !== 0 || adiFondValor !== 0) {
        // Sumar el valor adicional de certificados
        sumaCert += adiCertValor;

        // Sumar el valor adicional de fondo estratégico
        sumaFond += adiFondValor;

        // Sumar el valor total de los campos adicionales a la variable sumaTotal
        sumaTotal += adiCertValor + adiFondValor;
    }

    // Actualizar el valor del input "totFond"
    document.getElementById("totFond").value = sumaFond.toFixed(2);
    // Actualizar el valor del input "totCert"
    document.getElementById("totCert").value = sumaCert.toFixed(2);
    // Actualizar el valor del input "total"
    document.getElementById("total").value = sumaTotal.toFixed(2);
}

  

  function actualizarClavesPrimariasSeleccionadas() {
    // Obtener todas las claves primarias seleccionadas
    var clavesPrimariasSeleccionadas = obtenerClavesPrimariasSeleccionadas();

    console.log(
      "Claves Primarias Seleccionadas:",
      clavesPrimariasSeleccionadas
    );

    // Actualizar el valor del campo oculto con las claves primarias seleccionadas
    document.getElementById("clavesPrimariasSeleccionadas").value =
      clavesPrimariasSeleccionadas.join(",");
  }

  function obtenerClavesPrimariasSeleccionadas() {
    var clavesPrimarias = [];
    var checkboxesSeleccionados = document.querySelectorAll(
      'input[name="Pago[]"]:checked'
    );

    checkboxesSeleccionados.forEach(function (checkbox) {
      var fila = checkbox.closest("tr");
      var clavePrimaria = fila.getAttribute("data-pk_aprt_id");
      clavesPrimarias.push(clavePrimaria);
    });

    return clavesPrimarias;
  }
});

function agregarCamposPagoAdicional() {
  // Mostrar los campos adicionales
  document.getElementById("containerCertificados").classList.remove("d-none");
  document
    .getElementById("containerFondoEstrategico")
    .classList.remove("d-none");
  // Asegurarse de que los campos se muestren como flex
  document.getElementById("containerCertificados").classList.add("d-flex");
  document.getElementById("containerFondoEstrategico").classList.add("d-flex");
}

$(document).ready(function () {
  // Evento de pérdida de foco para el campo de Certificados
  $("#adiCert").on("blur", function () {
    var value = $(this).val();
    // Verificar si el valor es un múltiplo de 20
    if (value % 20 !== 0) {
      alert("El valor de Certificados debe ser un múltiplo de 20");
      // Puedes reiniciar el valor a un múltiplo de 20 o tomar alguna otra acción
      $(this).val("");
    }
  });

  // Evento de pérdida de foco para el campo de Fondo Estratégico
  $("#adiFond").on("blur", function () {
    var value = $(this).val();
    // Verificar si el valor es un múltiplo de 1
    if (value % 1 !== 0) {
      alert("El valor de Fondo Estratégico debe ser un múltiplo de 1");
      // Puedes reiniciar el valor a un múltiplo de 1 o tomar alguna otra acción
      $(this).val("");
    }
  });
});
