<?php
//file: logout.php

include_once __DIR__ . '/incs/sessie.php';
session_destroy(); // Sessie beëindigen
header("Location: loginForm.php"); // Terug naar loginpagina
exit;
