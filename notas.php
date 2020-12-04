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

//defined('MOODLE_INTERNAL') || die();

require('../../config.php');
require('lib.php');

$course_id = required_param('course_id', PARAM_INT);
// $user_id = required_param('user_id', PARAM_INT);

$user_id   = $USER->id;

//$course = $this->page->course;
//$course_id = $course->id;

//global $DB;

$courseparams = get_course($course_id);
$coursename = get_string('course', 'block_dashlearner') . ": " . $courseparams->fullname;

$user   = block_dashlearner_get_user($user_id);
foreach($user as $item) {
    $user_firstname = $item->firstname;
    $user_lastname  = $item->lastname;
}

$result = block_dashlearner_get_grades ($course_id, $user_id);
$number_results = count($result);

if ($number_results == 0) {
   echo '<h1 style="text-align:center;">Nenhuma avaliação encontrada!</h1> ';
   echo "<H1>  </H1>";
}
 
$result_avg_course = block_dashlearner_get_avg_grade_course($course_id);
$result_avg_user   = block_dashlearner_get_avg_grade_user($course_id, $user_id);


?>

<html>
<head>
<style>
    .highcharts-figure, .highcharts-data-table table {
        min-width: 360px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }
    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }
    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }
    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
        padding: 0.5em;
    }
    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }
    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>

</head>
<body>

<script src="externalref/highcharts.js"></script>
<script src="externalref/modules/series-label.js"></script>
<script src="externalref/modules/exporting.js"></script>
<script src="externalref/modules/export-data.js"></script>
<script src="externalref/modules/accessibility.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo get_string('note_frame', 'block_dashlearner'); ?></title>

<center>
<H2><?php  echo get_string('note_frame', 'block_dashlearner');?></H2>
<H3><?php  echo $coursename;?> </H3>
<H3><?php  echo $user_firstname." ".$user_lastname;?> </H3>

<h3> Comparativo de Médias de <?php echo $user_firstname ?>  com Médias do Grupo </h3>
<center>  <h4> [Atenção] As médias consideram atividades ainda a vencer </h4> </center>
        
</center>

<figure class="highcharts-figure">

<div id="container0"></div>
<center>  <h3> Comparativo de Notas de <?php echo $user_firstname ?> com Médias do Grupo </h3> </center>
        
<div id="container1"></div>    
   
<?php  
    $ind = 0;
    foreach($result as $item) {
        $ind = $ind + 1;
        echo '<div id="container'.$ind.'"></div>';
        echo " \n";
    }    
    $ind = $ind + 1;
    echo '<div id="container'.$ind.'"></div>';
    echo " \n";
    
?>
    
 </figure>



<script type="text/javascript">
    (function (H) {
        H.seriesType('lineargauge', 'column', null, {
            setVisible: function () {
                H.seriesTypes.column.prototype.setVisible.apply(this, arguments);
                if (this.markLine) {
                    this.markLine[this.visible ? 'show' : 'hide']();
                }
            },
            drawPoints: function () {
                // Draw the Column like always
                H.seriesTypes.column.prototype.drawPoints.apply(this, arguments);

                // Add a Marker
                var series = this,
                    chart = this.chart,
                    inverted = chart.inverted,
                    xAxis = this.xAxis,
                    yAxis = this.yAxis,
                    point = this.points[0], // we know there is only 1 point
                    markLine = this.markLine,
                    ani = markLine ? 'animate' : 'attr';

                // Hide column
                point.graphic.hide();

                if (!markLine) {
                    var path = inverted ? ['M', 0, 0, 'L', -5, -5, 'L', 5, -5, 'L', 0, 0, 'L', 0, 0 + xAxis.len] : ['M', 0, 0, 'L', -5, -5, 'L', -5, 5, 'L', 0, 0, 'L', xAxis.len, 0];
                    markLine = this.markLine = chart.renderer.path(path)
                        .attr({
                            fill: series.color,
                            stroke: series.color,
                            'stroke-width': 1
                        }).add();
                }
                markLine[ani]({
                    translateX: inverted ? xAxis.left + yAxis.translate(point.y) : xAxis.left,
                    translateY: inverted ? xAxis.top : yAxis.top + yAxis.len -  yAxis.translate(point.y)
                });
            }
        });
    }(Highcharts));

    Highcharts.chart('container0', {
        chart: {
            polar: true,
            borderWidth: 1,
            type: 'column'
        },

        title: {
            text: 'Comparativo das Médias das Notas',
            x: -5
        },

        pane: {
            size: '80%'
        },

        xAxis: {
            categories: [<?php foreach($result_avg_course as $item_avg){echo "'".get_string($item_avg->itemmodule,'block_dashlearner')."',";}?>],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },

        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            lineColor: '#FF0000',
            min: 0,
            max: 10,
            title: {
            text: 'Notas'
            }
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f}</b><br/>'
        },

        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 70,
            layout: 'vertical'
        },

        series: [
        {
            name: <?php echo "'Médias de ".$user_firstname."'"; ?>,
            data: [<?php foreach($result_avg_user as $item_avg){echo strval($item_avg->avg_grade_user).",";}?> ],
            color: '#b7d7e8'
            //pointPlacement: 'on'
        }, 
        {
            name: 'Médias do Grupo',
            data: [<?php foreach($result_avg_course as $item_avg){echo strval($item_avg->avg_grade_course).",";}?>],
            color:  '#b5e7a0'
            //pointPlacement: 'on'
        }]

    });




