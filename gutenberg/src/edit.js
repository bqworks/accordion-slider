import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { InspectorControls } from '@wordpress/blockEditor';
import { SelectControl, Placeholder } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { accordionSliderIcon } from './icons';

import './editor.scss';

export default function edit( props ) {
	const { attributes, setAttributes } = props;
	const [ accordions, setAccordions ] = useState([]);

	// Create a global object to store the accordion data, so
	// that it needs to be fetched only once, when the first
	// block is added. Additional blocks will use the accordion
	// data stored in the global object.
	if ( typeof window.accordionSlider === 'undefined' ) {
		window.accordionSlider = {
			accordions: [],
			accordionsDataStatus: '' // can be '', 'loading' or 'loaded'
		};
	}

	// Load the accordion data and store the accordion name and id,
	// as 'label' and 'value' to be used in the SelectControl.
	const getAccordionsData = () => new Promise( ( resolve ) => {
		wp.apiFetch({
			path: 'accordion-slider/v1/accordions'
		}).then( function( responseData ) {
			let accordionsData = [];
			
			for ( const key in responseData ) {
				accordionsData.push({
					label: `${ responseData[ key ] } (${ key })`,
					value: parseInt( key )
				});
			}

			resolve( accordionsData );
		});
	});

	// Get a accordion by its id.
	const getAccordion = ( accordionId ) => {
		const accordion = accordions.find( ( accordion ) => {
			return accordion.value === accordionId;
		});

		return typeof accordion !== 'undefined' ? accordion : false;
	};

	// Get the accordion's label by its id.
	const getAccordionLabel = ( accordionId ) => {
		const accordion = getAccordion( accordionId );

		return accordion !== false ? accordion.label: '';
	};

	// Initialize the component by setting the 'accordions' property
	// which will trigger the rendering of the component.
	//
	// If the accordions data is already globally available, set the 'accordions'
	// immediately. If the accordions data is currently loading, wait for it
	// to load and then set the 'accordions'. If it's not currently loading,
	// start the loading process.
	const init = () => {
		if ( window.accordionSlider.accordionsDataStatus === 'loaded' ) {
			setAccordions( window.accordionSlider.accordions );
		} else if ( window.accordionSlider.accordionsDataStatus === 'loading' ) {
			const checkApiFetchInterval = setInterval( function() {
				if ( window.accordionSlider.accordionsDataStatus === 'loaded' ) {
					clearInterval( checkApiFetchInterval );
					setAccordions( window.accordionSlider.accordions );
				}
			}, 100 );
		} else {
			window.accordionSlider.accordionsDataStatus = 'loading';

			getAccordionsData().then( ( accordionsData ) => {
				window.accordionSlider.accordionsDataStatus = 'loaded';
				window.accordionSlider.accordions = accordionsData;

				setAccordions( accordionsData );
			});
		}
	}

	useEffect( () => {
		init();
	}, [] );

	return (
		<div { ...useBlockProps() }>
			<Placeholder label='Accordion Slider' icon={ accordionSliderIcon }>
				{
					window.accordionSlider.accordionsDataStatus !== 'loaded' ?
						<div className='sp-gutenberg-accordion-placeholder-content'> { __( 'Loading Accordion Slider data...', 'accordion-slider' ) } </div>
					: (
						window.accordionSlider.accordions.length === 0 ?
							<div className='sp-gutenberg-accordion-placeholder-content'> { __( 'You don\'t have any created accordions yet.', 'accordion-slider' ) } </div>
						: (
							getAccordion( attributes.accordionId ) === false ?
								<div className='sp-gutenberg-accordion-placeholder-content'> { __( 'Select an accordion from the Block settings.', 'accordion-slider' ) } </div>
							: (
								<div className='sp-gutenberg-accordion-placeholder-content'>
									<p className='sp-gutenberg-accordion-identifier'> { getAccordionLabel( attributes.accordionId ) } </p>
									<a className='sp-gutenberg-edit-accordion' href={`${ as_gutenberg_js_vars.admin_url }?page=accordion-slider&id=${ attributes.accordionId }&action=edit`} target='_blank'> { __( 'Edit Accordion', 'accordion-slider' ) } </a>
								</div>
							)
						)
					)
				}
			</Placeholder>

			<InspectorControls>
				<SelectControl
					className='sp-gutenberg-select-accordion'
					label={ __( 'Select an accordion from the list:', 'accordion-slider' ) }
					options={ [ { label: __( 'None', 'accordion-slider'), value: -1 }, ...accordions ] }
					value={ attributes.accordionId }
					onChange={ ( newAccordionId ) => setAttributes( { accordionId: parseInt( newAccordionId ) } ) }
				/>
				{
					window.accordionSlider.accordions.length === 0 &&
					<p 
						className='sp-gutenberg-no-accordions-text'
						dangerouslySetInnerHTML={{
							__html: sprintf( __( 'You don\'t have any created accordions yet. You can create and manage accordions in the <a href="%s" target="_blank">dedicated area</a>, and then use the block to load the accordions.', 'accordion-slider' ), `${ as_gutenberg_js_vars.admin_url }?page=accordion-slider` )
						}}>
					</p>
				}
			</InspectorControls>
		</div>
	);
}
