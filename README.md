# NADIA - Núcleo de Apoyo para Dictámenes de Investigación Académica
Sistema integral para la gestión de proyectos de tesis, dictámenes y seguimiento académico.
## 🚀 Funciones Principales
### 📁 Gestión de Expediente Digital
- **Carga Categorizada:** Soporte para Proyecto (PDF/Word), Borrador, Correcciones y Requisitos.
- **Control de Versiones Automático:** Los archivos antiguos se eliminan del servidor al ser reemplazados para optimizar espacio.
- **Trazabilidad por Motivo:** Obligatoriedad de ingresar el motivo para cada carga, reemplazo o eliminación, registrado en el historial del proyecto.
### 👥 Administración de Docentes y Tesistas
- **Constancias con CVD:** Generación de Constancias de Participación con Código de Verificación Digital (CVD) y QR bajo normativa PCM.
- **Perfil Académico:** Seguimiento detallado de proyectos asignados por docente (Jurado/Asesor).
- **Gestión de Usuarios:** Control total de roles, facultades y estados (Activo/Bloqueado).
### 📨 Mensajería y Notificaciones
- **Buscador Inteligente:** Selección de destinatarios por nombre, DNI o código.
- **Adjuntos Múltiples:** Gestión avanzada de archivos adjuntos en comunicaciones.
- **Sistema SMTP:** Integración segura para notificaciones automáticas por correo electrónico.
### 📊 Panel de Control y Auditoría
- **Dashboard Estadístico:** Gráficos dinámicos de estados de proyectos, facultades y líneas de investigación.
- **Logs de Sistema:** Registro de todas las acciones del administrador para seguridad.
- **Paginación y Búsqueda Profesional:** Navegación optimizada para grandes bases de datos.
## 🛠 Requisitos Técnicos
- **Servidor Web:** Apache 2.4+ o Nginx.
- **Lenguaje:** PHP 8.1+ (con extensiones PDO, GD, OpenSSL).
- **Base de Datos:** MySQL 8.0+ o MariaDB 10.4+.
- **Frontend:** Bootstrap 5, Bootstrap Icons.

## 📦 Instalación

### 1. Clonar el Repositorio
```bash
git clone https://github.com/fcjpunap/NADIA.git
cd NADIA
```

### 2. Importar la Base de Datos
```bash
mysql -u root -p < nadia.sql
```

### 3. Configurar Credenciales
Edita el archivo `app/config/config.php` y reemplaza los valores de ejemplo:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_db');      // ⚠️ Cambiar por tu usuario real
define('DB_PASS', 'tu_password_db');     // ⚠️ Cambiar por tu contraseña real
define('DB_NAME', 'nadia');
```

**Nota:** Si tu instalación no está en la ruta `/sespecialidad/nadia/gemini/public/`, también debes ajustar:
```php
define('URL_BASE', '/tu_ruta_personalizada/');
```

Y editar el archivo `public/.htaccess` para cambiar la línea:
```apache
RewriteBase /sespecialidad/nadia/gemini/public/
```
Por tu ruta personalizada:
```apache
RewriteBase /tu_ruta_personalizada/
```

### 4. Configurar Permisos
```bash
chmod -R 755 public/
chmod -R 777 public/uploads/
```

### ⚙️ Configuración del Servidor Apache (Importante)
El sistema utiliza URLs amigables (ej: `/auth/login`) que requieren la reescritura de URLs en Apache.
1. Habilitar el módulo rewrite: `sudo a2enmod rewrite`
2. Configurar el VirtualHost o `apache2.conf` para permitir la lectura del archivo `.htaccess`:
   ```apache
   <Directory /var/www/html/nadia/gemini/public>
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```
   *Nota: Sin `AllowOverride All`, el sistema devolverá errores 404 en las rutas internas.*
## ✍️ Créditos y Desarrollo
Este sistema ha sido **elaborado, revisado y corregido con autofinanciamiento por Michael Espinoza Coila**, docente responsable del Laboratorio de Cómputo de la Facultad de Ciencias Jurídicas y Políticas de la Universidad Nacional del Altiplano, con la asistencia de **Gemini 3 Pro y Flash, Claude Sonnet 4.5, Grok Code Fast 1, GLM 4.7 y Big Pickle vía Antigravity y OpenCode**.
📧 **Contacto:** mespinoza@unap.edu.pe
## ⚠️ Aviso Importante (Disclaimer)
Este software es una herramienta educativa desarrollada de forma independiente por Michael Espinoza Coila. No representa oficialmente a la Universidad Nacional del Altiplano de Puno ni ha sido aprobado, revisado o respaldado por sus autoridades, comités científicos o administrativos. Úsalo bajo tu propio riesgo, respeta las leyes de privacidad de datos (como la Ley de Protección de Datos Personales en Perú) y no incluyas información sensible en implementaciones públicas. Este proyecto se comparte con fines educativos y de colaboración académica.
## 📄 Licencia
Este proyecto está licenciado bajo la GNU General Public License Version 3.
---
