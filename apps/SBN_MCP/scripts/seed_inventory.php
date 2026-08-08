<?php
/**
 * Seed script — genera inventario ficticio realista para SBN_MCP
 * Ejecutar: C:\xampp\php\php.exe apps/SBN_MCP/scripts/seed_inventory.php
 */
$dsn = 'mysql:host=localhost;dbname=hospital_bienes;charset=utf8mb4';
try {
    $conn = new PDO($dsn, 'root', 'admin', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, "Error de conexion: " . $e->getMessage() . "\n");
    exit(1);
}

echo "Conectado a hospital_bienes\n";
$conn->exec('SET FOREIGN_KEY_CHECKS = 0');
$conn->exec('SET SQL_LOG_BIN = 0');

// ── Limpiar ──
$tables = ['verificaciones','password_resets','notificaciones','auditoria','backups_log',
           'movimientos','mantenimientos','bienes','usuarios','areas','configuracion'];
foreach ($tables as $t) {
    $conn->exec("DELETE FROM `$t`");
}
foreach (['bienes','usuarios','areas','movimientos','mantenimientos','verificaciones'] as $t) {
    $conn->exec("ALTER TABLE `$t` AUTO_INCREMENT = 1");
}
echo "Tablas limpiadas\n";

// ── Helpers ──
function insert(PDO $c, string $table, array $row) {
    $cols = implode(', ', array_keys($row));
    $ph   = implode(', ', array_fill(0, count($row), '?'));
    $c->prepare("INSERT INTO `$table` ($cols) VALUES ($ph)")->execute(array_values($row));
}

function hash_() {
    return '$2y$12$LJ3m4kVz5K8Qz9v2X6y3OeYqHgFpDnMbWcRtYuIoPlKjHgFdSaS4m';
}

// ── USUARIOS ──
echo "Insertando usuarios...\n";
$usuarios = [
    [1,1,'20123456','admin','Maria','Elena','Rodriguez','Perez','Maria Elena Rodriguez Perez','admin@mcp.gob.ve',hash_(),'0414-1234567','Gerente de Bienes Nacionales',0,1],
    [2,2,'18765432','gerencia','Carlos','Alberto','Martinez','Lopez','Carlos Alberto Martinez Lopez','gerencia@mcp.gob.ve',hash_(),'0412-9876543','Subgerente Administrativo',0,1],
    [3,3,'21345678','inventario','Ana','Luz','Garcia','Torres','Ana Luz Garcia Torres','inventario@mcp.gob.ve',hash_(),'0416-5551234','Controlador de Inventario',0,1],
    [4,4,'19876543','registrador','Pedro','Jose','Fernandez','Ramos','Pedro Jose Fernandez Ramos','registrador@mcp.gob.ve',hash_(),'0424-7778899','Registrador de Bienes',0,1],
    [5,1,'17654321','admin2','Luis','Fernando','Sanchez','Mendez','Luis Fernando Sanchez Mendez','admin2@mcp.gob.ve',hash_(),'0414-3334455','Jefe de Area BN',0,1],
];
foreach ($usuarios as $u) {
    insert($conn, 'usuarios', [
        'id_usuario'=>$u[0],'id_rol'=>$u[1],'cedula'=>$u[2],'username'=>$u[3],
        'primer_nombre'=>$u[4],'segundo_nombre'=>$u[5],'primer_apellido'=>$u[6],
        'segundo_apellido'=>$u[7],'nombre_completo'=>$u[8],'email'=>$u[9],
        'password_hash'=>$u[10],'telefono'=>$u[11],'cargo'=>$u[12],
        'primer_login'=>$u[13],'activo'=>$u[14],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),
    ]);
}
echo "  5 usuarios creados\n";

// ── AREAS ──
echo "Insertando areas...\n";
$areas = [
    [1,'Mantenimiento Tecnico','Taller de mantenimiento','Principal',-1],
    [2,'Lavanderia','Servicio de lavanderia','Principal',-1],
    [3,'Electromedicina','Dept. electromedicina','Principal',-1],
    [4,'Servicios Generales','Servicios generales','Principal',-1],
    [5,'Telecomunicaciones','Centro telecomunicaciones','Principal',-1],
    [6,'Sala de Parto','Sala de partos','Principal',1],
    [7,'Quirófano','Salas de operaciones','Principal',1],
    [8,'Banco de Sangre','Banco de sangre','Principal',1],
    [9,'Anestesia','Dept. anestesia','Principal',1],
    [10,'Terapia Adulto','Unidad terapia adulto','Principal',1],
    [11,'Nutrición','Dept. nutrición','Principal',1],
    [12,'Gerencia de Bienes Nacionales','Dept. control bienes','Principal',1],
    [13,'Administracion','Area administrativa','Principal',1],
    [14,'Materno-Fetal','Unidad materno-fetal','Principal',2],
    [15,'Consultas de Eco','Ecografia','Principal',2],
    [16,'Hospitalizacion P2','Hosp. piso 2','Principal',2],
    [17,'Hospitalizacion P3','Hosp. piso 3','Principal',3],
    [18,'Hospitalizacion P5','Hosp. piso 5','Principal',5],
    [19,'Residencias Medicas','Residencias medicas','Principal',9],
    [20,'Milicianos','Sede milicianos','Anexo',-1],
    [21,'Odontologia','Servicio odontologia','Anexo',0],
    [22,'Prenatal','Consultas prenatales','Anexo',0],
    [23,'Adolescentes','Servicio adolescentes','Anexo',1],
    [24,'Fertilidad','Unidad fertilidad','Anexo',3],
    [25,'Psiquiatria','Servicio psiquiatria','Anexo',3],
    [26,'Medicina Interna','Medicina interna','Anexo',3],
    [27,'Cuidados Intensivos Neonatal','UCIN','Anexo',2],
    [28,'Urgencias','Servicio urgencias 24h','Principal',0],
    [29,'Farmacia','Farmacia central','Principal',0],
    [30,'Radiologia','Radiologia e imagenologia','Principal',0],
];
foreach ($areas as $a) {
    insert($conn, 'areas', [
        'id_area'=>$a[0],'nombre_area'=>$a[1],'descripcion'=>$a[2],
        'edificio'=>$a[3],'piso'=>$a[4],'activa'=>1,
        'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),
    ]);
}
echo "  30 areas creadas\n";

