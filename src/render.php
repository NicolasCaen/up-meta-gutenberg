<?php
/**
 * Rendu PHP pour le bloc Meta Display
 *
 * @param array    $attributes Les attributs du bloc.
 * @param string   $content    Le contenu du bloc.
 * @param WP_Block $block      L'instance du bloc.
 * @return string Le HTML rendu.
 */
function render_meta_display($attributes, $content, $block) {
	// Récupération des attributs
	$meta_key = $attributes['metaKey'] ?? '';
	$filter_type = $attributes['filterType'] ?? 'text';

	if (empty($meta_key)) {
		return '';
	}

	// Récupération de la valeur de la meta
	$meta_value = get_post_meta(get_the_ID(), $meta_key, true);

	if (empty($meta_value)) {
		return '';
	}

	// Formatage de la valeur selon le type de filtre
	switch ($filter_type) {
		case 'price':
			$formatted_value = number_format($meta_value, 0, ',', ' ') . ' €';
			break;
		case 'surface':
			$formatted_value = $meta_value . ' m²';
			break;
		case 'reference':
			$formatted_value = 'Ref: ' . $meta_value;
			break;
		case 'title':
			$formatted_value = '<strong>' . esc_html($meta_value) . '</strong>';
			break;
		default:
			$formatted_value = esc_html($meta_value);
	}

	// Récupération des classes du bloc avec support des couleurs
	$wrapper_attributes = get_block_wrapper_attributes();

	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$formatted_value
	);
}
