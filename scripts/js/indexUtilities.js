const banner = document.querySelector(".sticky-banner");

window.addEventListener("scroll", () => {
    if (window.scrollY > 200) {
        banner.classList.add("visible");
    } else {
        banner.classList.remove("visible");
    }
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add("in-view");
        }
    });
});
document.querySelectorAll('.hide_brush').forEach(el => observer.observe(el));
document.querySelectorAll('.text').forEach(el => observer.observe(el));
document.querySelectorAll('.border_part').forEach(el => observer.observe(el));
document.querySelectorAll('.slide_title').forEach(el => observer.observe(el));