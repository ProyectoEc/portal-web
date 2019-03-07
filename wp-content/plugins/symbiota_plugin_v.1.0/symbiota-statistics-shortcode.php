<?php

  add_shortcode('cod_sym_statistics','sym_shortcode_statistics');

  function sym_shortcode_statistics($atts){
    $attsArr = shortcode_atts( array(
      'id' => '0000',
      'campo' => 'SIN_REG'
   ), $atts );
   $columna = $attsArr['campo'];
   if ($attsArr['id'] == '1Q86'){
     include dirname(__FILE__) . '/symbiota-statistics-results.php';
   }
   else {
      return "0";
   }
  }

?>
