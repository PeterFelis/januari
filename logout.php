<?php
//file: logout.php

session_start();
session_destroy(); // Sessie beëindigen
header("Location: loginForm.php"); // Terug naar loginpagina
exit;
