/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.toolbar_Jquery = [
    ['PasteText','-','Bold','Italic'],
    ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Image','Table','-','Link', 'RemoveFormat','Source']
    ];
    
    config.toolbar = 'Jquery';
};
