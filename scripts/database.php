<?php

require_once(__DIR__."/../core/panel.php");

// Iniciamos la base de datos
Database::iniciar();

$files = System::getFolderFiles( BASE_DIR."/scripts/database" );
foreach($files as $file) { require($file); }

$table_usuarios->down();
$table_roles->down();

$table_roles->up()->default();
$table_usuarios->up()->default();