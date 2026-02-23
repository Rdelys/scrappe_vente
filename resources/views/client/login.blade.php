<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follup.io | Connexion</title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Arrière-plan animé */
        .bg-circle {
            position: fixed;
            width: min(80vw, 400px);
            height: min(80vw, 400px);
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.03) 70%);
            border-radius: 50%;
            animation: float 10s infinite ease-in-out;
            z-index: 0;
        }

        .bg-circle.one {
            top: -10%;
            left: -10%;
        }

        .bg-circle.two {
            bottom: -15%;
            right: -10%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(1deg); }
            66% { transform: translateY(15px) rotate(-1deg); }
        }

        /* Carte de connexion */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 420px;
            padding: clamp(30px, 5vw, 50px);
            border-radius: 24px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            animation: fadeUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            z-index: 10;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 24px;
                border-radius: 20px;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Branding */
        .brand {
            text-align: center;
            font-size: clamp(28px, 4vw, 36px);
            font-weight: 800;
            color: #667eea;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand span {
            color: #764ba2;
            font-weight: 900;
        }

        .subtitle {
            text-align: center;
            font-size: clamp(12px, 1.5vw, 14px);
            color: #888;
            margin-bottom: 32px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 500;
        }

        h2 {
            text-align: center;
            margin-bottom: 28px;
            color: #222;
            font-size: clamp(20px, 3vw, 24px);
            font-weight: 600;
        }

        /* Messages d'erreur */
        .error {
            background: linear-gradient(90deg, #ffe5e5 0%, #ffcccc 100%);
            color: #c0392b;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
            border: 1px solid #ffb8b8;
            font-size: 14px;
            line-height: 1.4;
        }

        @keyframes shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-3px); }
            40%, 60% { transform: translateX(3px); }
        }

        /* Formulaire */
        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            border-radius: 12px;
            border: 2px solid #e1e5e9;
            background: #f8fafc;
            font-size: 15px;
            color: #333;
            transition: all 0.3s ease;
            outline: none;
        }

        input::placeholder {
            color: #94a3b8;
        }

        input:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.15),
                0 4px 12px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        input:valid:not(:placeholder-shown) {
            border-color: #10b981;
        }

        /* Bouton */
        button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 24px rgba(102, 126, 234, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
            transition-duration: 0.1s;
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
    <div class="bg-circle one"></div>
    <div class="bg-circle two"></div>

    <div class="login-card">
        <div class="brand">FOLLUP.<span>IO</span></div>
        <div class="subtitle">CRM de prospection B to B</div>

        <h2>Connexion</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
<div class="password-wrapper">
    <input type="password" name="password" id="password" placeholder="Mot de passe" required>
    <span class="toggle-password" onclick="togglePassword()">🙈</span>
</div>
            <button type="submit">Se connecter</button>
        </form>
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