@extends('layouts.project')

@section('title', 'Online Order and Management System')
@section('description', 'Ordering and inventory system for industrial use, from creating products and managing raw materials to handling customer orders.')

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
    //         Put images in storage/app/public/KP, use asset('storage/KP/name.png')
    //         'label' => optional name of the slide. With 2+ labelled slides the popup shows tabs.
    //         More than one slide = slider with arrows + tabs.
    $features = [

        // =============== CUSTOMER SIDE ===============
        [
            'group' => 'Customer side',
            'icon'  => '🛒',
            'title' => 'Order',
            'short' => 'Pick menu, cart and confirm',
            'shots' => [
                
                [
                    'label' => 'Menu',
                    'img'   => asset('storage/KP/customer_order.png'),
                    'text'  => 'Customer browse and picks the product and proceed to order. Every order will be put on customer cart and after customer finish, customer can checkout and create the order',
                ],
                [
                    'label' => 'Order detail',
                    'img'   => asset('storage/KP/customer_orderdetail.png'),
                    'text'  => 'Shows the detail of the order that was placed and confirmmed by customer.',
                ],
                [
                    'label' => 'Payment',
                    'img'   => asset('storage/KP/customer_payment.png'),
                    'text'  => 'Customer can pay the bill form the order via transfer bank and upload the photo to the web and wait while admin (operator or manager) checks and validate it.',
                ],
            ],
        ],
        
        

        // =============== OPERATOR SIDE (everything except User list and Profit & Loss) ===============
        [
            'group' => 'Operator side',
            'icon'  => '💲',
            'title' => 'Tax',
            'short' => 'Tax settings',
            'shots' => [
                [
                    'img'  => asset('storage/KP/tax.png'),
                    'text' => 'Operator can change and sets the tax (Pajak/ faktur pajak) that is used in the orders.',
                ],
            ],
        ],
        [
            'group' => 'Operator side',
            'icon'  => '📦',
            'title' => 'Menu',
            'short' => 'Create, edit, formula, variant',
            'shots' => [
                [
                    'label' => 'Color',
                    'img'   => asset('storage/KP/color_product.png'),
                    'text'  => 'Operator and manager can do CRUD for registred color in the web and soon can be paired to the product so the product can have that color variants.',
                ],
                [
                    'label' => 'Create product',
                    'img'   => asset('storage/KP/add_product.png'),
                    'text'  => 'Operator and manager creates a new product to be sells on the web.',
                ],
                [
                    'label' => 'Edit menu',
                    'img'   => asset('storage/KP/edit_product.png'),
                    'text'  => 'Operator and manager can edits or modify a product to change the product detail.',
                ],
                [
                    'label' => 'Formula',
                    'img'   => asset('storage/KP/formula_product.png'),
                    'text'  => 'Operator and manager can set a formula to make the product that later used by system to control available stocks and cost to create the products. But each product only can have 1 formula.',
                ],
                [
                    'label' => 'Variant',
                    'img'   => asset('storage/KP/varian_product.png'),
                    'text'  => 'Operator and manager can add a variant of size to the product so the product can have alot of variation size to be orderred.',
                ],
                [
                    'label' => 'Delete',
                    'img'   => asset('storage/KP/delete_product.png'),
                    'text'  => 'In case of mistake, Operator and manager can delete a product as long the product never been ordered.',
                ],
            ],
        ],
        [
            'group' => 'Operator side',
            'icon'  => '🧱',
            'title' => 'Raw Material',
            'short' => 'Stock, orders and deliveries',
            'shots' => [
                [
                    'label' => 'Stock',
                    'img'   => asset('storage/KP/stock_rawmaterial.png'),
                    'text'  => 'A popup (modal) that shows how much stock of each raw material is available on the system with material onhold that where prepared to be used to complete a order.',
                ],
                [
                    'label' => 'Add',
                    'img'   => asset('storage/KP/add_rawmaterial.png'),
                    'text'  => 'Operator and manager can register a new type of raw material that will be used in the future.',
                ],
                [
                    'label' => 'Purchase order',
                    'img'   => asset('storage/KP/order_rawmaterial.png'),
                    'text'  => 'When stocks run empty operator and manager can place an order in real life and write it on here by creating an order for raw material.',
                ],
                [
                    'label' => 'Edit',
                    'img'   => asset('storage/KP/edit_rawmaterial.png'),
                    'text'  => 'Operator and manager can edits the data of supplier for this raw material order.',
                ],
                [
                    'label' => 'Delete',
                    'img'   => asset('storage/KP/delete_rawmaterial.png'),
                    'text'  => 'Operator and manager can deletes a raw material incase the order is wrong / voidded. Note: delete only can be done if there no deliveries been made.',
                ],
                
                [
                    'label' => 'Delivery',
                    'img'   => asset('storage/KP/deliveries_rawmaterial.png'),
                    'text'  => 'When a deliveries came, operator and manager can put the deliveries data to each raw material on that order. But for now the operator and manager enters them, not the supplier.',
                ],
            ],
        ],
        [
            'group' => 'Operator side',
            'icon'  => '📑',
            'title' => 'Orders',
            'short' => 'Manage and validate orders',
            'shots' => [
                [
                    'label' => 'Order',
                    'img'   => asset('storage/KP/operador_order.png'),
                    'text'  => 'Operator and manager can manage the order they desire to be proceed viewing the order detail, changing the order status, validate customer payment, and can sent a comment that customer read.',
                ],
            ],
        ],

        // =============== MANAGER SIDE (User list and Profit & Loss only) ===============
        [
            'group' => 'Manager side',
            'icon'  => '👥',
            'title' => 'Users',
            'short' => 'User list, flag and suspend',
            'shots' => [
                [
                    'label' => 'User',
                    'img'   => asset('storage/KP/user.png'),
                    'text'  => 'Shows list of registered user with its data that can be filtered, view, edit, flag, suspend, and delete.',
                ],
                [
                    'label' => 'Flag',
                    'img'   => asset('storage/KP/flag_user.png'),
                    'text'  => 'Manager can flags a user for specific reason such as troublesome or bad user behavior.',
                ],
                [
                    'label' => 'Suspend',
                    'img'   => asset('storage/KP/suspend_user.png'),
                    'text'  => 'Manager can suspends a user account so that user account wont be able to login or make an order.',
                ],
            ],
        ],
        [
            'group' => 'Manager side',
            'icon'  => '📊',
            'title' => 'Profit & Loss',
            'short' => 'Cost and profit/loss statistics',
            'shots' => [
                [
                    'img'  => asset('storage/KP/labarugi.png'),
                    'text' => 'Shows statistics and summary of the cost, earning ,and the profit or losses.',
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
        <h1>Online Order and Management System</h1>
        <small>Laravel 10 · Individual Project · 5 March – 3 July 2026</small>
    </div>
    <div class="pm-body">
        <p>This project is created to help customer and admin ( operator and manager) to manage its business operation, from Customer side can order the product with online, while on admin side are creating products, managing raw materials to handling customer orders, and statistic profit losses.</p>

        <h4>What I did</h4>
        <ul>
                <li>Customer, operator and manager roles</li>
                <li>Raw material purchase orders and stock</li>
                <li>Cost and profit/loss reports</li>
                <li>Customer ordering system</li>
                <li>Product menu management system</li>
                <li>User management</li>
                <li>Admin (operator and manager) order management</li>
        </ul>

        <h4>Features</h4>
        @foreach (['Customer side' => 'orders and pays', 'Operator side' => 'orders, menu, stock and tax', 'Manager side' => 'user list and profit & loss only'] as $group => $note)
            <h5 class="ft-group">{{ $group }} <span>· {{ $note }}</span></h5>
            <div class="ft-grid">
                @foreach ($features as $i => $f)
                    @continue(($f['group'] ?? '') !== $group)
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
        @endforeach

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
.ft-group { margin: 18px 0 10px; color: var(--medium-blue, #668BB8); font-size: 13px; font-weight: 800; }
.ft-group span { color: var(--text-muted, #66758A); font-weight: 500; }
.ft-group:first-of-type { margin-top: 6px; }
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