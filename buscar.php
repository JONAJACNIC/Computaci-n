<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buscar Socio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ajustes específicos para la búsqueda */
        .search-container {
            position: relative;
        }

        #sugerencias {
            display: none;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            border-radius: 8px;
            padding: 10px;
            z-index: 1000;
            background: whitesmoke;

        }

        #sugerencias div {
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        #sugerencias div:hover {
            background-color: #9c9393;
            /* Este es el cambio aquí */
        }

        #sugerencias::-webkit-scrollbar {
            width: 10px;
        }

        #sugerencias::-webkit-scrollbar-thumb {
            background-color: #ccc;
        }
    </style>
</head>

<body>
    <div class="col-md-3 d-flex align-items-center pb-1">
        <label for="searchInput" class="form-label me-1 mt-1">Buscar</label>
        <img src="iconos/magnifier-glass-icon.svg" alt="Icono de Editar" class="me-1 " style="width: 30px;">
        <input type="text" id="searchInput" name="searchInput" class="form-control w-75" oninput="getSuggestions()" autocomplete="off" />
        <p id="mensajeErrorBuscar"></p>
    </div>
    <div id="sugerencias" class="w-50 mt-5 ms-5" onclick="selectSuggestion(event)"></div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scrip.js"></script>


</body>

</html>