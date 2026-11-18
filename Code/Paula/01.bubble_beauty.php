<?php

function get_day_from_name($name){
	$explode_name = explode("-", $name);
	$day = $explode_name[2];
	return $day;
}

function day_sample_from_arr($sample_arr){
	$day_sample_arr = [];
	foreach($sample_arr as $sample => $arr){
		$day = get_day_from_name($sample);
		$mesocosm_number =
		$day_sample_arr[$day][] = $sample; 
	}
	return $day_sample_arr;
}

function order_sample_by_day($day_sample_arr){
	$sample_arr = [];
	foreach($day_sample_arr as $day_sample_arr){
		foreach($day_sample_arr as $sample){
			$sample_arr[] = $sample; 
		}
	}
	return $sample_arr;
}


function get_mesocosm_number_from_name($name){
	$mesocosm_number = 0;
	$explode_name = explode("_", $name);
	$second_explode = explode("-", $explode_name[1]);
	$mesocosm_number = $second_explode[1];
	return $mesocosm_number;
}

function make_day_mesocosm_sample_arr($day_mesocosm_arr){
	$day_mesocosmnumber_samplename = [];
	foreach($day_mesocosm_arr as $day => $mesocosms){
		foreach($mesocosms as $mesocosm){
			$number = get_mesocosm_number_from_name($mesocosm);
			$day_mesocosmnumber_samplename[$day][$number]= $mesocosm;
		}
	}
	return $day_mesocosmnumber_samplename;
}

function get_ordered_samplename_by_day_and_mesocosm_number($day_mesocosmnumber_samplename, $mesocosms_numbers = [5,8,6,7]){
	$ordered_samplenames = [];
	foreach($day_mesocosmnumber_samplename as $mesocosm_sample_name){
		foreach($mesocosms_numbers as $numbers){
			if(!isset($mesocosm_sample_name[$numbers])) continue;
			$samplename = $mesocosm_sample_name[$numbers];
			$ordered_samplenames[] = $samplename;
		}
	}
	return $ordered_samplenames;
}

function clean_site_name_2($site) {
  //$site = str_replace('Surface_', ' ', $site);
  return $site;
}


function make_sample_color_arr($day_sample_arr){
	$sample_color = [];
	foreach($day_sample_arr as $day => $sample_arr){
		foreach($sample_arr as $sample){
			if($day > 1 && $day <= 10) $color = '#66001e';
			if($day > 10 && $day <= 18) $color = '#f5b597';
			if($day > 18 && $day <= 37) $color = '#37abc8';
			if($day > 37 && $day <= 50) $color = '#022e61';
			$sample_color[$sample] = $color;
		}
	}
	return $sample_color;
}

function get_samplename_from_nifh_arr($nifh_arr){
	$samplename = [];
	foreach($nifh_arr as $sample_gene_count){
		foreach($sample_gene_count as $sample_name => $gene_count){
			$samplename[] = $sample_name;
		}
	}
	return $samplename;
}
//#336600
//#994C00