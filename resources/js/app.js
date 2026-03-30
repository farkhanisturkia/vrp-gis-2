import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// INIT GLOBAL
document.addEventListener('DOMContentLoaded', () => {
    initChoices();
});

// OPTIONAL: kalau nanti pakai modal dynamic
document.addEventListener('click', () => {
    setTimeout(() => {
        initChoices();
    }, 100);
});

function initChoices() {
    // SINGLE SELECT
    document.querySelectorAll('.js-choices').forEach(el => {
        if (el.dataset.choicesInit) return;

        el.choicesInstance = new Choices(el, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
        });

        el.dataset.choicesInit = true;
    });

    // MULTIPLE SELECT
    document.querySelectorAll('.js-choices-multiple').forEach(el => {
        if (el.dataset.choicesInit) return;

        el.choicesInstance = new Choices(el, {
            removeItemButton: true,
            duplicateItemsAllowed: false,
            searchEnabled: true,
        });

        el.dataset.choicesInit = true;
    });
}