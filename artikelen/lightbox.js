document.addEventListener('DOMContentLoaded', function () {
    const lightboxOverlay = document.getElementById('lightbox-overlay');
    const lightboxImage = document.getElementById('lightbox-image');

    // Selecteer alle afbeeldingen binnen de grid-container
    document.querySelectorAll('.grid-container img').forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function () {
            lightboxImage.src = this.src;
            lightboxOverlay.classList.add('active');
        });
    });

    // Klik op de overlay (of afbeelding) sluit de lightbox
    lightboxOverlay.addEventListener('click', function () {
        lightboxOverlay.classList.remove('active');
    });
});