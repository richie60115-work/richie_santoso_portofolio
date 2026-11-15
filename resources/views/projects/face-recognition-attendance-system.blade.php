@extends('layouts.project')

@section('title', 'Face Recognition Attendance System')
@section('description', 'Employee attendance system with built-in face recognition, CRUD features and attendance reports.')

@section('content')
@php
    // Video / demo / GitHub: full URLs, e.g. 'https://youtu.be/xxxx'
    $video  = '';
    $demo   = '';
    $github = '';

    // FEATURES
    // icon  : an emoji (or use 'icon_img' => asset('img/icons/cash.png') for your own logo)
    // title : feature name
    // short : one small line under the name (optional)
    // shots : list of slides. Each slide = photos (left 70%) + text (right 30%, max 200 characters).
    //         Photos: 'img'  => one photo
    //                 'imgs' => [photo1, photo2, photo3]  (1 to 3 photos, resized automatically)
    //         Both keys work (they are combined), max 3 photos per slide.
    //         Put images in storage/app/public/TA, use asset('storage/TA/name.png')
    //         'label' => optional name of the slide. With 2+ labelled slides the popup shows tabs.
    //         More than one slide = slider with arrows + tabs.
    $features = [

        // ---------- 1. PEGAWAI ----------
        [
            'icon'  => '👥',
            'title' => 'Pegawai',
            'short' => 'Employee list and management',
            'shots' => [
                [
                    'label' => 'Add',
                    'img'   => asset('storage/TA/add_employee.png'),
                    'text'  => 'When a new employee joinned, operator or manager create new employee profile.',
                ],
                [
                    'label' => 'View detail',
                    'img'   => asset('storage/TA/detail_employee.png'),
                    'text'  => 'Shows the full data of one employee. Note: manager role it shows more sensitive information that hidden on operator',
                ],
                [
                    'label' => 'Edit',
                    'img'   => asset('storage/TA/edit_employee.png'),
                    'text'  => 'Operator and manager can do edit to updates the data of an employee.',
                ],
                [
                    'label' => 'Delete',
                    'img'   => asset('storage/TA/delete_employee.png'),
                    'text'  => 'Operator and manajer can delete an employee to remove the employee from the list when the employee were fired or resign.',
                ],
                [
                    'label' => 'Register face',
                    'img'   => asset('storage/TA/face_employee.png'),
                    
                    'text'  => 'Capture the face of the employee and give to system to study it, so the employe could check-in by using the face recognition system.',
                ],
            ],
        ],

        // ---------- 2. FACE RECOGNITION ----------
        [
            'icon'  => '📷',
            'title' => 'Face Recognition',
            'short' => 'Clock in with your face',
            'shots' => [
                [
                    'img'  => asset('storage/TA/face_recognition.png'),
                    'text' => 'Automatic attendance system using face recogniition where  employee looks at the camera. The Python model recognises the face and records its attendance.',
                ],
            ],
        ],

        // ---------- 3. ATTENDANCE ----------
        [
            'icon'  => '📋',
            'title' => 'Attendance',
            'short' => 'Records, summary and reports',
            'shots' => [
                [
                    'label' => 'Main page',
                    'img'   => asset('storage/TA/attendance_view.png'),
                    'text'  => 'full page of attendance and its feature.',
                ],
                [
                    'label' => 'Izin',
                    'img'   => asset('storage/TA/izin_attendance.png'),
                    'text'  => 'Create records an employee attendance who is absent with permission (Izin/ sick).',
                ],
                [
                    'label' => 'Cuti Bersama',
                    'img'   => asset('storage/TA/cutibersama_attendance.png'),
                    'text'  => 'Create  attendance of every employe for the duration of leave days (Cuti Bersama).',
                ],
                [
                    'label' => 'All attendance',
                    'img'   => asset('storage/TA/employee_attendance.png'),
                    'text'  => 'Shows the data attendance of every employee and the attendance of every employee on selected date and perioid.',
                ],
               
                [
                    'label' => 'Detail',
                    'img'   => asset('storage/TA/detail_attendance.png'),
                    'text'  => 'Shows all the information of a single attendance record.',
                ],
                [
                    'label' => 'Edit',
                    'img'   => asset('storage/TA/edit_attendance.png'),
                    'text'  => 'Operator or manager can edit to corrects an missing attendance record. Note: attendance that already filled is unchangeable to reduce cheat',
                ],
                [
                    'label' => 'Recover',
                    'img'   => asset('storage/TA/recover_attendance.png'),
                    'text'  => 'Operator or manager can create a attendance to replace an employee missing attendance record from yesterday.',
                ],
                [
                    'label' => 'Daily report',
                    'img'   => asset('storage/TA/daily_attendance.png'),
                    'text'  => 'Sytem can generate the report of the attendance for one day at the selected day to be seen or download or printed for management purposes.',
                ],
            ],
        ],

        // ---------- 4. SALARY ----------
        [
            'icon'  => '💵',
            'title' => 'Salary',
            'short' => 'Salary of every employee',
            'shots' => [
                [
                    'label' => 'Overview',
                    'img'   => asset('storage/TA/salary.png'),
                    'text'  => 'Full page of the salary of the employees and its feature. Note: this feature only accessible by manager, this salary data where integrated with employee attendance record and counted by system.',
                ],
                 [
                    'label' => 'Detail',
                    'img'   => asset('storage/TA/detail_salary.png'),
                    'text'  => 'System shows the salary detail of one employee.',
                ],
                [
                    'label' => 'Edit',
                    'img'   => asset('storage/TA/edit_salary.png'),
                    'text'  => 'Manager can adjusts the salary data like giving bonus or penalty to the specific employee.',
                ],
               
            ],
        ],
    ];

    // Cut every shot text to 200 characters
    $features = collect($features)->map(function ($f) {
        $f['shots'] = collect($f['shots'])->map(function ($s) {
            $s['text'] = \Illuminate\Support\Str::limit($s['text'] ?? '', 200);
            return $s;
        })->values()->all();
        return $f;
    })->values()->all();
