
// ========================================
// RICHIE SANTOSO - PORTFOLIO JAVASCRIPT
// ========================================


// 1. MOBILE NAVIGATION

const menuToggle = document.getElementById("menu-toggle");
const navLinks = document.getElementById("nav-links");
const navItems = document.querySelectorAll(".nav-link");

menuToggle.addEventListener("click", () => {
    const isOpen = navLinks.classList.toggle("open");

    menuToggle.setAttribute("aria-expanded", isOpen);
});


// Close mobile menu after selecting a section

navItems.forEach((item) => {
    item.addEventListener("click", () => {
        navLinks.classList.remove("open");
        menuToggle.setAttribute("aria-expanded", "false");

        navItems.forEach((link) => {
            link.classList.remove("active");
        });

        item.classList.add("active");
    });
});


// 2. REVEAL ANIMATION

const revealElements = document.querySelectorAll(".reveal");

const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }
        });
    },
    {
        threshold: 0.12
    }
);

revealElements.forEach((element) => {
    observer.observe(element);
});


// 3. AUTOMATIC COPYRIGHT YEAR

const yearElement = document.getElementById("year");

yearElement.textContent = new Date().getFullYear();
