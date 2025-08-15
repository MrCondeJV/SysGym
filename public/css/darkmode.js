//Script de inicialización temprana con prioridad para light 

    (function() {
        const savedTheme = localStorage.getItem("theme");
        
        // Prioridad absoluta para el modo light si no hay preferencia guardada
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            // Por defecto siempre light (incluso si no hay nada en localStorage)
            document.documentElement.classList.remove('dark-mode');
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem("theme", "light"); // Forzar light como predeterminado
        }
    })();
