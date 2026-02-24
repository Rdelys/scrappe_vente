<!-- resources/views/client/dashboard.blade.php -->
@extends('client.layouts.app')

@section('title', 'Bienvenue - FOLLUP.IO')

@section('content')

<style>
    /* Reset et styles de base */
    .welcome-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        margin: 0;
        box-sizing: border-box;
        perspective: 1000px;
    }

    /* Animation du fond avec particules */
    .welcome-container::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.03) 0%, transparent 50%);
        pointer-events: none;
        animation: backgroundPulse 8s ease-in-out infinite;
    }

    @keyframes backgroundPulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    /* Carte principale avec animation 3D */
    .welcome-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 40px;
        padding: 70px 60px;
        max-width: 900px;
        width: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        animation: cardEntrance 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        border: 1px solid rgba(255,255,255,0.2);
        z-index: 1;
        transform-style: preserve-3d;
    }

    /* Effet de brillance sur la carte */
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 30%,
            rgba(255, 255, 255, 0.1) 50%,
            transparent 70%
        );
        animation: shine 8s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes cardEntrance {
        0% {
            opacity: 0;
            transform: rotateY(-10deg) translateZ(-100px) scale(0.9);
        }
        100% {
            opacity: 1;
            transform: rotateY(0) translateZ(0) scale(1);
        }
    }

    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        20%, 100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    /* Logo avec animation flottante */
    .logo-animated {
        text-align: center;
        margin-bottom: 30px;
        animation: logoFloat 3s ease-in-out infinite;
    }

    @keyframes logoFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-10px) scale(1.05); }
    }

    .logo-animated i {
        font-size: 90px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 10px 20px rgba(102, 126, 234, 0.3));
        transition: all 0.3s ease;
        animation: logoPulse 2s ease-in-out infinite;
    }

    @keyframes logoPulse {
        0%, 100% { filter: drop-shadow(0 10px 20px rgba(102, 126, 234, 0.3)); }
        50% { filter: drop-shadow(0 20px 30px rgba(102, 126, 234, 0.5)); }
    }

    .logo-animated:hover i {
        transform: scale(1.1) rotate(5deg);
    }

    /* Titre avec animation de texte */
    .welcome-title {
        color: #2d3748;
        font-size: 3.2rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 25px;
        line-height: 1.2;
        animation: titleReveal 1s ease-out 0.2s both;
    }

    @keyframes titleReveal {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .welcome-title span {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
        position: relative;
    }

    .welcome-title span::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        transform: scaleX(0);
        animation: underlineReveal 0.6s ease-out 0.8s forwards;
        transform-origin: left;
    }

    @keyframes underlineReveal {
        to { transform: scaleX(1); }
    }

    /* Message avec animation de vague */
    .welcome-message {
        color: #4a5568;
        font-size: 1.5rem;
        line-height: 1.8;
        text-align: center;
        margin-bottom: 40px;
        background: rgba(247, 250, 252, 0.8);
        backdrop-filter: blur(5px);
        padding: 30px 40px;
        border-radius: 30px;
        border: 1px solid rgba(226, 232, 240, 0.5);
        animation: messageSlide 1s ease-out 0.4s both;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .welcome-message::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: wave 3s ease-in-out infinite;
    }

    @keyframes wave {
        0% { left: -100%; }
        20%, 100% { left: 100%; }
    }

    @keyframes messageSlide {
        0% {
            opacity: 0;
            transform: translateX(-50px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .welcome-message strong {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        position: relative;
        display: inline-block;
        animation: strongPop 0.5s ease-out 1s both;
    }

    @keyframes strongPop {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Badge entreprise avec effet de carte */
    .company-info {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: 60px;
        padding: 20px 40px;
        margin-bottom: 40px;
        display: inline-block;
        animation: companyReveal 1s ease-out 0.6s both;
        border: 1px solid rgba(102, 126, 234, 0.3);
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .company-info:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
    }

    @keyframes companyReveal {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .company-badge {
        display: flex;
        align-items: center;
        gap: 20px;
        color: #2d3748;
    }

    .company-badge i {
        font-size: 35px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        animation: iconSpin 10s linear infinite;
    }

    @keyframes iconSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .company-badge:hover i {
        animation: iconBounce 0.5s ease;
    }

    @keyframes iconBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .company-badge .company-details {
        text-align: left;
    }

    .company-badge .company-details .label {
        font-size: 0.9rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 5px;
        animation: labelGlow 2s ease-in-out infinite;
    }

    @keyframes labelGlow {
        0%, 100% { opacity: 0.8; }
        50% { opacity: 1; text-shadow: 0 0 5px rgba(102, 126, 234, 0.3); }
    }

    .company-badge .company-details .name {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
        color: #2d3748;
        animation: nameShimmer 3s ease-in-out infinite;
    }

    @keyframes nameShimmer {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    /* Boutons avec animations avancées */
    .action-buttons {
        display: flex;
        gap: 25px;
        justify-content: center;
        flex-wrap: wrap;
        animation: buttonsReveal 1s ease-out 0.8s both;
    }

    @keyframes buttonsReveal {
        0% {
            opacity: 0;
            transform: translateY(50px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-welcome {
        padding: 18px 45px;
        border-radius: 60px;
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }

    .btn-welcome::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-welcome:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-welcome i {
        transition: transform 0.3s ease;
    }

    .btn-welcome:hover i {
        transform: translateX(5px) rotate(10deg);
    }

    .btn-primary-welcome {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    .btn-primary-welcome:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 30px 50px rgba(102, 126, 234, 0.5);
    }

    .btn-outline-welcome {
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
        position: relative;
        z-index: 1;
    }

    .btn-outline-welcome::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        transition: width 0.4s ease;
        z-index: -1;
    }

    .btn-outline-welcome:hover {
        color: white;
        border-color: transparent;
        transform: translateY(-5px);
    }

    .btn-outline-welcome:hover::after {
        width: 100%;
    }

    /* Footer avec animation de coeur */
    .footer-message {
        text-align: center;
        color: #a0aec0;
        margin-top: 40px;
        font-size: 1rem;
        animation: footerReveal 1s ease-out 1s both;
    }

    @keyframes footerReveal {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .footer-message i {
        color: #667eea;
        animation: heartBeat 1.5s ease-in-out infinite;
        display: inline-block;
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.2); color: #e53e3e; }
        35% { transform: scale(1); }
        45% { transform: scale(1.1); color: #e53e3e; }
        55% { transform: scale(1); }
    }

    /* Particules animées */
    .particle {
        position: absolute;
        width: 5px;
        height: 5px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        pointer-events: none;
        opacity: 0;
        animation: particleFloat 4s ease-in-out infinite;
    }

    @keyframes particleFloat {
        0% {
            opacity: 0;
            transform: translateY(0) translateX(0) rotate(0deg);
        }
        10% {
            opacity: 0.5;
        }
        90% {
            opacity: 0.5;
        }
        100% {
            opacity: 0;
            transform: translateY(-100px) translateX(100px) rotate(720deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .welcome-card {
            padding: 50px 40px;
        }
        
        .welcome-title {
            font-size: 2.8rem;
        }
        
        .welcome-message {
            font-size: 1.3rem;
            padding: 25px 30px;
        }
        
        .company-badge .company-details .name {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 40px 25px;
        }
        
        .welcome-title {
            font-size: 2.2rem;
        }
        
        .welcome-message {
            font-size: 1.1rem;
            padding: 20px 20px;
        }
        
        .company-badge {
            flex-direction: column;
            text-align: center;
        }
        
        .company-badge .company-details {
            text-align: center;
        }
        
        .company-badge .company-details .name {
            font-size: 1.5rem;
        }
        
        .btn-welcome {
            padding: 15px 35px;
            font-size: 1rem;
            width: 100%;
            justify-content: center;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .welcome-card {
            padding: 30px 20px;
        }
        
        .welcome-title {
            font-size: 1.8rem;
        }
        
        .logo-animated i {
            font-size: 70px;
        }
        
        .welcome-message {
            font-size: 1rem;
            padding: 15px;
        }
        
        .company-badge .company-details .name {
            font-size: 1.3rem;
        }
    }

    /* Fallback si les animations ne fonctionnent pas */
    .no-animations .welcome-card,
    .no-animations .welcome-card > * {
        opacity: 1 !important;
        animation: none !important;
    }
</style>

<div class="welcome-container" id="particleContainer">
    <div class="welcome-card">
        
        <!-- Logo animé -->
        <div class="logo-animated">
            <i class="fas fa-rocket"></i>
        </div>
        
        <!-- Titre avec animation -->
        <h1 class="welcome-title">
            Bienvenue sur <span>FOLLUP.IO</span>
        </h1>
        
        <!-- Message personnalisé avec animation -->
        <div class="welcome-message">
            Bonjour <strong>{{ session('client.first_name', 'Client') }}</strong>,<br>
            vous êtes connecté à l'espace professionnel de
            <strong>{{ session('client.company', 'votre entreprise') }}</strong>
        </div>
        
        <!-- Badge entreprise animé -->
        <div style="text-align: center;">
            <div class="company-info">
                <div class="company-badge">
                    <i class="fas fa-building"></i>
                    <div class="company-details">
                        <div class="label">Espace professionnel</div>
                        <div class="name">{{ session('client.company', 'Entreprise') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action animés -->
        <div class="action-buttons">
            <a href="{{ url('/crm/leads') }}" class="btn-welcome btn-primary-welcome">
                <i class="fas fa-tachometer-alt"></i>
                Leads
            </a>
            <a href="{{ url('/google') }}" class="btn-welcome btn-outline-welcome">
                <i class="fas fa-map-marked-alt"></i>
                Scraper Google Maps
            </a>
        </div>
        
        <!-- Message footer avec animation -->
        <div class="footer-message">
            <i class="fas fa-magic"></i> Prêt à générer des leads de qualité ? 
            <i class="fas fa-smile-wink"></i>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fallback pour les animations
    if (!('animation' in document.body.style)) {
        document.body.classList.add('no-animations');
    }
    
    // Vérifier que Font Awesome est chargé
    if (typeof window.FontAwesome === 'undefined') {
        console.log('Chargement de Font Awesome...');
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css';
        document.head.appendChild(link);
    }
    
    // Création des particules animées
    function createParticles() {
        const container = document.getElementById('particleContainer');
        
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Position aléatoire
            const left = Math.random() * 100;
            const top = Math.random() * 100;
            
            // Taille aléatoire
            const size = Math.random() * 6 + 2;
            
            // Délai d'animation aléatoire
            const delay = Math.random() * 5;
            
            particle.style.cssText = `
                left: ${left}%;
                top: ${top}%;
                width: ${size}px;
                height: ${size}px;
                animation-delay: ${delay}s;
                background: ${Math.random() > 0.5 ? '#667eea' : '#764ba2'};
            `;
            
            container.appendChild(particle);
        }
    }
    
    createParticles();
    
    // Animation de survol 3D pour la carte
    const card = document.querySelector('.welcome-card');
    
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;
        
        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
    });
    
    // Animation de texte dynamique pour le titre
    const title = document.querySelector('.welcome-title span');
    const originalText = title.textContent;
    
    setInterval(() => {
        title.style.transform = 'scale(1.1)';
        setTimeout(() => {
            title.style.transform = 'scale(1)';
        }, 200);
    }, 5000);
});
</script>
@endsection