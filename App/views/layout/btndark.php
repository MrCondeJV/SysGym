 <!--script de la logica del boton modo oscuro-->
 <script>
    document.addEventListener("DOMContentLoaded", function() {
            const darkModeSwitch = document.getElementById("darkModeSwitch");
            const htmlElement = document.documentElement;

            // Sincronizar el interruptor solo con el estado dark (light es por defecto)
            darkModeSwitch.checked = htmlElement.getAttribute('data-theme') === "dark";

            // Evento para alternar modo oscuro
            darkModeSwitch.addEventListener("change", function() {
                if (darkModeSwitch.checked) {
                    htmlElement.classList.add("dark-mode");
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem("theme", "dark");
                } else {
                    htmlElement.classList.remove("dark-mode");
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem("theme", "light");
                }
            });
        });
 </script>