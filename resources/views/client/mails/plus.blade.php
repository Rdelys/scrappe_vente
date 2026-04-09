@extends('client.layouts.app')

@section('title', 'Envoi mail en masse')

@section('content')

<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* ===== DESIGN SYSTEM ULTRA PREMIUM ===== */
    :root {
        --primary: #6366f1;
        --primary-light: #818cf8;
        --primary-dark: #4f46e5;
        --secondary: #8b5cf6;
        --success: #10b981;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
        --white: #ffffff;
        --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        --radius-xs: 0.375rem;
        --radius-sm: 0.5rem;
        --radius-md: 0.75rem;
        --radius-lg: 1rem;
        --radius-xl: 1.5rem;
        --radius-2xl: 2rem;
    }

    /* ===== CARTE PREMIUM ===== */
    .card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        padding: 2rem;
        margin: 2rem auto;
        max-width: 800px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-2xl);
        border-color: var(--gray-300);
    }

    /* ===== ALERTES ===== */
    .alert-success {
        background: var(--success-light);
        color: var(--success);
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
        border: 1px solid #bbf7d0;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease;
    }

    .alert-success i {
        color: var(--success);
        font-size: 1.25rem;
    }

    .alert-danger {
        background: var(--danger-light);
        color: var(--danger);
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
        border: 1px solid #fecaca;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease;
    }

    .alert-danger i {
        color: var(--danger);
        font-size: 1.25rem;
    }

    /* ===== TITRE ===== */
    h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        letter-spacing: -0.02em;
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 1rem;
    }

    h2 i {
        color: var(--primary);
        font-size: 2rem;
        background: var(--gray-100);
        padding: 0.75rem;
        border-radius: var(--radius-lg);
    }

    /* ===== FORMULAIRES ===== */
    label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }

    input[type="text"],
    input[type="email"],
    textarea {
        width: 100%;
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        background: var(--white);
        transition: all 0.2s;
        box-shadow: var(--shadow-xs);
        font-family: 'Inter', sans-serif;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    input[type="text"]:hover,
    input[type="email"]:hover,
    textarea:hover {
        border-color: var(--gray-400);
    }

    textarea {
        resize: vertical;
        min-height: 150px;
        line-height: 1.6;
    }

    /* ===== EMAIL WRAPPER ===== */
    #email-wrapper {
        min-height: 50px;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        background: var(--white);
        transition: all 0.2s;
    }

    #email-wrapper:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    #email-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* ===== BADGES EMAIL ===== */
    #email-container span {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-md);
        animation: badgeIn 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    #email-container span i {
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    #email-container span i:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    @keyframes badgeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ===== INPUT MANUEL ===== */
    #manual-email-input {
        border: none;
        outline: none;
        flex: 1;
        min-width: 200px;
        padding: 0.5rem;
        font-size: 0.95rem;
        background: transparent;
    }

    #manual-email-input::placeholder {
        color: var(--gray-400);
        font-style: italic;
    }

    /* ===== BOUTON AJOUT LEAD ===== */
    #openLeadModal {
        margin-top: 0.75rem;
        background: var(--white);
        color: var(--primary);
        border: 2px solid var(--primary);
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    #openLeadModal:hover {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    /* ===== BOUTON ENVOYER ===== */
    button[type="submit"] {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 1rem 2rem;
        border: none;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        margin-top: 0.5rem;
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
    }

    button[type="submit"] i {
        font-size: 1rem;
    }

    /* ===== MODAL PREMIUM ===== */
    #leadModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    #leadModal > div {
        background: var(--white);
        width: 500px;
        max-width: 90%;
        max-height: 80vh;
        border-radius: var(--radius-2xl);
        padding: 2rem;
        overflow: auto;
        box-shadow: var(--shadow-2xl);
        border: 1px solid var(--gray-200);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #leadModal h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    #leadModal h3::before {
        content: '\f0c0';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--primary);
    }

    /* ===== RECHERCHE MODAL ===== */
    #leadSearch {
        width: 100%;
        padding: 0.875rem 1.25rem;
        margin-bottom: 1.5rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        font-size: 0.95rem;
        transition: all 0.2s;
        background: var(--white);
    }

    #leadSearch:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    #leadSearch::placeholder {
        color: var(--gray-400);
        font-style: italic;
    }

    /* ===== LISTE LEADS ===== */
    #leadList {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        background: var(--gray-50);
    }

    .lead-item {
        padding: 1rem;
        cursor: pointer;
        border-bottom: 1px solid var(--gray-200);
        transition: all 0.2s;
        background: var(--white);
    }

    .lead-item:last-child {
        border-bottom: none;
    }

    .lead-item:hover {
        background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
        transform: translateX(4px);
        box-shadow: var(--shadow-sm);
    }

    .lead-item strong {
        color: var(--gray-900);
        font-size: 1rem;
        font-weight: 600;
        display: block;
        margin-bottom: 0.25rem;
    }

    .lead-item small {
        color: var(--gray-500);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .lead-item small::before {
        content: '\f0e0';
        font-family: 'Font Awesome 6 Free';
        font-weight: 400;
        color: var(--primary);
        font-size: 0.75rem;
    }

    /* ===== BOUTON FERMER MODAL ===== */
    #closeLeadModal {
        background: var(--white);
        color: var(--gray-700);
        border: 2px solid var(--gray-200);
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        margin-top: 1rem;
    }

    #closeLeadModal:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        transform: translateY(-2px);
    }

    /* ===== ESPACEMENTS ===== */
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    .mb-3 {
        margin-bottom: 1rem;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
    .mb-5 {
        margin-bottom: 2rem;
    }
    .mt-2 {
        margin-top: 0.5rem;
    }
    .mt-3 {
        margin-top: 1rem;
    }
    .mt-4 {
        margin-top: 1.5rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .card {
            padding: 1.5rem;
            margin: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h2 i {
            font-size: 1.5rem;
            padding: 0.5rem;
        }

        button[type="submit"] {
            width: 100%;
            justify-content: center;
        }

        #email-wrapper {
            padding: 0.375rem;
        }

        #email-container span {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 640px) {
        .card {
            padding: 1.25rem;
        }

        #openLeadModal {
            width: 100%;
            justify-content: center;
        }

        #leadModal > div {
            padding: 1.5rem;
        }

        #leadModal h3 {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 1rem;
        }

        h2 {
            font-size: 1.25rem;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            padding: 0.875rem 1rem;
        }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }
