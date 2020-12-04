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

// defined('MOODLE_INTERNAL') || die();

require('../../config.php');
require('lib.php');

global $DB, $CFG, $USER, $COURSE;

$user_id   = $USER->id;

$module = required_param('module', PARAM_TEXT);
$course_id = required_param('course_id', PARAM_TEXT);

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

if ($module == 'assign') {
    $result = block_dashlearner_get_assign_by_user($course_id, $user_id);
    $number_results = count($result);

    if ($number_results == 0) {
        echo "Nenhuma submissão encontrada!";
    }

    $imagens = [];
    $indice = 0;
    foreach ($result as $item) {
        if ($item->flg_submissao_prazo == 1) {
            $imagens[] = 'images/tick.gif';
        } elseif ($item->flg_submissao_fora_prazo == 1) {
            $imagens[] = 'images/warning.gif';
        } else {
            $imagens[] = 'images/cross.gif';
        }
    }
    
    $resultot = block_dashlearner_get_assign_total($course_id, $user_id);
    foreach($resultot as $item) {
        $total_assign_course =          $item->total_assign_course;
        $total_assign_user_prazo =      $item->total_assign_user_prazo;
        $total_assign_user_fora_prazo = $item->total_assign_user_fora_prazo;
        $total_assign_nao_realizadas = $total_assign_course - $total_assign_user_prazo - $total_assign_user_fora_prazo;
    }
}

if ($module == 'feedback')   {
    $result = block_dashlearner_get_feedback_by_user($course_id, $user_id);
    $number_results = count($result);

    if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhuma avaliação encontrada!</h1> ';
   }

    $imagens = [];
    $indice = 0;
    foreach ($result as $item) {
        if ($item->flg_submissao_prazo == 1) $imagens[] = 'images/tick.gif';
        else  $imagens[] = 'images/cross.gif';
    }
    
    $resultot = block_dashlearner_get_feedback_total ($course_id, $user_id);
    
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_feedback_course;
        $total_submission_user   = $item->total_feedback_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }
    
    
}

if (($module == 'chat') or ($module == 'page') or ($module == 'url') or ($module == 'resource')) {
    $result = block_dashlearner_get_acesses_by_user_table($course_id, $user_id, $module);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhuma acesso encontrado!</h1> ';
   }
    
    $resultot =  block_dashlearner_get_total_by_user_table($course_id, $user_id, $module);
    foreach ($resultot as $item) {
        $total_table_course = $item->total_table_course;
        $total_table_user   = $item->total_table_user;
        $total_table_nao_realizadas = $total_table_course - $total_table_user;
    }
}

if ($module == 'lesson')  {
    $result = block_dashlearner_get_lesson($course_id, $user_id);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhuma lição encontrada!</h1> ';
   }
    
    $resultot =  block_dashlearner_get_lesson_total($course_id, $user_id);
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_lesson_course;
        $total_submission_user   = $item->total_lesson_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }

}



if ($module == 'quiz') {
    $result = block_dashlearner_get_quiz($course_id, $user_id);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhum questionário encontrado!</h1> ';
   }
    
    $imagens = [];
    $indice = 0;
    foreach ($result as $item) {
        if ($item->flg_submissao_prazo == 1) $imagens[] = 'images/tick.gif';
        else  $imagens[] = 'images/cross.gif';
    }
    
    $resultot =  block_dashlearner_get_quiz_total($course_id, $user_id, $module);
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_quiz_course;
        $total_submission_user   = $item->total_quiz_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }

}

if ($module == 'vpl') {
    $result = block_dashlearner_get_vpl_by_user($course_id, $user_id);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhuma avaliação encontrada!</h1> ';
   }
    
    $imagens = [];
    $indice = 0;
    foreach ($result as $item) {
        if ($item->flg_submissao_prazo == 1) $imagens[] = 'images/tick.gif';
        else  $imagens[] = 'images/cross.gif';
    }
 
    $resultot =  block_dashlearner_get_vpl_total($course_id, $user_id);
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_vpl_course;
        $total_submission_user   = $item->total_vpl_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }

    
    
}

