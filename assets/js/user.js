document.addEventListener('DOMContentLoaded', () => {
    const activeElements = document.querySelectorAll('.active');
    activeElements.forEach(element => {
        if (element.textContent.trim().toLowerCase() === 'kích hoạt') {
            element.classList.add('active1');
        }
    });

    const logo = document.querySelector('.user-logo');
    if (logo) {
        const textElement = logo.querySelector('p');
        if (textElement) {
            const fullName = logo.dataset.name || textElement.textContent.trim();
            logo.style.backgroundColor = getBackgroundColorFromInitial(fullName);
        }
    }
});

function getBackgroundColorFromInitial(name) {
    if (!name) return 'hsl(0, 70%, 50%)';
    const initial = name.trim().charAt(0).toLowerCase();
    const hash = initial.charCodeAt(0);
    const hue = (hash * 137) % 360;
    return `hsl(${hue}, 70%, 50%)`;
}