<?php
  //Clases de cofig de Symbiota
  include_once('/var/www/html/Symbiota/config/symbini.php');
  include_once('/var/www/html/Symbiota/content/lang/index.'.$LANG_TAG.'.php');
  //Clases de la lista de colecciones
  include_once('/var/www/html/Symbiota/classes/OccurrenceManager.php');
  //Clases de la informacion de la meta data de colecciones
  include_once('/var/www/html/Symbiota/classes/OccurrenceCollectionProfile.php');
  header("Content-Type: text/html; charset=".$CHARSET);
?>
<?php
  	//Informacion estadistica de las colecciones
  	$collManager = new OccurrenceManager();
  	$collList = $collManager->getFullCollectionList();
  	//Total de colecciones
  	$totalColecciones = 0;
  	$totalColecciones = count($collList[spec][coll]) + count($collList[obs][coll]);
  	//Total de estadisticas de colecciones
  	$collManagerProfile = new OccurrenceCollectionProfile();
  	//Variables de estadisticas
  	$regEspecimenes=0;
  	$regGeoref=0;
  	$regFamilias=0;
  	$regGeneros=0;
  	$regEspecies=0;

  	foreach($collList[spec][coll] as $x => $x_value) {
  		$collManagerProfile->setCollid($x);
  		$statsArr = $collManagerProfile->getBasicStats();

  		if ($statsArr['recordcnt'])
  		{ $regEspecimenes = $regEspecimenes + $statsArr['recordcnt'];	}
  		else { $regEspecimenes = $regEspecimenes + 0;}

  		if ($statsArr['georefcnt'])
  		{ $regGeoref = $regGeoref + $statsArr['georefcnt'];	}
  		else { $regGeoref = $regGeoref + 0; }

  		if ($statsArr['familycnt'])
  		{ $regFamilias = $regFamilias + $statsArr['familycnt'];	}
  		else { $regFamilias = $regFamilias + 0; }

  		if ($statsArr['genuscnt'])
  		{ $regGeneros = $regGeneros + $statsArr['genuscnt'];	}
  		else { $regGeneros = $regGeneros + 0; }

  		if ($statsArr['speciescnt'])
  		{ $regEspecies = $regEspecies + $statsArr['speciescnt'];	}
  		else { $regEspecies = $regEspecies + 0; }
  	}

    //Visualizar las estadisticas segun el template seleccionado
    if ($columna == "TEMPLATE1"){
?>
        <div class="sc_section aligncenter">
          <h1 class="sc_title sc_title_regular sc_align_center" style="text-align:center;"><p></p></h1>
          <h3 class="sc_title sc_title_regular sc_align_center title_with_b">Sistema de&nbsp;<b>Biodiversidad&nbsp;</b>del Ecuador</h3>
          <p></p>
          <div class="sc_line sc_line_style_double" style="border-top-style:double;border-top-color:#81d742;"></div>
        </div>
        <div class="wpb_text_column wpb_content_element  vc_custom_1549468199750">
          <div class="wpb_wrapper">
            <p style="text-align: justify;">El Sistema Nacional de Monitoreo de Biodiversidad (SINMBio) es una estrategia que evalúa el impacto de las actividades humanas sobre la diversidad biológica de tal manera que permita orientar sus resultados a las políticas públicas y autoridades ambientales (MAE) en la planeación estratégica territorial a múltiples escalas.</p>
            <p style="text-align: justify;">La BNDB es una colección de información biológica del Ecuador organizada basada en los estándares biológicos internacionales Darwin Core que se presenta una herramienta de información taxonómica, de observaciones y de proyectos de biodiversidad ágil, eficiente, veraz y pertinente que permite representar la diversidad biológica del Ecuador con miras en apoyar la toma de decisiones preventivas, correctivas, políticas y sociales relacionadas con la biodiversidad nacional.</p>
          </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-4">
          <div class="vc_column-inner ">
            <div class="wpb_wrapper">
              <div class="columns_wrap sc_columns columns_nofluid sc_columns_count_1">
                <div class="column-1_1 sc_column_item sc_column_item_1 odd first">
                  <div class="sc_column_item_inner" style="background-color:#eaeaea;">
                    <div class="sc_section aligncenter">
                      <figure class="sc_image  sc_image_shape_round">
                        <img src="http://testbiodiversidad.ikiam.edu.ec/wp-content/uploads/2019/01/icono_wp.png" alt="icono_wp">
                      </figure>
                      <div id="sc_skills_diagram_1019434292" class="sc_skills sc_skills_counter" data-type="counter" data-subtitle="Skills">
                        <div class="sc_skills_item sc_skills_style_1 odd first inited">
                          <div class="sc_skills_count">
                            <div class="sc_skills_info">
                              <div class="sc_skills_label" style="color:;"></div>
                            </div>
                          </div>
                          <div class="sc_skills_total" data-start="0" data-stop="<?php echo($totalColecciones); ?>" data-step="55" data-max="<?php echo($totalColecciones); ?>" data-speed="29" data-duration="2889" style="color:;" data-ed=""><?php echo(number_format($totalColecciones,0,',','.')); ?></div>
                        </div>
                      </div>
                      <div class="sc_line sc_line_style_double" style="border-top-style:double;border-top-color:#81d742;">
                      </div>
                      <div class="sc_content content_wrap">
                        <p></p>
                        <h4>Colecciones</h4>
                        <p></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-4">
          <div class="vc_column-inner ">
            <div class="wpb_wrapper">
              <div class="columns_wrap sc_columns columns_nofluid sc_columns_count_1">
                <div class="column-1_1 sc_column_item sc_column_item_1 odd first">
                  <div class="sc_column_item_inner" style="background-color:#eaeaea;">
                    <div class="sc_section aligncenter">
                      <figure class="sc_image  sc_image_shape_round">
                        <img src="http://testbiodiversidad.ikiam.edu.ec/wp-content/uploads/2019/01/icono_wp_2.png" alt="icono_wp_2">
                      </figure>
                      <div id="sc_skills_diagram_699180984" class="sc_skills sc_skills_counter" data-type="counter" data-subtitle="Skills">
                        <div class="sc_skills_item sc_skills_style_1 odd first inited">
                          <div class="sc_skills_count">
                            <div class="sc_skills_info">
                              <div class="sc_skills_label" style="color:;">
                              </div>
                            </div>
                          </div>
                          <div class="sc_skills_total" data-start="0" data-stop="<?php echo($regEspecimenes); ?>" data-step="55" data-max="<?php echo($regEspecimenes); ?>" data-speed="29" data-duration="2889" style="color:;" data-ed=""><?php echo(number_format($regEspecimenes,0,',','.')); ?></div>
                        </div>
                      </div>
                      <div class="sc_line sc_line_style_double" style="border-top-style:double;border-top-color:#81d742;">
                      </div>
                      <div class="sc_content content_wrap">
                        <p></p>
                        <h4>Registros</h4>
                        <p></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-4">
          <div class="vc_column-inner ">
            <div class="wpb_wrapper">
              <div class="columns_wrap sc_columns columns_nofluid sc_columns_count_1">
                <div class="column-1_1 sc_column_item sc_column_item_1 odd first">
                  <div class="sc_column_item_inner" style="background-color:#eaeaea;">
                    <div class="sc_section aligncenter">
                      <figure class="sc_image  sc_image_shape_round">
                        <img src="http://testbiodiversidad.ikiam.edu.ec/wp-content/uploads/2019/01/icono_wp_2.png" alt="icono_wp_2">
                      </figure>
                      <div id="sc_skills_diagram_699180984" class="sc_skills sc_skills_counter" data-type="counter" data-subtitle="Skills">
                        <div class="sc_skills_item sc_skills_style_1 odd first inited">
                          <div class="sc_skills_count">
                            <div class="sc_skills_info">
                              <div class="sc_skills_label" style="color:;">
                              </div>
                            </div>
                          </div>
                          <div class="sc_skills_total" data-start="0" data-stop="<?php echo($regEspecies); ?>" data-step="55" data-max="<?php echo($regEspecies); ?>" data-speed="29" data-duration="2889" style="color:;" data-ed=""><?php echo(number_format($regEspecies,0,',','.')); ?></div>
                        </div>
                      </div>
                      <div class="sc_line sc_line_style_double" style="border-top-style:double;border-top-color:#81d742;">
                      </div>
                      <div class="sc_content content_wrap">
                        <p></p>
                        <h4>Especies</h4>
                        <p></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php
    }
    else {
?>
      <div>Error de Symbiota</div>
<?php
    }
?>
