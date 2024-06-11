<html>
<head>
  <meta charset="UTF-8">
  <style>
    body{
        font-family: sans-serif;
    }
    @page{
        margin: 120px 50px;
    }
    header {
        position: fixed;
        left: 0px;
        top: -100px;
        right: 0px;
        height: 100px;
        text-align: center;
    }
    header h3{
        margin: 10px 0px;
    }
    .text-center{
        text-align: center;
    }

    #content table{
        width: 100%;
    }

    #content table, th, td {
        border: .5px solid black;
        font-size: 9.5pt;
    }

    #content table thead th {
        text-align: center;
        font-weight: bold;
    }

    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }

    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }

    .cell-index{
        width: 30px;
        text-align: center;
    }

    .cell-barcode{
        padding: 4.5px;
        justify-content: center;
        display: flex;
    }

    .cell-code{
        width: 50px;
        padding-left: 10px;
    }

    .cell-horas{
        width: 75px;
    }
  </style>
<body>
  <header>
    <h3>CONTROL DE SEGURIDAD</h3>
    <p><b>Día: </b>{{$diaSemana}}. <b>Fecha:</b> {{$dia}} de {{$mes}} del {{$anio}}</p>
  </header>
  <footer>
    <table>
        <tr>
          <td></td>
          <td>
            <p class="page">
              Página
            </p>
          </td>
        </tr>
      </table>
  </footer>
  <div id="content">
    <table>
        <thead>
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">CÓD.BAR.</th>
                <th rowspan="2">CÓDIGO</th>
                <th colspan="2">MAÑANA</th>
                <th colspan="2">TARDE</th>
            </tr>
            <tr>
                <th>ENTRADA</th>
                <th>SALIDA</th>
                <th>ENTRADA</th>
                <th>SALIDA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empleados as $emp)
                <tr>
                    <td class="cell-index">{{$loop->index + 1}}</td>
                    <td class="text-center cell-barcode">
                        {!!$emp["qr"]!!}
                    </td>
                    <td class="cell-code">{{$emp["codigo"]}}</td>
                    <td class="cell-horas"></td>
                    <td class="cell-horas"></td>
                    <td class="cell-horas"></td>
                    <td class="cell-horas"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</body>
</html>
