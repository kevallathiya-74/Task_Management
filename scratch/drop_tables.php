<?php
$host = '127.0.0.1';
$db = 'task_management';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $queries = [
        "DROP TABLE IF EXISTS publishing_table_assignments",
        "DROP TABLE IF EXISTS publishing_report_cells",
        "DROP TABLE IF EXISTS publishing_report_rows",
        "DROP TABLE IF EXISTS publishing_reports"
    ];
    
    foreach ($queries as $query) {
        $pdo->exec($query);
        echo "Executed: $query\n";
    }
    echo "All tables dropped successfully.\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
