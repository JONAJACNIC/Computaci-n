<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla de amortización</title>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>Número de pago</th>
        <th>Fecha de pago</th>
        <th>Capital</th>
        <th>Cuota capital</th>
        <th>Intereses</th>
        <th>Valor de la cuota</th>
        <th>Saldo capital</th>
      </tr>
    </thead>
    <tbody>
      {% for pago in pagos %}
        <tr>
          <td>{{ pago.numero_pago }}</td>
          <td>{{ pago.fecha_pago }}</td>
          <td>{{ pago.capital }}</td>
          <td>{{ pago.cuota_capital }}</td>
          <td>{{ pago.intereses }}</td>
          <td>{{ pago.valor_cuota }}</td>
          <td>{{ pago.saldo_capital }}</td>
        </tr>
      {% endfor %}
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6">Total</td>
        <td>{{ total_capital }}</td>
      </tr>
    </tfoot>
  </table>
</body>
</html>
