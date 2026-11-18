<?php

function draw_text($x, $y, $text, $rotation_angle = 0, $size = 8, $weigth = 'normal'){
      echo <<<EOF
      <text
           font-family="Verdana"
           transform="translate($x, $y)rotate(-$rotation_angle)"
           font-size="$size"
           font-weight="$weigth"
           fill="black">
           $text
      </text>
EOF;
}

function draw_line($x1, $y1, $x2, $y2, $stroke, $width = 4) {
  printf('<line x1="%f" y1="%f" x2="%f" y2="%f" style="stroke:%s;stroke-width:%f" />', $x1, $y1, $x2, $y2, $stroke, $width);
  echo "\n";
}


function draw_bubble($x, $y, $value, $color = 'red') {
  printf('<ellipse cx="%f" cy="%f" rx="%f" ry="%f" fill-opacity="0.2" style="fill:%s;stroke:%s;stroke-width:1" />', $x, $y, $value, $value, $color, $color);
}

function get_max($data){
  $max = 0.0000001;
  foreach ($data as $sites){
    foreach($sites as $taxas){
      foreach($taxas as $counts){
        $max = max($counts, $max);      
      }
    }
  }
  return $max; 
}

function get_min($data){
$min = 100000000;
  foreach ($data as $sites){
    foreach($sites as $taxas){
      foreach($taxas as $counts){
        $min = min($counts, $min);      
      }
    }
  }
  return $min; 
}


function formatScientific($someFloat){
  return sprintf("%.2e", $someFloat);
}

function draw_scale_leyend($x, $y, $scale, $bubble_scale, $draw_bubble = True){
  $current_x = $x;
  foreach ($scale as $value){
    draw_bubble($current_x, $y +30, $bubble_scale*$value, '#4e4f51');
    $current_x += 50;
  }
  draw_line($x, $y+30, $current_x - 30, $y+30, 'black', 0.2);
  
  if($draw_bubble === True){ 

  draw_bubble($current_x , $y + 10, 6, '#66001e'); 
  draw_text($current_x + 10, $y + 15, 'Phase I', 0, 12);

  draw_bubble($current_x , $y + 30, 6, '#f5b597'); 
  draw_text($current_x +10, $y + 35, 'Phase II', 0, 12);

  draw_bubble($current_x , $y + 50, 6, '#37abc8'); 
  draw_text($current_x + 10, $y + 55, 'Phase III', 0, 12);

  draw_bubble($current_x , $y + 70, 6, '#022e61'); 
  draw_text($current_x + 10, $y + 75, 'Phase IV', 0, 12); 
  } 
}

function draw_scient_values ($x, $y, $values, $metaome = "metagenome"){
  draw_text($x, $y + 28, 'Relative abundance of ' . $metaome, 0, 8, 'bold');
  foreach ($values as $value){
    draw_text($x, $y + 18, $value, 0, 8);
    $x += 50;
  }
}

function draw_scale_leyend_by_clusters($x, $y, $scale, $bubble_scale){
  $current_x = $x;
  foreach ($scale as $value){
    draw_bubble($current_x, $y +70, $bubble_scale*$value, '#4e4f51');
    $current_x += 50;
  }
  draw_line($x, $y+70, $current_x - 30, $y+70, 'black', 0.2);

  draw_bubble($current_x , $y + 20, 6, 'green'); 
  draw_text($current_x +10, $y + 25, 'Unclustered Hadal Sed', 0, 12);

  draw_bubble($current_x , $y + 40, 6, '#ee9470ff'); 
  draw_text($current_x + 10, $y + 45, 'Cluster1', 0, 12);

  draw_bubble($current_x , $y + 60, 6, '#35638aff'); 
  draw_text($current_x + 10, $y + 65, 'Cluster2', 0, 12);

  draw_bubble($current_x , $y + 80, 6, '#ff8a00ff'); 
  draw_text($current_x + 10, $y + 85, 'Cluster3', 0, 12);

  draw_bubble($current_x , $y + 100, 6, 'grey'); 
  draw_text($current_x + 10, $y + 105, 'Cluster4', 0, 12);

  draw_bubble($current_x , $y + 120, 6, 'royalblue'); 
  draw_text($current_x + 10, $y + 125, 'Cluster5', 0, 12);


}

function draw_scale_leyend_by_clusters_only_seds($x, $y, $scale, $bubble_scale){
  $current_x = $x;
  foreach ($scale as $value){
    draw_bubble($current_x, $y +70, $bubble_scale*$value, '#4e4f51');
    $current_x += 50;
  }
  draw_line($x, $y+70, $current_x - 30, $y+70, 'black', 0.2);

  draw_bubble($current_x , $y + 20, 6, 'brown'); 
  draw_text($current_x + 10, $y + 25, 'Cluster1', 0, 12);

  draw_bubble($current_x , $y + 40, 6, 'darkblue'); 
  draw_text($current_x + 10, $y + 45, 'Cluster2', 0, 12);

  draw_bubble($current_x , $y + 60, 6, 'orange'); 
  draw_text($current_x + 10, $y + 65, 'Cluster3', 0, 12);

  draw_bubble($current_x , $y + 80, 6, 'grey'); 
  draw_text($current_x + 10, $y + 85, 'Cluster4', 0, 12);

  draw_bubble($current_x , $y + 120, 6, 'green'); 
  draw_text($current_x +10, $y + 125, 'Unclustered Hadal Sed', 0, 12);

  draw_bubble($current_x , $y + 100, 6, 'royalblue'); 
  draw_text($current_x + 10, $y + 105, 'Cluster5', 0, 12);


}


function draw_scient_values_by_clusters ($x, $y, $values, $metaome = "metagenome"){
  draw_text($x, $y + 68, '' . $metaome, 0, 8, 'bold');
  foreach ($values as $value){
    draw_text($x, $y + 58, $value, 0, 8);
    $x += 50;
  }
}

function sqrt_draw_scient_values_by_clusters ($x, $y, $values, $metaome = "metagenome"){
  draw_text($x, $y + 68, '' . $metaome, 0, 8, 'bold');
  foreach ($values as $value){
    draw_text($x, $y + 58, ($value)^2, 0, 8);
    $x += 50;
  }
}

function draw_leyend ($x, $y, $leyend_scale, $bubble_scale, $scientific_notation, $metaome = "", $draw_bubbles = True){
  draw_scale_leyend($x, $y + 20, $leyend_scale, $bubble_scale, $draw_bubbles);
  draw_scient_values_by_clusters ($x - 15, $y + 30, $scientific_notation, $metaome);
}

function draw_sqrt_leyend ($x, $y, $leyend_scale, $bubble_scale, $scientific_notation, $metaome = "SAGs"){
  draw_scale_leyend($x, $y + 20, $leyend_scale, $bubble_scale);
  sqrt_draw_scient_values_by_clusters($x - 15, $y + 30, $scientific_notation, $metaome);
}

