## ckeditor4-modx-evo v4.5.10

Ready for production. All settings and configurations are adapted to the old Modx/TinyMCE3-settings as good as possible

  - Filebrowser / KCfinder integrated for images, flash and files
  - CKEditor-plugin "embed" for media-files
  - Easy link-creation by choosing from Modx ressource-tree
  - Insert templates/chunks with template-button
  - Webuser- / custom-template handling as per TinyMCE3
  - Settings via Modx- / user- and plugin-configuration
  - "Unlimited" themes can be created
  - Multilanguage-Support

------------------------------------------------------------------------------

Manual installation of plugin `assets/plugins/ckeditor4/plugin.ckeditor4.php`:

  - Copy files of `assets/plugins/ckeditor4/` to your Modx-installation 
  - In Modx Manager go to Elements -> Plugins and create new plugin
  - Name it "CKEditor4"
  - Paste content of file `assets/plugins/ckeditor4/plugin.ckeditor4.php` into Modx Plugin-Code
  - Set system-events `OnLoadWebDocument,OnParseDocument,OnWebPagePrerender,OnLoadWebPageCache,OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender`
  - Save new plugin
  
------------------------------------------------------------------------------
    
##### @todo:
  - add linklist/quicksearch to link-dialog (internal links can already be created by choosing from Modx ressource-tree)
  - templates-button: set previewimages-path via custom configuration 
  - add 2 example preview-images
  - prepare screenshots
  - check inline @todos

##### History:
  - 01.09.2016:
    - updated using latest modxRTEbridge
    - new feature: Frontend / Inline-Edit
    - new feature: Introtext-RTE
    - updated to CKEditor 4.5.10
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