/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
	return (
		<div { ...useBlockProps( { className: 'featured-dish-editor' } ) }>
			<p className="featured-dish-editor__title">
				{ __( 'Uitgelicht gerecht', 'restaurant-pro' ) }
			</p>
			<p className="featured-dish-editor__description">
				{ __( 'Voegt automatisch het eerstvolgende uitgelichte gerecht toe. Beheer de inhoud via het “Dishes” menu.', 'restaurant-pro' ) }
			</p>
		</div>
	);
}
