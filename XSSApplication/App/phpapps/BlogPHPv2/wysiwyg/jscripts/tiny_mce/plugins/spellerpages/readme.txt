SpellerPages plugin for TinyMCE created by MeejVaj
Published at 8 July 2005
This plugin uses Speller Pages version 0.5.1
------------------------------
NOTICE:
  This plugin only supports php as the server-side-script
  
REQUIREMENTS:
  PHP
  ASPELL
  TinyMCE (if you don't have this then you are going to have a really hard time)

INSTALL:
  -- Besure you have aspell installed
  -- Copy the spellerpages directory to the plugins directory of TinyMCE
  -- Edit "files/server-scripts/spellchecker.php" to choose the correct location for aspell (default is LINUX location)
  -- Add plugin to TinyMCE plugin option list example: plugins : "spellerpages".
  -- Add the spellerpages button name to button list, example: theme_advanced_buttons3_add : "spellerpages".
  -- EXAMPLE:
        tinyMCE.init({
          theme : "advanced",
          mode : "textareas",
          plugins : "spellerpages",
          theme_advanced_buttons3_add : "spellerpages"
        });

EXTRA:
  --Use at your own risk. Be sure to test this plugin on your system before going live with it.
  
  --This plugin uses some unstandard ways of getting Speller Pages to work.  
      They may not be fully TinyMCE compliant (see editor_plugin.js for more details)
      
  --Developer's please be aware that many of the Speller Pages files had to be modified. You can not update
      the Speller Pages version by replacing the files in the "files" directory.
      
CREDIT:
  The speller icon came directly from the FCKeditor project that comes with Speller Pages support built-in.
  Also much of the alterations to the Speller Pages code came from the FCKeditor project files.
  I thank them for their support.