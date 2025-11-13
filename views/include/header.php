<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Hoteles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }

        .sidebar-header h1 {
            font-size: 18px;
            margin: 0;
            color: #ecf0f1;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 10px 20px;
        }

        .sidebar-menu li a {
            color: #bdc3c7;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }

        .sidebar-menu li a:hover {
            color: #ecf0f1;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 16px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1><i class="fas fa-hotel"></i> Gestión de Hoteles</h1>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?php echo BASE_URL; ?>views/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>views/hoteles_list.php"><i class="fas fa-building"></i> Hoteles</a></li>
             <li><a href="<?php echo BASE_URL; ?>views/usuarios_list.php"><i class="fas fa-users-cog"></i> Gestionar Usuarios</a></li>
          
             <li><a href="<?php echo BASE_URL; ?>views/clientes_list.php"><i class="fas fa-users"></i> Gestionar Clientes API</a></li>
             <li><a href="<?php echo BASE_URL; ?>views/tokens_list.php"><i class="fas fa-key"></i> Gestionar Tokens API</a></li>

            <li><a href="<?php echo BASE_URL; ?>public/index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
           
           
        </ul>
    </div>
    <div class="main-content">
        <main>
