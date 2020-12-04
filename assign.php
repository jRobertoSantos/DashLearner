<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require('../../config.php');
require('lib.php');

global $DB, $CFG, $USER, $COURSE;

$course_id = $COURSE->id;
$user_id   = $USER->id;

$result = block_dashlearner_get_assign_by_user ($course_id, $user_id);
$number_results = count($result);

if ($number_results == 0) {
    echo "Nenhuma ocorrencia encontrada!";
}

$course = block_dashlearner_get_course($course_id);
$user   = block_dashlearner_get_user($user_id);


foreach($course as $item) {
    $course_fullname = $item->fullname;
    $course_shortname = $item->shortname;
}

foreach($user as $item) {
    $user_firstname = $item->firstname;
    $user_lastname  = $item->lastname;
}

$imagens = [];
$indice = 0;
foreach($result as $item){
    if ($item->flg_submissao_prazo == 1)  {
        $imagens[] = 'images/tick.gif';
    }
    elseif ($item->flg_submissao_fora_prazo == 1) {
        $imagens[] = 'images/warning.gif';
    }
    else {
        $imagens[] = 'images/cross.gif';
    }
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tarefas submetidas</title>

</head>
<style type="text/css">

    #container {
        height: 700px;
    }


</style>

<body>

<script src="externalref/highcharts.js"></script>
<script src="externalref/exporting.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">

    </p>
</figure>

<script type="text/javascript">
    Highcharts.chart('container', {
            chart: {
                type: 'bar',
                borderWidth: 1
            },
            title: {
                text:  <?php echo "'Quadro de tarefas submetidas - Curso: ".$course_fullname."'"; ?>
            },
            subtitle: {
                text:  <?php echo "'Aluno: ".$user_firstname." ".$user_lastname."'"; ?>
            },
            xAxis: {
                categories: [
                    <?php foreach($result as $item){echo "'".$item->name."',";} ?>
                ],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Quantidade ',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Submetidas no Prazo',
                color: 'green',
                data: [
                    <?php foreach($result as $item){echo strval($item->qtd_submissoes_prazo).",";} ?>
                ]
            }, {
                name: 'Submetidas fora Prazo',
                color:  'yellow',
                data: [
                    <?php foreach($result as $item){echo strval($item->qtd_submissoes_atraso).",";} ?>
                ]
            }, {
                name: 'Não submetidas',
                color:  'red',
                data: [
                    <?php foreach($result as $item){echo strval($item->qtd_matriculados-$item->qtd_submissoes_prazo-$item->qtd_submissoes_atraso).",";} ?>
                ]
            }]
        }, function (chart) { // on complete

            <?php echo 'var imagens = '.json_encode($imagens).';'; ?>

            for (var i = 0; i < this.xAxis[0].categories.length; i ++) {
                chart.renderer.image(imagens[i], this.yAxis[0].toPixels(0)-15, this.xAxis[0].toPixels(this.series[0].data[i].x)-7, 10, 10)
                    .add();
            }

            chart.renderer.image('images/tick.gif', 20, 17 , 10, 10).add();
            chart.renderer.text("Minha submissão no prazo",35,25).add();
            chart.renderer.image('images/warning.gif', 20, 27 , 10, 10).add();
            chart.renderer.text("Minha submissão atrasada",35,37).add();
            chart.renderer.image('images/cross.gif', 20, 39 , 10, 10).add();
            chart.renderer.text("Minha submissão não realizada",35,49).add();

        }
    );
</script>
</body>
</html>

}






