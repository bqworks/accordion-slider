import { useBlockProps } from '@wordpress/block-editor';

export default function save( props ) {
	const { attributes } = props;

	return (
		<div { ...useBlockProps.save() }>
			{ attributes.accordionId !== -1 && `[accordion_slider id="${ attributes.accordionId }"]` }
		</div>
	);
}
