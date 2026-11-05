# SaaS Academia — Plataforma de Gestión para Academias Deportivas

Proyecto escolar desarrollado como parte de la formación en Desarrollo y Gestión de Software. Consiste en una plataforma web multi-inquilino (SaaS Multi-tenant) diseñada para que escuelas y academias deportivas gestionen eficientemente sus operaciones, alumnos, categorías, partidos y pagos.

---

## 📌 Descripción del Proyecto

El sistema resuelve la problemática de organización y administración que sufren las academias deportivas al gestionar listas manuales o dispersas. Permite a cualquier academia registrarse y obtener un entorno propio e independiente para llevar el control administrativo y ofrecer a los padres de familia un portal transparente sobre las actividades deportivas de sus hijos.

### 🚀 Funcionalidades Principales

1. **Arquitectura Multi-Tenant (Multi-Academia):**
   * Cada academia registrada cuenta con su propia base de datos independiente (SQLite/MySQL) para garantizar la privacidad y seguridad de sus datos.
   * Detección automática por subdominio (ejemplo: `cuervos.academiahe5.com`, `demo.academiahe5.com`).

2. **Panel de Administración de la Academia:**
   * **Gestión de Alumnos (Jugadores):** Registro con nombres, fecha de nacimiento, categoría asignada, estatus y estadísticas (goles).
   * **Categorías Deportivas:** Configuración de rangos de edad (Sub-8, Sub-10, Sub-12, etc.) y asignación de horarios de entrenamiento.
   * **Control de Partidos:** Programación de encuentros, rivales, sedes (local o visitante), fechas, horarios y registro de marcadores finales.
   * **Administración de Tutores:** Registro de padres de familia y generación de accesos para vincularlos con sus respectivos hijos.
   * **Finanzas y Pagos:** Registro de folios de pago, control de mensualidades y vinculación con pasarela de pagos.
   * **Personalización Institucional:** Carga de escudo/logo oficial y selección de colores corporativos.

3. **Portal para Padres de Familia / Tutores:**
   * Vista optimizada para dispositivos móviles y web.
   * Consulta del perfil deportivo de sus hijos.
   * Calendario de partidos programados y resultados recientes.
   * Consulta de horarios y días de entrenamiento por categoría.
   * Módulo de pago de mensualidades con integración de pasarela de pago (Stripe).

4. **Panel SuperAdmin:**
   * Visualización global de todas las academias registradas en la plataforma.
   * Gestión de planes de suscripción (Básico, Elite, Premium) y estado de las academias.

5. **Recorrido Interactivo (Tutorial):**
   * Tutorial guiado paso a paso integrado con Driver.js para enseñar tanto a los directores como a los padres a utilizar todas las secciones del sistema de forma clara e interactiva.

---

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP 8.2+, Laravel 11.
* **Base de Datos:** SQLite / MySQL con migraciones automáticas por inquilino.
* **Frontend:** Blade Templates, CSS3 moderno con variables CSS, JavaScript vanilla.
* **Librerías:** SweetAlert2 (alertas interactivas), FontAwesome (iconografía), Driver.js (tutorial interactivo).
* **Integraciones:** Stripe PHP SDK (pasarela de pagos de mensualidades y suscripciones).

---

## 📂 Estructura del Código

```text
saas-academia/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores (Tenant, SuperAdmin, Auth, Pagos)
│   │   └── Middleware/           # Detección de inquilino (ResolveTenant), roles (EnsureRole) y cabeceras de seguridad
│   ├── Models/                   # Modelos centrales (Tenant, User) y por inquilino (Player, Category)
│   ├── Notifications/            # Notificaciones del sistema
│   └── Services/                 # Lógica de aprovisionamiento de bases de datos y control de límites por plan
├── config/                       # Archivos de configuración de Laravel
├── database/
│   ├── migrations/
│   │   ├── central/              # Tablas principales (tenants, saas_plans, users)
│   │   └── tenant/               # Tablas por academia (players, categories, tutors, matches, payments)
│   └── seeders/                  # Datos de prueba iniciales
├── public/
│   ├── css/                      # Estilos personalizados y tema del tutorial
│   ├── js/                       # Scripts y lógica del recorrido interactivo
│   └── vendor/                   # Librerías estáticas de terceros
├── resources/
│   └── views/
│       ├── tenant/               # Vistas del dashboard administrativo y portal de padres
│       ├── superadmin/           # Vistas del administrador general
│       └── ...                   # Landing page, registro, login y checkout
├── routes/
│   └── web.php                   # Definición de rutas del sistema
└── tests/                        # Pruebas unitarias y de integración
```

---

## ⚙️ Requisitos Previos

* PHP >= 8.2
* Composer
* Extensión PHP `pdo_sqlite` y `curl` activadas
* Node.js y NPM (opcional para compilar assets)

---

## 💻 Instalación y Puesta en Marcha Local

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/ShadowCreat18/saas-academia.git
   cd saas-academia
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar el archivo de entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Ejecutar las migraciones de base de datos:**
   ```bash
   php artisan migrate
   ```

5. **Iniciar el servidor local:**
   ```bash
   php artisan serve
   ```
   Abrir en el navegador: `http://localhost:8000`

---

## 👤 Autor

* **Christian Esparza**
* Proyecto de Software para Gestión de Academias Deportivas.
