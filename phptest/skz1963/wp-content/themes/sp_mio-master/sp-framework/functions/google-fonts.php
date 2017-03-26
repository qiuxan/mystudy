<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* list of google fonts in an option array
******************************************************************/

/*
 * builds the font listing array
 *
 * @return array
 */
function sp_google_fonts_array() 
{
		$option = array( 'Advent Pro' => 'Advent Pro', 
						'Alerta' => 'Alerta',
						'Anton' => 'Anton',
						'Armata' => 'Armata',
						'Cabin' => 'Cabin',
						'Copse' => 'Copse',
						'Cuprum' => 'Cuprum',
						'Economica' => 'Economica',
						'Exo' => 'Exo',
						'Fresca' => 'Fresca',
						'Gruppo' => 'Gruppo',
						'Herr Von Muellerhoff' => 'Herr Von Muellerhoff',
						'Josefin Slab' =>'Josefin Slab',
						'Oswald' => 'Oswald',
						'Pacifico' => 'Pacifico',
						'Parisienne' => 'Parisienne',
						'Poiret One' => 'Poiret One',
						'Prosto One' => 'Prosto One',
						'Righteous' => 'Righteous',
						'Rokkitt' => 'Rokkitt',
						'Seaweed Script' => 'Seaweed Script',
						'Share' => 'Share',
						'Ubuntu Condensed' => 'Ubuntu Condensed',
						'Voltaire' => 'Voltaire',
						'Wire One' => 'Wire One',
						'Yanone Kaffeesatz' => 'Yanone Kaffeesatz'
				);	
		array_unshift( $option, '--' );						
		
		return $option;
}
?>