// ── BIENES ──
echo "Insertando bienes...\n";
$bienes = [
    ['BN-2024-000001','01-1-PRN-1-0001','MN-2024-001',0,'Escritorio Ejecutivo','Escritorio con cajones','ESC-001','Ofimax','EX-400','Marron',2022,1,'Bueno',1,1,13,'Maria Elena Rodriguez','20123456','Principal','1','Administracion','Direccion','P-01','FAC-001','2024-01-15',350,35,10],
    ['BN-2024-000002','01-1-PRN-1-0002','MN-2024-002',0,'Silla Ergonomica','Giratoria ergonomica','SIL-002','Eurotech','ZUO','Negro',2023,1,'Nuevo',1,1,13,'Carlos Alberto Martinez','18765432','Principal','1','Administracion','Gerencia','P-02','FAC-002','2024-02-10',280,28,10],
    ['BN-2024-000003','01-1-PRN-1-0003','MN-2024-003',0,'Computadora de Escritorio','PC i5 8GB 256GB SSD','PC-003','HP','Pro 280 G6','Negro',2023,1,'Nuevo',1,14,12,'Ana Luz Garcia','21345678','Principal','1','Gerencia BN','Control','P-03','FAC-003','2024-01-20',890,89,5],
    ['BN-2024-000004','01-1-PRN-1-0004','MN-2024-004',0,'Monitor 24 pulgadas','LED Full HD 24"','MON-004','Samsung','LF24T350','Negro',2023,1,'Nuevo',1,14,12,'Ana Luz Garcia','21345678','Principal','1','Gerencia BN','Control','P-04','FAC-004','2024-01-20',195,19.5,5],
    ['BN-2024-000005','01-1-PRN-1-0005','MN-2024-005',0,'Impresora Laser','Laser monocromatica','IMP-005','Brother','HL-L2350DW','Gris',2023,1,'Nuevo',1,14,13,'Maria Elena Rodriguez','20123456','Principal','1','Administracion','Archivo','P-05','FAC-005','2024-03-01',220,22,5],
    ['BN-2024-000006','01-1-PRN-1-0006','MN-2024-006',0,'Archivador Metalico','4 cajones metalico','ARC-006','Vidaland','A4-400','Gris',2022,1,'Bueno',1,1,13,'Pedro Jose Fernandez','19876543','Principal','1','Administracion','Archivo','P-06','FAC-006','2024-01-10',180,18,10],
    ['BN-2024-000007','01-1-PRN-1-0007','MN-2024-007',0,'Aire Acondicionado','Split 12000 BTU','AAC-007','Daikin','FTKF35TV','Blanco',2023,1,'Nuevo',1,3,13,'Luis Fernando Sanchez','17654321','Principal','1','Administracion','Gerencia','P-07','FAC-007','2024-02-15',650,65,10],
    ['BN-2024-000008','01-1-PRN-1-0008','MN-2024-008',0,'Proyector Multimedia','LED 3500 lumenes','PRY-008','Epson','EB-W52','Blanco',2023,1,'Nuevo',1,10,10,'Carlos Alberto Martinez','18765432','Principal','1','Terapia Adulto','Sala Reunion','P-08','FAC-008','2024-03-10',520,52,10],
    ['BN-2024-000009','01-1-PRN-1-0009','MN-2024-009',0,'Mesa de Reuniones','8 personas','MR-009','OFIMBLE','MR-8P','Marron',2022,1,'Bueno',1,1,13,'Maria Elena Rodriguez','20123456','Principal','1','Administracion','Sala Juntas','P-09','FAC-009','2024-01-05',450,45,10],
    ['BN-2024-000010','01-1-PRN-1-0010','MN-2024-010',0,'Telefono VoIP','Telefono IP escritorio','TEL-010','Cisco','SPA303','Negro',2023,1,'Nuevo',1,6,5,'Pedro Jose Fernandez','19876543','Principal','-1','Telecomunicaciones','Central','P-10','FAC-010','2024-02-20',150,15,8],
    ['BN-2024-000011','06-1-PRN-1-0001','MN-2024-011',0,'Camilla Hospitalaria','Con ruedas y barandas','CAM-011','Hill-Rom','Centera','Blanco',2022,1,'Bueno',1,7,6,'Pedro Jose Fernandez','19876543','Principal','1','Sala de Parto','Box 1','P-11','FAC-011','2024-01-15',1800,180,10],
    ['BN-2024-000012','06-1-PRN-1-0002','MN-2024-012',0,'Monitor Signos Vitales','Multiparametrico ECG/SpO2','MSV-012','Nihon Kohden','BSM-6301K','Gris',2023,1,'Nuevo',1,7,7,'Maria Elena Rodriguez','20123456','Principal','1','Quirófano','Q1','P-12','FAC-012','2024-02-01',3500,350,10],
    ['BN-2024-000013','06-1-PRN-1-0003','MN-2024-013',0,'Desfibrilador','Automatico externo','DEF-013','Philips','HeartStart FRx','Gris',2023,1,'Nuevo',1,7,28,'Carlos Alberto Martinez','18765432','Principal','0','Urgencias','Sala R','P-13','FAC-013','2024-03-05',4200,420,10],
    ['BN-2024-000014','06-1-PRN-1-0004','MN-2024-014',0,'Equipo de Anestesia','De cabecera','EQA-014','Dräger','Fabius GS','Blanco',2022,1,'Bueno',1,8,9,'Luis Fernando Sanchez','17654321','Principal','1','Anestesia','Q2','P-14','FAC-014','2024-01-25',12000,1200,10],
    ['BN-2024-000015','06-1-PRN-1-0005','MN-2024-015',0,'Incubadora Neonatal','Cuidados intensivos neonatal','INC-015','Draeger','BABybreze','Blanco',2023,1,'Nuevo',1,8,27,'Ana Luz Garcia','21345678','Anexo','2','Cuidados Intensivos Neonatal','UCIN','P-15','FAC-015','2024-04-01',8500,850,10],
    ['BN-2024-000016','06-1-PRN-1-0006','MN-2024-016',0,'Mesa Quirurgica','Hidraulica multifuncional','MQ-016','Trumpf','TruSystem 7500','Gris',2022,1,'Bueno',1,8,7,'Pedro Jose Fernandez','19876543','Principal','1','Quirófano','Q1','P-16','FAC-016','2024-01-10',8900,890,10],
    ['BN-2024-000017','06-1-PRN-1-0007','MN-2024-017',0,'Lampara Quirurgica LED','Techo LED quirófano','LQ-017','Stryker','Solaris 7000','Blanco',2023,1,'Nuevo',1,8,7,'Maria Elena Rodriguez','20123456','Principal','1','Quirófano','Q1','P-17','FAC-017','2024-02-20',5600,560,10],
    ['BN-2024-000018','06-1-PRN-1-0008','MN-2024-018',0,'Bomba de Infusion','Volumetrica','BI-018','B.Braun','Infusomat S','Blanco',2023,1,'Nuevo',1,8,10,'Carlos Alberto Martinez','18765432','Principal','1','Terapia Adulto','Box T','P-18','FAC-018','2024-03-15',1200,120,10],
    ['BN-2024-000019','06-1-PRN-1-0009','MN-2024-019',0,'Oximetro de Pulso','Dedo','OXI-019','Nonin','3230','Azul',2022,1,'Bueno',1,7,6,'Ana Luz Garcia','21345678','Principal','1','Sala de Parto','Box 2','P-19','FAC-019','2024-01-05',350,35,10],
    ['BN-2024-000020','06-1-PRN-1-0010','MN-2024-020',0,'Equipo Rayos X','Portatil digital','RX-020','Carestream','DRX-Revolution','Gris',2023,1,'Nuevo',1,10,30,'Luis Fernando Sanchez','17654321','Principal','0','Radiologia','Sala RX','P-20','FAC-020','2024-02-01',15000,1500,10],
    ['BN-2024-000021','07-1-PRN-1-0001','MN-2024-021',0,'Servidor Rack','Servidor de datos','SRV-021','Dell','PowerEdge T350','Negro',2023,1,'Nuevo',1,14,5,'Pedro Jose Fernandez','19876543','Principal','-1','Telecomunicaciones','Server','P-21','FAC-021','2024-01-30',3200,320,5],
    ['BN-2024-000022','07-1-PRN-1-0002','MN-2024-022',0,'Switch Red 48 Puertos','Gestionable Gigabit','SW-022','Cisco','CBS250-48T','Negro',2023,1,'Nuevo',1,14,5,'Pedro Jose Fernandez','19876543','Principal','-1','Telecomunicaciones','Rack','P-22','FAC-022','2024-01-30',890,89,5],
    ['BN-2024-000023','07-1-PRN-1-0003','MN-2024-023',0,'UPS 3000VA','No-break 3000VA','UPS-023','APC','SRT3000','Negro',2023,1,'Nuevo',1,4,5,'Maria Elena Rodriguez','20123456','Principal','-1','Telecomunicaciones','Rack','P-23','FAC-023','2024-02-10',1800,180,5],
    ['BN-2024-000024','07-1-PRN-1-0004','MN-2024-024',0,'Router Inalambrico','WiFi 6 empresarial','RUT-024','Ubiquiti','UniFi U6','Blanco',2023,1,'Nuevo',1,6,5,'Carlos Alberto Martinez','18765432','Principal','-1','Telecomunicaciones','Techo','P-24','FAC-024','2024-03-01',320,32,8],
    ['BN-2024-000025','07-1-PRN-1-0005','MN-2024-025',0,'Notebook Ejecutiva','i7 16GB 512GB SSD','NB-025','Lenovo','ThinkPad T14','Negro',2023,1,'Nuevo',1,14,13,'Luis Fernando Sanchez','17654321','Principal','1','Administracion','Gerencia','P-25','FAC-025','2024-02-15',1450,145,5],
    ['BN-2024-000026','08-1-PRN-1-0001','MN-2024-026',0,'Aspiradora Industrial','Agua y polvo industrial','ASP-026','Kärcher','NT 30/1','Amarillo',2022,1,'Bueno',1,3,1,'Pedro Jose Fernandez','19876543','Principal','-1','Mantenimiento','Taller','P-26','FAC-026','2024-01-10',850,85,10],
    ['BN-2024-000027','08-1-PRN-1-0002','MN-2024-027',0,'Hidrolavadora','Presion limpieza','HID-027','Kärcher','K5','Amarillo',2023,1,'Nuevo',1,3,1,'Ana Luz Garcia','21345678','Principal','-1','Mantenimiento','Taller','P-27','FAC-027','2024-02-01',620,62,10],
    ['BN-2024-000028','08-1-PRN-1-0003','MN-2024-028',0,'Plancha Industrial Vapor','Industrial lavanderia','PLA-028','Speed Queen','SP18','Gris',2022,1,'Bueno',1,3,2,'Maria Elena Rodriguez','20123456','Principal','-1','Lavanderia','Planchado','P-28','FAC-028','2024-01-20',2800,280,10],
    ['BN-2024-000029','08-1-PRN-1-0004','MN-2024-029',0,'Lavadora Industrial','Automatica 20kg','LAV-029','Miele','PW 6123','Gris',2023,1,'Nuevo',1,3,2,'Carlos Alberto Martinez','18765432','Principal','-1','Lavanderia','Lavado','P-29','FAC-029','2024-03-01',4500,450,10],
    ['BN-2024-000030','08-1-PRN-1-0005','MN-2024-030',0,'Secadora Industrial','Tambor industrial','SEC-030','Miele','PT 8303','Gris',2023,1,'Nuevo',1,3,2,'Luis Fernando Sanchez','17654321','Principal','-1','Lavanderia','Secado','P-30','FAC-030','2024-03-01',3800,380,10],
    ['BN-2024-000031','06-2-PRN-1-0001','MN-2024-031',0,'Silla Odontologica','Dental instrumentos basicos','SOD-031','A dec','Allegro II','Azul',2023,1,'Nuevo',1,9,21,'Pedro Jose Fernandez','19876543','Anexo','0','Odontologia','Consultorio','P-31','FAC-031','2024-02-01',5200,520,10],
    ['BN-2024-000032','06-2-PRN-1-0002','MN-2024-032',0,'Rayos X Dental','Periapical dental','RXD-032','Dürr','Dentalix','Blanco',2022,1,'Bueno',1,9,21,'Ana Luz Garcia','21345678','Anexo','0','Odontologia','Consultorio','P-32','FAC-032','2024-01-15',3200,320,10],
    ['BN-2024-000033','06-2-PRN-1-0003','MN-2024-033',0,'Compresor Sin Aceite','Dental sin aceite','CDA-033','Tecnogaz','T201','Blanco',2023,1,'Nuevo',1,9,21,'Maria Elena Rodriguez','20123456','Anexo','0','Odontologia','Consultorio','P-33','FAC-033','2024-02-10',1800,180,10],
    ['BN-2024-000034','06-2-PRN-1-0004','MN-2024-034',0,'Autoclave Dental','Esterilizacion instrumentos','ACL-034','Cox','Prestige','Gris',2023,1,'Nuevo',1,9,21,'Carlos Alberto Martinez','18765432','Anexo','0','Odontologia','Esterilizacion','P-34','FAC-034','2024-03-01',2100,210,10],
    ['BN-2024-000035','06-2-PRN-1-0005','MN-2024-035',0,'Lampara Operatoria Dental','LED operatoria dental','LOD-035','LED Medical','D-LED','Blanco',2023,1,'Nuevo',1,9,21,'Luis Fernando Sanchez','17654321','Anexo','0','Odontologia','Consultorio','P-35','FAC-035','2024-02-15',450,45,10],
    ['BN-2024-000036','07-1-PRN-1-0006','MN-2024-036',0,'Ecografo Portatil','Sonda convexa','ECO-036','GE','Venue Go','Gris',2023,1,'Nuevo',1,10,15,'Pedro Jose Fernandez','19876543','Principal','2','Consultas de Eco','Sala Eco','P-36','FAC-036','2024-02-20',8500,850,10],
    ['BN-2024-000037','07-1-PRN-1-0007','MN-2024-037',0,'Ecografo de Piso','Con impresora termica','ECOP-037','Philips','Affiniti 50','Gris',2022,1,'Bueno',1,10,14,'Ana Luz Garcia','21345678','Principal','2','Materno-Fetal','Sala MF','P-37','FAC-037','2024-01-10',18000,1800,10],
    ['BN-2024-000038','06-1-PRN-1-0011','MN-2024-038',0,'Doppler Fetal','Portatil','DOP-038','Huntleigh','DigiMax','Azul',2023,1,'Nuevo',1,8,22,'Maria Elena Rodriguez','20123456','Anexo','0','Prenatal','Consulta','P-38','FAC-038','2024-03-10',380,38,10],
    ['BN-2024-000039','06-1-PRN-1-0012','MN-2024-039',0,'Tocodinametro','Monitor contracciones','TOD-039','Corometrics','259','Gris',2022,1,'Bueno',1,8,6,'Carlos Alberto Martinez','18765432','Principal','1','Sala de Parto','Box 1','P-39','FAC-039','2024-01-20',2200,220,10],
    ['BN-2024-000040','07-1-PRN-1-0008','MN-2024-040',0,'Rayos X Panoramico','Dental panoramico','RXP-040','Planmeca','ProMax','Blanco',2023,1,'Nuevo',1,10,30,'Luis Fernando Sanchez','17654321','Principal','0','Radiologia','Sala RX','P-40','FAC-040','2024-03-15',22000,2200,10],
    ['BN-2024-000041','03-1-PRN-1-0001','MN-2024-041',0,'Refrigerador Farmaceutico','400L medicamentos','REF-041','Westinghouse','WRB400','Blanco',2023,1,'Nuevo',1,2,29,'Pedro Jose Fernandez','19876543','Principal','0','Farmacia','Almacen','P-41','FAC-041','2024-02-01',850,85,10],
    ['BN-2024-000042','03-1-PRN-1-0002','MN-2024-042',0,'Refrigerador Vacunas','Monitor temperatura','REV-042','Gram','Biomedical','Blanco',2023,1,'Nuevo',1,2,29,'Ana Luz Garcia','21345678','Principal','0','Farmacia','Vaccunas','P-42','FAC-042','2024-02-10',1200,120,10],
    ['BN-2024-000043','03-1-PRN-1-0003','MN-2024-043',0,'Nevera Portatil','Transporte medicamentos','NEV-043','Igloo','BMX 65','Azul',2023,1,'Nuevo',1,2,11,'Maria Elena Rodriguez','20123456','Principal','1','Nutricion','Almacen','P-43','FAC-043','2024-03-01',180,18,10],
    ['BN-2024-000044','03-1-PRN-1-0004','MN-2024-044',0,'Mesa Instrumentos','Inoxidable quirurgica','MEI-044','Mikron','MI-2000','Gris',2022,1,'Bueno',1,3,7,'Carlos Alberto Martinez','18765432','Principal','1','Quirófano','Q1','P-44','FAC-044','2024-01-15',650,65,10],
    ['BN-2024-000045','03-1-PRN-1-0005','MN-2024-045',0,'Carro Curaciones','Multi-uso con cajones','CC-045','Bender','Moduline','Gris',2023,1,'Nuevo',1,3,28,'Luis Fernando Sanchez','17654321','Principal','0','Urgencias','Sala Curac','P-45','FAC-045','2024-03-10',420,42,10],
    // Inoperativos
    ['BN-2023-000056','01-1-PRN-1-0013','MN-2023-056',0,'Impresora Matriz','Antigua impresora','IPM-056','Epson','LQ-350','Gris',2018,1,'Regular',2,14,13,'Pedro Jose Fernandez','19876543','Principal','1','Administracion','Archivo','P-56','FAC-056','2023-06-01',650,65,5],
    ['BN-2023-000057','06-1-PRN-1-0019','MN-2023-057',0,'Oximetro de Piso','Pantalla TFT','OXIP-057','Masimo','Rad-97','Gris',2020,1,'Bueno',2,7,27,'Ana Luz Garcia','21345678','Anexo','2','Cuidados Intensivos Neonatal','UCIN','P-57','FAC-057','2023-03-15',1800,180,10],
    // En resguardo
    ['BN-2022-000058','03-1-PRN-1-0007','MN-2022-058',0,'Regulador 2000VA','Antiguo regulador','REG-058','Sencamer','RVE-2000','Gris',2015,1,'Malo',3,4,5,'Maria Elena Rodriguez','20123456','Principal','-1','Telecomunicaciones','Bodega','P-58','FAC-058','2022-08-01',280,28,5],
    ['BN-2023-000061','06-1-PRN-1-0021','MN-2023-061',0,'Bomba Infusion 2','Falla electronica','BI2-061','B.Braun','Accufuser','Blanco',2021,1,'Bueno',2,8,10,'Pedro Jose Fernandez','19876543','Principal','1','Terapia Adulto','Bodega','P-61','FAC-061','2023-01-10',980,98,10],
    ['BN-2022-000062','05-1-PRN-1-0002','MN-2022-062',0,'Motocicleta Oficial','Mensajeria institucional','MOT-062','Honda','Wave 110','Azul',2020,1,'Bueno',3,5,4,'Ana Luz Garcia','21345678','Principal','-1','Servicios Gral','Garage','P-62','FAC-062','2022-03-01',1200,120,15],
    ['BN-2023-000063','06-1-PRN-1-0022','MN-2023-063',0,'Silla de Ruedas','Estandar plegable','SRU-063','Drive','Silver','Azul',2022,1,'Bueno',3,8,28,'Maria Elena Rodriguez','20123456','Principal','0','Urgencias','Bodega','P-63','FAC-063','2023-02-01',350,35,10],
    // Desincorporados
    ['BN-2021-000059','03-1-PRN-1-0008','MN-2021-059',0,'Cama Hospitalaria Manual','Con ruedas','CHM-059','Stryker','InTouch','Gris',2017,1,'Malo',5,8,17,'Carlos Alberto Martinez','18765432','Principal','3','Hospitalizacion P3','Hab 302','P-59','FAC-059','2021-05-01',2200,220,10],
    ['BN-2021-000060','01-1-PRN-1-0014','MN-2021-060',0,'Computadora Portatil','Laptop antigua','LPT-060','HP','Pavilion','Plata',2016,1,'Regular',2,14,13,'Luis Fernando Sanchez','17654321','Principal','1','Administracion','Archivo','P-60','FAC-060','2021-09-15',780,78,5],
    ['BN-2020-000064','03-1-PRN-1-0008','MN-2020-064',0,'Extintor ABC 10lbs','Quimico seco ABC','EXT-064','Amerex','B402','Rojo',2019,1,'Nuevo',5,3,28,'Carlos Alberto Martinez','18765432','Principal','0','Urgencias','Pasillo','P-64','FAC-064','2020-01-15',85,8.5,5],
    ['BN-2020-000065','06-1-PRN-1-0023','MN-2020-065',0,'Manguera Incendio','30 metros','MI-065','Tirosol','30m-65mm','Rojo',2018,1,'Regular',5,3,1,'Luis Fernando Sanchez','17654321','Principal','-1','Mantenimiento','Bodega','P-65','FAC-065','2020-06-01',220,22,5],
    // Adicionales
    ['BN-2024-000046','04-1-PRN-1-0001','MN-2024-046',0,'Regulador 5000VA','Automatico','REG-046','Sencamer','RVE-5000','Gris',2022,1,'Bueno',1,4,5,'Pedro Jose Fernandez','19876543','Principal','-1','Telecomunicaciones','Rack','P-46','FAC-046','2024-01-10',380,38,5],
    ['BN-2024-000047','01-1-PRN-1-0011','MN-2024-047',0,'Pizarra Digital','Interactiva 65"','PD-047','ViewSonic','TD2455','Negro',2023,1,'Nuevo',1,11,10,'Ana Luz Garcia','21345678','Principal','1','Terapia Adulto','Sala Prof','P-47','FAC-047','2024-02-20',1200,120,10],
    ['BN-2024-000048','05-1-PRN-1-0001','MN-2024-048',0,'Camioneta 4x4','Transporte institucional','CAM-048','Toyota','Hilux','Blanco',2023,1,'Nuevo',1,5,4,'Maria Elena Rodriguez','20123456','Principal','-1','Servicios Gral','Garage','P-48','FAC-048','2024-01-05',35000,3500,15],
    ['BN-2024-000049','06-1-PRN-1-0014','MN-2024-049',0,'Mamografo Digital','Mamografia digital','MAM-049','Hologic','Selenia','Gris',2023,1,'Nuevo',1,10,14,'Carlos Alberto Martinez','18765432','Principal','2','Materno-Fetal','Sala Mamog','P-49','FAC-049','2024-03-01',45000,4500,10],
    ['BN-2024-000050','06-1-PRN-1-0015','MN-2024-050',0,'Colposcopio','Digital con camara','COL-050','Leisegang','Euroloupe','Gris',2022,1,'Bueno',1,10,22,'Luis Fernando Sanchez','17654321','Anexo','0','Prenatal','Consulta','P-50','FAC-050','2024-01-20',3200,320,10],
    ['BN-2024-000051','01-1-PRN-1-0012','MN-2024-051',0,'Silla Espera 3 plazas','Banca tapizada','SE-051','OFIMBLE','SE-3P','Gris',2022,1,'Bueno',1,1,28,'Pedro Jose Fernandez','19876543','Principal','0','Urgencias','Sala Espera','P-51','FAC-051','2024-01-15',280,28,10],
    ['BN-2024-000052','06-1-PRN-1-0016','MN-2024-052',0,'Equipo ECG 12 Deriv.','Digital 12 deriv.','ECG-052','GE','MAC 2000','Gris',2023,1,'Nuevo',1,7,10,'Ana Luz Garcia','21345678','Principal','1','Terapia Adulto','Box T','P-52','FAC-052','2024-02-10',2800,280,10],
    ['BN-2024-000053','06-1-PRN-1-0017','MN-2024-053',0,'Nebulizador','Ultrasónico portatil','NEB-053','Omron','NE-C801','Blanco',2023,1,'Nuevo',1,8,17,'Maria Elena Rodriguez','20123456','Principal','3','Hospitalizacion P3','Hab 301','P-53','FAC-053','2024-03-05',180,18,10],
    ['BN-2024-000054','06-1-PRN-1-0018','MN-2024-054',0,'Camilla Exploracion','Con rollo papel','CE-054','Nagar','SP-100','Gris',2022,1,'Bueno',1,1,19,'Carlos Alberto Martinez','18765432','Principal','9','Residencias Medicas','Consulta','P-54','FAC-054','2024-01-25',450,45,10],
    ['BN-2024-000055','03-1-PRN-1-0006','MN-2024-055',0,'Mesa de Ayuda','Coordinacion','MAO-055','OFIMBLE','MO-2000','Gris',2023,1,'Nuevo',1,1,28,'Luis Fernando Sanchez','17654321','Principal','0','Urgencias','Mesa Op','P-55','FAC-055','2024-02-20',680,68,10],
    ['BN-2024-000066','06-1-PRN-1-0024','MN-2024-066',0,'Equipo Laparoscopia','Torre HD','LAP-066','Stryker','1688 AIM','Gris',2023,1,'Nuevo',1,8,7,'Pedro Jose Fernandez','19876543','Principal','1','Quirófano','Q2','P-66','FAC-066','2024-04-01',28000,2800,10],
    ['BN-2024-000067','06-1-PRN-1-0025','MN-2024-067',0,'Mesa de Parto','Electrica con frenos','MPT-067','Berger','Primus','Gris',2023,1,'Nuevo',1,8,6,'Ana Luz Garcia','21345678','Principal','1','Sala de Parto','Box 3','P-67','FAC-067','2024-03-15',6800,680,10],
    ['BN-2024-000068','06-1-PRN-1-0026','MN-2024-068',0,'Incubadora Prematuro','Control humedad','INC2-068','Draeger','BAByplus','Blanco',2023,1,'Nuevo',1,8,27,'Maria Elena Rodriguez','20123456','Anexo','2','Cuidados Intensivos Neonatal','UCIN','P-68','FAC-068','2024-04-01',9200,920,10],
    ['BN-2024-000069','07-1-PRN-1-0008','MN-2024-069',0,'Impresora 3D Medica','Modelos anatomicos','I3D-069','Ultimaker','S5','Negro',2023,1,'Nuevo',1,14,10,'Carlos Alberto Martinez','18765432','Principal','1','Terapia Adulto','Laboratorio','P-69','FAC-069','2024-05-01',3500,350,5],
    ['BN-2024-000070','06-1-PRN-1-0027','MN-2024-070',0,'Autoclave Grande','Mesa quirurgica','ACL2-070','Getinge','HS66','Gris',2023,1,'Nuevo',1,8,7,'Luis Fernando Sanchez','17654321','Principal','1','Quirófano','Esteril','P-70','FAC-070','2024-02-15',5800,580,10],
    ['BN-2024-000071','01-1-PRN-1-0015','MN-2024-071',0,'Escaner Documentos','Alta velocidad','ESC2-071','Fujitsu','ScanSnap iX1600','Blanco',2023,1,'Nuevo',1,14,13,'Pedro Jose Fernandez','19876543','Principal','1','Administracion','Archivo','P-71','FAC-071','2024-03-01',420,42,5],
    ['BN-2024-000072','06-1-PRN-1-0028','MN-2024-072',0,'Pulsoximetro Neonatal','Sonda dedo','PXN-072','Masimo','LNOP','Azul',2023,1,'Nuevo',1,8,27,'Ana Luz Garcia','21345678','Anexo','2','Cuidados Intensivos Neonatal','UCIN','P-72','FAC-072','2024-04-10',320,32,10],
    ['BN-2024-000073','06-1-PRN-1-0029','MN-2024-073',0,'Equipo Ultrasonido','Portatil 3 sondas','US-073','GE','Venue Ace','Gris',2023,1,'Nuevo',1,10,14,'Maria Elena Rodriguez','20123456','Principal','2','Materno-Fetal','Sala US','P-73','FAC-073','2024-03-20',12500,1250,10],
    ['BN-2024-000074','03-1-PRN-1-0009','MN-2024-074',0,'Bomba de Calor','Climatizacion quirófano','BCL-074','Carrier','42CE','Blanco',2023,1,'Nuevo',1,3,7,'Carlos Alberto Martinez','18765432','Principal','1','Quirófano','Techos','P-74','FAC-074','2024-02-01',8500,850,15],
    ['BN-2024-000075','06-1-PRN-1-0030','MN-2024-075',0,'Unidad de Succion','Quirurgica portatil','USU-075','Medela','Vacuum','Azul',2023,1,'Nuevo',1,8,7,'Luis Fernando Sanchez','17654321','Principal','1','Quirófano','Q1','P-75','FAC-075','2024-03-10',680,68,10],
    ['BN-2024-000076','01-1-PRN-1-0016','MN-2024-076',0,'Silla Oficina','Ejecutiva reposabrazos','SO-076','OFIMBLE','SE-PRO','Negro',2024,1,'Nuevo',1,1,13,'Pedro Jose Fernandez','19876543','Principal','1','Administracion','Gerencia','P-76','FAC-076','2024-05-01',320,32,10],
    ['BN-2024-000077','06-1-PRN-1-0031','MN-2024-077',0,'Tableta Clinica','Gestion clinica','TAB-077','Apple','iPad 10th','Plata',2024,1,'Nuevo',1,14,28,'Ana Luz Garcia','21345678','Principal','0','Urgencias','Estacion','P-77','FAC-077','2024-04-15',580,58,5],
    ['BN-2024-000078','06-1-PRN-1-0032','MN-2024-078',0,'Desfibrilador Pediatrico','Pads pediatricos','DEF2-078','Philips','HeartStart FRx','Gris',2024,1,'Nuevo',1,7,28,'Maria Elena Rodriguez','20123456','Principal','0','Urgencias','Sala R2','P-78','FAC-078','2024-05-01',4800,480,10],
    ['BN-2024-000079','06-1-PRN-1-0033','MN-2024-079',0,'Monitor Fetal','Frecuencia cardiaca fetal','MFT-079','GE','Corometrics 259cx','Gris',2024,1,'Nuevo',1,8,6,'Carlos Alberto Martinez','18765432','Principal','1','Sala de Parto','Box 1','P-079','FAC-079','2024-05-15',3200,320,10],
    ['BN-2024-000080','06-1-PRN-1-0034','MN-2024-080',0,'Equipo Electrocirugia','Pinzas y pedales','ECQ-080','Erbe','VIO 300','Gris',2024,1,'Nuevo',1,8,7,'Luis Fernando Sanchez','17654321','Principal','1','Quirófano','Q1','P-080','FAC-080','2024-06-01',15000,1500,10],
];

