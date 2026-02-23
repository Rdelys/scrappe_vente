<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follup.io | Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Grille de fond animée */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: min(50px, 5vw) min(50px, 5vw);
            animation: gridMove 30s linear infinite;
            mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 70%);
            -webkit-mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 70%);
        }

        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: min(50px, 5vw) min(50px, 5vw); }
        }

        /* Particules flottantes */
        .particle {
            position: fixed;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            animation: floatParticle 15s infinite linear;
            z-index: 1;
        }

        @keyframes floatParticle {
            0% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(100px, -50px) rotate(90deg); }
            50% { transform: translate(50px, 100px) rotate(180deg); }
            75% { transform: translate(-100px, 50px) rotate(270deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        /* Carte de connexion */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            width: 100%;
            max-width: 440px;
            padding: clamp(32px, 5vw, 52px);
            border-radius: 24px;
            box-shadow: 
                0 32px 64px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 0 100px rgba(99, 102, 241, 0.2) inset;
            animation: cardAppear 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            z-index: 10;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 22px;
                border-radius: 20px;
                max-width: 92vw;
            }
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Branding */
        .brand {
            text-align: center;
            font-size: clamp(26px, 4vw, 32px);
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #0f172a 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand span {
            font-weight: 900;
        }

        .subtitle {
            text-align: center;
            font-size: clamp(11px, 1.5vw, 12px);
            color: #64748b;
            margin-bottom: 32px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            position: relative;
            padding-bottom: 12px;
        }

        .subtitle::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #4f46e5, transparent);
        }

        h2 {
            text-align: center;
            margin-bottom: 32px;
            color: #1e293b;
            font-size: clamp(20px, 3vw, 24px);
            font-weight: 700;
        }

        /* Messages d'erreur */
        .error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #7f1d1d;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 28px;
            text-align: center;
            animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both;
            border: 1px solid #fca5a5;
            font-size: 14px;
            line-height: 1.4;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }

        /* Formulaire */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input {
            width: 100%;
            padding: 18px 22px;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            font-size: 15px;
            color: #1e293b;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            font-weight: 500;
        }

        input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        input:focus {
            border-color: #4f46e5;
            background: #ffffff;
            box-shadow: 
                0 0 0 4px rgba(79, 70, 229, 0.2),
                0 6px 20px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        input:valid:not(:placeholder-shown) {
            border-color: #10b981;
            background: linear-gradient(to right, #f8fafc, #f0fdf4);
        }

        /* Bouton */
        button {
            width: 100%;
            padding: 19px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.6s;
        }

        button:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 16px 32px rgba(79, 70, 229, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
            transition-duration: 0.1s;
        }

        /* Protection sécurité */
        .security-notice {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-notice::before {
            content: '🔒';
            font-size: 10px;
        }

         /* Password toggle */
.password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper input {
    padding-right: 50px;
}

.toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    user-select: none;
    opacity: 0.6;
    transition: 0.2s;
}

.toggle-password:hover {
    opacity: 1;
}
    </style>
</head>

<body>
    <div class="bg-grid"></div>
    
    <!-- Particules dynamiques -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.body;
            for (let i = 0; i < 8; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 60 + 20;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                particle.style.animationDelay = `${Math.random() * 5}s`;
                particle.style.opacity = Math.random() * 0.1 + 0.05;
                container.appendChild(particle);
            }
        });
    </script>

    <div class="login-card">
        <div class="brand">Delegg <span>Hub</span></div>
        <div class="subtitle">Administration sécurisée</div>

        <h2>Connexion Admin</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <input type="email" name="email" placeholder="Email admin" required>
<div class="password-wrapper">
    <input type="password" name="password" id="password" placeholder="Mot de passe" required>
    <span class="toggle-password" onclick="togglePassword()">🙈</span>
</div>
            <button type="submit">Accéder au tableau de bord</button>
        </form>

        <div class="security-notice">Connexion chiffrée • Accès restreint</div>
    </div>
</body>
<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "👁";
    } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "🙈";
    }
}
</script>
</html>