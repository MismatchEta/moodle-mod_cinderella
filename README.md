# Cinderella Activity Plugin for Moodle

Todos:
* Replace filepicker with filechooser or save the uploaded file in local fs, not in "mdl_cinderella" table, so that it can be reloaded into the draft area of the filechooser when editing and does not have to be reuploaded every time.
* Find a Moodle like solution for displaying the Cinderella file, instead of the build_cindy_html() in locallib.php
* Don't store CindyJS object as string in DB, instead create a class for it.
* Implement error checks EVERYWHERE.
* Redo the whole locallib.php $cindy creation. Don't force usage of CindyScript for calling javascript, use the appropriate mouseevents on the CSCanvas div. Just get the mover() value from CindyScript.
* If a user e.g. MOVES a Point P in two unrelated activities, the current system would not have a possibility of discerning the two. Find xAPI option to solve this (maybe through activityType using some sort of Moodle identifier)