foreach ($bienes as $b) {
    $esSn = $b[3];
    $nombre = $b[4];
    $desc = $b[5];
    $serial = $b[6];
    $marca = $b[7];
    $modelo = $b[8];
    $color = $b[9];
    $anio = $b[10];
    $cant = $b[11];
    $condicion = $b[12];
    $estado = $b[13];
    $tipo = $b[14];
    $area = $b[15];
    $respNom = $b[16];
    $respCed = $b[17];
    $edif = $b[18];
    $piso = $b[19];
    $depto = $b[20];
    $oficina = $b[21];
    $posicion = $b[22];
    $factura = $b[23];
    $fecha = $b[24];
    $valor = $b[25];
    $resid = $b[26];
    $vida = $b[27];

    $codigoBase = preg_replace('/[^0-9]/', '', $b[1]);
    $CodigoInt = substr($b[1], -4);
    $codigoInterno = sprintf('%02d-%d-ANE-%d-%s',
        (int)substr($CodigoInt,0,2),
        ($anio > 2022 ? 2 : 1),
        min($area, 9),
        str_pad($CodigoInt, 4, '0', STR_PAD_LEFT)
    );

    insert($conn, 'bienes', [
        'codigo_sudebip' => $b[0],
        'codigo_interno' => $codigoInterno,
        'nro_bien_ministerio' => ($esSn ? 'S/N' : 'MN-'.str_pad(substr($codigoBase,-3),3,'0',STR_PAD_LEFT)),
        'es_sn' => $esSn,
        'nombre' => $nombre,
        'descripcion' => $desc,
        'serial' => $serial,
        'marca' => $marca,
        'modelo' => $modelo,
        'color' => $color,
        'anio_fabricacion' => $anio,
        'cantidad' => $cant,
        'condicion_inicial' => $condicion,
        'id_estado' => $estado,
        'id_tipo' => $tipo,
        'id_area' => $area,
        'responsable_nombre' => $respNom,
        'responsable_cedula' => $respCed,
        'cin_edificio' => $edif,
        'cin_piso' => $piso,
        'cin_departamento' => $depto,
        'cin_oficina' => $oficina,
        'cin_posicion' => $posicion,
        'numero_factura' => $factura,
        'fecha_adquisicion' => $fecha,
        'valor_inicial' => $valor,
        'valor_residual' => $resid,
        'vida_util_anos' => $vida,
        'created_at' => $fecha . ' 10:00:00',
        'updated_at' => $fecha . ' 10:00:00',
    ]);
}
echo "  " . count($bienes) . " bienes insertados\n";