</style>

<div class="card">
    @if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    <h2>
        <i class="fa-solid fa-paper-plane"></i>
        Envoi de mails en masse
    </h2>

    <form method="POST" action="{{ route('client.mails.plus.send') }}">
        @csrf

        <div class="mb-4">
            <label>Sujet</label>
            <input type="text" name="subject" placeholder="Objet de l'email" required>
        </div>

        <div class="mb-4">
            <label>Destinataires</label>

            <div id="email-wrapper">
                <div id="email-container"></div>
                <input type="text"
                       id="manual-email-input"
                       placeholder="Ajouter email et appuyer sur Entrée">
            </div>

            <input type="hidden" name="emails" id="emails-input">

            <button type="button" id="openLeadModal">
                <i class="fa-solid fa-plus"></i> Ajouter depuis les leads
            </button>
        </div>

        <div class="mb-5">
            <label>Message</label>
            <textarea name="message" rows="6" placeholder="Contenu de votre message..." required></textarea>
        </div>

        <button type="submit">
            <i class="fa-solid fa-paper-plane"></i> Envoyer les emails
        </button>
    </form>
</div>

<!-- Modal Leads -->
<div id="leadModal">
    <div>
        <h3>Sélectionner des leads</h3>

        <input type="text" id="leadSearch" placeholder="Rechercher...">

        <div id="leadList">
            @foreach($leads as $lead)
                <div class="lead-item"
                     data-email="{{ strtolower($lead->email) }}"
                     id="lead-{{ md5($lead->email) }}">
                    <strong>{{ $lead->prenom_nom ?? $lead->nom }}</strong>
                    <small>{{ $lead->email }}</small>
                </div>
            @endforeach
        </div>

        <div style="text-align: right;">
            <button type="button" id="closeLeadModal">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("leadModal");
    const openBtn = document.getElementById("openLeadModal");
    const closeBtn = document.getElementById("closeLeadModal");
    const emailContainer = document.getElementById("email-container");
    const hiddenInput = document.getElementById("emails-input");
    const manualInput = document.getElementById("manual-email-input");
    const searchInput = document.getElementById("leadSearch");
    const leadItems = document.querySelectorAll(".lead-item");

    let emails = [];

    // ========================
    // OUVERTURE / FERMETURE
    // ========================
    openBtn.onclick = () => modal.style.display = "flex";
    closeBtn.onclick = () => modal.style.display = "none";

    // ========================
    // AJOUT EMAIL (COMMUN)
    // ========================
    function addEmail(email) {

        email = email.toLowerCase().trim();

        if (!validateEmail(email)) return;
        if (emails.includes(email)) return;

        emails.push(email);
        updateHidden();

        // badge
        const badge = document.createElement("span");
        badge.dataset.email = email;

        badge.innerHTML = `
            ${email}
            <i class="fa-solid fa-xmark"></i>
        `;

        // suppression badge
        badge.querySelector("i").onclick = function () {
            emails = emails.filter(e => e !== email);
            badge.remove();
            updateHidden();

            // réafficher dans modal
            const leadItem = document.getElementById("lead-" + md5(email));
            if (leadItem) leadItem.style.display = "block";
        };

        emailContainer.appendChild(badge);

        // cacher dans modal (visuellement seulement)
        const leadItem = document.getElementById("lead-" + md5(email));
        if (leadItem) leadItem.style.display = "none";
    }

    function updateHidden() {
        hiddenInput.value = emails.join(",");
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ========================
    // AJOUT MANUEL (ESPACE / ENTER / VIRGULE)
    // ========================
    manualInput.addEventListener("keydown", function (e) {

        if (["Enter", " ", ",", ";"].includes(e.key)) {

            e.preventDefault();

            processManualInput();
        }
    });

    // Gestion du collage (paste)
    manualInput.addEventListener("paste", function (e) {

        setTimeout(() => {
            processManualInput();
        }, 100);
    });

    function processManualInput() {

        let value = manualInput.value;

        if (!value) return;

        // Séparer par espace, virgule ou point-virgule
        let parts = value.split(/[\s,;]+/);

        parts.forEach(email => {
            email = email.trim();
            if (email !== "") {
                addEmail(email);
            }
        });

        manualInput.value = "";
    }

    // ========================
// CLICK SUR LEAD (SUPPORT MULTI EMAIL)
// ========================
leadItems.forEach(item => {

    item.onclick = function () {

        let rawEmails = this.dataset.email;

        if (!rawEmails) return;

        // Séparer par virgule
        let parts = rawEmails.split(",");

        parts.forEach(email => {

            email = email.trim();

            if (email !== "") {
                addEmail(email);
            }
        });

        // Cacher visuellement le lead
        this.style.display = "none";
    };
});

    // ========================
    // RECHERCHE LIVE
    // ========================
    searchInput.addEventListener("keyup", function () {
        const value = this.value.toLowerCase();

        leadItems.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(value) ? "block" : "none";
        });
    });

    // ========================
    // FONCTION MD5 JS SIMPLE
    // ========================
    function md5(str) {
        return CryptoJS.MD5(str).toString();
    }

});
</script>

<!-- Ajoute CryptoJS pour md5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<!-- Styles supplémentaires pour le responsive -->
<style>
    /* Hide on very small screens */
    @media (max-width: 480px) {
        #email-container span {
            width: 100%;
            justify-content: space-between;
        }
        
        .alert-success,
        .alert-danger {
            flex-direction: column;
            text-align: center;
        }
    }

    /* Loading state for button */
    button[type="submit"].loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    button[type="submit"].loading i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Focus visible for accessibility */
    button:focus-visible,
    input:focus-visible,
    textarea:focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    /* Print styles */
    @media print {
        .card {
            box-shadow: none;
            border: 1px solid #000;
        }
        
        button,
        #openLeadModal,
        #closeLeadModal {
            display: none;
        }
    }
</style>

@endsection