@extends('client.layouts.app')

@section('title', 'Système d\'envoi de mails')

@section('content')

<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Google Fonts - Inter (font plus élégante) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #6366f1; --primary-dark: #4f52e0; --primary-light: #8183f5;
    --secondary: #10b981; --secondary-dark: #059669; --secondary-light: #34d399;
    --danger: #ef4444; --danger-dark: #dc2626; --danger-light: #f87171;
    --warning: #f59e0b; --warning-dark: #d97706; --warning-light: #fbbf24;
    --success: #10b981; --success-dark: #059669; --success-light: #34d399;
    --dark: #1e293b; --darker: #0f172a; --gray: #64748b; --gray-light: #94a3b8;
    --light: #f8fafc; --lighter: #f1f5f9; --white: #ffffff;
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    --shadow-inner: inset 0 2px 4px 0 rgb(0 0 0 / 0.05);
    --radius-xs: 6px; --radius-sm: 8px; --radius: 12px; --radius-lg: 16px; --radius-xl: 20px; --radius-2xl: 24px;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.mail-container {
    max-width: 1440px; margin: 0 auto; padding: 32px 24px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: #f9fafc; min-height: 100vh;
}
.mail-card {
    background: rgba(255,255,255,0.98); backdrop-filter: blur(10px);
    border-radius: var(--radius-xl); box-shadow: var(--shadow-xl);
    padding: 32px 36px; margin-bottom: 32px; transition: var(--transition);
    border: 1px solid rgba(255,255,255,0.3); position: relative;
    overflow: hidden;
}
.mail-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}
.mail-card:hover {
    box-shadow: var(--shadow-2xl); transform: translateY(-4px);
    border-color: rgba(99,102,241,0.2);
}
.section-title {
    font-weight: 700; font-size: 1.6rem; margin-bottom: 28px;
    background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; letter-spacing: -0.02em; display: flex;
    align-items: center; gap: 14px; border-bottom: 2px solid var(--lighter);
    padding-bottom: 20px;
}
.section-title i { 
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    font-size: 2rem;
}
.stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;
}
.stat-box {
    border-radius: var(--radius-lg); padding: 32px 24px; text-align: center;
    color: var(--white); font-weight: 500; transition: var(--transition);
    position: relative; overflow: hidden; backdrop-filter: blur(10px);
    box-shadow: var(--shadow-lg);
}
.stat-box::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
    opacity: 0; transition: var(--transition);
}
.stat-box:hover::before { opacity: 1; }
.stat-box:hover { transform: scale(1.02); box-shadow: var(--shadow-2xl); }
.stat-box i { font-size: 3rem; margin-bottom: 20px; opacity: 0.95; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); }
.stat-box h3 { font-size: 3rem; font-weight: 800; margin-bottom: 8px; line-height: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.stat-box p { font-size: 1rem; opacity: 0.9; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 600; }
.bg-blue { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
.bg-green { background: linear-gradient(135deg, #10b981, #3b82f6); }
.bg-red { background: linear-gradient(135deg, #ef4444, #f97316); }
.smtp-status {
    display: flex; align-items: center; gap: 20px; margin-bottom: 28px;
    padding: 20px 24px; background: var(--lighter); border-radius: var(--radius);
    border: 1px solid rgba(99,102,241,0.1); box-shadow: var(--shadow-inner);
}
.smtp-status i { font-size: 2.5rem; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); }
.smtp-status .status-text { font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; gap: 8px; }
.smtp-status .status-details { color: var(--gray); font-size: 0.95rem; margin-top: 4px; font-weight: 400; }
.status-success { color: var(--success); }
.status-error { color: var(--danger); }
.status-warning { color: var(--warning); }
.config-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;
}
.config-card {
    background: var(--lighter); border-radius: var(--radius); padding: 24px;
    border: 1px solid rgba(99,102,241,0.1); box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.config-card:hover { box-shadow: var(--shadow-md); border-color: rgba(99,102,241,0.3); }
.config-card h3 {
    font-size: 1.2rem; font-weight: 700; margin-bottom: 20px;
    background: linear-gradient(135deg, var(--dark), var(--primary));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    display: flex; align-items: center; gap: 10px;
}
.config-card h3 i { font-size: 1.3rem; background: none; -webkit-text-fill-color: var(--primary); }
.config-details {
    background: var(--white); border-radius: var(--radius-sm); padding: 16px;
    margin: 20px 0; border: 1px solid rgba(99,102,241,0.15); box-shadow: var(--shadow-xs);
}
.config-details p {
    margin-bottom: 10px; font-size: 0.95rem; display: flex; align-items: center; gap: 10px;
    color: var(--dark); padding: 4px 0;
}
.config-details p:last-child { margin-bottom: 0; }
.config-details i { width: 20px; color: var(--primary); font-size: 1rem; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.btn-test {
    background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white;
    border: none; box-shadow: var(--shadow-md);
}
.btn-test:hover {
    background: linear-gradient(135deg, #7c3aed, #4f52e0);
    transform: translateY(-2px); box-shadow: var(--shadow-lg);
}
.form-group { margin-bottom: 22px; }
.form-group label {
    font-weight: 600; color: var(--dark); margin-bottom: 8px; display: block;
    font-size: 0.95rem; letter-spacing: 0.3px;
}
.form-group label i { margin-right: 10px; color: var(--primary); width: 18px; font-size: 1rem; }
.form-control {
    width: 100%; padding: 14px 20px; font-size: 0.95rem; border: 2px solid transparent;
    border-radius: var(--radius); transition: var(--transition);
    background: var(--white); color: var(--dark); box-shadow: var(--shadow-sm);
}
.form-control:hover { border-color: var(--primary-light); box-shadow: var(--shadow-md); }
.form-control:focus {
    outline: none; border-color: var(--primary); background: var(--white);
    box-shadow: 0 0 0 4px rgba(99,102,241,0.15), var(--shadow-md);
}
textarea.form-control { min-height: 140px; resize: vertical; }
.btn-modern {
    display: inline-flex; align-items: center; justify-content: center; padding: 14px 32px;
    font-weight: 600; font-size: 1rem; border-radius: 50px; border: none; cursor: pointer;
    transition: var(--transition); letter-spacing: 0.3px; box-shadow: var(--shadow-md);
    gap: 12px; position: relative; overflow: hidden;
}
.btn-modern::before {
    content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0;
    border-radius: 50%; background: rgba(255,255,255,0.3);
    transform: translate(-50%, -50%); transition: width 0.6s, height 0.6s;
}
.btn-modern:hover::before { width: 300px; height: 300px; }
.btn-modern:active { transform: translateY(1px); box-shadow: var(--shadow-sm); }
.btn-modern i { font-size: 1.1rem; position: relative; z-index: 1; }
.btn-modern span { position: relative; z-index: 1; }
.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
}
.btn-primary-modern:hover { background: linear-gradient(135deg, var(--primary-dark), var(--primary)); }
.btn-success-modern {
    background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
    color: var(--white);
}
.btn-success-modern:hover { background: linear-gradient(135deg, var(--secondary-dark), var(--secondary)); }
.btn-link-modern {
    background: transparent; color: var(--primary); text-decoration: none; padding: 10px 20px;
    border-radius: 50px; display: inline-flex; align-items: center; gap: 10px;
    font-weight: 600; transition: var(--transition); border: 2px solid transparent;
}
.btn-link-modern:hover {
    background: rgba(99,102,241,0.1); border-color: var(--primary-light);
    transform: translateX(4px);
}
.alert {
    padding: 20px 24px; border-radius: var(--radius); margin-top: 24px; font-size: 0.95rem;
    display: flex; align-items: flex-start; gap: 16px; border: none;
    box-shadow: var(--shadow-md); backdrop-filter: blur(10px);
}
.alert i { font-size: 1.5rem; margin-top: 2px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
.alert-info { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }
.alert-warning { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #92400e; }
.alert-success { background: linear-gradient(135deg, #a7f3d0, #6ee7b7); color: #065f46; }
.alert strong { font-weight: 800; display: block; margin-bottom: 8px; font-size: 1.1rem; }
.tutorial-steps { list-style: none; padding: 0; margin-bottom: 24px; }
.tutorial-steps li {
    display: flex; align-items: center; gap: 18px; padding: 14px 0;
    border-bottom: 1px solid rgba(99,102,241,0.1);
}
.tutorial-steps li:last-child { border-bottom: none; }
.tutorial-steps li i {
    width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 700; box-shadow: var(--shadow-md); flex-shrink: 0;
}
.tutorial-steps li span { color: var(--dark); font-weight: 500; line-height: 1.6; font-size: 1rem; }
.tutorial-links {
    display: flex; gap: 16px; flex-wrap: wrap; margin-top: 24px; padding-top: 24px;
    border-top: 2px solid rgba(99,102,241,0.1);
}
.table-responsive {
    overflow-x: auto; border-radius: var(--radius); margin-top: 16px;
    box-shadow: var(--shadow-lg);
}
.table {
    width: 100%; border-collapse: collapse; background: var(--white);
    border-radius: var(--radius); overflow: hidden;
}
.table thead tr {
    background: linear-gradient(135deg, var(--lighter), var(--light));
    border-bottom: 2px solid rgba(99,102,241,0.2);
}
.table th {
    padding: 18px 20px; font-weight: 700; color: var(--dark); font-size: 0.9rem;
    text-transform: uppercase; letter-spacing: 0.5px; text-align: left;
}
.table th i { margin-right: 10px; color: var(--primary); font-size: 1rem; }
.table td {
    padding: 16px 20px; border-bottom: 1px solid var(--lighter); color: var(--gray);
    font-size: 0.95rem; font-weight: 500;
}
.table tbody tr:hover { background: linear-gradient(135deg, #fafbff, #f5f7ff); }
.table tbody tr:last-child td { border-bottom: none; }
.badge-success {
    background: linear-gradient(135deg, #10b98120, #05966920);
    color: #065f46; padding: 8px 16px; border-radius: 30px; font-size: 0.85rem;
    font-weight: 700; display: inline-flex; align-items: center; gap: 8px;
    border: 1px solid #10b98140; backdrop-filter: blur(4px);
}
.badge-success i { color: #10b981; font-size: 0.8rem; }
.badge-danger {
    background: linear-gradient(135deg, #ef444420, #dc262620);
    color: #991b1b; padding: 8px 16px; border-radius: 30px; font-size: 0.85rem;
    font-weight: 700; display: inline-flex; align-items: center; gap: 8px;
    border: 1px solid #ef444440; backdrop-filter: blur(4px);
}
.badge-danger i { color: #ef4444; font-size: 0.8rem; }
details {
    background: var(--lighter); border-radius: var(--radius); padding: 20px;
    margin-top: 24px; border: 1px solid rgba(99,102,241,0.1);
}
details summary {
    font-weight: 700; color: var(--dark); cursor: pointer; list-style: none;
    display: flex; align-items: center; gap: 12px; font-size: 1.1rem;
}
details summary i { color: var(--primary); transition: var(--transition); }
details[open] summary i { transform: rotate(180deg); }
.form-check { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.form-check input[type="checkbox"] {
    width: 20px; height: 20px; cursor: pointer; accent-color: var(--primary);
}
.mb-3 { margin-bottom: 1rem; }
.mt-4 { margin-top: 1.5rem; }
.text-muted { color: var(--gray); }
.d-flex { display: flex; }
.align-center { align-items: center; }
.gap-2 { gap: 8px; }
@media (max-width: 1200px) {
    .mail-container { padding: 28px 20px; }
    .mail-card { padding: 28px 30px; }
    .section-title { font-size: 1.5rem; }
}
@media (max-width: 1024px) {
    .mail-container { padding: 24px 16px; }
    .mail-card { padding: 24px 24px; }
    .section-title { font-size: 1.4rem; margin-bottom: 24px; }
    .config-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; gap: 20px; }
    .stat-box { padding: 28px 20px; }
    .stat-box h3 { font-size: 2.5rem; }
    .mail-card { padding: 22px 20px; margin-bottom: 24px; }
    .section-title { font-size: 1.3rem; padding-bottom: 16px; }
    .btn-modern { width: 100%; padding: 16px 24px; }
    .table th, .table td { padding: 14px 12px; font-size: 0.9rem; }
    .alert { padding: 18px 20px; }
    .smtp-status { flex-direction: column; text-align: center; gap: 12px; }
    .form-row { grid-template-columns: 1fr; }
    .tutorial-links { flex-direction: column; }
}
@media (max-width: 480px) {
    .mail-container { padding: 20px 12px; }
    .mail-card { padding: 20px 16px; border-radius: var(--radius-lg); }
    .section-title { font-size: 1.2rem; margin-bottom: 20px; }
    .stat-box h3 { font-size: 2.2rem; }
    .stat-box p { font-size: 0.9rem; }
    .form-control { padding: 12px 16px; font-size: 0.95rem; }
    .btn-modern { padding: 14px 20px; font-size: 0.95rem; }
    .table th, .table td { padding: 12px 8px; font-size: 0.85rem; }
    .badge-success, .badge-danger { padding: 6px 12px; font-size: 0.75rem; }
    .tutorial-steps li { gap: 12px; padding: 12px 0; }
    .tutorial-steps li i { width: 28px; height: 28px; font-size: 0.9rem; }
    .tutorial-steps li span { font-size: 0.95rem; }
}
</style>

<div class="mail-container">
    @if(session('success'))
        <div class="mail-card" style="background: linear-gradient(135deg, #a7f3d0, #6ee7b7); color: #065f46; border-left: 4px solid #10b981;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mail-card" style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border-left: 4px solid #ef4444;">{{ session('error') }}</div>
    @endif

    {{-- STATISTIQUES PREMIUM --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-chart-pie"></i> Tableau de bord</h2>
        <div class="stats-grid">
            <div class="stat-box bg-blue"><i class="fas fa-envelope"></i><h3>{{ $stats['total'] }}</h3><p>Messages envoyés</p></div>
            <div class="stat-box bg-green"><i class="fas fa-check-circle"></i><h3>{{ $stats['success'] }}</h3><p>Livraisons réussies</p></div>
            <div class="stat-box bg-red"><i class="fas fa-exclamation-circle"></i><h3>{{ $stats['failed'] }}</h3><p>Échecs d'envoi</p></div>
        </div>
    </div>

    {{-- CONFIGURATION SMTP PREMIUM --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-sliders-h"></i> Configuration avancée</h2>

        {{-- ÉTAT SMTP DYNAMIQUE --}}
        <div class="smtp-status">
            @if($smtp && $smtp->last_test_success)
                <i class="fas fa-check-circle status-success"></i>
                <div><div class="status-text status-success"><i class="fas fa-circle"></i> Connexion établie</div><div class="status-details">{{ $smtp->host }}:{{ $smtp->port }} · {{ strtoupper($smtp->encryption ?? 'Sans chiffrement') }}</div></div>
            @elseif($smtp && $smtp->last_test_success === false)
                <i class="fas fa-times-circle status-error"></i>
                <div><div class="status-text status-error"><i class="fas fa-circle"></i> Échec de connexion</div><div class="status-details">Dernier test · Paramètres invalides</div></div>
            @else
                <i class="fas fa-exclamation-circle status-warning"></i>
                <div><div class="status-text status-warning"><i class="fas fa-circle"></i> En attente</div><div class="status-details">Configuration requise · Testez votre SMTP</div></div>
            @endif
        </div>

        <div class="config-grid">
            {{-- FORMULAIRE --}}
            <div class="config-card">
                <h3><i class="fas fa-cog"></i> Paramètres serveur</h3>
                <form method="POST" action="{{ route('client.mails.smtp.save') }}">
                    @csrf
                    <div class="form-group"><label><i class="fas fa-server"></i> Hôte SMTP</label><input type="text" name="host" class="form-control" value="{{ $smtp->host ?? 'smtp.gmail.com' }}" placeholder="smtp.gmail.com" required></div>
                    <div class="form-row">
                        <div class="form-group"><label><i class="fas fa-plug"></i> Port</label><input type="number" name="port" class="form-control" value="{{ $smtp->port ?? 587 }}" placeholder="587" required></div>
                        <div class="form-group"><label><i class="fas fa-lock"></i> Chiffrement</label><select name="encryption" class="form-control"><option value="tls" {{ ($smtp->encryption ?? '')=='tls'?'selected':'' }}>TLS (recommandé)</option><option value="ssl" {{ ($smtp->encryption ?? '')=='ssl'?'selected':'' }}>SSL</option><option value="">Aucun</option></select></div>
                    </div>
                    <div class="form-group"><label><i class="fas fa-envelope"></i> Identifiant</label><input type="email" name="username" class="form-control" value="{{ $smtp->username ?? '' }}" placeholder="votre@email.com" required></div>
                    <div class="form-group"><label><i class="fas fa-key"></i> Mot de passe</label><input type="password" name="password" class="form-control" placeholder="••••••••"></div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;"><button type="submit" class="btn-modern btn-primary-modern"><i class="fas fa-save"></i><span>Enregistrer</span></button></div>
                </form>
                @if($smtp)
                    <form method="POST" action="{{ route('client.mails.smtp.test') }}" style="margin-top:16px;">@csrf<button type="submit" class="btn-modern btn-test"><i class="fas fa-vial"></i><span>Tester la connexion</span></button></form>
                @endif
            </div>

            {{-- CONFIGURATION ACTUELLE --}}
            <div class="config-card">
                <h3><i class="fas fa-info-circle"></i> Détails actuels</h3>
                @if($smtp)
                    <div class="config-details">
                        <p><i class="fas fa-server"></i><strong>Serveur :</strong> {{ $smtp->host }}:{{ $smtp->port }}</p>
                        <p><i class="fas fa-lock"></i><strong>Chiffrement :</strong> {{ strtoupper($smtp->encryption ?? 'Non chiffré') }}</p>
                        <p><i class="fas fa-envelope"></i><strong>Email :</strong> {{ $smtp->username }}</p>
                        @if($smtp->last_tested_at)<p><i class="fas fa-history"></i><strong>Dernier test :</strong> {{ $smtp->last_tested_at->format('d/m/Y H:i') }}</p>@endif
                    </div>
                @else
                    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i><div>Aucune configuration enregistrée</div></div>
                @endif
                <div class="alert alert-info" style="margin-top:20px;"><i class="fas fa-lightbulb"></i><div><strong>Configuration Gmail</strong><br>smtp.gmail.com · 587 · TLS</div></div>
            </div>
        </div>
    </div>

    {{-- TUTORIEL GMAIL --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Guide Gmail</h2>
        <ul class="tutorial-steps">
            <li><i class="fas fa-1"></i><span>Accédez à votre <strong>compte Google</strong></span></li>
            <li><i class="fas fa-2"></i><span>Activez la <strong>validation en deux étapes</strong></span></li>
            <li><i class="fas fa-3"></i><span>Générez un <strong>mot de passe d'application</strong></span></li>
            <li><i class="fas fa-4"></i><span>Utilisez ce mot de passe à 16 caractères</span></li>
        </ul>
        <div class="tutorial-links">
            <a href="https://myaccount.google.com/security" target="_blank" class="btn-link-modern"><i class="fas fa-external-link-alt"></i>Sécurité Google</a>
            <a href="https://myaccount.google.com/apppasswords" target="_blank" class="btn-link-modern"><i class="fas fa-external-link-alt"></i>App Passwords</a>
            <a href="https://mail.google.com/" target="_blank" class="btn-link-modern"><i class="fas fa-external-link-alt"></i>Gmail</a>
        </div>
        <div class="alert alert-warning mt-4"><i class="fas fa-exclamation-triangle"></i><div><strong>Important</strong>N'utilisez jamais votre mot de passe Gmail personnel</div></div>
    </div>

    {{-- ENVOI DE MESSAGE --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-paper-plane"></i> Nouveau message</h2>
        @if(!$smtp)
            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i><div>Configuration SMTP requise avant l'envoi</div></div>
        @else
            <form method="POST" action="{{ route('client.mails.send') }}">@csrf
                <div class="form-group"><label><i class="fas fa-user"></i> Destinataire</label><input type="email" name="to" class="form-control" value="{{ old('to') }}" placeholder="contact@exemple.com" required>@error('to')<small style="color:var(--danger);">{{ $message }}</small>@enderror</div>
                <div class="form-group"><label><i class="fas fa-heading"></i> Objet</label><input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="Nouveau message" required>@error('subject')<small style="color:var(--danger);">{{ $message }}</small>@enderror</div>
                <div class="form-group"><label><i class="fas fa-comment"></i> Contenu (HTML)</label><textarea name="message" class="form-control" rows="6" placeholder="Votre message..." required>{{ old('message') }}</textarea>@error('message')<small style="color:var(--danger);">{{ $message }}</small>@enderror</div>
                <button type="submit" class="btn-modern btn-success-modern"><i class="fas fa-paper-plane"></i><span>Envoyer maintenant</span></button>
            </form>
        @endif
    </div>

    {{-- HISTORIQUE DES ENVOIS --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-history"></i> Historique</h2>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th><i class="fas fa-user"></i> Destinataire</th><th><i class="fas fa-tag"></i> Sujet</th><th><i class="fas fa-calendar"></i> Date</th><th><i class="fas fa-flag"></i> Statut</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><i class="fas fa-envelope-open-text" style="color:var(--primary); margin-right:8px;"></i>{{ $log->to }}</td>
                            <td style="font-weight:600;">{{ $log->subject }}</td>
                            <td><i class="fas fa-clock" style="color:var(--gray); margin-right:6px;"></i>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge-success"><i class="fas fa-check-circle"></i> Livré</span>
                                @else
                                    <span class="badge-danger"><i class="fas fa-times-circle"></i> Échec</span>
                                    @if($log->error_message)<div style="font-size:12px;color:var(--danger);margin-top:6px;">{{ Str::limit($log->error_message, 70) }}</div>@endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--gray);"><i class="fas fa-inbox" style="font-size:2rem; margin-bottom:12px; display:block;"></i>Aucun message envoyé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($logs, 'links'))<div class="mt-4 d-flex justify-center">{{ $logs->links() }}</div>@endif
    </div>

    {{-- SÉCURITÉ --}}
    <div class="mail-card" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <i class="fas fa-shield-alt" style="font-size: 3.5rem; color: var(--primary); filter: drop-shadow(0 4px 6px rgba(99,102,241,0.3));"></i>
            <div style="flex: 1;"><h3 style="margin-bottom: 8px; color: white; font-weight:700;">Protection renforcée</h3><p style="color: var(--gray-light); font-size:1rem;">Activez la validation en deux étapes et générez un mot de passe d'application pour une sécurité maximale.</p></div>
            <a href="https://myaccount.google.com/security" target="_blank" class="btn-modern btn-primary-modern"><i class="fas fa-external-link-alt"></i><span>Configurer</span></a>
        </div>
    </div>
</div>
@endsection