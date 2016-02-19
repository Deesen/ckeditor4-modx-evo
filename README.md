## ckeditor4-modx-evo v4.5.7.0

Ready for production. All settings and configurations are adapted to the old TinyMCE3-settings as good as possible.

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
  - add linklist to link-button
  - customize all language-files
  - add optional modx-langKey to editor-langKey translation to bridge?
  - check inline @todos

##### History:
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