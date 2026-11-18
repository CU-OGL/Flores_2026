<?php

///*** nifh functs
function make_sample_name_from_nifh_tables($name = "P8 M6 S"){
	$samplename = "";
	$explode_name = explode(" ", $name);
	$gene = $explode_name[0];
	$explode_mesocosm = explode("M", $explode_name[1]);
	$mesocosm = $explode_mesocosm[1];
	if($explode_name[2] == "S") $surface_bottom = "Surface";
	else $surface_bottom = "Bottom";
	$samplename = ["$surface_bottom"."_"."Mesocosm-"."$mesocosm", $gene];
	return $samplename;
}

function get_column_names_from_nifh_table($file){
	$f = fopen($file, "r");
	$column_names = [];
	$i =0;
	while($line = fgets($f)){
		if($i != 0) break;
		$line = trim($line);
		$cols = explode(",", $line);
		for ($j=1; $j < count($cols) ; $j++) { 
			$column_names[$j] = make_sample_name_from_nifh_tables($cols[$j]);
		}
		$i++;
	}
	fclose($f);
	return $column_names;
}

function make_samplename_nifh_counts($file, $colnames, $trans = log10, $day_filter_arr, $bottom_up){
	$samplename_nifh_counts = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode(",", $line);
		if($cols[0] == "day") continue;
		else $day = $cols[0];
		if(!in_array($day, $day_filter_arr)) continue;
		for ($i=1; $i < count($cols) ; $i++) { 
			$mesocosm = $colnames[$i][0];
			$explode_mesocosm = explode("_", $mesocosm);
			$surf_bottom = $explode_mesocosm[0];
			if($surf_bottom != $bottom_up) continue; ///*** Surface Bottom
			$samplename = "$mesocosm"."_Day-"."$day";
			//echo "$samplename/n";
			$gene = $colnames[$i][1];
			$counts = $trans($cols[$i]);
			if(!isset($samplename_nifh_counts[$samplename][$gene])) $samplename_nifh_counts[$samplename][$gene] = $counts;
			else $samplename_nifh_counts[$samplename][$gene] += $counts;
		}

	}
	fclose($f);
	return $samplename_nifh_counts;
}


///*** hzo and nirs functs
function get_information_from_name($name, $pos){
	$explode_name = explode(" ", $name);
	$mesocosm_inf = $explode_name[$pos];
	return $mesocosm_inf; 
}

function make_mesocosm_name_from_genomic_data($mesocosm_number, $day, $surf_bottom){
	$mesocosm_name = "";
	$explode_mesocosm_number = explode("-", $mesocosm_number);
	$number = $explode_mesocosm_number[1];
	if($surf_bottom == "Surf" || $surf_bottom == "Surface") $depth = "Surface";
	if($surf_bottom == "Bottom") $depth = "Bottom";
	$mesocosm_name = "$depth"."_Mesocosm-"."$number"."_Day-"."$day";
	return $mesocosm_name;
}

function get_sample_hzo_nirs_copies($file, $filter_mesocosm_arr = [1,2,3,4], $trans = log10 , $bottom_up){
	$sample_hzo_gene_copies = [];
	$f = fopen($file, "r");
	while($line = fgets($f)){
		$line = trim($line);
		$cols = explode("	", $line);
		if($cols[6] == "Day") continue;
		$mesocosm_number = get_information_from_name($cols[1], 0);
		if($mesocosm_number == "PAC") continue;
		if($mesocosm_number == "DW") continue;
		$explode_mesocosm_number = explode("-", $mesocosm_number);
		$number = $explode_mesocosm_number[1];
		if(in_array($number, $filter_mesocosm_arr)) continue;
		$surf_bottom = get_information_from_name($cols[1], 1);
		if($surf_bottom == "Surf" || $surf_bottom == "Surface") $depth = "Surface";
		if($surf_bottom == "Bottom") $depth = "Bottom";
		if($depth != $bottom_up) continue; //////Surface o Bottom
		$day = $cols[6];
		$sample = make_mesocosm_name_from_genomic_data($mesocosm_number, $day, $surf_bottom);
		$hzo_copies = $trans($cols[2]);
		$nirs_copies = $trans($cols[4]);
		if(!isset($sample_hzo_gene_copies[$sample]["hzo"])) $sample_hzo_gene_copies[$sample]["hzo"] = $hzo_copies;
		else $sample_hzo_gene_copies[$sample]["hzo"] += $hzo_copies;

		if(!isset($sample_hzo_gene_copies[$sample]["nirs"])) $sample_hzo_gene_copies[$sample]["nirs"] = $nirs_copies;
		else $sample_hzo_gene_copies[$sample]["nirs"] += $nirs_copies;
	}
	fclose($f);
	return $sample_hzo_gene_copies;
}

function flip_horizontal_gene_arr($gene_arr){
	$flip = [];
	foreach($gene_arr as $big_group =>$col_rows){
		foreach($col_rows as $col =>$rows){
			foreach($rows as $row => $value){
				$flip[$big_group][$row][$col] = $value;
			}
		}

	}
	return $flip;
}

function nifh_sample_list($nifh_gene_by_day){
	$sample_list = [];
	foreach($nifh_gene_by_day as $nifh_day_list){
		foreach ($nifh_day_list as $sample) {
			$sample_list[] = $sample;
		}
	}
	return $sample_list;
}