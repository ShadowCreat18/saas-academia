/**
 * ============================================================================
 * TUTORIAL INTERACTIVO CON DRIVER.JS - SAAS ACADEMIA
 * Recorrido guiado paso a paso para Administradores y Padres de Familia
 * ============================================================================
 */

(function () {
    "use strict";

    // Función auxiliar para comprobar que la librería Driver.js esté lista
    function getDriverConstructor() {
        if (window.driver && window.driver.js && typeof window.driver.js.driver === "function") {
            return window.driver.js.driver;
        }
        if (typeof window.driver === "function") {
            return window.driver;
        }
        return null;
    }

    /**
     * ========================================================================
     * 1. RECORRIDO PARA ADMINISTRADORES (Dashboard de la Academia)
     * ========================================================================
     */
    window.initAdminTour = function (options) {
        options = options || {};
        const academyName = options.academyName || "tu Academia";
        const plan = (options.plan || "elite").toUpperCase();

        const DriverFn = getDriverConstructor();
        if (!DriverFn) {
            console.warn("Driver.js no está disponible en la página.");
            return;
        }

        const adminDriver = DriverFn({
            animate: true,
            allowClose: true,
            overlayColor: "rgba(5, 5, 12, 0.45)",
            stagePadding: 8,
            stageRadius: 12,
            showProgress: true,
            progressText: "Paso {{current}} de {{total}}",
            nextBtnText: "Siguiente →",
            prevBtnText: "← Anterior",
            doneBtnText: "¡Finalizar Tutorial! 🚀",
            popoverClass: "saas-driver-popover",
            onDestroyed: function () {
                localStorage.setItem("saas_admin_tour_seen", "1");
                // Regresar a la vista de inicio
                if (typeof window.switchTab === "function") {
                    const startTab = document.querySelector('.nav-item[data-tab="inicio"]');
                    window.switchTab("inicio", startTab);
                }
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "¡Tutorial de Administrador completado!",
                        text: "Puedes reabrirlo cuando quieras con el botón 'Ver Tutorial'.",
                        showConfirmButton: false,
                        timer: 4000,
                        background: "#11111d",
                        color: "#F0E6FF"
                    });
                }
            },
            steps: [
                // 1. Bienvenida general
                {
                    popover: {
                        title: `🚀 ¡Bienvenido a ${academyName}!`,
                        description: `
                            Has configurado tu academia con éxito en la plataforma.
                            <br><br>
                            Este recorrido interactivo te enseñará <strong>de principio a fin</strong> a dominar todas las herramientas: gestionar alumnos, categorías, credenciales de padres, partidos, finanzas y cobros automáticos.
                        `,
                        side: "over",
                        align: "center"
                    }
                },
                // 2. Identidad institucional
                {
                    element: ".sidebar .logo",
                    popover: {
                        title: "🛡️ Identidad de tu Academia",
                        description: `
                            Aquí se muestra el escudo y nombre comercial de tu academia. Puedes actualizar el logotipo oficial y la paleta de colores corporativos cuando quieras desde la sección de Configuración.
                        `,
                        side: "right",
                        align: "start"
                    }
                },
                // 3. Plan actual y capacidad
                {
                    element: "#tour-topbar-plan",
                    popover: {
                        title: "💎 Plan Activo y Capacidad",
                        description: `
                            Tu academia cuenta actualmente con el <strong>PLAN ${plan}</strong>.
                            <br><br>
                            Si deseas alumnos y categorías ilimitadas, fixture completo de partidos y soporte prioritario, puedes actualizar al <strong>Plan Premium</strong> en cualquier momento.
                        `,
                        side: "bottom",
                        align: "start"
                    }
                },
                // 4. Métricas e Indicadores Rápidos (KPIs)
                {
                    element: "#tour-stats-grid",
                    popover: {
                        title: "📊 Indicadores Clave en Tiempo Real",
                        description: `
                            Monitorea el pulso de tu escuela deportiva:
                            <br>
                            • <strong>Alumnos Activos:</strong> Jugadores inscritos en cancha.<br>
                            • <strong>Categorías:</strong> Grupos por edad.<br>
                            • <strong>Tutores:</strong> Familias conectadas al portal.<br>
                            • <strong>Ingresos del Mes:</strong> Cobranza acumulada en tiempo real.
                        `,
                        side: "bottom",
                        align: "center"
                    }
                },
                // 5. Comunicación Rápida con Padres
                {
                    element: "#tour-quick-notif",
                    popover: {
                        title: "📢 Notificaciones Push y Avisos",
                        description: `
                            ¿Se suspendió un entrenamiento por lluvia? ¿Hay junta de padres?
                            <br><br>
                            Redacta un aviso rápido desde aquí. Todos los tutores lo recibirán de forma inmediata en la pantalla principal de su portal.
                        `,
                        side: "top",
                        align: "start"
                    }
                },
                // 6. Menú Lateral de Navegación
                {
                    element: "#tour-sidebar",
                    popover: {
                        title: "🧭 Barra de Navegación Lateral",
                        description: `
                            Desde aquí accedes a cada módulo del sistema. A continuación revisaremos cada sección una por una para que conozcas sus funciones operativas.
                        `,
                        side: "right",
                        align: "center"
                    }
                },
                // 7. Módulo de Categorías
                {
                    element: "#section-categorias",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="categorias"]');
                            window.switchTab("categorias", tabEl);
                        }
                    },
                    popover: {
                        title: "⚽ Categorías y Horarios de Práctica",
                        description: `
                            Organiza a tus alumnos por año de nacimiento o nivel (ej. <em>Sub-8, Sub-12, Femenil</em>).
                            <br><br>
                            Al definir los <strong>días y horarios</strong> de entrenamiento, los padres podrán consultarlos directamente en su teléfono celular.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 8. Módulo de Alumnos
                {
                    element: "#section-alumnos",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="alumnos"]');
                            window.switchTab("alumnos", tabEl);
                        }
                    },
                    popover: {
                        title: "🏃 Directorio y Registro de Alumnos",
                        description: `
                            Inscribe a nuevos jugadores con su nombre, fecha de nacimiento y categoría asignada.
                            <br><br>
                            Lleva el control de su <strong>estatus (Activo/Inactivo)</strong> y registra su <strong>conteo de goles</strong> para motivar a los pequeños deportistas.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 9. Módulo de Tutores y Credenciales
                {
                    element: "#section-tutores",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="tutores"]');
                            window.switchTab("tutores", tabEl);
                        }
                    },
                    popover: {
                        title: "👨‍👩‍👧 Tutores y Credenciales de la App",
                        description: `
                            ¡Paso fundamental! Aquí registras a los padres de familia y los vinculas con sus hijos.
                            <br><br>
                            Haciendo clic en el <strong>icono de la llave dorada 🔑</strong> puedes generarles su correo y contraseña para que ingresen a su propia App de Padres.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 10. Módulo de Partidos y Torneos
                {
                    element: "#section-partidos",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="partidos"]');
                            window.switchTab("partidos", tabEl);
                        }
                    },
                    popover: {
                        title: "🏆 Partidos, Convocatorias y Marcadores",
                        description: `
                            Publica el rol de juegos: equipo rival, categoría convocada, fecha, hora y cancha.
                            <br><br>
                            Al concluir el encuentro, registra el <strong>resultado final</strong> para mantener a toda la comunidad informada de las victorias.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 11. Módulo de Finanzas y Pagos
                {
                    element: "#section-pagos",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="pagos"]');
                            window.switchTab("pagos", tabEl);
                        }
                    },
                    popover: {
                        title: "💳 Finanzas, Mensualidades y Recibos",
                        description: `
                            Emite cobros por mensualidades, uniformes o arbitrajes con número de folio.
                            <br><br>
                            Visualiza al instante qué padres están al día (verde) y quiénes tienen adeudos pendientes (rojo), registrando pagos manuales o en línea.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 12. Módulo de Configuración y Stripe
                {
                    element: "#section-config",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="config"]');
                            window.switchTab("config", tabEl);
                        }
                    },
                    popover: {
                        title: "⚙️ Configuración de Marca y Pagos Stripe",
                        description: `
                            • <strong>Pagos con Tarjeta:</strong> Conecta tus llaves de Stripe para que los padres paguen en línea con tarjeta y el dinero llegue a tu cuenta bancaria.<br>
                            • <strong>Branding:</strong> Sube el escudo oficial de tu academia y elige los colores corporativos.
                        `,
                        side: "top",
                        align: "center"
                    }
                },
                // 13. Vista previa del Portal de Padres
                {
                    element: "#tour-app-tutors-btn",
                    onHighlightStarted: function () {
                        if (typeof window.switchTab === "function") {
                            const tabEl = document.querySelector('.nav-item[data-tab="inicio"]');
                            window.switchTab("inicio", startTabEl(tabEl));
                        }
                    },
                    popover: {
                        title: "📱 Vista Previa: App de Padres",
                        description: `
                            ¿Quieres ver exactamente lo que experimentan los padres de familia?
                            <br><br>
                            Usa este botón para abrir la vista de la App de Tutores y comprobar cómo se ven los horarios, avisos y pagos en el teléfono celular.
                        `,
                        side: "bottom",
                        align: "end"
                    }
                },
                // 14. Notificaciones y Cierre de Sesión
                {
                    element: "#tour-user-menu",
                    popover: {
                        title: "🔔 Alertas y Seguridad de tu Cuenta",
                        description: `
                            La campana te alertará de nuevos cobros y eventos. Al concluir tu jornada de trabajo, puedes cerrar tu sesión de forma segura desde este menú.
                        `,
                        side: "bottom",
                        align: "end"
                    }
                },
                // 15. Botón de Tutorial Siempre Disponible
                {
                    element: "#btn-start-tour",
                    popover: {
                        title: "🎓 ¿Necesitas repasar el sistema?",
                        description: `
                            ¡Felicidades! Has completado el recorrido completo del administrador.
                            <br><br>
                            Siempre que desees repasar o capacitar a un nuevo profesor o director, haz clic en este botón <strong>'Ver Tutorial'</strong>.
                        `,
                        side: "bottom",
                        align: "end"
                    }
                }
            ]
        });

        function startTabEl(tabEl) {
            return tabEl || document.querySelector('.nav-item[data-tab="inicio"]');
        }

        window.startAdminTutorial = function (force) {
            if (force) {
                adminDriver.drive(0);
                return;
            }
            const alreadySeen = localStorage.getItem("saas_admin_tour_seen");
            if (!alreadySeen) {
                setTimeout(function () {
                    adminDriver.drive(0);
                }, 800);
            }
        };

        // Auto-iniciar si es la primera visita del administrador
        window.startAdminTutorial(false);
    };

    /**
     * ========================================================================
     * 2. TUTORIAL PARA PADRES DE FAMILIA / TUTORES (Portal Móvil & Web)
     * ========================================================================
     */
    let _parentDriverInstance = null;

    window.initParentTour = function (options) {
        options = options || {};
        const force = (options === true || options.force === true);
        const academyName = options.academyName || "tu Academia";
        const userName = options.userName || "Tutor";

        const DriverFn = getDriverConstructor();
        if (!DriverFn) {
            console.warn("Driver.js no está disponible en la página.");
            return;
        }

        const parentDriver = DriverFn({
            animate: true,
            allowClose: true,
            overlayColor: "rgba(5, 5, 12, 0.45)",
            stagePadding: 8,
            stageRadius: 16,
            showProgress: true,
            progressText: "Paso {{current}} de {{total}}",
            nextBtnText: "Siguiente →",
            prevBtnText: "← Anterior",
            doneBtnText: "¡Entendido! 🎉",
            popoverClass: "saas-driver-popover",
            onDestroyed: function () {
                localStorage.setItem("saas_parent_tour_seen", "1");
            },
            steps: [
                // 1. Bienvenida al portal de padres
                {
                    popover: {
                        title: `🌟 ¡Bienvenido, ${userName}!`,
                        description: `
                            Esta es tu aplicación exclusiva de <strong>${academyName}</strong>.
                            <br><br>
                            Aquí podrás consultar la información deportiva de tus hijos, enterarte de los horarios de entrenamiento, seguir los partidos y realizar pagos de mensualidades de forma rápida y segura.
                        `,
                        side: "over",
                        align: "center"
                    }
                },
                // 2. Perfil y Sesión
                {
                    element: "#tour-parent-user",
                    popover: {
                        title: "👤 Tu Cuenta de Tutor",
                        description: `
                            Aquí puedes confirmar tu sesión activa como padre de familia o tutor responsable y cerrar sesión con total seguridad al finalizar.
                        `,
                        side: "bottom",
                        align: "end"
                    }
                },
                // 3. Mis Deportistas
                {
                    element: "#tour-my-athletes",
                    popover: {
                        title: "🏅 Ficha de tus Deportistas",
                        description: `
                            En esta tarjeta verás a tus hijos inscritos en la academia.
                            <br><br>
                            Consulta su <strong>categoría deportiva</strong>, estatus de alumno activo y el <strong>conteo de goles</strong> anotados en el torneo.
                        `,
                        side: "bottom",
                        align: "start"
                    }
                },
                // 4. Horarios de Entrenamiento
                {
                    element: "#tour-schedules",
                    popover: {
                        title: "📅 Horarios de Entrenamiento",
                        description: `
                            ¡Nunca faltes a una práctica!
                            <br><br>
                            Revisa los días, turnos y horarios exactos de entrenamiento correspondientes a cada categoría por año de nacimiento.
                        `,
                        side: "bottom",
                        align: "start"
                    }
                },
                // 5. Partidos y Convocatorias
                {
                    element: "#tour-matches",
                    popover: {
                        title: "⚽ Partidos, Sedes y Marcadores",
                        description: `
                            Sigue a tu equipo en cada jornada:
                            <br>
                            • Equipo rival al que enfrentarán.<br>
                            • Fecha, hora y ubicación de la cancha deportiva.<br>
                            • Marcador final oficial tras terminar el encuentro.
                        `,
                        side: "bottom",
                        align: "start"
                    }
                },
                // 6. Pagos y Estado de Cuenta
                {
                    element: "#tour-payments",
                    popover: {
                        title: "💳 Estado de Cuenta y Pagos Seguros",
                        description: `
                            Lleva el control de tus colegiaturas y cuotas:
                            <br>
                            • Visualiza tu saldo pendiente total.<br>
                            • Revisa el historial de conceptos liquidados.<br>
                            • Haz clic en el botón <strong>'Pagar con Stripe'</strong> para liquidar tus cuotas en línea mediante pasarela cifrada.
                        `,
                        side: "left",
                        align: "start"
                    }
                },
                // 7. Avisos de la Academia
                {
                    element: "#tour-notices",
                    popover: {
                        title: "📢 Avisos Oficiales de la Academia",
                        description: `
                            Entérate de las novedades más importantes publicadas por los directores y entrenadores: suspensiones por lluvia, convocatorias especiales, torneos o juntas informativas.
                        `,
                        side: "left",
                        align: "start"
                    }
                },
                // 8. Botón para Reabrir Tutorial
                {
                    element: "#btn-start-parent-tour",
                    popover: {
                        title: "🎓 ¿Tienes dudas sobre la app?",
                        description: `
                            ¡Listo! Ya conoces todas las secciones de tu aplicación.
                            <br><br>
                            Puedes volver a consultar este tutorial interactivo cuando quieras presionando este botón <strong>'Ver Tutorial'</strong> o el botón de ayuda en la esquina inferior.
                        `,
                        side: "bottom",
                        align: "end"
                    }
                }
            ]
        });

        _parentDriverInstance = parentDriver;

        window.startParentTutorial = function (forceParam) {
            const shouldForce = (forceParam === true || forceParam === 1 || force === true);
            if (shouldForce) {
                try {
                    parentDriver.drive(0);
                } catch (e) {
                    console.error("Error al arrancar el tutorial de padres:", e);
                }
                return;
            }
            const alreadySeen = localStorage.getItem("saas_parent_tour_seen");
            if (!alreadySeen) {
                setTimeout(function () {
                    try {
                        parentDriver.drive(0);
                    } catch (e) {}
                }, 800);
            }
        };

        if (force) {
            window.startParentTutorial(true);
        } else {
            window.startParentTutorial(false);
        }
    };

    // Fallback global por si se hace clic antes de inicializar
    window.startParentTutorial = function (force) {
        if (_parentDriverInstance) {
            _parentDriverInstance.drive(0);
        } else if (typeof window.initParentTour === "function") {
            window.initParentTour({ force: true });
        }
    };

})();
