import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.getElementById('is_reccuring_transfer').addEventListener('change', (e) => {
    const checkbox = document.getElementById('is_reccuring_transfer');

    document.querySelectorAll('.recurring').forEach(el => {
        console.log(el);
        el.classList.toggle('hidden');
    })
})