<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        button {
            background-color: #1e3a5e; /* Azul oscuro */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #152c4e; /* Tonos más oscuros al pasar el ratón */
        }
    </style>
</head>
<body>

<h2>Comprobantes</h2>

<button onclick="window.location.href='comproegreso.php'">Egreso Socios</button>
<button onclick="window.location.href='EgresoCaja.php'">Egresos Caja</button>

</body>
</html>
