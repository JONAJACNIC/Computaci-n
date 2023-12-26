<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="container-fluid">
        <h5 class="text-center"> Solimulador Préstamo</h5>
        <div class="card">
            <div class="card-header fw-bold">
            <div class="card-body">
            <div class="row mb-2">
        <form>
                <div>
                    <label for="capital">CAPITAL 
                        <input type="text" placeholder="Ingrese el monto" id="capital">
                    </label>
                </div>
                <div>
                    <label for="couta">CUOTAS 
                        <input type="text" placeholder="Ingrese las cuotas" id="couta">
                    </label>
                </div>
                <div>
                    <label for="interes" id="interes">INTERES: 1.5
                    </label>
                </div>
            </form>
        <button onclick="gen_table();">CALCULAR</button>
        </fieldset>
        <hr>
        <table class="table table-condensed table-bordered table-striped text-center tab">
            <thead>
                <tr>
                    <td>NRO</td>
                    <td>CAPITAL</td>
                    <td>INTERES</td>
                    <td>IMPORTE A PAGAR</td>
                </tr>
            </thead>
            <tbody id="tab">
            </tbody>
            <tfoot>
                <tr>
                    <td class="ft">TOTAL</td>
                    <td id="t1"></td>
                    <td id="t2"></td>
                    <td id="t3"></td>
                </tr>
            </tfoot>
            </table>
            </div>
            </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/jquery/dist/jquery.js "></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="simuladorPres.js"></script>
</body>

</html>
