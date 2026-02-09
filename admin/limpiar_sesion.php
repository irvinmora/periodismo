<?php
session_start();
session_destroy();
echo "Sesión limpiada. <a href='login.php'>Volver al login</a>";
?>