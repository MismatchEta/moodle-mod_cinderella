# Cinderella Activity Plugin for Moodle

Todos:
* Replace filepicker with filechooser or save the uploaded file in local fs, not in "mdl_cinderella" table, so that it can be reloaded into the draft area of the filechooser when editing and does not have to be reuploaded every time.
* Find a Moodle like solution for displaying the Cinderella file, instead of the build_cindy_html() in locallib.php
* Don't store CindyJS object as string in DB, instead create a class for it.
    