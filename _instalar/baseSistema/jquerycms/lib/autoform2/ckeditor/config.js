/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.skin = 'office2003';
    //config.removePlugins =  'elementspath,enterkey,entities,forms,pastefromword,htmldataprocessor,specialchar' ;
    //config.toolbar = 'Basic';
    //'Templates'
    CKEDITOR.config.toolbar = [
    ['PasteText','-','Bold','Italic'],
    ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Image','Table','-','Link', 'RemoveFormat','Source']
    ] ;
    
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_P;
};
