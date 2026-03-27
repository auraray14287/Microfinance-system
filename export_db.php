<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=loan_management', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $sql = "SET FOREIGN_KEY_CHECKS=0;\n\n";

    foreach ($tables as $table) {
        $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
        $sql .= "DROP TABLE IF EXISTS `$table`;\n" . $create[1] . ";\n\n";

        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $vals = array_map(fn($v) => $v === null ? 'NULL' : $pdo->quote($v), $row);
            $sql .= "INSERT INTO `$table` VALUES (" . implode(',', $vals) . ");\n";
        }
        $sql .= "\n";
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    file_put_contents('rafikibora_export.sql', $sql);
    echo "Done - " . number_format(strlen($sql)) . " bytes\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