@endphp
<div class="pm-box">
    <div class="pm-head">
        <h1>Face Recognition Attendance System</h1>
        <small>Laravel 10 + Python 3.10 · Individual Project · 12 February 2025 – 3 June 2026</small>
    </div>
    <div class="pm-body">
        <p>This project is created to replace manual employee attendance on PT.Abadi Nyon to be automatic attendance system. This project had built-in face recognition using Python program with version 3.10, This project had CRUD features for employee list, attendance reports, and salary management that automatically counted by system using each employee attendance records.</p>

        <h4>What I did</h4>
        <ul>
                <li>Built the web application with Laravel 10</li>
                <li>Build and integrated the Python face recognition model using Python v3.10</li>
                <li>Attendance reports</li>
                <li>Employee management (CRUD)</li>
                <li>Salary that integrated with employees attendance records</li>
        </ul>

        <h4>Features</h4>
        <div class="ft-grid">
            @foreach ($features as $i => $f)
                <button type="button" class="ft-btn" data-feature="{{ $i }}">
                    <span class="ft-icon">
                        @if (!empty($f['icon_img']))
                            <img src="{{ $f['icon_img'] }}" alt="">
                        @else
                            {{ $f['icon'] }}
                        @endif
                    </span>
                    <span>
                        <strong>{{ $f['title'] }}</strong>
                        @if (!empty($f['short']))<small>{{ $f['short'] }}</small>@endif
                    </span>
                </button>
            @endforeach
        </div>

        <div class="pm-btns">
            <a class="pm-btn on {{ $video ? '' : 'off' }}" @if($video) href="{{ $video }}" target="_blank" rel="noopener" @endif>Watch demo video</a>
            <a class="pm-btn {{ $demo ? '' : 'off' }}" @if($demo) href="{{ $demo }}" target="_blank" rel="noopener" @endif>Try live demo</a>
            <a class="pm-btn {{ $github ? '' : 'off' }}" @if($github) href="{{ $github }}" target="_blank" rel="noopener" @endif>View code on GitHub</a>
        </div>
    </div>
</div>

{{-- FEATURE POPUP (slider) --}}
<div class="fm" id="fm" hidden>
    <div class="fm-box" role="dialog" aria-modal="true" aria-labelledby="fm-title">
        <div class="fm-head">
            <h3 id="fm-title"></h3>
            <button type="button" class="fm-x" id="fm-close" aria-label="Close">×</button>
        </div>
        <div class="fm-tabs" id="fm-tabs" role="tablist" hidden></div>
        <div class="fm-stage">
            <button type="button" class="fm-nav fm-prev" id="fm-prev" aria-label="Previous slide">‹</button>
            <div class="fm-track" id="fm-track"></div>
            <button type="button" class="fm-nav fm-next" id="fm-next" aria-label="Next slide">›</button>
        </div>
        <div class="fm-count" id="fm-count" aria-live="polite"></div>
    </div>
</div>