// ── MOVIMIENTOS ──
echo "Insertando movimientos...\n";
$movimientos = [
    [1,3,'incorporacion',null,12,4,1,'2024-01-15','2024-01-15','Incorporacion PC Gerencia BN','aprobado',
     '<p>Acta Incorporación <strong>BN-2024-000003</strong> (PC) → Gerencia BN. Estado: Nuevo.</p>'],
    [2,11,'traslado',6,7,4,1,'2024-03-01','2024-03-01','Traslado camilla Sala Parto → Quirófano','aprobado',
     '<p>Acta Traslado <strong>BN-2024-000011</strong> (Camilla). Sala Parto → Quirófano.</p>'],
    [3,1,'traslado',13,13,4,2,'2024-04-01','2024-04-01','Reubicacion escritorio admin','aprobado',
     '<p>Acta Reubicación <strong>BN-2024-000001</strong> (Escritorio) dentro de Administración.</p>'],
    [4,56,'desincorporacion',13,null,3,1,'2024-05-10','2024-05-10','Impresora falla irreparable','aprobado',
     '<p>Acta Desincorporación <strong>BN-2023-000056</strong> (Impresora Matriz). Falla alimentador.</p>'],
    [5,59,'desincorporacion',17,null,3,1,'2024-06-01','2024-06-01','Cama oxidada → chatarra','aprobado',
     '<p>Acta Desincorporación <strong>BN-2021-000059</strong> (Cama). Estructura oxidada.</p>'],
    [6,36,'incorporacion',null,15,4,1,'2024-02-20','2024-02-20','Ecografo portatil Consultas Eco','aprobado',
     '<p>Acta Incorporación <strong>BN-2024-000036</strong> (Ecógrafo) → Consultas de Eco.</p>'],
    [7,15,'incorporacion',null,27,4,1,'2024-04-01','2024-04-01','Incubadora neonatal UCIN','aprobado',
     '<p>Acta Incorporación <strong>BN-2024-000015</strong> (Incubadora) → UCIN.</p>'],
    [8,61,'traslado',10,10,4,2,'2024-05-15','2024-05-15','Bomba falla → bodega revision','aprobado',
     '<p>Acta Traslado <strong>BN-2023-000061</strong> (Bomba) → bodega.</p>'],
    [9,49,'incorporacion',null,14,4,1,'2024-03-01','2024-03-01','Mamografo digital Materno-Fetal','aprobado',
     '<p>Acta Incorporación <strong>BN-2024-000049</strong> (Mamógrafo) → Materno-Fetal.</p>'],
    [10,67,'incorporacion',null,6,4,1,'2024-03-15','2024-03-15','Mesa de parto Sala Parto','aprobado',
     '<p>Acta Incorporación <strong>BN-2024-000067</strong> (Mesa Parto) → Sala Parto Box 3.</p>'],
];
foreach ($movimientos as $m) {
    insert($conn, 'movimientos', [
        'bien_id'=>$m[1],'tipo_movimiento'=>$m[2],
        'area_origen_id'=>$m[3],'area_destino_id'=>$m[4],
        'usuario_solicita_id'=>$m[5],'usuario_aprueba_id'=>$m[6],
        'fecha_solicitud'=>$m[7].' 09:00:00',
        'fecha_aprobacion'=>$m[8].' 10:00:00',
        'motivo'=>$m[9],'estado'=>$m[10],
        'contenido_acta'=>$m[11],
        'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),
    ]);
}
echo "  10 movimientos creados\n";

