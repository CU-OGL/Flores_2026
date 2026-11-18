<?php

function make_sample_names($file = "../data/sample_names.txt"){
	$sample_names = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$sample_name = "";
		$line = trim($line);
		$cols = explode("\t", $line);
		$origin = $cols[0];
		$station_cm = explode("_", $cols[1]);
		$depth = $cols[2];
		if($origin == "Inner_trench") {
			$station = $station_cm[0];
			$cm = $station_cm[1];
			$sample_name = "$origin"."_"."st"."$station"."_"."$cm"."cm"."_"."$depth"."m";
		}
		if($origin == "Continental_slope"){
			$station = $station_cm[1];

			$sample_name = "$origin"."_"."st"."$station"."_"."$depth"."m";
		}
		$sample_names[$cols[1]] = [$sample_name, $depth];
		
	}
	fclose($f);
	return $sample_names;
}
function flip($sample_class_compound_relab){
	$flip = [];
	foreach($sample_class_compound_relab as $sample => $class_compound_relab){
		if ($sample === "Surface_Mesocosm-6_Day-24") continue;
		if($sample === "Surface_Mesocosm-8_Day-24") continue;
		foreach($class_compound_relab as $class => $compound_relab){
			$flip[$class][$sample] = $compound_relab;
		}
	}
	return $flip;
}

function flip_horizontal($sample_class_compound_relab){
	$flip = [];
	foreach($sample_class_compound_relab as $sample => $class_compound_relab){
		if ($sample === "Surface_Mesocosm-6_Day-24") continue;
		if($sample === "Surface_Mesocosm-8_Day-24") continue;
		foreach($class_compound_relab as $class => $compound_relab){
			foreach($compound_relab as $compound => $relab){
				$flip[$class][$compound][$sample] = $relab;

				}

			}
		}
	return $flip;
}

///***cambiar los nombres id por station en array de tres niveles
function make_sample_class_compound_relab($id_class_compound_relab, $sample_names){
	$sample_class_compound_relab = [];
	foreach($id_class_compound_relab as $id => $class_compound_relab){
		if(!isset($sample_names[$id])) $sample_name = $id;
		else $sample_name = $sample_names[$id][0];
		$sample_class_compound_relab[$sample_name] = $class_compound_relab;
	}
	return $sample_class_compound_relab;
}

///*** los compuestos que están unicamente en la fosa
function get_station_type_by_name($id){
	$station_name = "";
	$explode_name = explode("_", $id);
	$station_name = $explode_name[1];
	return $station_name;
}

function make_typest_compound_sum($st_class_compound_relab){
	$typest_compound_sum = [];
	foreach($st_class_compound_relab as $st => $class_compound_relab){
		$type_st = get_station_type_by_name($st);
		foreach($class_compound_relab as $compound_relab){
			foreach($compound_relab as $compound => $relab){
				if(!isset($typest_compound_sum[$type_st][$compound])) $typest_compound_sum[$type_st][$compound] = $relab;
				else $typest_compound_sum[$type_st][$compound] += $relab;
			}
		}
	}
	return $typest_compound_sum;
}

function filter_st_class_compound_relab($st_class_compound_relab, $typest_compound_total, $filter, $type_st){
	$filtered_arr = [];
	foreach($st_class_compound_relab as $st => $class_compound_relab){
		foreach($class_compound_relab as $class => $compound_relab){
			foreach($compound_relab as $compound => $relab){
				if(isset($typest_compound_total[$type_st][$compound])) continue;
				else $filtered_arr[$st][$class][$compound]  = $relab;
			}
		}
	}
	return $filtered_arr;
}