<style>
/* Feature buttons */
.ft-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 12px; }
.ft-btn {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px; background: #fff; text-align: left;
    border: 1px solid var(--border, #DCE6F1); border-radius: 12px;
    color: var(--navy, #15345D); font: inherit; cursor: pointer; transition: .2s;
}
.ft-btn:hover { border-color: var(--medium-blue, #668BB8); background: var(--light-blue, #EAF1FA); }
.ft-btn:focus-visible, .fm-x:focus-visible, .fm-nav:focus-visible { outline: 3px solid var(--medium-blue, #668BB8); outline-offset: 3px; }
.ft-icon {
    display: grid; place-items: center; flex: none; width: 44px; height: 44px;
    border-radius: 10px; background: var(--light-blue, #EAF1FA); font-size: 22px;
}
.ft-icon img { width: 26px; height: 26px; object-fit: contain; }
.ft-btn strong { display: block; font-size: 15px; line-height: 1.3; }
.ft-btn small { display: block; color: var(--text-muted, #66758A); font-size: 12px; line-height: 1.4; }

/* Popup */
.fm { position: fixed; inset: 0; z-index: 1200; display: grid; place-items: center; padding: 16px; background: rgba(10, 25, 50, .65); }
.fm[hidden] { display: none; }
.fm-box { width: min(980px, 100%); background: #fff; border-radius: var(--radius, 18px); overflow: hidden; box-shadow: 0 24px 70px rgba(0, 0, 0, .35); }
.fm-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 20px; background: var(--navy, #15345D); color: #fff; }
.fm-head h3 { font-size: 18px; line-height: 1.3; }
.fm-x { flex: none; width: 36px; height: 36px; border: 0; border-radius: 50%; background: rgba(255, 255, 255, .2); color: #fff; font-size: 20px; cursor: pointer; }
.fm-stage { position: relative; }
.fm-track { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none; }
.fm-track::-webkit-scrollbar { display: none; }

/* One slide: photos 70% | text 30% */
.fm-slide {
    flex: 0 0 100%; scroll-snap-align: center;
    display: grid; grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
    gap: 24px; align-items: center;
    padding: 24px 64px; max-height: calc(92vh - 110px); overflow-y: auto;
}

/* 1 to 3 photos share the 70% area equally */
.fm-slide .fm-pics { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; min-width: 0; }
.fm-slide .fm-pics img {
    flex: 1 1 0; min-width: 0; width: auto; max-width: 100%; height: auto;
    max-height: min(62vh, 540px); object-fit: contain;
    border: 1px solid var(--border, #DCE6F1); border-radius: 10px;
}

.fm-slide .fm-text { padding-left: 22px; border-left: 2px solid var(--border, #DCE6F1); }
.fm-label { color: var(--navy, #15345D); font-size: 15px; margin-bottom: 6px; }
.fm-slide p {
    color: var(--text-muted, #66758A);
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-line;          /* lets \n make a new line */
}

/* Tabs */
.fm-tabs { display: flex; gap: 8px; padding: 12px 20px; overflow-x: auto; border-bottom: 1px solid var(--border, #DCE6F1); scrollbar-width: thin; }
.fm-tabs[hidden] { display: none; }
.fm-tab {
    flex: none; padding: 7px 14px; border: 1px solid var(--border, #DCE6F1); border-radius: 999px;
    background: #fff; color: var(--navy, #15345D); font: inherit; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: .2s;
}
.fm-tab:hover { background: var(--light-blue, #EAF1FA); }
.fm-tab[aria-selected="true"] { background: var(--navy, #15345D); border-color: var(--navy, #15345D); color: #fff; }
.fm-tab:focus-visible { outline: 3px solid var(--medium-blue, #668BB8); outline-offset: 3px; }
.fm-empty { grid-template-columns: 1fr; text-align: center; padding: 56px 64px; }
.fm-empty p { border: 0; padding: 0; }
.fm-nav {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 2;
    width: 40px; height: 40px; border: 1px solid var(--border, #DCE6F1); border-radius: 50%;
    background: #fff; color: var(--navy, #15345D); font-size: 24px; line-height: 1; cursor: pointer;
    box-shadow: 0 4px 14px rgba(21, 52, 93, .15);
}
.fm-prev { left: 12px; }
.fm-next { right: 12px; }
.fm-nav[hidden] { display: none; }
.fm-count { padding: 0 0 16px; text-align: center; color: var(--text-muted, #66758A); font-size: 13px; }

@media (max-width: 640px) {
    .fm-slide { grid-template-columns: 1fr; gap: 14px; padding: 20px 52px; }
    .fm-slide .fm-pics { gap: 6px; }
    .fm-slide .fm-pics img { max-height: 42vh; }
    .fm-prev { left: 6px; }
    .fm-next { right: 6px; }
    .fm-slide .fm-text { padding: 14px 0 0; border-left: 0; border-top: 2px solid var(--border, #DCE6F1); text-align: center; }
}
</style>

<script>
(function () {
    const features = @json($features);

    const modal = document.getElementById("fm");
    const track = document.getElementById("fm-track");
    const title = document.getElementById("fm-title");
    const count = document.getElementById("fm-count");
    const prev  = document.getElementById("fm-prev");
    const next  = document.getElementById("fm-next");
    const tabs  = document.getElementById("fm-tabs");
    let opener = null;

    // Turns anything (string, list, object of strings, list of objects) into a flat list of image URLs
    function collectPics(value) {
        if (!value) return [];
        if (typeof value === "string") return [value];
        if (Array.isArray(value)) return value.flatMap(collectPics);
        if (typeof value === "object") return Object.values(value).flatMap(collectPics);
        return [];
    }

    function updateCount() {
        const total = track.children.length;
        const current = Math.round(track.scrollLeft / track.clientWidth);
        count.textContent = total > 1 ? (current + 1) + " / " + total : "";

        tabs.querySelectorAll(".fm-tab").forEach((tab, i) => {
            const active = i === current;
            tab.setAttribute("aria-selected", active ? "true" : "false");
            if (active) tab.scrollIntoView({ inline: "center", block: "nearest" });
        });
    }

    function openFeature(index, button) {
        const f = features[index];
        opener = button;
        title.textContent = f.title;
        track.innerHTML = "";

        if (!f.shots.length) {
            const empty = document.createElement("div");
            empty.className = "fm-slide fm-empty";
            empty.innerHTML = "<p>Screenshots coming soon.</p>";
            track.appendChild(empty);
        } else {
            f.shots.forEach((shot) => {
                // Accept any shape: text, list, or key => value list. Keep max 3 photos.
                const pics = collectPics([shot.img, shot.imgs, shot.images, shot.photos])
                    .filter((src, i, all) => all.indexOf(src) === i)
                    .slice(0, 3);

                const slide = document.createElement("div");
                slide.className = "fm-slide";

                const wrap = document.createElement("div");
                wrap.className = "fm-pics";
                pics.forEach((src, n) => {
                    const img = document.createElement("img");
                    img.alt = f.title + " screenshot " + (n + 1);
                    img.onerror = () => { console.warn("Image not found:", src); img.remove(); };
                    img.src = src;
                    wrap.appendChild(img);
                });

                const textBox = document.createElement("div");
                textBox.className = "fm-text";
                if (shot.label) {
                    const heading = document.createElement("h4");
                    heading.className = "fm-label";
                    heading.textContent = shot.label;
                    textBox.appendChild(heading);
                }
                const text = document.createElement("p");
                text.textContent = shot.text || "";
                textBox.appendChild(text);

                slide.appendChild(wrap);
                slide.appendChild(textBox);
                track.appendChild(slide);
            });
        }

        // Tabs: only when there are 2+ slides and they have labels
        tabs.innerHTML = "";
        const labelled = f.shots.length > 1 && f.shots.some((shot) => shot.label);
        tabs.hidden = !labelled;
        if (labelled) {
            f.shots.forEach((shot, i) => {
                const tab = document.createElement("button");
                tab.type = "button";
                tab.className = "fm-tab";
                tab.setAttribute("role", "tab");
                tab.textContent = shot.label || ("Part " + (i + 1));
                tab.addEventListener("click", () => {
                    track.scrollTo({ left: i * track.clientWidth, behavior: "smooth" });
                });
                tabs.appendChild(tab);
            });
        }

        const many = track.children.length > 1;
        prev.hidden = !many;
        next.hidden = !many;
        modal.hidden = false;
        document.body.style.overflow = "hidden";
        track.scrollLeft = 0;
        updateCount();
        document.getElementById("fm-close").focus();
    }

    function closeFeature() {
        modal.hidden = true;
        track.innerHTML = "";
        document.body.style.overflow = "";
        if (opener) opener.focus();
    }

    function go(direction) {
        track.scrollBy({ left: direction * track.clientWidth, behavior: "smooth" });
    }

    document.querySelectorAll(".ft-btn").forEach((button) => {
        button.addEventListener("click", () => openFeature(+button.dataset.feature, button));
    });

    prev.addEventListener("click", () => go(-1));
    next.addEventListener("click", () => go(1));
    track.addEventListener("scroll", updateCount);
    document.getElementById("fm-close").addEventListener("click", closeFeature);
    modal.addEventListener("click", (e) => { if (e.target === modal) closeFeature(); });
    document.addEventListener("keydown", (e) => {
        if (modal.hidden) return;
        if (e.key === "Escape") closeFeature();
        if (e.key === "ArrowLeft") go(-1);
        if (e.key === "ArrowRight") go(1);
    });
})();
</script>
@endsection