if ($module == 'wiki') {
    $result = block_dashlearner_get_wiki($course_id, $user_id);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhum Wiki encontrado!</h1> ';
   }
    
    $resultot =  block_dashlearner_get_wiki_total($course_id, $user_id);
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_wiki_course;
        $total_submission_user   = $item->total_wiki_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }
}

if ($module == 'forum') {
    $result = block_dashlearner_get_forum_by_user($course_id, $user_id);
    $number_results = count($result);

   if ($number_results == 0) {
      echo '<h1 style="text-align:center;">Nenhum Fórum encontrado!</h1> ';
   }
    
    $resultot =  block_dashlearner_get_forum_total($course_id, $user_id);
    foreach ($resultot as $item) {
        $total_submission_course = $item->total_forum_course;
        $total_submission_user   = $item->total_forum_user;
        $total_submission_nao_realizadas = $total_submission_course - $total_submission_user;
    }
}


?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php
           if ($module == 'assign')    echo 'Tarefas submetidas';
           if ($module == 'feedback')  echo 'Pesquisas realizadas';
           if ($module == 'quiz') echo 'Questionarios submetidos';
           if ($module == 'chat') echo 'Chats acessados';
           if ($module == 'page') echo 'Páginas acessadas';
           if ($module == 'url') echo 'URLs acessadas';
           if ($module == 'vpl') echo 'VPL submetidas';
           if ($module == 'lesson') echo 'Licões submetidas';
           if ($module == 'wiki') echo 'Wikis submetidos';
           if ($module == 'resource') echo 'Arquivos acessados';
           if ($module == 'forum') echo 'Fóruns acessados';
           
           
        ?>
    </title>

