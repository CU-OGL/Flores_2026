<?php

include_once("../src/00.process_IPL_table.php");
include_once("../src/01.bubble_beauty.php");
include_once("../src/02.process_gene_tables.php");
include_once("../src/03.bubble_chart_horizontal.php");
include_once("../src/bubble_funcs.php");
include_once("../src/bubble_leyend.php");
include_once("../src/06.metabarcoding_processing.php");

$bubble_scale = 0.05;
$mtbc_bubble_scale = 0.6;
$nifh_scale = 1;
$text_size = 9.6;
$delta_x = 15;
$delta_y = 13;
$bottom_up = "Surface";//**Surface or Bottom

 $Phospholipids = ["PG", "PC", "PE", "PME", "PDME"];
 $Glycolipids = ["MG", "DG", "SQ"];
 $Betaine_lipids = ["DGTA", "DGTS", "DGCC"];
 $others = ["Cer"];

$IPL_file = "../data/micro_lipids_only.csv";

$ipls_to_remove = ["Gly-GDGT-2", "Gly-GDGT-0", "Gly-GDGT-5", "Gly-GDGT-1", "Gly-GDGT-4", "PI-AR", "Gly-GDGT-2-dGly", "Gly-OH-GDGT-1", "Gly-OH-GDGT-0", "Gly-GDGT-2", "PC-AR"];

//$samplename_class_compound_counts = get_sample_class_compound_counts($IPL_file, $ipls_to_remove);

$metabarcoding_file = "../test/16S.csv";
$metabarcoding_file = "../data/merged_data_clean_M5_M6_ORDER_phases.csv";

$metabarcoding_headers = get_headers_from_metabarcoding_file($metabarcoding_file);

$mtbc_st_phylum_order_counts = get_st_phylum_order_counts_arr($metabarcoding_file, $bottom_up, $metabarcoding_headers);

//print_r($mtbc_st_phylum_order_counts);

///***Filter by simper results

$mtbc_simper_file = "../data/Simper_tables/16s_simper_"."$bottom_up".".csv";

$mtbc_simper_list = make_comparison_order_filter_by_pvalue_arr($mtbc_simper_file);

$mtbc_unique_order_list = make_unique_order_list($mtbc_simper_list);

//print_r($mtbc_unique_order_list);
$filtered_mtbc_st_phylum_order_counts = filter_three_level_arr_by_compound_list($mtbc_st_phylum_order_counts, $mtbc_unique_order_list);

$mtbc_st_phylum_order_counts = $filtered_mtbc_st_phylum_order_counts; //coment this for unfiltered_results

//print_r($mtbc_st_phylum_order_counts);



///*** IPLS

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

$samplename_class_compound_counts = get_sample_class_compound_counts($IPL_file, $ipls_to_remove, $bottom_up);

///***filter by simper results

$ipl_simper_file = "../data/Simper_tables/SIMPER_"."$bottom_up"."_comparison.csv";
$ipl_simper_list = ipls_make_comparison_order_filter_by_pvalue_arr($ipl_simper_file);
//print_simper_results($ipl_simper_list);
$unique_compound_list = make_unique_order_list($ipl_simper_list);

$filtered_samplename_class_compound_counts = filter_three_level_arr_by_compound_list($samplename_class_compound_counts, $unique_compound_list); 
$samplename_class_compound_counts = $filtered_samplename_class_compound_counts; //*****comment this for unfiltered_results

//print_r($samplename_class_compound_counts);
///*** IPLS bubbles

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

///****Metabarcoding

$mtbc_bubble_arr = flip_horizontal($mtbc_st_phylum_order_counts);
$mtbc_day_sample = day_sample_metabarcoding_from_arr($mtbc_st_phylum_order_counts);
ksort($mtbc_day_sample);
$mtbc_order_samplenames_by_day = order_sample_by_day($mtbc_day_sample,$mesocosm_order =[5,8,6,7]);


$mtbc_sample_color = make_sample_color_arr($mtbc_day_sample);

$mtbc_ordered_row_names = get_ordered_row_names_by_big_group($mtbc_bubble_arr);

$mtbc_mesocosm_number_samplename = make_metabarcoding_day_mesocosm_sample_arr($mtbc_day_sample);

$mtbc_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($mtbc_mesocosm_number_samplename, $mtbc_mesocosms_numbers = [5,6]);
$mtbc_ordered_bubble_arr = order_bubble_arr($mtbc_bubble_arr, $mtbc_ordered_row_names, $mtbc_order_samplenames_by_day, $others);

//print_r($mtbc_ordered_bubble_arr);

/////***** hzo and nirs genes
$gene_file = "../data/Kai_qPCRs_amx_nirS.txt";

$sample_hzo_nirs_copies["hzo/nirs"] = get_sample_hzo_nirs_copies($gene_file, $filter_mesocosm_arr = [1,2,3,4], $trans = 'sqrt', $bottom_up);
$bubble_sample_hzo_nirs_copies = flip_horizontal_gene_arr($sample_hzo_nirs_copies);
$flip_genes_sample = flip($sample_hzo_nirs_copies);

