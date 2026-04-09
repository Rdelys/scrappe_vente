@extends('client.layouts.app')

@section('title', 'Mails programmés')

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
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #0f172a;
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

    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--white);
        min-height: 100vh;
    }

    /* ===== CONTAINER PRINCIPAL ===== */
    .mail-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        position: relative;
    }

    /* ===== CARTES PREMIUM ===== */
    .card-premium {
        background: var(--white);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--gray-200);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 1;
    }

    .card-premium:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-2xl);
        border-color: var(--gray-300);
    }

    /* ===== TITRES ===== */
    .section-title {
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

    .section-title i {
        color: var(--primary);
        font-size: 2.2rem;
        background: var(--gray-100);
        padding: 0.75rem;
        border-radius: var(--radius-lg);
    }

    .section-subtitle {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-subtitle i {
        color: var(--primary);
        font-size: 1.8rem;
    }

    /* ===== FORMULAIRES PREMIUM ===== */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }

    .form-label i {
        color: var(--primary);
        margin-right: 0.5rem;
        width: 1rem;
    }

    .form-control-premium {
        width: 100%;
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        background: var(--white);
        transition: all 0.2s;
        box-shadow: var(--shadow-xs);
    }

    .form-control-premium:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-control-premium:hover {
        border-color: var(--gray-400);
    }

    /* ===== BOUTONS SPECTACULAIRES ===== */
    .btn-premium {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        padding: 1rem 2rem;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: var(--radius-lg);
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        z-index: 1;
    }

    .btn-premium::before {
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
        z-index: -1;
    }

    .btn-premium:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary-premium {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }

    .btn-danger-premium {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-danger-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    .btn-delete-sent {
        background: linear-gradient(135deg, var(--gray-500), var(--gray-600));
        color: white;
        box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-delete-sent:hover {
        background: linear-gradient(135deg, var(--gray-600), var(--gray-700));
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(100, 116, 139, 0.4);
    }

    /* ===== TABLE PREMIUM ===== */
    .table-responsive-premium {
        overflow-x: auto;
        border-radius: var(--radius-xl);
        margin: 1.5rem 0;
        border: 1px solid var(--gray-200);
        background: var(--white);
    }

    .table-premium {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .table-premium thead tr {
        background: var(--gray-50);
        border-bottom: 2px solid var(--gray-300);
    }

    .table-premium th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: var(--gray-700);
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .table-premium td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        color: var(--gray-600);
        font-size: 0.95rem;
    }

    .table-premium tbody tr {
        transition: all 0.2s;
    }

    .table-premium tbody tr:hover {
        background: var(--gray-50);
    }

    .table-premium tbody tr:last-child td {
        border-bottom: none;
    }

    /* ===== BADGES DE STATUT ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .status-badge i {
        font-size: 0.75rem;
    }

    .status-pending {
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fed7aa;
    }

    .status-sent {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* ===== GRID RESPONSIVE ===== */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-full {
        grid-column: span 2;
    }

    /* ===== SEPARATEUR ===== */
    .separator-premium {
        margin: 3rem 0;
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gray-300), transparent);
    }

    /* ===== ICON CIRCLE ===== */
    .icon-circle {
        width: 40px;
        height: 40px;
        background: var(--gray-100);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        margin-right: 0.75rem;
        border: 1px solid var(--gray-200);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: var(--gray-50);
        border-radius: var(--radius-xl);
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--gray-400);
    }

    .empty-state p {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .empty-state small {
        color: var(--gray-400);
    }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1200px) {
        .mail-container {
            padding: 1.5rem;
        }
    }

    @media (max-width: 1024px) {
        .section-title {
            font-size: 1.75rem;
        }
        
        .section-title i {
            font-size: 2rem;
        }
    }

    @media (max-width: 768px) {
        .mail-container {
            padding: 1rem;
        }
        
        .card-premium {
            padding: 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .section-subtitle {
            font-size: 1.25rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-full {
            grid-column: span 1;
        }
        
        .btn-premium {
            width: 100%;
        }
        
        .table-premium {
            min-width: 600px;
        }
    }

    @media (max-width: 640px) {
        .card-premium {
            padding: 1.25rem;
            border-radius: var(--radius-xl);
        }
        
        .section-title {
            font-size: 1.25rem;
            padding-bottom: 0.75rem;
        }
        
        .section-title i {
            font-size: 1.5rem;
            padding: 0.5rem;
        }
        
        .form-control-premium {
            padding: 0.875rem 1rem;
        }
        
        .status-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.7rem;
        }
        
        .icon-circle {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
        
        .table-premium th,
        .table-premium td {
            padding: 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 480px) {
        .mail-container {
            padding: 0.75rem;
        }
        
        .card-premium {
            padding: 1rem;
        }
        
        .btn-premium {
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
        }
        
        .empty-state {
            padding: 2rem;
        }
        
        .empty-state i {
            font-size: 2.5rem;
        }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-premium {
        animation: slideIn 0.5s ease;
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

    /* ===== UTILITAIRES ===== */
    .text-muted-premium {
        color: var(--gray-500);
        font-size: 0.875rem;
        display: block;
        margin-top: 0.25rem;
    }

    .d-flex {
        display: flex;
        align-items: center;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    /* ===== ACCESSIBILITÉ ===== */
    .btn-premium:focus-visible,
    .form-control-premium:focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    .btn-disabled {
    background: linear-gradient(135deg, #9ca3af, #6b7280) !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    opacity: 0.7;
}

.btn-disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}
</style>

<div class="mail-container">

    <!-- Carte de programmation -->
    <div class="card-premium">
        <h2 class="section-title">
            <i class="fas fa-clock"></i>
            Programmer un email
        </h2>
        <p class="text-muted-premium" style="margin-bottom:1.5rem;">
            <i class="fas fa-globe"></i>
            Heure actuel : 
            <strong>{{ $serverTimezone }}</strong>
            (actuellement {{ $serverNow->format('d/m/Y H:i') }})
        </p>
        @if(!$smtp)
    <div class="status-badge" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;margin-bottom:1.5rem;">
        <i class="fas fa-times-circle"></i>
        SMTP non configuré
    </div>

@elseif($smtp->last_test_success === true)
    <div class="status-badge status-sent" style="margin-bottom:1.5rem;">
        <i class="fas fa-check-circle"></i>
        SMTP configuré et fonctionnel
        @if($smtp->last_tested_at)
            (testé le {{ $smtp->last_tested_at->format('d/m/Y H:i') }})
        @endif
    </div>

@elseif($smtp->last_test_success === false)
    <div class="status-badge" style="background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;margin-bottom:1.5rem;">
        <i class="fas fa-exclamation-triangle"></i>
        SMTP configuré mais test échoué
    </div>

@else
    <div class="status-badge status-pending" style="margin-bottom:1.5rem;">
        <i class="fas fa-clock"></i>
        SMTP configuré mais non testé
    </div>
@endif
        <form method="POST" action="{{ route('client.mails.programmes.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        Destinataire
                    </label>
                    <input type="email" 
                           name="to" 
                           class="form-control-premium" 
                           placeholder="exemple@email.com" 
                           required>
                    <small class="text-muted-premium">Adresse email du destinataire</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-heading"></i>
                        Sujet
                    </label>
                    <input type="text" 
                           name="subject" 
                           class="form-control-premium" 
                           placeholder="Objet de l'email" 
                           required>
                </div>

                <div class="form-group form-full">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Message
                    </label>
                    <textarea name="body" 
                              class="form-control-premium" 
                              rows="5" 
                              placeholder="Contenu de votre message..." 
                              required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date d'envoi programmée
                    </label>
                    <input type="datetime-local" 
                           name="scheduled_at" 
                           class="form-control-premium" 
                           required>
                </div>

                <div class="form-group" style="display: flex; align-items: flex-end;">
                <button type="submit"
                class="btn-premium btn-primary-premium {{ (!$smtp || $smtp->last_test_success !== true) ? 'btn-disabled' : '' }}"
                {{ (!$smtp || $smtp->last_test_success !== true) ? 'disabled' : '' }}>                        <i class="fas fa-calendar-check"></i>
                        Programmer l'envoi
                    </button>
                </div>
                @if(!$smtp || $smtp->last_test_success !== true)
    <small class="text-muted-premium" style="color: var(--danger); margin-top: 0.5rem;">
        <i class="fas fa-exclamation-circle"></i>
        Vous devez configurer et tester votre SMTP avant de programmer un email.
        <a href="{{ url('/mails/envoyes') }}" style="color: blue; text-decoration: underline; margin-left: 5px;">
            Programmer SMTP
        </a>
    </small>
@endif
            </div>
        </form>
    </div>

    <!-- Séparateur décoratif -->
    <hr class="separator-premium">

    <!-- Carte des emails programmés -->
    <div class="card-premium">
        <div class="d-flex justify-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2 class="section-subtitle" style="margin-bottom: 0;">
                <i class="fas fa-list"></i>
                Mes emails programmés
            </h2>
            <span class="status-badge status-pending">
                <i class="fas fa-clock"></i>
                {{ $mails->where('status', 'pending')->count() }} en attente
            </span>
        </div>

        <div class="table-responsive-premium">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th><i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>Destinataire</th>
                        <th><i class="fas fa-heading" style="margin-right: 0.5rem;"></i>Sujet</th>
                        <th><i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>Date prévue</th>
                        <th><i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>Statut</th>
                        <th><i class="fas fa-cog" style="margin-right: 0.5rem;"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mails as $mail)
                        <tr>
                            <td>
                                <div class="d-flex">
                                    <span class="icon-circle">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span>{{ $mail->to }}</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $mail->subject }}</strong>
                            </td>
                            <td>
                                <i class="far fa-clock" style="color: var(--gray-400); margin-right: 0.5rem;"></i>
                                {{ $mail->scheduled_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($mail->status == 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-hourglass-half"></i>
                                        En attente
                                    </span>
                                @elseif($mail->status == 'sent')
                                    <span class="status-badge status-sent">
                                        <i class="fas fa-check-circle"></i>
                                        Envoyé
                                    </span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" 
                                      action="{{ route('client.mails.programmes.delete', $mail->id) }}" 
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    @if($mail->status == 'pending')
                                        <button type="submit" 
                                                class="btn-premium btn-danger-premium"
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cet email programmé ?')">
                                            <i class="fas fa-trash"></i>
                                            <span class="hide-mobile">Annuler</span>
                                        </button>
                                    @else
                                        <button type="submit" 
                                                class="btn-premium btn-delete-sent"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet email ?')">
                                            <i class="fas fa-trash"></i>
                                            <span class="hide-mobile">Supprimer</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Aucun email programmé</p>
                                <small>Utilisez le formulaire ci-dessus pour programmer votre premier email</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mails->count() > 0)
            <div class="mt-4" style="display: flex; justify-content: flex-end;">
                <small class="text-muted-premium">
                    <i class="fas fa-info-circle"></i>
                    Les emails programmés seront envoyés automatiquement à la date prévue
                </small>
            </div>
        @endif
    </div>

</div>

<!-- Script pour le responsive -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du texte sur mobile pour les boutons
        function handleResize() {
            const hideMobile = document.querySelectorAll('.hide-mobile');
            if (window.innerWidth <= 640) {
                hideMobile.forEach(el => el.style.display = 'none');
            } else {
                hideMobile.forEach(el => el.style.display = 'inline');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();

        // Animation des cartes au scroll
        const cards = document.querySelectorAll('.card-premium');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            observer.observe(card);
        });
    });
</script>

<!-- Styles supplémentaires pour le responsive -->
<style>
    /* Hide on mobile */
    @media (max-width: 640px) {
        .hide-mobile {
            display: none !important;
        }
        
        .btn-premium {
            padding: 0.5rem 1rem;
        }
        
        .btn-premium i {
            margin-right: 0;
        }
        
        .table-premium th,
        .table-premium td {
            white-space: nowrap;
        }
        
        .status-badge {
            white-space: nowrap;
        }
        
        .icon-circle {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
    }

    /* Tooltip personnalisé */
    [data-tooltip] {
        position: relative;
        cursor: help;
    }

    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-8px);
        padding: 0.5rem 1rem;
        background: var(--gray-900);
        color: white;
        font-size: 0.75rem;
        border-radius: var(--radius-md);
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        z-index: 10;
        pointer-events: none;
        box-shadow: var(--shadow-lg);
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-4px);
    }

    /* Améliorations accessibilité */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* Focus visible */
    .btn-premium:focus-visible,
    .form-control-premium:focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    /* Print styles */
    @media print {
        .btn-premium {
            display: none;
        }
        
        .card-premium {
            box-shadow: none;
            border: 1px solid #000;
        }
    }
</style>

@endsection