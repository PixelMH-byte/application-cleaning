document.addEventListener('DOMContentLoaded', () => {
    // Dinámica para tarjetas
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            alert('Funcionalidad en desarrollo para esta sección.');
        });
    });

    // Botón de navegación activa
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(nav => nav.classList.remove('active'));
            link.classList.add('active');
        });
    });

    // Mostrar/ocultar detalles de tareas
    const taskDetailsButtons = document.querySelectorAll('.btn-task-details');
    taskDetailsButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const taskId = event.target.dataset.taskId;
            const taskDetails = document.querySelector(`#details-${taskId}`);
            if (taskDetails) {
                taskDetails.classList.toggle('d-none');
            }
        });
    });
});