$gene_day_sample = day_sample_from_arr($flip_genes_sample);
$gene_order_samplenames_by_day = order_sample_by_day($gene_day_sample);
$gene_sample_color = make_sample_color_arr($gene_day_sample);
$gene_mesocosm_number_samplename = make_day_mesocosm_sample_arr($gene_day_sample);
$gene_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($gene_mesocosm_number_samplename, $mesocosms_numbers = [5,8,6,7]);

$min_genes = get_min($sample_hzo_nirs_copies);


////***** nifh genes

//$test = make_sample_name_from_nifh_tables($name = "P8 M6 S");
$nifh_file = "../data/nifH_data.csv";
$nifh_colnames = get_column_names_from_nifh_table($nifh_file);
$nifh_samplename_gene_count["NifH"] = make_samplename_nifh_counts($nifh_file, $nifh_colnames, $trans = 'log10', $day_filter_arr = [2,12,13,14,15,16,17,28,32,48,24,44], $bottom_up);
$bubble_nifh_samplename_gene_count = flip_horizontal_gene_arr($nifh_samplename_gene_count);


$nifh_flip_genes_sample = flip($nifh_samplename_gene_count);
$nifh_gene_day_sample = day_sample_from_arr($nifh_flip_genes_sample);
$nifh_gene_sample_color = make_sample_color_arr($nifh_gene_day_sample);
$nifh_sample_list = nifh_sample_list($nifh_gene_day_sample);

$nifh_mesocosm_number_samplename = make_day_mesocosm_sample_arr($nifh_gene_day_sample);
$nifh_order_samplenames_by_day_and_mesocosm_number = get_ordered_samplename_by_day_and_mesocosm_number($nifh_mesocosm_number_samplename, $mesocosms_numbers = [5,8,6,7]);
//print_r($nifh_order_samplenames_by_day_and_mesocosm_number);

//print_r($nifh_sample_list);


$min = get_max($mtbc_ordered_bubble_arr);
//echo "$min\n";

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

 $leyend_scale_mtbc = [5, 15, 35, 50];

$scientific_notation_mtbc = [];
foreach ($leyend_scale_mtbc  as $value){
  $scient = formatScientific($value);
  $scientific_notation_mtbc[] = $scient;
}
echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="1500" width="2000">';

//draw_bubble2(200, 200, 200, 'red');
//draw_line2(200,200,600, 200, 'black', 0.1);PDME
//draw_row_bubbles(200, 200, $bubble_arr["DGTS"]["DGTS-31:0"], $delta_x, $bubble_scale);
//draw_big_group_bubbles(200, 200, $ordered_bubble_arr["DGTS"], $delta_x, $bubble_scale, $delta_y, $sample_color, $filter = 5);
if($bottom_up == "Bottom") $bottom_up = "Subsurface";
draw_text2(350, 130, $bottom_up, $rotation_angle = 0, $text_size + 5, $weigth = 'normal');
draw_horizontal_metabarcoding_bubbles(1220, 200, $mtbc_ordered_bubble_arr, $delta_x, $mtbc_bubble_scale, $delta_y = 15, $mtbc_sample_color, $filter = 10, $mtbc_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);

draw_horizontal_bubbles(500, 150, $ordered_bubble_arr, $delta_x, $bubble_scale, $delta_y, $sample_color, $filter = 4, $IPL_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);

draw_horizontal_bubbles(410, 920, $bubble_sample_hzo_nirs_copies, $delta_x, $bubble_scale, $delta_y, $gene_sample_color, $filter = 5, $gene_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);

draw_horizontal_bubbles(410, 1020, $bubble_nifh_samplename_gene_count, $delta_x, $nifh_scale, $delta_y, $nifh_gene_sample_color, $filter = 5, $nifh_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);
///****Bottom
/*
draw_horizontal_bubbles(500, 150, $ordered_bubble_arr, $delta_x, $bubble_scale, $delta_y, $sample_color, $filter = 4, $IPL_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size); //***Bottom


draw_horizontal_bubbles(490, 920, $bubble_sample_hzo_nirs_copies, $delta_x, $bubble_scale, $delta_y, $gene_sample_color, $filter = 5, $gene_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);

draw_horizontal_bubbles(420, 1020, $bubble_nifh_samplename_gene_count, $delta_x, $nifh_scale, $delta_y, $nifh_gene_sample_color, $filter = 5, $nifh_order_samplenames_by_day_and_mesocosm_number, $with_cols = True, $text_size);
*/
//*****

draw_leyend(1200, 575, $leyend_scale_mtbc, $mtbc_bubble_scale, $scientific_notation_mtbc, "Relative abundance of ribosomal SSU amplicons", $draw_bubbles = False);

draw_leyend(1100, 720, $leyend_scale_ipls, $bubble_scale, $scientific_notation, "IPL concentration (ng L-1)", $draw_bubbles = True);

draw_leyend(1220, 850, $leyend_scale_genes, $bubble_scale, $scientific_notation_genes, "Sqrt copies L-1", $draw_bubbles = False); 

draw_leyend(1220, 950, $leyend_scale_genes_nifh, $nifh_scale, $scientific_notation_nifh, "Log10 copies L-1", $draw_bubbles = False);


echo '</svg>'; 