// ── VERIFICACIONES ──
echo "Insertando verificaciones...\n";
$verifs = [
    [1,3,3,'semestral','PC OK','2024-06-30'],
    [2,11,3,'semestral','Camilla barandas OK','2024-06-30'],
    [3,12,3,'semestral','Monitor calibrado','2024-12-30'],
    [4,14,3,'semestral','Anestesia OK post-rep','2024-12-30'],
    [5,21,3,'semestral','Servidor actualizado','2024-12-30'],
    [6,36,3,'semestral','Ecografo calibrado','2024-12-30'],
    [7,7,3,'semestral','AC filtros OK','2024-12-30'],
    [8,49,3,'semestral','Mamografo OK post-rep','2025-01-30'],
];
foreach ($verifs as $v) {
    insert($conn, 'verificaciones', [
        'bien_id'=>$v[1],'usuario_id'=>$v[2],'tipo'=>$v[3],
        'observaciones'=>$v[4],'fecha_verificacion'=>$v[5],
    ]);
}
echo "  8 verificaciones creadas\n";

// ── NOTIFICACIONES ──
echo "Insertando notificaciones...\n";
$notifs = [
    [1,1,'Bien incorporado','PC incorporada a Gerencia BN','info'],
    [2,1,'Traslado aprobado','Camilla a Quirófano aprobada','success'],
    [3,2,'Desincorp. pendiente','Pendiente impresora BN-2023','warning'],
    [4,3,'Mantenimiento prog.','Ecógrafo mañana','info'],
    [5,1,'Bien en chatarra','Cama BN-2021 clasificada','error'],
];
foreach ($notifs as $n) {
    insert($conn, 'notificaciones', [
        'usuario_id'=>$n[1],'titulo'=>$n[2],'mensaje'=>$n[3],'tipo'=>$n[4],'leida'=>($n[4]==='success'?1:0),
    ]);
}
echo "  5 notificaciones creadas\n";