<?php 
    $ind = 0;
    foreach($result as $item) {
        $ind = $ind + 1;
        echo "Highcharts.chart('container".$ind."', { \n";
        echo "chart: { \n";
        echo "type: 'lineargauge',\n";
        echo " borderWidth: 1, \n";
        echo "inverted: true,\n";
        echo "height: 150\n";
        echo "},\n";
        echo "title: {\n";
        echo "text: '[".get_string($item->itemmodule,'block_dashlearner')."] - ".$item->itemname."'\n";
        echo "    },\n";
        echo "    xAxis: {\n";
        echo "        lineColor: '#C0C0C0',\n";
        echo "        labels: {\n";
        echo "            enabled: false\n";
        echo "        },\n";
        echo "        tickLength: 10\n";
        echo "    },\n";
        echo "    yAxis: {\n";
        echo "        min: 0,\n";
        echo "        max: 100,\n";
        echo "        tickLength: 5,\n";
        echo "        tickWidth: 1,\n";
        echo "        tickColor: '#C0C0C0',\n";
        echo "        gridLineColor: '#C0C0C0',\n";
        echo "        gridLineWidth: 1,\n";
        echo "        minorTickInterval: 5,\n";
        echo "        minorTickWidth: 1,\n";
        echo "        minorTickLength: 5,\n";
        echo "        minorGridLineWidth: 0,\n";

        echo "        title: null,\n";
        echo "        labels: {\n";
        echo "            format: '{value}%'\n";
        echo "      },\n";
        echo "        plotBands: [{\n";
        echo "            from: 0,\n";
        echo "            to: 50,\n";
        echo "            color: 'rgba(250, 121, 33, 1)'\n";
        echo "        }, {\n";
        echo "            from: 50,\n";
        echo "            to: 80,\n";
        echo "            color: 'rgba(255,255,0,0.5)'\n";
        echo "        }, {\n";
        echo "            from: 80,\n";
        echo "            to: 100,\n";
        echo "            color: 'rgba(0,255,0,0.5)'\n";
        echo "        }]\n";
        echo "    },\n";
        echo "    legend: {\n";
        echo "        enabled: false\n";
        echo "    },\n";

        echo "    series: [{";
        echo "        data: ["; if ($item->perc_grade_user == '1000') echo "0";  else echo $item->perc_grade_user; echo "],\n";
        echo "        name: ";  if ($item->perc_grade_user == '1000') echo "0";  else echo $item->perc_grade_user; echo  ",\n";                                                        

        echo "        color: '#4B0082s',\n";
        echo "        dataLabels: {\n";
        echo "            enabled: true,\n";
        echo "            align: 'center',\n";
        echo "            color: '#4B0082s',\n";
        echo "            format: ";
        if      ($item->perc_grade_user == '1000') echo "'".$user_firstname.": Sem Nota'\n"; else echo "'".$user_firstname.": {point.y}%'\n";
        echo "            }\n";
        echo "          },\n";
        echo "                   {\n";
        echo "        data: [".$item->perc_grade_course."],\n";
        echo "        name: 'Nota Média do Grupo' ,\n";
        echo "        color: '#FF4500',\n";
        echo "        dataLabels: {\n";
        echo "            enabled: true,\n";
        echo "            align: 'center',\n";
        echo "            color: '#FF4500',\n";
        echo "            format: 'Grupo: {point.y}%'\n";
        echo "           }\n";
        echo "        }\n";
        echo "    ]\n";
        echo "}\n";
        echo ");\n";
    }
  ?>  
</script>

</body>
</html>
