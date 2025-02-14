<?php
require __DIR__ . '/../phpoffice/vendor/autoload.php'; // Asegúrate de que la ruta es correcta
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$config = [
    'host' => 'mariadb',
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hansitox'
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Construir la consulta base
    $sql = "
        SELECT 
            p.id,
            p.nombre AS padre_nombre,
            p.dni,
            p.telefono,
            p.email,
            p.metodo_pago,
            j.nombre_completo AS jugador_nombre,
            j.grupo,
            j.modalidad,
            j.demarcacion,
            d.descuento
        FROM padres p
        LEFT JOIN jugadores j ON p.id = j.padre_id
        LEFT JOIN descuentos d ON j.id = d.jugador_id
    ";

    // Aplicar filtro de categoría si se especifica
    $categoria = $_GET['categoria'] ?? '';
    $params = [];
    if ($categoria) {
        $sql .= " WHERE j.grupo = ?";
        $params[] = $categoria;
    }

    $sql .= " ORDER BY p.fecha_registro DESC, j.jugador_numero";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crear nuevo documento Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Establecer encabezados
    $encabezados = [
        'ID', 'Padre/Tutor', 'DNI', 'Teléfono', 'Email', 'Método Pago',
        'Jugador', 'Categoría', 'Modalidad', 'Demarcación', 'Descuento'
    ];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '1', $encabezado);
        $col++;
    }

    // Añadir datos
    $row = 2;
    foreach ($datos as $dato) {
        $col = 'A';
        foreach ($dato as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }

    // Configurar cabeceras HTTP para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="inscripciones_campus.xlsx"');
    header('Cache-Control: max-age=0');

    // Guardar archivo
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    error_log("Error en exportación: " . $e->getMessage());
    header('Location: dashboard.php?error=export');
    exit;
}