// ── VERIFICAR ──
$conn->exec('SET FOREIGN_KEY_CHECKS = 1');
$r = $conn->query('SELECT COUNT(*) as n FROM bienes')->fetch();
echo "\n=== RESUMEN ===\n";
echo "Bienes:      " . $r['n'] . "\n";
$r = $conn->query('SELECT COUNT(*) as n FROM usuarios')->fetch();
echo "Usuarios:    " . $r['n'] . "\n";
$r = $conn->query('SELECT COUNT(*) as n FROM areas')->fetch();
echo "Areas:       " . $r['n'] . "\n";
$r = $conn->query('SELECT COUNT(*) as n FROM movimientos')->fetch();
echo "Movimientos: " . $r['n'] . "\n";
$r = $conn->query('SELECT COUNT(*) as n FROM verificaciones')->fetch();
echo "Verificaciones: " . $r['n'] . "\n";
$r = $conn->query('SELECT COUNT(*) as n FROM notificaciones')->fetch();
echo "Notificaciones: " . $r['n'] . "\n";

// Distribucion por estado
echo "\n--- Por estado ---\n";
$rows = $conn->query('SELECT e.nombre, COUNT(b.id_bien) as n FROM bienes b JOIN estados e ON b.id_estado=e.id_estado GROUP BY e.nombre ORDER BY n DESC')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) echo "  {$row['nombre']}: {$row['n']}\n";

// Distribucion por edificio
echo "\n--- Por edificio ---\n";
$rows = $conn->query('SELECT cin_edificio, COUNT(*) as n FROM bienes GROUP BY cin_edificio ORDER BY n DESC')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) echo "  {$row['cin_edificio']}: {$row['n']}\n";

echo "\nSeed completado exitosamente!\n";
