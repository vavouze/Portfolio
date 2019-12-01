<?php

	/*
	Template name: offres
	*/


	get_header(); ?>

	<link rel='stylesheet' id='elementor-animations-css'  href='http://localhost/amenia/wp-content/plugins/elementor/assets/lib/animations/animations.min.css?ver=2.5.15' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-frontend-css'  href='http://localhost/amenia/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=2.5.15' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-global-css'  href='http://localhost/amenia/wp-content/uploads/elementor/css/global.css?ver=1557478778' type='text/css' media='all' />
	<link rel='stylesheet' id='js_composer_front-css'  href='http://localhost/amenia/wp-content/plugins/js_composer/assets/css/js_composer.min.css?ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='google-fonts-1-css'  href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=5.1.1' type='text/css' media='all' />

	<section class="container" id="site-content">
	    <div class="normal1">
	    	<h1>Offre d'emplois</h1>
	    	<p>70 % de nos missions portant sur des fonctions</br>
				middle et top management, sont confidentielles.</br>
				Toutes nos opportunités ne figurent donc pas sur notre site</p>

	        
	    	<div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element " >
					<div class="wpb_wrapper">


						<table id="tablepress-1" class="tablepress tablepress-id-1">
							
							<thead>
								<tr class="row-1 odd">
									<th class="column-1">Références</th><th class="column-2">Offres</th><th class="column-3">lieu de travail</th><th class="column-4">Détails</th>
								</tr>
							</thead>
							<tbody class="row-hover">
								
								
							


						<?php
                            $args = array(
                                'post_type' => 'offres',
                                'post_status' => 'publish',
                                'ignore_sticky_posts' => true,
                                'posts_per_page' => 100,
                                'meta_query' => array(),
                                
                            );
                            $query = new WP_Query( $args );

                        if ( $query->have_posts() ) {
                        	foreach ( $query->posts as $post ) {
                                $fields = get_fields($post);
                                ?>
                                <tr class="row-2 even">
									<td class="column-1"><?php echo($fields['reference']);?></td><td class="column-2"><?php echo($post->post_title);?></td><td class="column-3"><?php echo ($fields['lieu_de_travail']);?></td><td class="column-4"><a href="<?php echo($post->guid);?>" rel="noopener" target="_blank">Détails de l'offre</a></td>
								</tr>
								<?php 
								/*echo '<pre>';
								var_dump($post);
								//var_dump($fields);
								//var_dump($post);
								
								die;*/
    						}
                        }
                        	?>

				
							</tbody>
						</table>


					</div>
				</div>
			</div></div></div></div>
	                
	    </div>
	</section>



	<?php get_footer(); ?>
<script type='text/javascript' src='http://localhost/amenia/wp-content/plugins/js_composer/assets/js/dist/js_composer_front.min.js?ver=5.5.5'></script>
<script type='text/javascript' src='http://localhost/amenia/wp-content/plugins/tablepress/js/jquery.datatables.min.js?ver=1.9.2'></script>
<script type="text/javascript">
jQuery(document).ready(function($){
var DataTables_language={};
DataTables_language["fr_FR"]={"emptyTable":"Aucun élément à afficher","info":"Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments","infoEmpty":"Affichage de l'élément 0 à 0 sur 0 éléments","infoFiltered":"(filtré de _MAX_ éléments au total)","infoPostFix":"","lengthMenu":"Afficher _MENU_ éléments","loadingRecords":"Chargement en cours...","processing":"Traitement en cours...","search":"Rechercher:","zeroRecords":"Aucun élément à afficher","paginate": {"first":"Premier","previous":"Précédent","next":"Suivant","last":"Dernier"},"aria": {"sortAscending":": activer pour trier la colonne par ordre croissant","sortDescending":": activer pour trier la colonne par ordre décroissant"},"decimal":",","thousands":"."};
$('#tablepress-1').dataTable({"language":DataTables_language["fr_FR"],"order":[],"orderClasses":false,"stripeClasses":["even","odd"],"pagingType":"simple"});
});
</script>
