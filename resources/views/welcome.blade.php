<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Richie Santoso | Portfolio</title>

    <meta name="description" content="Richie Santoso's personal portfolio. Web development, software engineering, and artificial intelligence projects.">

    @verbatim
    <style>

/* ========================================
   RICHIE SANTOSO - PERSONAL PORTFOLIO
   Color Theme: Light Blue / White / Navy
======================================== */

/* 1. GLOBAL SETTINGS */
:root {
    --light-blue: #EAF1FA;
    --soft-blue: #D6E3F3;
    --medium-blue: #668BB8;
    --navy: #15345D;
    --dark: #17212F;
    --white: #FFFFFF;
    --text-muted: #66758A;
    --border: #DCE6F1;

    --shadow: 0 12px 35px rgba(21, 52, 93, 0.08);
    --radius: 18px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
}

body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: var(--white);
    color: var(--dark);
    line-height: 1.7;
    overflow-x: hidden;
}

a {
    text-decoration: none;
    color: inherit;
}

button {
    font: inherit;
}

.container {
    width: min(1120px, 90%);
    margin: auto;
}

.section {
    padding: 100px 0;
}


/* 2. NAVIGATION */
.header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(15px);
    border-bottom: 1px solid var(--border);
}

.navbar {
    min-height: 76px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    font-size: 28px;
    font-weight: 900;
    color: var(--navy);
    letter-spacing: -2px;
}

.logo span {
    color: var(--medium-blue);
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 32px;
    list-style: none;
}

.nav-link {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    transition: 0.3s;
}

.nav-link:hover,
.nav-link.active {
    color: var(--navy);
}

.contact-nav {
    background: var(--navy);
    color: white;
    padding: 11px 20px;
    border-radius: 30px;
}

.contact-nav:hover {
    background: var(--medium-blue);
    color: white;
}

.menu-toggle {
    display: none;
    border: none;
    background: none;
    cursor: pointer;
}


/* 3. HERO SECTION */
.hero {
    min-height: 650px;
    padding-top: 100px;
    padding-bottom: 60px;
    position: relative;
    background:
        radial-gradient(circle at 5% 10%, #EAF1FA 0, transparent 32%),
        radial-gradient(circle at 95% 80%, #DCE9F7 0, transparent 30%),
        white;
}

.hero-container {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    align-items: center;
    gap: 60px;
}

.eyebrow,
.section-label {
    display: inline-block;
    color: var(--medium-blue);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

.eyebrow {
    background: var(--soft-blue);
    padding: 9px 16px;
    border-radius: 30px;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #36A477;
    border-radius: 50%;
    margin-right: 7px;
}

.hero-greeting {
    color: var(--text-muted);
    font-size: 22px;
    font-weight: 600;
}

.hero h1 {
    color: var(--navy);
    font-size: clamp(44px, 6vw, 72px);
    line-height: 1.12;
    letter-spacing: -3px;
    margin: 8px 0;
}

.hero h1 span,
.section-heading h2 span {
    color: var(--medium-blue);
}

.hero h2 {
    color: var(--dark);
    font-size: 23px;
    font-weight: 600;
    margin: 15px 0;
}

.hero-description {
    color: var(--text-muted);
    max-width: 520px;
    font-size: 16px;
    margin-bottom: 30px;
}

.hero-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 13px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    transition: 0.3s;
    cursor: pointer;
}

.btn-primary {
    background: var(--navy);
    color: white;
}

.btn-primary:hover {
    background: var(--medium-blue);
    transform: translateY(-3px);
}

.btn-outline {
    border: 1px solid var(--navy);
    color: var(--navy);
}

.btn-outline:hover {
    background: var(--light-blue);
    transform: translateY(-3px);
}

.hero-socials {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-top: 35px;
    color: var(--text-muted);
    font-size: 13px;
}

.hero-socials a {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    border: 1px solid var(--border);
    border-radius: 50%;
    color: var(--navy);
    font-size: 12px;
    font-weight: 800;
    transition: 0.3s;
}

.hero-socials a:hover {
    background: var(--navy);
    color: white;
}


/* 4. PROFILE VISUAL */
.hero-visual {
    min-height: 430px;
    position: relative;
    display: grid;
    place-items: center;
}

.profile-card {
    width: 300px;
    position: relative;
    z-index: 2;
    background: white;
    padding: 20px;
    border: 1px solid var(--border);
    border-radius: 24px;
    box-shadow: 0 25px 65px rgba(21, 52, 93, 0.15);
    text-align: center;
}

.profile-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.profile-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #36A477;
}

.profile-image {
    height: 230px;
    margin: 20px 0;
    border-radius: 17px;
    background: linear-gradient(145deg, #EAF1FA, #C5D8EE);
    display: grid;
    place-items: center;
    border: 1px solid var(--border);
}

.profile-placeholder {
    font-size: 75px;
    font-weight: 900;
    color: var(--navy);
    letter-spacing: -6px;
}

.profile-info h3 {
    color: var(--navy);
    font-size: 20px;
}

.profile-info p {
    color: var(--dark);
    font-size: 13px;
    font-weight: 600;
}

.profile-info > span {
    color: var(--text-muted);
    font-size: 12px;
}

.profile-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--border);
    color: var(--medium-blue);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.5px;
}
.profile-image {
    overflow: hidden;
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.profile-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}
.visual-decoration {
    position: absolute;
    border-radius: 50%;
    background: var(--soft-blue);
    opacity: 0.6;
}

