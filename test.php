<?php
echo fsockopen("smtp.gmail.com", 587, $errno, $errstr, 10) ? "OPEN" : "BLOCKED: $errstr";
?>
