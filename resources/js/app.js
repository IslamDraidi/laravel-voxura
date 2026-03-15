import './bootstrap';
// Intersection Observer — Featured Products Animation
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                // لو بدك الـ animation تتكرر كل مرة تسكرول
                entry.target.classList.remove('visible');
            }
        });
    }, {
        threshold: 0.3   // يظهر لما 30% من العنصر يبان
    });

    document.querySelectorAll('.featured-product').forEach(el => {
        observer.observe(el);
    });
     document.querySelectorAll('.pg-header, .product-card').forEach(el => {
        observer.observe(el);
    });
    document.querySelectorAll('.about-header, .about-card').forEach(el => {
    observer.observe(el);
});
document.querySelectorAll('.contact-header, .contact-form-wrap, .contact-info, .contact-info-item').forEach(el => {
    observer.observe(el);
});
});


