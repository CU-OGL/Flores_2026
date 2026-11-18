<?php

function make_phylum_order_from_name($name){
	$phylum_order = [];
	$first_explode = explode("..", $name);
	$phylum = $first_explode[0];
	$order = $first_explode[1];
	$phylum_order = [$phylum, $order];
	return $phylum_order;	
}

function get_bottom_or_suface_from_st_name($name){
	$bottom_up = "";
	$first_explode = explode("_", $name);
	$bottom_up = $first_explode[0];
	return $bottom_up;
}

function get_headers_from_metabarcoding_file($file){
	$headers = [];
	$f = fopen($file, "r");
	$i = 0;
	while($line = fgets($f)){
		$i++;
		if($i != 1) break;

		$line = trim($line);
		$cols = explode(",", $line);
		$counts = count($cols);
		for ($i=2; $i < $counts; $i++) { 
			$current_name = $cols[$i];
			$phylum_order = make_phylum_order_from_name($current_name);
			$headers[$i] = $phylum_order;
		}
	}

	fclose($f);
	return $headers;
}

function get_st_phylum_order_counts_arr($file, $bottom_up, $headers){
	$st_phylum_order_counts = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode(",", $line);
		if($cols[0] == "ID") continue;
		else $st = $cols[0];
		$bottom_surface = get_bottom_or_suface_from_st_name($st);
		if($bottom_surface != $bottom_up) continue;
		$counts = count($cols);
		for ($i=2; $i < $counts; $i++) { 
			$count = $cols[$i];
			$phylum = $headers[$i][0];
			$order = $headers[$i][1];
			if($order == "Chloroplast") continue;
			if(!isset($st_phylum_order_counts[$st][$phylum][$order])) $st_phylum_order_counts[$st][$phylum][$order] = $count;
			else $st_phylum_order_counts[$st][$phylum][$order] += $count;

		}
	}
	fclose($f);
	return $st_phylum_order_counts;
}

function get_day_from_metabarcoding_name($name){
	$explode_name = explode("_", $name);
	$pos = count($explode_name) - 1; 
	$day = $explode_name[$pos];
	return $day;
}

function day_sample_metabarcoding_from_arr($sample_arr){
	$day_sample_arr = [];
	foreach($sample_arr as $sample => $arr){
		$day = get_day_from_metabarcoding_name($sample);
		$mesocosm_number =
		$day_sample_arr[$day][] = $sample; 
	}
	return $day_sample_arr;
}

function get_metabarcoding_mesocosm_number_from_name($name){
	$mesocosm_number = 0;
	$explode_name = explode(" ", $name);
	$second_explode = explode("_", $explode_name[1]);
	$mesocosm_number = $second_explode[0];
	return $mesocosm_number;
}

function make_metabarcoding_day_mesocosm_sample_arr($day_mesocosm_arr){
	$day_mesocosmnumber_samplename = [];
	foreach($day_mesocosm_arr as $day => $mesocosms){
		foreach($mesocosms as $mesocosm){
			$number = get_metabarcoding_mesocosm_number_from_name($mesocosm);
			$day_mesocosmnumber_samplename[$day][$number]= $mesocosm;
		}
	}
	return $day_mesocosmnumber_samplename;
}


///*** processing simper tables

function make_comparison_order_filter_by_pvalue_arr($file){
	$comparison_phylum_order_arr = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode(",", $line);
		if($cols[0] == "comparison") continue;
		$comparison = $cols[0];
		$species = make_phylum_order_from_name($cols[1]);
		$phylum = $species[0];
		$order = $species[1];
		$pvalue = $cols[9];
		if($pvalue > 0.05) continue;
		$comparison_phylum_order_arr[$comparison][] = $order;
	}
	fclose($f);
	return $comparison_phylum_order_arr;
}

function print_simper_results($comparison_order_arr){
	foreach ($comparison_order_arr as $comparison => $order_arr) {
		echo "\n$comparison:\n";
		foreach($order_arr as $order){
			echo "$order\n";
		}
	}
}

function make_unique_order_list($comparison_order_arr){
	$unique_order_list = [];
	foreach($comparison_order_arr as $order_list){
		foreach($order_list as $order){
			$unique_order_list[$order] = $order;
		}
	}
	return $unique_order_list;
}

function filter_three_level_arr_by_compound_list($three_level_arr, $unique_compount_list){
	$filtered_arr = [];
	foreach($three_level_arr as $mesocosm => $phylum_order_counts){
		foreach($phylum_order_counts as $phylum => $order_counts){
			foreach($order_counts as $order => $counts){
				if(!isset($unique_compount_list[$order])) continue;
				else $filtered_arr[$mesocosm][$phylum][$order] = $counts;
			}
		}
	}
	return $filtered_arr;
}

///***correlations

function make_order_arr($ipl_cluster_arr, $ipl_cluster_to_filter){
	$order_arr = [];
	foreach($ipl_cluster_arr[$ipl_cluster_to_filter] as $phylum_order){
		$phylum_order = make_phylum_order_from_name($phylum_order);
		$order = $phylum_order[1];
		$order_arr[$order] = $order;
	}
	return $order_arr;
}


function make_order_arr_from_all_clusters($ipl_cluster_arr){
	$order_arr = [];
	foreach($ipl_cluster_arr as  $ipl_cluster => $phylum_order_arr){
		foreach($phylum_order_arr as $phylum_order){
		$phylum_order = make_phylum_order_from_name($phylum_order);
		$order = $phylum_order[1];
		$order_arr[$order] = $order;
		}
	}
	return $order_arr;
}