<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <style>
        html,
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.975rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 34px;
            color: black;
        }

        .links>strong {
            color: black;
            padding: 0 25px;
            font-size: 13px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-top: 30px;
            margin-bottom: 0px;
        }

        img {
            position: absolute;
            width: 80px;
            height: 80px;
            margin: 10px;
        }

        .consulta {
            padding: -10px 15px;
            color: black;
            font-family: 'Times New Roman', Times, serif;
        }

        p {
            position: relative;
            margin-top: -15px;
            display: inline-block;
        }

        h4 {
            text-decoration: #636b6f;
        }

        ul {
            display: block;
            margin: 0px;

        }

        li {
            display: inline-block;
            margin: 0 10px;
        }

        /* Tabla */
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
        .indicacion{
            padding: 0px;
            margin: 0px;

        }
    </style>
</head>

<body>
    <div class="img">
        <img style="width: 100%;height: auto;position: absolute ;margin: 0px;" src="data:image/png;base64,{{$imagen}}" alt="">
    </div>
        <br>
        <br>
        <br>
        <br>
        <br>
    @foreach($historiaM as $hpaciente)
    <div class="content">
        <div class="title m-b-md">
            Historia Clinica:{{$hpaciente['nCitamed']}} <br>
        </div>
        <p style="margin: 0 300px">Fecha Creación: {{$hpaciente['fecha_creacion']}}</p>
    </div>

    <div class="links">
        <strong >Nombre del Paciente : {{$hpaciente['nombre']}} {{$hpaciente['apellido']}} </strong> <strong>DNI: {{$hpaciente['dni']}} </strong> <br>
        <strong  > Sexo: {{$hpaciente['sexo']}} </strong>
        <strong  > Edad : {{$hpaciente['edad']}} años</strong>
        <strong  > Fecha Nacimiento : {{$hpaciente['fecha_nacimiento']}}</strong> <br>
        <strong  > Dirección :{{$hpaciente['direccion']}}</strong>
        <strong  > Celular :{{$hpaciente['celular']}}</strong>
        <strong  > whatsapp:{{$hpaciente['whatsapp']}}</strong> <br>
        <strong  > Correo: {{$hpaciente['email']}}</strong>
    </div>
    <div class="consulta">
        <u>
            <h4>MOTIVO DE CONSULTA </h4>
        </u> 
        <p style="font-family: monospace"> {!! strip_tags(BBCode::convertToHtml($hpaciente['motivoCons']),'<b><i><u>') !!}</p>
    </div>
    <div class="consulta">
        <ul style="margin-top: 10px;">
            <li>GP: {{$hpaciente['GP']}}</li>
            <li>FUR: {{$hpaciente['FUR']}}</li>
            <li>PAP: {{$hpaciente['PAP']}}</li>
            <li>MAC: {{$hpaciente['MAC']}}</li>
            <li>RAM: {{$hpaciente['RAM']}}</li>
        </ul>
    </div>
    @if($hpaciente['antecedenteP'])
    <div class="consulta">
        <u>
            <h4>ANTECEDENTES PERSONALES </h4>
        </u> 
        <p style="font-family: monospace">{!! strip_tags(BBCode::convertToHtml($hpaciente['antecedenteP']),'<b><i><u>') !!}</p>
    </div>
    @endif
    @if($hpaciente['antecedenteF'])
    <div class="consulta">
        <u>
            <h4>ANTECEDENTES FAMILIARES </h4>
        </u> 
        <p style="font-family: monospace">{!! strip_tags(BBCode::convertToHtml($hpaciente['antecedenteF']),'<b><i><u>') !!}</p>
    </div>
    @endif
    <div class="consulta">
        <u>
            <h4>EXAMEN CLINICO</h4>
        </u>
        <ul>
            <li>PA: {{$hpaciente['pa']}}</li>
            <li>T: {{$hpaciente['t']}}</li>
            <li>FC: {{$hpaciente['fc']}}</li>
            <li>FR: {{$hpaciente['fr']}}</li>
            <li>PESO: {{$hpaciente['peso']}} kg</li>
            <li>Talla: {{$hpaciente['talla']}} cm</li>
        </ul>
        <br>
        @if($hpaciente['Comentclinico']!=null)
        <P style="font-family: monospace">{!! strip_tags(BBCode::convertToHtml($hpaciente['Comentclinico']),'<b><i><u>') !!}</P>
        @endif
    </div>
    @if($hpaciente['diagnostico']!=null)
    <div class="consulta">
        <u>
            <h4>DIAGNOSTICO </h4>
        </u>
        <table class="table table-bordered table-striped table-sm">
            <tbody>
                @foreach($hpaciente['diagnostico'] as $diagnosticado)
                <tr>
                    <td style="vertical-align: middle;text-align: center;">
                        {{$diagnosticado}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @if($hpaciente['Tratamiento']!=null)
    <div class="consulta" >
        <u>
            <h4>TRATAMIENTO </h4>
        </u>
        <table class="table table-bordered table-striped table-sm">
                <tr>
                    <th style="vertical-align: middle;text-align: center;">INDICACIONES MEDICAS</th>
                </tr>
            <tbody>
                @foreach($hpaciente['Tratamiento'] as $indicacion)
                <tr>
                    <td style="vertical-align: middle;text-align: center;">
                    {!! strip_tags(BBCode::convertToHtml($indicacion['indicaciones']),'<b><i><u>') !!} 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- @if($hpaciente['DocLaboratorio'])
    <div class="consulta">
        <u>
            <h4>LABORATORIO </h4>
        </u> 
        <p style="font-family: sans-serif">{{$hpaciente['DocLaboratorio']}} </p>
    </div>
    @endif -->
    @if($hpaciente['imageneologia'])
    <div class="consulta">
        <u>
            <h4>IMAGENEOLOGIA </h4>
        </u> 
        <p style="font-family: monospace">  {!! strip_tags(BBCode::convertToHtml($hpaciente['imageneologia']),'<b><i><u>') !!}</p>
    </div>
    @endif
    @if($hpaciente['pcita'])
    <div class="consulta">
        <u>
            <h4>Proxima Cita </h4>
        </u> 
        <p style="font-family: sans-serif;padding: 0px;">{{$hpaciente['pcita']}} </p>
    </div>
    @endif

    @endforeach
</body>

</html>