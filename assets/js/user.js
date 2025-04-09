document.addEventListener('DOMContentLoaded', function() {
    const active = document.querySelectorAll('.active');
    active.forEach(e => {
        const text = e.textContent.trim().toLowerCase();
        if(text === "activated") {
            e.classList.add('active1');
        } 
    })
})