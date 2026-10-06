<!-- Autors:Ervīns Jēkabsons; Izlogošaņas koda daļa kurš izslēdz lietotāju no piekļuves auto reģistrācijai
un lai atkal veiktu reģistrāciju būs niepieciešams pieslēgties no jauna
Veidots 2024.gadā, jaunākā vesrija-->
<?php
session_start();
session_destroy(); // Destroy the session to log out the user

// Redirect to login page
header("Location: index.php");
exit();

session_start();
session_unset();
session_destroy();
header("Location: admin_login.php");
exit;
?>
