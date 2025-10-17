<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# AppSalon – Sistema de Gestión de Salón de Belleza  


## 🎯 Descripción
Aplicación web para la gestión de servicios y citas en un salón de belleza. Incluye autenticación de usuarios, roles (cliente y administrador), gestión de servicios y visualización pública de catálogo.

## 🛠️ Tecnologías utilizadas
- **Backend**: Laravel 10+
- **Frontend**: Tailwind CSS, Alpine.js
- **Base de datos**: MySQL
- **Autenticación**: Laravel Breeze (personalizado)
- **Despliegue local**: PHP Artisan Serve o npm run dev

## 📥 Instrucciones de instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/appsalon.git
   cd appsalon
2. Instalar dependencias:
   ```bash
   composer install
   npm install
3. Configurar entorno:
   ```bash
   cp .env.example .env
4. Generar clave de aplicación:
   ```bash
   php artisan key:generate
5. Ejecutar migraciones y datos de prueba:
   ```bash
   php artisan migrate --seed
6. Iniciar el servidor:
   ```bash
   npm run dev o php artisan serve
## 👤 Usuarios de prueba
- **Correo**:admin@salonflow.com
- **Contraseña**:12345678
- **Rol**: Administrador
- **Correo**:cliente@salonflow.com
- **Contraseña**:12345678
- **Rol**:Usuario normal
## ✅ Funcionalidades implementadas
- **Base de datos**: Tablas usuarios, servicios, citas, citasServicios con relaciones y claves foráneas.
- **Autenticación**: Registro, login, logout, contraseñas hasheadas (bcrypt), validación de formularios, protección XSS.
- **Gestión de servicios**:
    - **Público**: listado de servicios disponibles.
    - **Admin**: CRUD completo con validación.
- **Roles**: Interfaz diferente para administrador y usuario normal.
- **Arquitectura MVC**: Separación clara de capas, conexión a BD, manejo de errores.
- **UI/UX**: Diseño responsive, mensajes de éxito/error, navegación intuitiva.