.decoration-one {
    width: 280px;
    height: 280px;
    top: 30px;
    right: 0;
}

.decoration-two {
    width: 110px;
    height: 110px;
    bottom: 15px;
    left: 0;
    background: #B8CCE5;
}

.floating-card {
    position: absolute;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 13px;
    background: white;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.floating-card-top {
    top: 50px;
    left: -15px;
}

.floating-card-bottom {
    bottom: 55px;
    right: -15px;
}

.floating-icon {
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    background: var(--light-blue);
    color: var(--navy);
    border-radius: 10px;
    font-weight: 900;
}

.floating-card strong,
.floating-card small {
    display: block;
}

.floating-card strong {
    color: var(--navy);
    font-size: 12px;
}

.floating-card small {
    color: var(--text-muted);
    font-size: 10px;
}

.hero-bottom {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 75px;
    color: var(--text-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
}

.scroll-line {
    width: 55px;
    height: 1px;
    background: var(--medium-blue);
}


/* 5. SECTION HEADINGS */
.section-heading {
    text-align: center;
    margin-bottom: 55px;
}

.section-heading .section-label {
    margin-bottom: 10px;
}

.section-heading h2 {
    color: var(--navy);
    font-size: clamp(32px, 4vw, 44px);
    letter-spacing: -1.5px;
}

.section-heading p {
    color: var(--text-muted);
    font-size: 15px;
    margin-top: 10px;
}


/* 6. ABOUT */
.about-section {
    background: var(--light-blue);
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.about-main,
.about-details {
    background: white;
    padding: 35px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.about-icon {
    color: var(--medium-blue);
    font-size: 14px;
    font-weight: 900;
    margin-bottom: 20px;
}

.about-main h3 {
    color: var(--navy);
    font-size: 24px;
    margin-bottom: 18px;
}

.about-main p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 15px;
}

.about-details {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    gap: 22px;
}

.detail-card {
    display: flex;
    gap: 18px;
    align-items: flex-start;
}

.detail-number {
    display: grid;
    place-items: center;
    min-width: 42px;
    height: 42px;
    background: var(--light-blue);
    border-radius: 12px;
    color: var(--navy);
    font-size: 13px;
    font-weight: 900;
}

.detail-card h4 {
    color: var(--navy);
    font-size: 15px;
}

.detail-card p {
    color: var(--dark);
    font-size: 13px;
}

.detail-card small {
    color: var(--text-muted);
    font-size: 11px;
}


=====================================================
1) CSS: replace the whole "/* 7. SKILLS */" block
   (from .skills-section down to .skill-card p { ... })
   with this:
=====================================================

/* 7. SKILLS */
.skills-section {
    background: white;
}

.skills-list {
    max-width: 820px;
    margin: 0 auto;
    border-top: 2px solid var(--navy);
}

.skills-row {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 24px;
    align-items: baseline;
    padding: 20px 0;
    border-bottom: 1px solid var(--border);
}

.skills-row h3 {
    color: var(--navy);
    font-size: 17px;
}

.skills-row p {
    color: var(--text-muted);
    font-size: 15px;
}

@media (max-width: 700px) {
    .skills-row {
        grid-template-columns: 1fr;
        gap: 4px;
    }
}



       

/* 8. PROJECTS */
.projects-section {
    background: var(--light-blue);
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

.project-card {
    overflow: hidden;
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition: 0.3s;
}

.project-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow);
}

.project-image {
    height: 230px;
    position: relative;
    display: grid;
    place-items: center;
    overflow: hidden;
}

.project-image-one {
    background: linear-gradient(135deg, #15345D, #668BB8);
}

.project-image-two {
    background: linear-gradient(135deg, #203B63, #819EBE);
}

.project-category {
    position: absolute;
    top: 18px;
    left: 18px;
    padding: 6px 11px;
    background: rgba(255, 255, 255, 0.17);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.project-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: white;
    line-height: 1.3;
    text-align: center;
}

.project-preview > span {
    font-size: 14px;
    letter-spacing: 5px;
}

.project-preview strong {
    font-size: 34px;
    letter-spacing: 2px;
}

.project-preview small {
    font-size: 9px;
    letter-spacing: 2px;
    opacity: 0.8;
    margin-top: 8px;
}

.project-number {
    position: absolute;
    bottom: -20px;
    right: 15px;
    color: rgba(255, 255, 255, 0.13);
    font-size: 90px;
    font-weight: 900;
}

.project-content {
    padding: 27px;
}

.project-type {
    color: var(--medium-blue);
    font-size: 10px;
    font-weight: 900;
    letter-spacing: 1.5px;
}

.project-content h3 {
    color: var(--navy);
    font-size: 21px;
    margin: 8px 0 12px;
}

.project-content p {
    color: var(--text-muted);
    font-size: 13px;
    line-height: 1.8;
    min-height: 90px;
}

.project-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 20px 0;
}

.project-tags span {
    background: var(--light-blue);
    color: var(--navy);
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.project-link {
    display: inline-flex;
    gap: 10px;
    align-items: center;
    color: var(--navy);
    font-size: 13px;
    font-weight: 800;
    transition: 0.3s;
}

.project-link:hover {
    color: var(--medium-blue);
    gap: 15px;
}
=====================================================
1) CSS: tempel SEBELUM baris "/* 9. CONTACT */"
=====================================================

/* 8b. CERTIFICATIONS */
.cert-section {
    background: white;
}

.cert-list {
    max-width: 820px;
    margin: 0 auto;
    border-top: 2px solid var(--navy);
}

.cert-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 8px 24px;
    align-items: baseline;
    padding: 20px 0;
    border-bottom: 1px solid var(--border);
}

.cert-row h3 {
    color: var(--navy);
    font-size: 17px;
}

.cert-row p {
    color: var(--text-muted);
    font-size: 14px;
}

.cert-year {
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 500;
    text-align: right;
}

.cert-link {
    color: var(--navy);
    font-size: 13px;
    font-weight: 800;
    text-align: right;
    transition: 0.3s;
}

.cert-link:hover {
    color: var(--medium-blue);
}
button.cert-link {
    background: none;
    border: 0;
    padding: 0;
    cursor: pointer;
    font-family: inherit;
}
 
.cert-link:focus-visible,
.pm-x:focus-visible {
    outline: 3px solid var(--medium-blue);
    outline-offset: 3px;
}
 
.cert-img {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: calc(90vh - 200px);
    margin: 0 auto;
    object-fit: contain;
    border: 1px solid var(--border);
    border-radius: 8px;
}
@media (max-width: 700px) {
    .cert-row {
        grid-template-columns: 1fr;
    }

    .cert-year,
    .cert-link {
        text-align: left;
    }
}
        

/* 9. CONTACT */
.contact-section {
    background: white;
}

.contact-box {
    text-align: center;
    padding: 75px 25px;
    border-radius: 25px;
    background: linear-gradient(135deg, #15345D, #668BB8);
    color: white;
    box-shadow: var(--shadow);
}

.contact-box .section-label {
    color: #D6E3F3;
}

.contact-box h2 {
    font-size: clamp(30px, 5vw, 45px);
    line-height: 1.3;
    margin-bottom: 20px;
}

.contact-box h2 span {
    display: block;
    color: #C9DCF1;
}

.contact-box > p {
    max-width: 550px;
    margin: 0 auto 28px;
    color: #E3ECF7;
    font-size: 14px;
}

.btn-light {
    background: white;
    color: var(--navy);
}

.btn-light:hover {
    background: var(--light-blue);
    transform: translateY(-3px);
}

.contact-links {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 35px;
}

.contact-links a {
    color: white;
    font-size: 12px;
    font-weight: 600;
    opacity: 0.85;
    transition: 0.3s;
}

.contact-links a:hover {
    opacity: 1;
    text-decoration: underline;
}


/* 10. FOOTER */
.footer {
    padding: 25px 0;
    border-top: 1px solid var(--border);
    background: var(--light-blue);
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.footer-content p,
.footer-content > span {
    color: var(--text-muted);
    font-size: 11px;
}


/* 11. ANIMATIONS */
.reveal {
    opacity: 0;
    transform: translateY(25px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}

.reveal.visible {
    opacity: 1;
    transform: translateY(0);
}


/* 12. RESPONSIVE DESIGN */
@media (max-width: 900px) {

    .hero-container {
        gap: 30px;
    }

    .floating-card-top {
        left: -5px;
    }

    .floating-card-bottom {
        right: -5px;
    }

    .skills-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}


@media (max-width: 700px) {

    .section {
        padding: 75px 0;
    }

    .menu-toggle {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 8px;
    }

    .menu-toggle span {
        display: block;
        width: 24px;
        height: 2px;
        background: var(--navy);
    }

    .nav-links {
        display: none;
        position: absolute;
        top: 76px;
        left: 0;
        width: 100%;
        padding: 25px 5%;
        background: white;
        border-bottom: 1px solid var(--border);
        flex-direction: column;
        align-items: stretch;
        gap: 20px;
    }

    .nav-links.open {
        display: flex;
    }

    .nav-links li {
        text-align: center;
    }

    .contact-nav {
        display: inline-block;
    }

    .hero {
        padding-top: 65px;
    }

    .hero-container {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .hero-description {
        margin-left: auto;
        margin-right: auto;
    }

    .hero-buttons,
    .hero-socials {
        justify-content: center;
    }

    .hero-visual {
        margin-top: 30px;
        min-height: 400px;
    }

    .hero-bottom {
        margin-top: 35px;
    }

    .about-grid {
        grid-template-columns: 1fr;
    }

    .skills-grid {
        grid-template-columns: 1fr 1fr;
    }

    .projects-grid {
        grid-template-columns: 1fr;
    }

    .project-content p {
        min-height: auto;
    }

    .footer-content {
        flex-direction: column;
        text-align: center;
    }
}


@media (max-width: 420px) {

    .container {
        width: 92%;
    }

    .hero h1 {
        font-size: 43px;
    }

    .hero h2 {
        font-size: 19px;
    }

    .profile-card {
        width: 265px;
    }

    .floating-card {
        padding: 9px;
    }

    .floating-card-top {
        left: -3px;
    }

    .floating-card-bottom {
        right: -3px;
    }

    .floating-card strong {
        font-size: 10px;
    }

    .floating-card small {
        font-size: 9px;
    }

    .skills-grid {
        grid-template-columns: 1fr;
    }

    .about-main,
    .about-details {
        padding: 25px;
    }

    .contact-links {
        gap: 18px;
    }
}


/* 13. ACCESSIBILITY */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        scroll-behavior: auto !important;
        transition-duration: 0.01ms !important;
        animation-duration: 0.01ms !important;
    }
}

/* ===== PROJECT POPUP ===== */
button.project-link{background:none;border:0;cursor:pointer;font-family:inherit;padding:0}
.pm{position:fixed;inset:0;z-index:999;background:rgba(10,25,50,.6);display:grid;place-items:center;padding:16px}
.pm[hidden]{display:none}
.pm-box{background:#fff;border-radius:var(--radius);width:min(760px,100%);max-height:90vh;overflow:auto;box-shadow:0 24px 70px rgba(0,0,0,.35);animation:pmIn .22s ease-out}
@keyframes pmIn{from{opacity:0;transform:translateY(14px)}}
.pm-head{background:var(--navy);color:#fff;padding:20px 24px;display:flex;justify-content:space-between;gap:12px}
.pm-head h3{font-size:20px;line-height:1.3}.pm-head small{opacity:.85}
.pm-x{background:rgba(255,255,255,.2);border:0;color:#fff;width:36px;height:36px;border-radius:50%;font-size:20px;cursor:pointer;flex:none}
.pm-body{padding:22px 24px 26px}
.pm-body h4{color:var(--navy);margin:18px 0 8px;font-size:14px}
.pm-body ul{padding-left:20px;color:var(--text-muted);font-size:14px}
.pm-body p{color:var(--text-muted);font-size:14px}
.pm-tip{background:var(--light-blue);border-left:4px solid var(--medium-blue);border-radius:6px;padding:10px 14px;font-size:13px;margin-bottom:14px}
.pm-gal{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px}
.pm-gal figure{aspect-ratio:16/10;border:2px dashed var(--border);border-radius:10px;display:grid;place-items:center;color:var(--text-muted);font-size:12px;text-align:center;overflow:hidden}
.pm-gal img{width:100%;height:100%;object-fit:cover}
.pm-btns{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}
.pm-btn{padding:9px 18px;border-radius:10px;font-weight:700;font-size:13px;text-decoration:none;border:1.5px solid var(--navy);color:var(--navy)}
.pm-btn.on{background:var(--navy);color:#fff}
.pm-btn.off{opacity:.45;pointer-events:none}

    </style>
    @endverbatim
</head>
<body>

    <!-- NAVIGATION -->
    <header class="header">
        <nav class="navbar container">
            <a href="#home" class="logo">
                R<span>.</span>S
            </a>

            <button class="menu-toggle" id="menu-toggle"
                    aria-label="Toggle navigation"
                    aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links" id="nav-links">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#skills" class="nav-link">Skills</a></li>
                <li><a href="#projects" class="nav-link">Projects</a></li>
                <li><a href="#contact" class="nav-link contact-nav">Contact Me</a></li>
            </ul>
        </nav>
    </header>


    <main>

        <!-- HOME / HERO -->
        <section class="hero section" id="home">
            <div class="container hero-container">

                <div class="hero-content reveal">
                    <span class="eyebrow">
                        <span class="status-dot"></span>
                        WELCOME TO MY PORTFOLIO
                    </span>

                    <p class="hero-greeting">Hello, I'm</p>

                    <h1>
                        Richie <span>Santoso.</span>
                    </h1>

                    <h2>
                        Web Developer · AI · Data Science
                    </h2>

                    <p class="hero-description">
                        An Informatics graduate from
                        University of Surabaya, interested in building
                        practical web applications and exploring
                        artificial intelligence solutions.
                    </p>

                    <div class="hero-buttons">
                        <a href="#projects" class="btn btn-primary">
                            Explore My Projects
                            <span>→</span>
                        </a>

                        <a href="#contact" class="btn btn-outline">
                            Contact Me
                        </a>
                    </div>

                    <div class="hero-socials">
                        <span>Find me on</span>
                        <a href="https://www.linkedin.com/in/richie-santoso-9005b1429" target="_blank" rel="noopener" aria-label="LinkedIn">in</a>
                        <a href="https://github.com/richie60115-work" aria-label="GitHub">Git</a>
                        <a href="https://instagram.com/richie60115" aria-label="instagram">inst</a>
                        
                    </div>
                </div>


                <div class="hero-visual reveal">
                    <div class="visual-decoration decoration-one"></div>
                    <div class="visual-decoration decoration-two"></div>

                    <div class="profile-card">
                        <div class="profile-top">
                            <span>PERSONAL PROFILE</span>
                            <span class="profile-dot"></span>
                        </div>

                        <div class="profile-image">
                            <div class="profile-placeholder">
                                <img src="{{ asset('storage/foto_richie.png') }}" alt="Profile Photo">
                            </div>
                        </div>

                        <div class="profile-info">
                            <h3>Richie Santoso</h3>
                            <p>Informatics Engineering</p>
                            <span>Universitas Surabaya</span>
                        </div>

                        <div class="profile-footer">
                            <span>Fresh Graduate</span>
                            <span>GPA 3.28/4.00</span>
                            
                        </div>
                    </div>

                    <div class="floating-card floating-card-top">
                        <span class="floating-icon">⌘</span>
                        <div>
                            <strong>Developer</strong>
                            <small>Building solutions</small>
                        </div>
                    </div>

                    <div class="floating-card floating-card-bottom">
                        <span class="floating-icon">AI</span>
                        <div>
                            <strong>AI Enthusiast</strong>
                            <small>Exploring technology</small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="hero-bottom container">
                <span>SCROLL TO EXPLORE</span>
                <div class="scroll-line"></div>
            </div>
        </section>


        <!-- ABOUT -->
        <section class="section about-section" id="about">
            <div class="container">

                <div class="section-heading reveal">
                    <span class="section-label">GET TO KNOW ME</span>
                    <h2>About <span>Me</span></h2>
                    <p>A little introduction about myself and my interests.</p>
                </div>

                <div class="about-grid">

                    <div class="about-main reveal">
                        <h3>Hello, I'm Richie Santoso!</h3>

                        <p>
                            I am a fresh graduate in Informatics Engineering
                            with a specialization in Data Science and
                            Artificial Intelligence (DSAI) from
                            University of Surabaya (UBAYA).
                        </p>

                        <p>
                            I have experience developing web applications,
                            database-driven systems, and AI-based solutions
                            through academic and practical projects.
                        </p>

                        <p>
                            I enjoy learning new technologies, solving
                            problems, and analysis data to find information
                            that can be used.
                        </p>
                    </div>

                    <div class="about-details reveal">

                        <div class="detail-card">
                            <span class="detail-number">01</span>
                            <div>
                                <h4>Education</h4>
                                <p>Universitas Surabaya (UBAYA)</p>
                                <small>Informatics Engineering · DSAI</small>
                            </div>
                        </div>

                        <div class="detail-card">
                            <span class="detail-number">02</span>
                            <div>
                                <h4>Development</h4>
                                <p>Web & Software Applications</p>
                                <small>Laravel · PHP · MySQL</small>
                            </div>
                        </div>

                        <div class="detail-card">
                            <span class="detail-number">03</span>
                            <div>
                                <h4>Artificial Intelligence</h4>
                                <p>Computer Vision & Machine Learning</p>
                                <small>Python · Face Recognition · NLP</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>


         <!-- SKILLS -->
        <section class="section skills-section" id="skills">
            <div class="container">

                <div class="section-heading reveal">
                    <span class="section-label">WHAT I WORK WITH</span>
                    <h2>Technical <span>Skills</span></h2>
                    <p>Technologies and tools I have worked with.</p>
                </div>

                <div class="skills-list reveal">
                    <div class="skills-row">
                        <h3>Programming</h3>
                        <p>Python, JavaScript, PHP</p>
                    </div>
                    <div class="skills-row">
                        <h3>Web Development</h3>
                        <p>Laravel, AdminLTE 4, HTML, CSS, Bootstrap, jQuery</p>
                    </div>
                    <div class="skills-row">
                        <h3>Database</h3>
                        <p>MySQL</p>
                    </div>
                    <div class="skills-row">
                        <h3>AI / Machine Learning</h3>
                        <p>Face Recognition, NLP</p>
                    </div>
                    <div class="skills-row">
                        <h3>Tools</h3>
                        <p>Git, VS Code, Android Studio, Figma, Sublime</p>
                    </div>
                    <div class="skills-row">
                        <h3>Other Interests</h3>
                        <p>Problem Solving, Data Analysis, Automation</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- PROJECTS -->
        <section class="section projects-section" id="projects">
            <div class="container">

                <div class="section-heading reveal">
                    <span class="section-label">MY RECENT WORK</span>
                    <h2>Featured <span>Projects</span></h2>
                    <p>Select a project to see its details, screenshots and demo video.</p>
                </div>

                <div class="projects-grid">
    <article class="project-card reveal">
        <div class="project-image project-image-one">
            <span class="project-category">ANDROID</span>
            <div class="project-preview">
                <span>ANDROID APPLICATION</span>
                <strong>BUSINESS MANAGEMENT</strong>
                <small>18 MAY – 8 JULY 2025</small>
            </div>
            <span class="project-number">01</span>
        </div>
        <div class="project-content">
            <span class="project-type">TEAM PROJECT</span>
            <h3>Business Funds Management App</h3>
            <p>Android app built to help manage business operations funds. I designed the interface, built the button functions and page navigation, and fixed bugs.</p>
            <div class="project-tags"><span>Android Studio</span><span>Kotlin/Java</span></div>
            <a href="{{ route('projects.business-management-app') }}" class="project-link">View details <span>→</span></a>
        </div>
    </article>

    

    <article class="project-card reveal">
        <div class="project-image project-image-one">
            <span class="project-category">AI</span>
            <div class="project-preview">
                <span>LARAVEL 10 + PYTHON 3.10</span>
                <strong>FACE RECOGNITION</strong>
                <small>12 FEBRUARY 2025 – 3 June 2026</small>
            </div>
            <span class="project-number">02</span>
        </div>
        <div class="project-content">
            <span class="project-type">INDIVIDUAL PROJECT</span>
            <h3>Face Recognition Attendance System</h3>
            <p>Employee attendance system with built-in face recognition, CRUD features, attendance reports, and salary.</p>
            <div class="project-tags"><span>Laravel 10</span><span>Python 3.10</span><span>Face Recognition</span><span>AdminLTE 4</span><span>MySQL</span></div>
            <a href="{{ route('projects.face-recognition-attendance-system') }}" class="project-link">View details <span>→</span></a>
        </div>
    </article>

    <article class="project-card reveal">
        <div class="project-image project-image-two">
            <span class="project-category">WEB</span>
            <div class="project-preview">
                <span>LARAVEL 10</span>
                <strong>ONLINE ORDER and MANAGEMENT</strong>
                <small>5 MARCH – 3 JULY 2026</small>
            </div>
            <span class="project-number">03</span>
        </div>
        <div class="project-content">
            <span class="project-type">INDIVIDUAL PROJECT</span>
            <h3>Online Order and Management System</h3>
            <p>Ordering and inventory system for industrial use, from creating products and managing raw materials to handling customer orders with customer online order buildin.</p>
            <div class="project-tags"><span>Laravel 10</span><span>AdminLTE 4</span><span>MySQL</span><span>jQuery</span></div>
            <a href="{{ route('projects.online-order-and-management-system') }}" class="project-link">View details <span>→</span></a>
        </div>
    </article>
</div>
            </div>
        </section>

       <!-- CERTIFICATIONS -->
        <section class="section cert-section" id="certifications">
            <div class="container">
 
                <div class="section-heading reveal">
                    <span class="section-label">LEARNING JOURNEY</span>
                    <h2>Online Course <span>Certifications</span></h2>
                    <p>Courses I have completed to keep my skills up to date.</p>
                </div>
 
                <div class="cert-list reveal">
 
                    <div class="cert-row">
                        <div>
                            <h3>PCAP - Programming Essentials in Python</h3>
                            <p>Cisco</p>
                        </div>
                        <span class="cert-year">2024</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/PCAP.png') }}"
                                data-title="PCAP - Programming Essentials in Python"
                                data-sub="Cisco · 2024">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>Introduction to TensorFlow for Artificial Intelligence, Machine Learning, and Deep Learning</h3>
                            <p>Coursera</p>
                        </div>
                        <span class="cert-year">2024</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/AI.png') }}"
                                data-title="Introduction to TensorFlow for Artificial Intelligence, Machine Learning, and Deep Learning"
                                data-sub="Coursera · 2024">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>Convolutional Neural Networks in TensorFlow</h3>
                            <p>Coursera</p>
                        </div>
                        <span class="cert-year">2024</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/CNN.png') }}"
                                data-title="Convolutional Neural Networks in TensorFlow"
                                data-sub="Coursera · 2024">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>Advanced Computer Vision with TensorFlow</h3>
                            <p>Coursera</p>
                        </div>
                        <span class="cert-year">2025</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/Advanced_comvis.png') }}"
                                data-title="Advanced Computer Vision with TensorFlow"
                                data-sub="Coursera · 2025">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>HCIA-AI V3.5 (Indonesian)</h3>
                            <p>Huawei ICT Academy</p>
                        </div>
                        <span class="cert-year">2025</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/HCIA_v35.png') }}"
                                data-title="HCIA-AI V3.5 (Indonesian)"
                                data-sub="Huawei ICT Academy · 2025">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>Data Analytics Essentials</h3>
                            <p>Cisco</p>
                        </div>
                        <span class="cert-year">2025</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/Data_Analytics.png') }}"
                                data-title="Data Analytics Essentials"
                                data-sub="Cisco · 2025">View certificate</button>
                    </div>
 
                    <!-- TODO: isi nama course, penerbit, dan tahun untuk Data_Science.png -->
                    <div class="cert-row">
                        <div>
                            <h3>Introduction to Data Science</h3>
                            <p>Issuer</p>
                        </div>
                        <span class="cert-year">Year</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/Data_Science.png') }}"
                                data-title="Data Science"
                                data-sub="Issuer · Year">View certificate</button>
                    </div>
 
                    <div class="cert-row">
                        <div>
                            <h3>HCIA-AI V4</h3>
                            <p>Huawei ICT Academy</p>
                        </div>
                        <span class="cert-year">2025</span>
                        <button type="button" class="cert-link"
                                data-img="{{ asset('storage/certificates/HCIA_v4.png') }}"
                                data-title="HCIA-AI V4"
                                data-sub="Huawei ICT Academy · 2025">View certificate</button>
                    </div>
 
                </div>
            </div>
        </section>
 
 

 
    <!-- CERTIFICATE POPUP -->
    <div class="pm" id="cert-modal" hidden>
        <div class="pm-box" role="dialog" aria-modal="true" aria-labelledby="cert-title">
            <div class="pm-head">
                <div><h3 id="cert-title"></h3><small id="cert-sub"></small></div>
                <button type="button" class="pm-x" id="cert-close" aria-label="Close">×</button>
            </div>
            <div class="pm-body">
                <img class="cert-img" id="cert-img" alt="">
            </div>
        </div>
    </div>
 
        <!-- CONTACT -->
        <section class="section contact-section" id="contact">
            <div class="container">

                <div class="contact-box reveal">
                    <span class="section-label">LET'S CONNECT</span>

                    <h2>
                        Let's Build Something
                        <span>Great Together.</span>
                    </h2>

                    <p>
                        I am open to connecting with professionals,
                        exploring opportunities, and discussing
                        interesting projects.
                    </p>

                    <a href="mailto:richie60115@gmail.com"
                       class="btn btn-light">
                        Get In Touch <span>→</span>
                    </a>

                    <div class="contact-links">
                        <a href="https://www.linkedin.com/in/richie-santoso-9005b1429" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                        <a href="https://github.com/richie60115-work" target="_blank" rel="noopener noreferrer">GitHub</a>
                        <a href="https://instagram.com/richie60115" target="_blank" rel="noopener noreferrer">instagram</a>
                        <a href="mailto:richie60115@gmail.com">Email</a>
                   
                    </div>
                </div>

            </div>
        </section>

    </main>



    <!-- PROJECT POPUP -->
    <!-- <div class="pm" id="pm" hidden>
        <div class="pm-box" role="dialog" aria-modal="true" aria-labelledby="pm-title">
            <div class="pm-head">
                <div><h3 id="pm-title"></h3><small id="pm-sub"></small></div>
                <button type="button" class="pm-x" id="pm-close" aria-label="Close">×</button>
            </div>
            <div class="pm-body" id="pm-body"></div>
        </div>
    </div> -->

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-content">
            <a href="#home" class="logo">R<span>.</span>S</a>
            <p>Designed & Developed by Richie Santoso</p>
            <span>© <span id="year"></span> All Rights Reserved.</span>
        </div>
    </footer>


    @verbatim
    <script>

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

// 4. PROJECT POPUP
// Fill in images (e.g. "img/orders1.png" inside public/img), video (YouTube link), github and demo for each project.
// const PROJECTS = [
//   {
//     "title": "Business Management App",
//     "type": "Android Application",
//     "role": "Team Project",
//     "date": "18 May – 8 July 2025",
//     "short": "Android app built to help manage business operations. I designed the interface, built the button functions and page navigation, and fixed bugs.",
//     "points": [
//       "Designed the application interface",
//       "Built button functions and page navigation",
//       "Checked that every button worked as intended",
//       "Found and fixed bugs"
//     ],
//     "images": [],
//     "video": "",
//     "github": "",
//     "demo": ""
//   },
//   {
//     "title": "Restaurant Ordering App",
//     "type": "Laravel 10",
//     "role": "Team Project",
//     "date": "11 June – 6 July 2025",
//     "short": "Food ordering application with separate functions for customers and administrators.",
//     "points": [
//       "Created the database tables and connected them to the app",
//       "Made sure CRUD actions are performed and stored correctly",
//       "Helped find and fix bugs"
//     ],
//     "images": [],
//     "video": "",
//     "github": "",
//     "demo": ""
//   },
//   {
//     "title": "Face Recognition Attendance System",
//     "type": "Laravel 10 + Python 3.10",
//     "role": "Individual Project",
//     "date": "12 February 2025 – 2026",
//     "short": "Employee attendance system with built-in face recognition, CRUD features and attendance reports.",
//     "points": [
//       "Built the web application with Laravel 10",
//       "Integrated the Python face recognition model",
//       "Attendance reports",
//       "Employee management (CRUD)"
//     ],
//     "images": [],
//     "video": "",
//     "github": "",
//     "demo": ""
//   },
//   {
//     "title": "Online Order and Management System",
//     "type": "Laravel 10 + Python 3.10",
//     "role": "Individual Project",
//     "date": "5 March – 3 July 2026",
//     "short": "Ordering and inventory system for industrial use, from creating products and managing raw materials to handling customer orders.",
//     "points": [
//       "Customer, operator and manager roles",
//       "Raw material purchase orders and stock",
//       "Cost and profit/loss reports",
//       "Chat between customer and staff"
//     ],
//     "images": [],
//     "video": "",
//     "github": "",
//     "demo": ""
//   }
// ];

// const pm = document.getElementById("pm");
// const esc = (t) => String(t).replace(/[&<>"]/g, (c) => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
// const btn = (url, label, primary) =>
//     `<a class="pm-btn ${primary ? "on" : ""} ${url ? "" : "off"}" ${url ? `href="${esc(url)}" target="_blank" rel="noopener"` : ""}>${label}</a>`;

// document.querySelectorAll(".project-link").forEach((b) => {
//     b.addEventListener("click", () => {
//         const p = PROJECTS[+b.dataset.project];
//         document.getElementById("pm-title").textContent = p.title;
//         document.getElementById("pm-sub").textContent = `${p.type} · ${p.role} · ${p.date}`;
//         const shots = p.images.length
//             ? p.images.map((s) => `<figure><img src="${esc(s)}" alt="${esc(p.title)} screenshot" loading="lazy"></figure>`).join("")
//             : [1, 2, 3].map((n) => `<figure>Screenshot ${n}<br>coming soon</figure>`).join("");
//         document.getElementById("pm-body").innerHTML =
//             `<p>${esc(p.short)}</p><h4>What I did</h4><ul>${p.points.map((x) => `<li>${esc(x)}</li>`).join("")}</ul>` +
//             `<h4>Screenshots</h4><div class="pm-gal">${shots}</div>` +
//             `<div class="pm-btns">${btn(p.video, "Watch demo video", true)}${btn(p.demo, "Try live demo")}${btn(p.github, "View code on GitHub")}</div>`;
//         pm.hidden = false;
//         document.body.style.overflow = "hidden";
//     });
// });

// function closePopup() { pm.hidden = true; document.body.style.overflow = ""; }
// document.getElementById("pm-close").addEventListener("click", closePopup);
// pm.addEventListener("click", (e) => { if (e.target === pm) closePopup(); });
// document.addEventListener("keydown", (e) => { if (e.key === "Escape" && !pm.hidden) closePopup(); });

const certModal = document.getElementById("cert-modal");
const certImg = document.getElementById("cert-img");
let certOpener = null;
 
document.querySelectorAll(".cert-link[data-img]").forEach((button) => {
    button.addEventListener("click", () => {
        certOpener = button;
        document.getElementById("cert-title").textContent = button.dataset.title;
        document.getElementById("cert-sub").textContent = button.dataset.sub;
        certImg.src = button.dataset.img;
        certImg.alt = "Certificate: " + button.dataset.title;
        certModal.hidden = false;
        document.body.style.overflow = "hidden";
        document.getElementById("cert-close").focus();
    });
});
 
function closeCertModal() {
    certModal.hidden = true;
    certImg.removeAttribute("src");
    document.body.style.overflow = "";
    if (certOpener) certOpener.focus();
}
 
document.getElementById("cert-close").addEventListener("click", closeCertModal);
certModal.addEventListener("click", (e) => { if (e.target === certModal) closeCertModal(); });
document.addEventListener("keydown", (e) => { if (e.key === "Escape" && !certModal.hidden) closeCertModal(); });
 
    </script>
    @endverbatim
</body>
</html>