<!-- Autors:Ervīns Jēkabsons; JS funkcija kas ļauj about lapā automātiski sķirstīt auto bildes kuras esmu oievienojis
Veidots 2024.gadā, jaunākā vesrija-->
let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    slides.forEach((slide, i) => {
        slide.style.display = i === index ? 'block' : 'none';
    });
}

// Initialize slideshow
document.addEventListener('DOMContentLoaded', () => {
    showSlide(currentSlide);
});
