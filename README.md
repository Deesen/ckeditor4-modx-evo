## ckeditor4-modx-evo v4.5.7.0

Ready for production. All settings and configurations are adapted to the old Modx/TinyMCE3-settings as good as possible

  - Filebrowser / KCfinder integrated for images, flash and files
  - CKEditor-plugin "embed" for media-files
  - Easy link-creation by choosing from Modx ressource-tree
  - Insert templates/chunks with template-button
  - Webuser- / custom-template handling as per TinyMCE3
  - Settings via Modx- / user- and plugin-configuration
  - Multilanguage-Support

------------------------------------------------------------------------------

Manual installation of plugin `assets/plugins/ckeditor4/plugin.ckeditor4.php`:

  - Copy files of `assets/plugins/ckeditor4/` to your Modx-installation 
  - In Modx Manager go to Elements -> Plugins and create new plugin
  - Name it "CKEditor4"
  - Paste content of file `assets/plugins/ckeditor4/plugin.ckeditor4.php` into Modx Plugin-Code
  - Set system-events `OnRichTextEditorRegister, OnRichTextEditorInit, OnInterfaceSettingsRender`
  - Save new plugin
  - Optional for "richtextmini" (not yet merged into Modx 1.1RC): Same steps as above BUT
    - Name it "CKEditor4 Mini"
    - Paste content of file `assets/plugins/ckeditor4/plugin.ckeditor4_mini.php` into Modx Plugin-Code
  
------------------------------------------------------------------------------
    
##### @todo:
  - add linklist/quicksearch to link-dialog (internal links can already be created by choosing from Modx ressource-tree)
  - templates-button: set previewimages-path via custom configuration 
  - check inline @todos

##### History:
  - 20.02.2016:
    - added language-support to bridge for gsettings/custom
    - added language files from old TinyMCE3
      - modified lang.gsettings for general settings
      - prepared lang.custom for future translations
  - 19.02.2016: 
    - Finished themes / plugin / settings
    - added connector.template to support template-button for inserting ressources and chunks
  - 11.02. - 17.02.2016:
    - link-creation with option to choose from Modx-Ressourcetree
    - kcfinder for img/file-linking
    - documentDirty is catched
    - introducing concept of new class "modxRTEbridge"
        - provides logic for easy integration of Richtext-Editors into Modx Evolution
        - allows dynamic settings via Modx- / user-configuration for multiple RTEs
        - allows dynamically translation of Modx- to specific editor-params
        - themes are handled like skins (choose theme-file instead of limited theme-list) 
    - ready for richtextmini (only concept right now)
    - based on CKEditor 4.5.7