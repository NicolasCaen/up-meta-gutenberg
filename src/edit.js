/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { 
	useBlockProps, 
	InspectorControls,
	BlockControls,
	AlignmentToolbar,
} from '@wordpress/block-editor';

import { 
	PanelBody, 
	SelectControl, 
	TextControl,
	ToolbarGroup,
} from '@wordpress/components';

import { useEffect } from 'react';
import { useSelect } from '@wordpress/data';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { metaKey, filterType, align } = attributes;

	// Options de filtres disponibles
	const filterOptions = [
		{ label: __('Texte', 'up-meta-gutenberg'), value: 'text' },
		{ label: __('Prix', 'up-meta-gutenberg'), value: 'price' },
		{ label: __('Surface', 'up-meta-gutenberg'), value: 'surface' },
		{ label: __('Titre', 'up-meta-gutenberg'), value: 'title' },
		{ label: __('Référence', 'up-meta-gutenberg'), value: 'reference' }
	];

	// Récupération de la valeur de la meta
	const metaValue = useSelect((select) => {
		if (!metaKey) return '';
		return select('core/editor').getEditedPostAttribute('meta')?.[metaKey] || '';
	}, [metaKey]);

	// Formatage de la valeur selon le filtre
	const formatValue = (value) => {
		if (!value) return '';

		switch (filterType) {
			case 'price':
				return new Intl.NumberFormat('fr-FR', {
					style: 'currency',
					currency: 'EUR'
				}).format(value);
			case 'surface':
				return `${value} m²`;
			case 'reference':
				return `Ref: ${value}`;
			case 'title':
				return <strong>{value}</strong>;
			default:
				return value;
		}
	};

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={align}
					onChange={(newAlign) => setAttributes({ align: newAlign })}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody title={__('Paramètres', 'up-meta-gutenberg')}>
					<TextControl
						label={__('Clé de la meta', 'up-meta-gutenberg')}
						value={metaKey}
						onChange={(value) => setAttributes({ metaKey: value })}
						help={__('Entrez la clé de la meta à afficher', 'up-meta-gutenberg')}
					/>
					<SelectControl
						label={__('Type de filtre', 'up-meta-gutenberg')}
						value={filterType}
						options={filterOptions}
						onChange={(value) => setAttributes({ filterType: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				{metaValue ? (
					formatValue(metaValue)
				) : (
					<div className="placeholder">
						{metaKey ? (
							__('Aucune valeur trouvée pour cette meta', 'up-meta-gutenberg')
						) : (
							__('Veuillez sélectionner une clé de meta', 'up-meta-gutenberg')
						)}
					</div>
				)}
			</div>
		</>
	);
}
