<?php

include_once("../src/00.process_IPL_table.php");
include_once("../src/01.bubble_beauty.php");
include_once("../src/02.process_gene_tables.php");
include_once("../src/03.bubble_chart_horizontal.php");
include_once("../src/bubble_funcs.php");
include_once("../src/bubble_leyend.php");

$bubble_scale = 0.05;
$nifh_scale = 1;
$delta_x = 15;
$delta_y = 13;

 $Phospholipids = ["PG", "PC", "PE", "PME", "PDME"];
 $Glycolipids = ["MG", "DG", "SQ"];
 $Betaine_lipids = ["DGTA", "DGTS", "DGCC"];
 $others = ["Cer"];

$IPL_file = "../data/micro_lipids_only.csv";

$ipls_to_remove = ["Gly-GDGT-2", "Gly-GDGT-0", "Gly-GDGT-5", "Gly-GDGT-1", "Gly-GDGT-4", "PI-AR", "Gly-GDGT-2-dGly", "Gly-OH-GDGT-1", "Gly-OH-GDGT-0", "Gly-GDGT-2", "PC-AR"];

$samplename_class_compound_counts = get_sample_class_compound_counts($IPL_file, $ipls_to_remove);

//print_r($samplename_class_compound_counts);
///*** IPLS

$bubble_arr = flip_horizontal($samplename_class_compound_counts);
$day_sample = day_sample_from_arr($samplename_class_compound_counts);
$order_samplenames_by_day = order_sample_by_day($day_sample,$mesocosm_order =[5,8,6,7]);
$sample_color = make_sample_color_arr($day_sample);

$order_samplenames_by_day = order_sample_by_day($day_sample,$mesocosm_order =[5,8,6,7]);
$ordered_row_names = get_ordered_row_names_by_big_group($bubble_arr);

$IPL_mesocosm_number_samplename = make_day_mesocosm_sample_arr($day_sample);
$IPL_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($IPL_mesocosm_number_samplename, $mesocosms_numbers = [5,8,6,7]);
//print_r($IPL_order_samplenames_by_day_and_mesocosm_number);



$ordered_bubble_arr = order_bubble_arr($bubble_arr, $ordered_row_names, $order_samplenames_by_day, $others);

//print_r($ordered_bubble_arr);

/////***** hzo and nirs genes
$gene_file = "../data/Kai_qPCRs_amx_nirS.txt";

$sample_hzo_nirs_copies["hzo/nirs"] = get_sample_hzo_nirs_copies($gene_file, $filter_mesocosm_arr = [1,2,3,4], $trans = 'sqrt');
$bubble_sample_hzo_nirs_copies = flip_horizontal_gene_arr($sample_hzo_nirs_copies);
$flip_genes_sample = flip($sample_hzo_nirs_copies);

$gene_day_sample = day_sample_from_arr($flip_genes_sample);
$gene_order_samplenames_by_day = order_sample_by_day($gene_day_sample);
$gene_sample_color = make_sample_color_arr($gene_day_sample);
$gene_mesocosm_number_samplename = make_day_mesocosm_sample_arr($gene_day_sample);
$gene_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($gene_mesocosm_number_samplename, $mesocosms_numbers = [5,8,6,7]);
//print_r($gene_order_samplenames_by_day_and_mesocosm_number);


$min_genes = get_min($sample_hzo_nirs_copies);


////***** nifh genes

//$test = make_sample_name_from_nifh_tables($name = "P8 M6 S");
$nifh_file = "../data/nifH_data.csv";
$nifh_colnames = get_column_names_from_nifh_table($nifh_file);
$nifh_samplename_gene_count["NifH"] = make_samplename_nifh_counts($nifh_file, $nifh_colnames, $trans = 'log10', $day_filter_arr = [2,12,13,14,15,16,17,28,32,48,24,44]);
$bubble_nifh_samplename_gene_count = flip_horizontal_gene_arr($nifh_samplename_gene_count);


$nifh_flip_genes_sample = flip($nifh_samplename_gene_count);
$nifh_gene_day_sample = day_sample_from_arr($nifh_flip_genes_sample);
$nifh_gene_sample_color = make_sample_color_arr($nifh_gene_day_sample);
$nifh_sample_list = nifh_sample_list($nifh_gene_day_sample);

$nifh_mesocosm_number_samplename = make_day_mesocosm_sample_arr($nifh_gene_day_sample);
$nifh_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($nifh_mesocosm_number_samplename, $mesocosms_numbers = [5,8,6,7]);
//print_r($nifh_order_samplenames_by_day_and_mesocosm_number);

//print_r($nifh_sample_list);




/////****leyends
$leyend_scale_ipls = [100e-0, 250e-0, 500e-0, 750e-0, 1000e-0];
$scientific_notation = [];
foreach ($leyend_scale_ipls as $value){
  $scient = formatScientific($value);
  $scientific_notation[] = $scient;
}

$leyend_scale_genes = [10e-0, 50e-0, 100e-0, 200e-0];
$scientific_notation_genes = [];
foreach ($leyend_scale_genes as $value){
  $scient = formatScientific($value);
  $scientific_notation_genes[] = $scient;
}

$leyend_scale_genes_nifh = [2e-0, 4e-0, 7e-0, 10e-0];
$scientific_notation_nifh = [];
foreach ($leyend_scale_genes_nifh as $value){
  $scient = formatScientific($value);
  $scientific_notation_nifh[] = $scient;
}


echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="1500" width="2000">';

//draw_bubble2(200, 200, 200, 'red');
//draw_line2(200,200,600, 200, 'black', 0.1);PDME
//draw_row_bubbles(200, 200, $bubble_arr["DGTS"]["DGTS-31:0"], $delta_x, $bubble_scale);
//draw_big_group_bubbles(200, 200, $ordered_bubble_arr["DGTS"], $delta_x, $bubble_scale, $delta_y, $sample_color, $filter = 5);
draw_text2(350, 180, "Surface", $rotation_angle = 0, $size = 12, $weigth = 'normal');

draw_horizontal_bubbles(490, 200, $ordered_bubble_arr, $delta_x, $bubble_scale, $delta_y, $sample_color, $filter = 5, $IPL_order_samplenames_by_day_and_mesocosm_number, $with_cols = True);

draw_horizontal_bubbles(370, 650, $bubble_sample_hzo_nirs_copies, $delta_x, $bubble_scale, $delta_y, $gene_sample_color, $filter = 5, $gene_order_samplenames_by_day_and_mesocosm_number, $with_cols = True);

draw_horizontal_bubbles(370, 700, $bubble_nifh_samplename_gene_count, $delta_x, $nifh_scale, $delta_y, $nifh_gene_sample_color, $filter = 5, $nifh_order_samplenames_by_day_and_mesocosm_number, $with_cols = False);

draw_leyend(1100, 400, $leyend_scale_ipls, $bubble_scale, $scientific_notation, "IPL concentration (ng L-1)", $draw_bubbles = True);

draw_leyend(1120, 595, $leyend_scale_genes, $bubble_scale, $scientific_notation_genes, "Sqrt copies L-1", $draw_bubbles = True); 

draw_leyend(1120, 665, $leyend_scale_genes_nifh, $nifh_scale, $scientific_notation_nifh, "Log10 copies L-1", $draw_bubbles = False);


echo '</svg>'; 


