<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Medicas</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;
        }

        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 0px;
            padding: 0px;
            background-color: transparent;
            border-collapse: collapse;

        }

        .table-bordered {
            border: 1px solid #c2cfd6;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table th,
        .table td {
            padding: 0.55rem;
            vertical-align: top;
            border-top: 1px solid #c2cfd6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #c2cfd6;
        }

        th,
        td {
            display: table-cell;

        }

        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .izquierda {
            float: left;
        }

        .derecha {
            float: right;
        }

        .indicacion {
            padding: 0px;
            margin: 0px;
        }
        img {
            position: absolute;
            width: 80px;
            height: 80px;
            margin: 10px;
        }
    </style>
</head>

<body>
        <div class="img">
        <img style="width: 115%;height: auto;margin: -50px -50px -50px -50px;padding: 0px;" src="data:image/png;base64,{{$imagen}}" alt="">
        </div>
        <br>
        <br>
        <br>
        <br>
    <div>
        <h3>CENTRO MEDICO SAN GERONIMO HUACHO <span class="derecha">{{now()}}</span></h3>
    </div>


    <div class="izquierda">
        <strong>Paciente:</strong> {{$Paciente->nombre}} {{$Paciente->apellido}} <br>
        <strong>Historia Clinina:</strong> {{$Paciente->nCitamed}}
    </div>
    <div class="derecha">
        <strong>Edad:</strong> {{$Paciente->edad}} <br>
        <strong>DNI:</strong> {{$Paciente->dni}}
    </div>
    <br>
    <br>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Indicaciones del Doctor</th>

                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($IndiDoctor as $indicacion)
                <tr>
                    <td class="indicacion">
                        <strong> Pastilla:</strong> {{$indicacion['medicamento']}} <br>
                        {!! strip_tags(BBCode::convertToHtml($indicacion['formingerir']),'<b><i><u>') !!} 
                    </td>

                    <td style="vertical-align: middle;text-align: center;font-size: 20px;">{{$indicacion['cantidad']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="izquierda">
            <strong>Medico:</strong> {{$nombreDoctor['nombre']}} {{$nombreDoctor['apellido']}} <br>
        </div>
        <br>
        <br>
        <br>
        <div class="derecha">
            <p>------------------------------------</p>
            <strong>Firma y Sello del Medico</strong>


        </div>

    </div>
</body>

</html>