</head>
<style type="text/css">

    #container { 
        height: <?php echo strval(max(count($result)*50,700))."px;"; ?> 
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
                text:  <?php
                          if ($module == 'assign')
                             echo "'Tarefas - Curso: ".$course_fullname."'";
                          if ($module == 'feedback')
                              echo "'Pesquisas - Curso: ".$course_fullname."'";
                          if ($module == 'chat')
                              echo "'Chats - Curso: ".$course_fullname."'";
                          if ($module == 'page')
                              echo "'Páginas - Curso: ".$course_fullname."'";
                          if ($module == 'quiz')
                              echo "'Questionários - Curso: ".$course_fullname."'";
                          if ($module == 'url')
                              echo "'URLs - Curso: ".$course_fullname."'";
                          if ($module == 'vpl')
                             echo "'VPLs - Curso: ".$course_fullname."'";
                          if ($module == 'lesson')
                             echo "'Lições - Curso: ".$course_fullname."'";
                          if ($module == 'wiki')
                             echo "'Wiki - Curso: ".$course_fullname."'";
                          if ($module == 'resource')
                             echo "'Arquivos  - Curso: ".$course_fullname."'";
                          if ($module == 'forum')
                             echo "'Fóruns  - Curso: ".$course_fullname."'";

                       ?>
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
            yAxis: [
              {min: 0,
               softMax: 10,
               minTickInterval: 1,
                title: {
                    text: 'Quantidade ',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
              },
              {min: 0,
                softMax:10,
                minTickInterval: 1,
                linkedTo: 0,
                title: {
                    text: 'Quantidade ',
                    align: 'high'
                },
                opposite: true,
              }
            ],
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                },
                spline: {
                    dataLabels: {
                        enabled: true
                    }
                }
                
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -50,
                y: -10,
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
                name: <?php
                         if ($module == 'assign')   echo  "'[Turma] - Submissões no prazo'";
                         if ($module == 'feedback') echo  "'[Turma] - Submissões realizadas'";
                         if ($module == 'quiz') echo  "'[Turma] - Submissões realizadas'";
                         if (($module == 'chat') or ($module == 'page') or ($module == 'url')) echo  "'[Turma] - Qtde de alunos que acessaram'";
                         if ($module == 'vpl') echo  "'[Turma] - Alunos com submissões realizadas'";
                         if (($module == 'lesson') or ($module == 'resource'))  echo  "'[Turma] - Qtde de alunos que acessaram'";
                         if ($module == 'wiki') echo  "'[Turma] - Qtde de alunos que acessaram'";
                         if ($module == 'forum') echo  "'[Turma] - Qtde de alunos que acessaram'";
                         ?> ,
                color:  'lime',
                data: [
                    <?php
                        if (($module == 'assign'))    foreach($result as $item){echo strval($item->qtd_submissoes_prazo).",";}
                        if (($module == 'feedback') or ($module == 'chat') or ($module == 'page') or ($module == 'quiz') or ($module == 'url') or
                                 ($module == 'vpl') or ($module == 'lesson') or ($module == 'wiki') or ($module == 'resource') or ($module == 'forum'))
                            foreach($result as $item){echo strval($item->qtd_usuarios_acessos).",";}
                    ?>
                ]
            }, {
                name: <?php
                         if ($module == 'assign')   echo  "'[Turma] - Submissões atrasadas'";
                         if ($module == 'vpl')   echo  "'[Turma] - Submissões não realizadas'";
                         if ($module == 'feedback') echo  "'[Turma] - Submissões não realizadas'";
                         if ($module == 'quiz') echo  "'[Turma] - Submissões não realizadas'";
                         if (($module == 'chat') or ($module == 'page') or ($module == 'url') or ($module == 'lesson') or ($module == 'wiki') or ($module == 'resource') or ($module == 'forum'))
                             echo  "'[Turma] - Qtde de alunos que não acessaram'";
                      ?> ,
                color:  'gold',
                data: [
                    <?php
                       if ($module == 'assign')   foreach($result as $item){echo strval($item->qtd_submissoes_atraso).",";}
                       if ($module == 'feedback')
                           foreach($result as $item) {echo strval($item->qtd_matriculados - $item->qtd_usuarios_acessos).",";}
                       if (($module == 'chat') or ($module == 'page') or ($module == 'quiz') or ($module == 'url') or ($module == 'vpl') or ($module == 'lesson')
                               or ($module =='wiki') or ($module == 'resource') or ($module == 'forum'))
                            foreach($result as $item) {echo strval($item->qtd_matriculados - $item->qtd_usuarios_acessos).",";}

                    ?>
                ]
            }, {
                name: <?php
                         if ($module == 'assign')   echo  "'[Turma] - Submissões não realizadas'";
                         if ($module == 'feedback') echo  "'[".$user_firstname."] - Acessos realizados'";
                         if ($module == 'quiz') echo  "'[".$user_firstname."] - Tentativas realizadas'";
                         if (($module == 'chat') or ($module == 'page') or ($module == 'url') or ($module == 'lesson') or ($module == 'resource'))
                             echo  "'[".$user_firstname."] - Acessos realizados'";
                         if ($module == 'wiki')
                             echo  "'[".$user_firstname."] - Comentários acessados'";
                         if ($module == 'vpl')
                             echo  "'[".$user_firstname."] - Acessos realizados '";
                         if ($module == 'forum') 
                             echo  "'[".$user_firstname."] - Acessos realizados '";
                       ?> ,
               color:  'red',  
               <?php if ($module != 'assign') echo "type: 'spline',"; ?>
                   data: [
                    <?php
                        if ($module == 'quiz') foreach($result as $item){
                             echo strval($item->qtd_meus_acessos).",";}
                        if ($module == 'assign') foreach($result as $item){
                             echo strval($item->qtd_matriculados - $item->qtd_submissoes_prazo - $item->qtd_submissoes_atraso).",";}
                        if (($module == 'feedback') or ($module == 'chat') or ($module == 'page') or ($module == 'url') or ($module == 'vpl') or ($module == 'lesson'))
                            foreach($result as $item){echo strval($item->qtd_meus_acessos).",";}
                        if ($module == 'wiki')    
                            foreach($result as $item){echo strval($item->qtd_comentarios_acessados).",";}
                        if ($module == 'forum')    
                             foreach($result as $item){echo strval($item->qtd_forum_acessados).",";}
                    ?>
                ]
            }
         <?php  if ($module == 'forum') {
            echo ", {name: '[".$user_firstname."] - Tópicos criados',";
            echo " color:  'green' ,";
            echo " type: 'spline',";
            echo " data: [";
            foreach ($result as $item){echo strval($item->qtd_topicos_criados).",";}
            echo "]}";
            echo ", {name: '[".$user_firstname."] - Tópicos comentados',";
            echo " color:  'deepskyblue' ,";
            echo " type: 'spline', ";
            echo " data: [";
            foreach ($result as $item){echo strval($item->qtd_topicos_comentados).",";}
            echo " ]}";
         }
         ?>    
         <?php  if ($module == 'wiki') {
            echo ", {name: '".$user_firstname."] - Comentários criados ',";
            echo " color:  'yellow' ,";
            echo " type: 'spline', ";
            echo " data: [";
            foreach ($result as $item){echo strval($item->qtd_comentarios_criados).",";}
            echo "]}";
            echo ", {name: '".$user_firstname."] - Comentários atualizados ',";
            echo " color:  'deepskyblue' ,";
            echo " type: 'spline', ";
            echo " data: [";
            foreach ($result as $item){echo strval($item->qtd_comentarios_atualizados).",";}
            echo " ]}";
         }
         ?>    
        ]
        }, function (chart) { // on complete

            <?php
               if (($module == 'assign') or ($module =='feedback') or ($module == 'quiz') or ($module == 'vpl'))
                     echo 'var imagens = '.json_encode($imagens).';';

               if (($module == 'assign') or ($module == 'feedback') or ($module == 'quiz') or ($module == 'vpl'))
                echo 'for (var i = 0; i < this.xAxis[0].categories.length; i ++) {
                chart.renderer.image(imagens[i], this.yAxis[0].toPixels(0)-15, this.xAxis[0].toPixels(this.series[0].data[i].x)-7, 10, 10)
                    .add();} ';

               if ($module == 'assign') {
                   echo 'chart.renderer.text("Total de submissões: '.strval($total_assign_course).' ",35,25).add();';
                   echo 'chart.renderer.image(\'images/tick.gif\', 20, 29 , 10, 10).add();';
                   echo 'chart.renderer.text("Minhas submissões no prazo      :'.strval($total_assign_user_prazo).'",35,37).add();';
                   echo 'chart.renderer.image(\'images/warning.gif\', 20, 41 , 10, 10).add();';
                   echo 'chart.renderer.text("Minhas submissões atrasadas     :'.strval($total_assign_user_fora_prazo).'",35,49).add();';
                   echo 'chart.renderer.image(\'images/cross.gif\', 20, 53 , 10, 10).add();';
                   echo 'chart.renderer.text("Minhas submissões não realizadas:'.strval($total_assign_nao_realizadas).'",35,61).add();';
               }

        if (($module == 'feedback') or ($module == 'quiz')) {
            echo 'chart.renderer.text("Total de submissões: '.strval($total_submission_course).'",35,25).add();';
            echo 'chart.renderer.image(\'images/tick.gif\', 20, 29 , 10, 10).add();';
            echo 'chart.renderer.text("Minhas submissões realizadas:'.strval($total_submission_user).'",35,37).add();';
            echo 'chart.renderer.image(\'images/cross.gif\', 20, 41 , 10, 10).add();';
            echo 'chart.renderer.text("Minhas submissões não realizadas: '.strval($total_submission_nao_realizadas).'",35,49).add();';
        }
                
        if ($module == 'vpl') {
            echo 'chart.renderer.text("Total de submissões: '.strval($total_submission_course).'",35,25).add();';
            echo 'chart.renderer.image(\'images/tick.gif\', 20, 29 , 10, 10).add();';
            echo 'chart.renderer.text("Minhas submissões realizadas:'.strval($total_submission_user).'",35,37).add();';
            echo 'chart.renderer.image(\'images/cross.gif\', 20, 41 , 10, 10).add();';
            echo 'chart.renderer.text("Minhas submissões não realizadas: '.strval($total_submission_nao_realizadas).'",35,49).add();';
        }
        
        if (($module == 'lesson')){
            echo 'chart.renderer.text("Total de Lições: '.strval($total_submission_course).'",35,25).add();';
            echo 'chart.renderer.text("Lições acessadas por '.$user_firstname.':'.strval($total_submission_user).'",35,37).add();';
            echo 'chart.renderer.text("Lições não acessadas por '.$user_firstname.': '.strval($total_submission_nao_realizadas).'",35,49).add();';
        }
        if (($module == 'wiki')){
            echo 'chart.renderer.text("Total de Wikis: '.strval($total_submission_course).'",35,25).add();';
            echo 'chart.renderer.text("Wikis acessados por '.$user_firstname.': '.strval($total_submission_user).'",35,37).add();';
            echo 'chart.renderer.text("Wikis não acessados por '.$user_firstname.': '.strval($total_submission_nao_realizadas).'",35,49).add();';
        }
        if ($module == 'chat') {
            echo 'chart.renderer.text("Total Chats: '.strval($total_table_course).'",35,25).add();';
            echo 'chart.renderer.text("Chats acessados por '.$user_firstname.': '.strval($total_table_user).'",35,37).add();';
            echo 'chart.renderer.text("Chats não acessados por '.$user_firstname.': '.strval($total_table_nao_realizadas).'",35,49).add();';
        }
                
        if ($module == 'page') {
            echo 'chart.renderer.text("Total Páginas: '.strval($total_table_course).'",35,25).add();';
            echo 'chart.renderer.text("Páginas acessadas por '.$user_firstname.': '.strval($total_table_user).'",35,37).add();';
            echo 'chart.renderer.text("Páginas não acessadas por '.$user_firstname.': '.strval($total_table_nao_realizadas).'",35,49).add();';
        }

        if ($module == 'url') {
            echo 'chart.renderer.text("Total URLs: '.strval($total_table_course).'",35,25).add();';
            echo 'chart.renderer.text("URLs acessadas por '.$user_firstname.': '.strval($total_table_user).'",35,37).add();';
            echo 'chart.renderer.text("URLs não acessadas por '.$user_firstname.': '.strval($total_table_nao_realizadas).'",35,49).add();';
        }

        if ($module == 'resource') {
            echo 'chart.renderer.text("Total Arquivos: '.strval($total_table_course).'",35,25).add();';
            echo 'chart.renderer.text("Arquivos acessados por '.$user_firstname.': '.strval($total_table_user).'",35,37).add();';
            echo 'chart.renderer.text("Arquivos não acessados por '.$user_firstname.': '.strval($total_table_nao_realizadas).'",35,49).add();';
        }
        
        if ($module == 'forum') {
            echo 'chart.renderer.text("Total Fóruns: '.strval($total_submission_course).'",35,25).add();';
            echo 'chart.renderer.text("Fóruns acessados por '.$user_firstname.': '.strval($total_submission_user).'",35,37).add();';
            echo 'chart.renderer.text("Fóruns não acessados por '.$user_firstname.': '.strval($total_submission_nao_realizadas).'",35,49).add();';
        }
 
        ?>
 
        }
    );
</script>
</body>
</html>








