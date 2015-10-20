<?php

header("HTTP/1.0 404 Not Found");
exit("<h1>Not Found</h1>
The requested URL " . $_SERVER["REQUEST_URI"] . " was not found on this server.
<hr>");

?>