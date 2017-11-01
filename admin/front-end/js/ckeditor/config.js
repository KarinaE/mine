/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) 
{
    CKEDITOR.dtd.$removeEmpty['i'] = false;
    CKEDITOR.dtd.$removeEmpty['span'] = false;
    config.allowedContent = true;
    config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);
    config.protectedSource.push(/<(span)[^>]*>.*<\/span>/ig);
    config.extraPlugins = 'removeformat';
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
