<?php

function make_sample_name($depth, $mesocosm, $day){
	$sample_name = "";
	if($depth == 1) $first = "Surface";
	if($depth == 2) $first = "Bottom";
	$sample_name = "$first"."_"."Mesocosm"."-"."$mesocosm"."_"."Day"."-"."$day";
	return $sample_name;

}

function get_sample_class_compound_counts($file, $filter_arr, $bottom_up){
	$samplename_class_coumpound_counts = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode(",", $line);
		$depth = $cols[1];
		if($depth == "Depth") continue;
		if($depth == 1) $surface_bottom = "Surface";
		if($depth == 2) $surface_bottom = "Bottom";
		if($surface_bottom != $bottom_up) continue;
		//if($depth == 2) continue; //////of Surface(1) or Bottom(2) continue
		$mesocosm = $cols[2];
		$day = $cols[3];
		$counts = $cols[4];
		$class = $cols[5];
		if($class == "GDGT") continue;
		$compound = $cols[6];
		if(in_array($compound, $filter_arr)) continue;
		$samplename = make_sample_name("$depth", "$mesocosm", "$day");
		if(!isset($samplename_class_coumpound_counts[$samplename][$class][$compound])) $samplename_class_coumpound_counts[$samplename][$class][$compound] = $counts;
		else $samplename_class_coumpound_counts[$samplename][$class][$compound] += $counts;
	}
	fclose($f);
	return $samplename_class_coumpound_counts;
}

///*** processing simper tables

function change_ipls_name_of_r_output($name){
	$new_name = "";
	$first_explode = explode(".", $name);
	$count = count($first_explode);
	if($count == 3){
		$new_name = "$first_explode[0]"."-"."$first_explode[1]".":"."$first_explode[2]";
	}
	else if($count == 4){
		$new_name = "$first_explode[0]"."-"."$first_explode[1]"."-"."$first_explode[2]".":"."$first_explode[3]";
	}
	else echo "Warning: look for $name in function and edit function change_ipls_name_of_r_output\n";

	return $new_name;
}

function ipls_make_comparison_order_filter_by_pvalue_arr($file){
	$comparison_phylum_order_arr = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode(",", $line);
		if($cols[0] == "comparison") continue;
		$comparison = $cols[0];
		if($cols[1] == "PC.C20.3.3.Diacyl") continue;
		$compound = change_ipls_name_of_r_output($cols[1]);
		$compound = str_replace("\"", "", $compound);
		$pvalue = $cols[9];
		if($pvalue > 0.05) continue;
		$comparison_phylum_order_arr[$comparison][] = $compound;
	}
	fclose($f);
	return $comparison_phylum_order_arr;
}

///*** correlations

function make_ipl_arr_one_cluster($ipl_cluster_arr, $ipl_cluster_to_filter){
	$order_arr = [];
	foreach($ipl_cluster_arr[$ipl_cluster_to_filter] as $ipl){
		$ipl = change_ipls_name_of_r_output($ipl);
		$order_arr[$ipl] = $ipl;
	}
	return $order_arr;
}


function make_ipl_arr_from_all_clusters($ipl_cluster_arr){
	$order_arr = [];
	foreach($ipl_cluster_arr as  $ipl_cluster => $ipl_arr){
		foreach($ipl_arr as $ipl){
		$ipl = change_ipls_name_of_r_output($ipl);
		$order_arr[$ipl] = $ipl;
		}
	}
	return $order_arr;
}
