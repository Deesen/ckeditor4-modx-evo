## ckeditor4-modx-evo v4.5.7.0

Requires installation of plugin `assets/plugins/ckeditor4/plugin.ckeditor4.php`:

  - Copy files of `assets/plugins/ckeditor4/` to your Modx-installation 
  - In Modx Manager go to Elements -> Plugins and create new plugin
  - Name it "CKEditor4"
  - Paste content of file `assets/plugins/ckeditor4/plugin.ckeditor4.php` into Modx Plugin-Code
  - Set system-events `OnRichTextEditorRegister, OnRichTextEditorInit, OnInterfaceSettingsRender`
  - Save new plugin
  - Optional for "richtextmini": Same steps as above BUT
    - Name it "CKEditor4 Mini"
    - Paste content of file `assets/plugins/ckeditor4/plugin.ckeditor4_mini.php` into Modx Plugin-Code
  
------------------------------------------------------------------------------
    
##### @todo:
  - finish bridgeParams.path_options
  - rename theme.default to theme.base (base sets general params like editorpath etc)
  - remove option theme.base from configuration-settings
  - add theme "custom": use "custom_plugins", "custom_buttons" ..
  - custom plugin "template button" to insert content of chunk/ressource at cursor position
  - customize all language-files
  - kcfinder-integration safe / final? compare with tinymce3´s `mceOpenServerBrowser()`
  - add custom editorCss-path to settings
  - theme.default: block_format causing error, why?
  - @todos inline

##### History:  
  - 17.02.2016 v4.5.7.0: Initial release
    - link-creation with option to choose from Modx-Ressourcetree
    - kcfinder for img/file-linking
    - documentDirty is catched
    - introducing concept of new class "modxRTEbridge"
        - provides logic for easy integration of Richtext-Editors into Modx Evolution
        - allows dynamic settings via Modx- / user-configuration for multiple RTEs
        - allows dynamically translation of Modx- to specific editor-params
        - themes are handled like skins (choose theme-file instead of limited theme-list) 
    - ready for richtextmini 
    - based on CKEditor 4.5.7