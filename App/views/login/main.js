let angle = 135;
let running = true;

function animateGradient() {
    if (!running) return;
    angle += (Math.random() - 0.5) * 10; // Movimiento aleatorio en diferentes direcciones

    // Gradiente de escala de azules
    document.body.style.background = `radial-gradient(circle at center, #0d47a1, #1976d2, #42a5f5, #90caf9, #e3f2fd)`;
    document.body.style.backgroundSize = "200% 200%";
    document.body.style.animation = "gradientMove 5s infinite alternate ease-in-out";

    requestAnimationFrame(animateGradient);
}

window.addEventListener("visibilitychange", () => {
    running = !document.hidden;
    if (running) animateGradient();
});

animateGradient();

// Agregamos CSS dinámicamente para la animación
const style = document.createElement("style");
style.innerHTML = `
    @keyframes gradientMove {
        0% { background-position: 50% 50%; }
        25% { background-position: 40% 60%; }
        50% { background-position: 60% 40%; }
        75% { background-position: 30% 70%; }
        100% { background-position: 70% 30%; }
    }
`;
document.head.appendChild(style);
