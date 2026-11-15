<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | Richie Santoso</title>
<meta name="description" content="@yield('description')">

@verbatim
<style>
:root{--light-blue:#EAF1FA;--soft-blue:#D6E3F3;--medium-blue:#668BB8;--navy:#15345D;--dark:#17212F;--white:#FFFFFF;--text-muted:#66758A;--border:#DCE6F1;--shadow:0 12px 35px rgba(21,52,93,.08);--radius:18px}
*{margin:0;padding:0;box-sizing:border-box;scroll-behavior:smooth}
body{font-family:"Segoe UI",Arial,sans-serif;background:var(--white);color:var(--dark);line-height:1.7;overflow-x:hidden}
a{text-decoration:none;color:inherit}
button{font:inherit}
.container{width:min(1120px,90%);margin:auto}
.header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.94);backdrop-filter:blur(15px);border-bottom:1px solid var(--border)}
.navbar{min-height:76px;display:flex;align-items:center;justify-content:space-between}
.logo{font-size:28px;font-weight:900;color:var(--navy);letter-spacing:-2px}
.logo span{color:var(--medium-blue)}
.nav-links{display:flex;align-items:center;gap:32px;list-style:none}
.nav-link{font-size:14px;font-weight:600;color:var(--text-muted);transition:.3s}
.nav-link:hover,.nav-link.active{color:var(--navy)}
.contact-nav{background:var(--navy);color:#fff;padding:11px 20px;border-radius:30px}
.contact-nav:hover{background:var(--medium-blue);color:#fff}
.menu-toggle{display:none;border:none;background:none;cursor:pointer}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:12px;padding:13px 24px;border-radius:8px;font-size:14px;font-weight:700;transition:.3s;cursor:pointer}
.btn-primary{background:var(--navy);color:#fff}
.btn-primary:hover{background:var(--medium-blue);transform:translateY(-3px)}
.btn-outline{border:1px solid var(--navy);color:var(--navy)}
.btn-outline:hover{background:var(--light-blue);transform:translateY(-3px)}
.detail-section{background:var(--light-blue);padding:70px 0 100px;min-height:calc(100vh - 76px - 75px)}
.detail-wrap{width:min(860px,90%);margin:auto}
.back-link{display:inline-flex;gap:10px;align-items:center;color:var(--navy);font-size:13px;font-weight:800;margin-bottom:22px;transition:.3s}
.back-link:hover{color:var(--medium-blue);gap:14px}
.pm-box{background:#fff;border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow)}
.pm-head{background:var(--navy);color:#fff;padding:26px 28px}
.pm-head h1{font-size:26px;line-height:1.3}.pm-head small{opacity:.85}
.pm-body{padding:22px 28px 30px}
.pm-body h4{color:var(--navy);margin:18px 0 8px;font-size:14px}
.pm-body ul{padding-left:20px;color:var(--text-muted);font-size:14px}
.pm-body p{color:var(--text-muted);font-size:14px}
.pm-gal{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px}
.pm-gal figure{aspect-ratio:16/10;border:2px dashed var(--border);border-radius:10px;display:grid;place-items:center;color:var(--text-muted);font-size:12px;text-align:center;overflow:hidden}
.pm-gal img{width:100%;height:100%;object-fit:cover}
.pm-btns{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}
.pm-btn{padding:9px 18px;border-radius:10px;font-weight:700;font-size:13px;border:1.5px solid var(--navy);color:var(--navy)}
.pm-btn.on{background:var(--navy);color:#fff}
.pm-btn.off{opacity:.45;pointer-events:none}
.footer{padding:25px 0;border-top:1px solid var(--border);background:var(--light-blue)}
.footer-content{display:flex;justify-content:space-between;align-items:center;gap:15px}
.footer-content p,.footer-content>span{color:var(--text-muted);font-size:11px}
@media (max-width:700px){
.menu-toggle{display:flex;flex-direction:column;gap:5px;padding:8px}
.menu-toggle span{display:block;width:24px;height:2px;background:var(--navy)}
.nav-links{display:none;position:absolute;top:76px;left:0;width:100%;padding:25px 5%;background:#fff;border-bottom:1px solid var(--border);flex-direction:column;align-items:stretch;gap:20px}
.nav-links.open{display:flex}
.nav-links li{text-align:center}
.contact-nav{display:inline-block}
.detail-section{padding:50px 0 75px}
.pm-head,.pm-body{padding-left:20px;padding-right:20px}
.footer-content{flex-direction:column;text-align:center}}
@media (prefers-reduced-motion:reduce){*,*::before,*::after{scroll-behavior:auto!important;transition-duration:.01ms!important}}
</style>
@endverbatim
</head>
<body>

<header class="header">
  <nav class="navbar container">
    <a href="{{ url('/') }}#home" class="logo">R<span>.</span>S</a>
    <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation" aria-expanded="false"><span></span><span></span><span></span></button>
    <ul class="nav-links" id="nav-links">
      <li><a href="{{ url('/') }}#home" class="nav-link">Home</a></li>
      <li><a href="{{ url('/') }}#about" class="nav-link">About</a></li>
      <li><a href="{{ url('/') }}#skills" class="nav-link">Skills</a></li>
      <li><a href="{{ url('/') }}#projects" class="nav-link active">Projects</a></li>
      <li><a href="{{ url('/') }}#contact" class="nav-link contact-nav">Contact Me</a></li>
    </ul>
  </nav>
</header>

<main class="detail-section">
  <div class="detail-wrap">
    <a href="{{ url('/') }}#projects" class="back-link"><span>←</span> Back to projects</a>
    @yield('content')
  </div>
</main>

<footer class="footer">
  <div class="container footer-content">
    <a href="{{ url('/') }}#home" class="logo">R<span>.</span>S</a>
    <p>Designed &amp; Developed by Richie Santoso</p>
    <span>© <span id="year"></span> All Rights Reserved.</span>
  </div>
</footer>

@verbatim
<script>
const t = document.getElementById("menu-toggle"), n = document.getElementById("nav-links");
t.addEventListener("click", () => { t.setAttribute("aria-expanded", n.classList.toggle("open")); });
document.getElementById("year").textContent = new Date().getFullYear();
</script>
@endverbatim
</body>
</html>