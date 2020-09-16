<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\HistoriaClinica::class, function (Faker $faker) {
    $doctoriIds=User::pluck('id');
    $citaM=['2019-77','2020-76','2076'];
    $nombre=['ronaldo','genesis','xiomara'];
    $apellido=['Durand','Luna','Ibañez'];
    $edad=['22','32','11'];
    $direccion=['Puquio Cano','Hualmay','Los pinos'];
    $celular=[987654321,321654987,123456789];
  
    $correo=['smithxd118@gmail.com','smithxd1@gmail.com','smithxd@gmail.com'];
    $motivoCons=['Dolor de Cabeza','Parpado Caido','Dolor de Musculos'];
    $pa=['A1','A2','A3'];
    $t=['B1','B2','B3'];
    $fc=['B1','B2','B3'];
    $fr=['C1','C2','C3'];
    $peso=['1.76 kg','1.78 kg','2.1 kg'];
    $talla=['1.76 cm','1.78 cm','2.1 cm'];
    $diagnostico=['Sano','Enfermo','Curado'];
    $Tratamiento=['Analgesico','Ibuprofeno','Clorfenamina'];
    $laboratio=['Analgesico','Ibuprofeno','Clorfenamina'];
    $date=$faker->dateTimeBetween('-1 years','now');
    $sheduled_date=$date->format('Y-m-d');
    return [
        'nCitamed'=>$faker->randomElement($citaM),
        'user_id'=>$faker->randomElement($doctoriIds),
        'nombre'=>$faker->randomElement($nombre),
        'apellido'=>$faker->randomElement($apellido),
        'edad'=>$faker->randomElement($edad),
        'dni'=>'75144370',
        'fecha_nacimiento'=>$sheduled_date,
        'direccion'=>$faker->randomElement($direccion),
        'celular'=>$faker->randomElement($celular),
        'whatsapp'=>$faker->randomElement($celular),
        'email'=>$faker->randomElement($correo),
        'motivoCons'=>$faker->randomElement($motivoCons),

        'diagnostico'=>$faker->randomElement($diagnostico),
        'Tratamiento'=>$faker->randomElement($Tratamiento),
        'DocLaboratorio'=>$faker->randomElement($laboratio),
        'imageneologia'=>$faker->randomElement($laboratio),
        'vigencia_paciente'=>1,
    ];
});
