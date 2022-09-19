import { registerBlockType } from '@wordpress/blocks';
import { accordionSliderIcon } from './icons';
import edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType( metadata, {
	icon: accordionSliderIcon,
	edit,
	save,
});
