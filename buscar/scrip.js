// Declarar suggestions en un ámbito global o accesible para ambas funciones
var suggestions = [];

function getSuggestions() {
  var searchTerm = document.getElementById("searchInput").value;

  // Verificar que la longitud del término de búsqueda sea al menos 3 caracteres
  if (searchTerm.length < 3) {
    // Ocultar la lista de sugerencias si no hay al menos 3 caracteres
    $("#sugerencias").hide();
    sugerenciasVisible = false;
    return;
  }

  // Realizar la llamada AJAX para obtener sugerencias
  $.ajax({
    type: "POST",
    url: "../buscar/search.php",
    data: {
      term: searchTerm,
    },
    dataType: "json",
    success: function (response) {
      suggestions = response; // Asignar las sugerencias al ámbito global
      var sugerenciasContainer = $("#sugerencias");
      // Limpiar las sugerencias anteriores
      sugerenciasContainer.empty();
      if (response.length > 0) {
        response.forEach(function (suggestion) {
          var suggestionElement = $("<div>")
            .text(
              suggestion.nombre +
                "-" +
                suggestion.apellido +
                "-" +
                suggestion.cedula +
                "-" +
                suggestion.idSoc
            )
            .addClass("sugerencia-item")
          sugerenciasContainer.append(suggestionElement);
        });

        // Mostrar la lista de sugerencias
        sugerenciasContainer.show();
        sugerenciasVisible = true;
      } else {
        // Si no hay resultados, ocultar la lista de sugerencias
        sugerenciasContainer.hide();
        sugerenciasVisible = false;
      }
    },
    error: function (xhr, status, error) {
      var mensajeError = "No existe ese registro";
      console.log(error);
      // Limpiar o ocultar el mensaje anterior
      document.getElementById("mensajeErrorBuscar").innerHTML = "";
      $("#mensajeErrorBuscar").popover("dispose"); // Dispose elimina el popover anterior si existe

      // Configurar el popover de Bootstrap
      $("#mensajeErrorBuscar").popover({
        title: "Mensaje",
        content: mensajeError,
        placement: "right",
        trigger: "manual",
      });
      // Mostrar el popover
      $("#mensajeErrorBuscar").popover("show");
      // Limpiar el campo de búsqueda
      document.getElementById("searchInput").value = "";
      // Ocultar el popover después de unos segundos (opcional)
      setTimeout(function () {
        $("#mensajeErrorBuscar").popover("hide");
      }, 3000);
    },
  });
}

function selectSuggestion(event) {
  if (!sugerenciasVisible) {
    return;
  }

  var selectedSuggestion = event.target.textContent.trim();
  var suggestion = suggestions.find(function (s) {
    return (
      s.nombre + "-" + s.apellido + "-" + s.cedula + "-" + s.idSoc ===
      selectedSuggestion
    );
  });

  document.getElementById("searchInput").value = selectedSuggestion;
  $("#sugerencias").hide();
  sugerenciasVisible = false;

  $.ajax({
    type: "POST",
    url: "../buscar/guardar_dato.php",
    data: { dato_seleccionado: JSON.stringify(suggestion) },
    dataType: "json",
    success: function (response) {
      location.reload();
    },
    error: function (xhr, status, error) {
      console.error("Error al intentar guardar el dato en la sesión:", error);
    },
  });
}
