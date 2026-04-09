@extends('client.layouts.app')

@section('title', 'Mails reçus')

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
    --secondary-light: #a78bfa;
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
    --glass: rgba(255, 255, 255, 0.7);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    --blur: blur(20px);
    --radius-xs: 0.375rem;
    --radius-sm: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.5rem;
    --radius-2xl: 2rem;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #ffffff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* ===== CONTAINER PRINCIPAL ===== */
.mail-container {
    max-width: 1440px;
    margin: 0 auto;
    padding: 2rem;
    min-height: 100vh;
    position: relative;
}

/* ===== CARTES GLASSMORPHISM ===== */
.glass-card {
    background: var(--glass);
    backdrop-filter: var(--blur);
    -webkit-backdrop-filter: var(--blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-2xl), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
    background: rgba(255, 255, 255, 0.8);
}

/* ===== TITRES ===== */
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    letter-spacing: -0.02em;
}

.section-title i {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 2rem;
}

/* ===== STATUS BADGE ===== */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.875rem;
    background: white;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.status-success {
    color: var(--success);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    border-color: rgba(16, 185, 129, 0.2);
}

.status-error {
    color: var(--danger);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    border-color: rgba(239, 68, 68, 0.2);
}

.status-warning {
    color: var(--warning);
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
    border-color: rgba(245, 158, 11, 0.2);
}

/* ===== FORMULAIRES PREMIUM ===== */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    letter-spacing: 0.025em;
    text-transform: uppercase;
}

.form-group label i {
    color: var(--primary);
    margin-right: 0.5rem;
    width: 1rem;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1.25rem;
    font-size: 0.95rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    background: white;
    transition: all 0.2s;
    box-shadow: var(--shadow-xs);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* ===== BOUTONS SPECTACULAIRES ===== */
.btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
    padding: 0.875rem 1.75rem;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: var(--radius-lg);
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    z-index: 1;
    text-decoration: none;
}

.btn::before {
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

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn-outline:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.btn-glass {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: var(--blur);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: var(--gray-800);
}

.btn-glass:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
}

.btn-block {
    width: 100%;
}

/* ===== MODAL CINÉMATIQUE AMÉLIORÉE (SANS HEADER) ===== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 1rem;
}

.modal-overlay.active {
    display: flex;
    opacity: 1;
}

.modal-container {
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    background: white;
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-2xl);
    overflow: hidden;
    transform: scale(0.9) translateY(20px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    position: relative;
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
    opacity: 1;
}

/* Bouton de fermeture flottant */
.modal-close-floating {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: 1.25rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: var(--shadow-md);
    z-index: 10;
}

.modal-close-floating:hover {
    background: white;
    transform: rotate(90deg);
    box-shadow: var(--shadow-lg);
    color: var(--primary);
}

.modal-content {
    padding: 2rem;
    overflow-y: auto;
    flex: 1;
    background: white;
}

.modal-meta {
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.modal-avatar {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    box-shadow: var(--shadow-lg);
    flex-shrink: 0;
}

.modal-sender-info {
    flex: 1;
    min-width: 200px;
}

.modal-sender-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.modal-sender-email {
    font-size: 0.875rem;
    color: var(--gray-500);
    word-break: break-word;
}

.modal-date {
    font-size: 0.875rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
    flex-wrap: wrap;
}

.modal-body {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--gray-700);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--gray-200);
    white-space: pre-wrap;
    word-break: break-word;
}

.modal-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    padding-bottom: 0.5rem;
    overflow-x: auto;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
}

.modal-tab {
    background: none;
    border: none;
    padding: 0.75rem 1.25rem;
    cursor: pointer;
    border-radius: var(--radius-md);
    font-weight: 500;
    color: var(--gray-500);
    transition: all 0.2s;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-tab i {
    font-size: 1rem;
}

.modal-tab:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.modal-tab.active {
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
    border-radius: 0;
}

.modal-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.modal-attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.modal-attachment-item {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s;
}

.modal-attachment-item:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.modal-attachment-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 1.25rem;
}

.modal-attachment-info {
    flex: 1;
    min-width: 0;
}

.modal-attachment-name {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.modal-attachment-size {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.modal-attachment-download {
    color: var(--primary);
    font-size: 1.125rem;
    padding: 0.25rem;
}

.modal-headers pre {
    white-space: pre-wrap;
    word-break: break-word;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.8125rem;
    background: var(--gray-100);
    padding: 1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-200);
    margin: 0;
}

/* ===== LISTE EMAILS ===== */
.email-grid {
    display: grid;
    gap: 0.75rem;
}

.email-item {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.email-item:hover {
    background: linear-gradient(135deg, white, var(--gray-50));
    border-color: var(--primary-light);
    transform: translateX(4px) scale(1.01);
    box-shadow: var(--shadow-md);
}

.email-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
    box-shadow: var(--shadow-md);
    flex-shrink: 0;
}

.email-content {
    flex: 1;
    min-width: 0;
}

.email-sender {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.email-sender i {
    color: var(--primary);
    font-size: 0.75rem;
}

.email-subject {
    font-weight: 500;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.email-preview {
    font-size: 0.875rem;
    color: var(--gray-500);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.5rem;
}

.email-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--gray-400);
    font-size: 0.75rem;
    flex-wrap: wrap;
}

.email-date {
    background: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    color: var(--gray-600);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ===== PAGINATION ===== */
.pagination-modern {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.pagination-modern a,
.pagination-modern span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination-modern a:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: scale(1.1);
}

.pagination-modern .active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    box-shadow: var(--shadow-md);
}

/* ===== TOOLTIPS ===== */
[data-tooltip] {
    position: relative;
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
    border-radius: var(--radius-sm);
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
    z-index: 10;
    pointer-events: none;
}

[data-tooltip]:hover:before {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-4px);
}

/* ===== ALERTS ===== */
.alert {
    padding: 1rem 1.25rem;
    border-radius: var(--radius-lg);
    border: 1px solid;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
}

.alert i {
    font-size: 1.125rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
    .mail-container {
        padding: 1.5rem;
    }
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .glass-card {
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .mail-container {
        padding: 1rem;
    }
    
    .glass-card {
        padding: 1.25rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .section-title {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title i {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 0.75rem 1.25rem;
    }
    
    .modal-container {
        max-height: 95vh;
    }
    
    .modal-content {
        padding: 1.5rem;
    }
    
    .modal-close-floating {
        top: 0.75rem;
        right: 0.75rem;
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .modal-meta {
        padding: 1.25rem;
    }
    
    .modal-avatar {
        width: 56px;
        height: 56px;
        font-size: 1.25rem;
    }
    
    .modal-sender-name {
        font-size: 1.25rem;
    }
    
    .modal-body {
        padding: 1.25rem;
    }
}

@media (max-width: 640px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .email-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 1rem;
    }
    
    .email-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .email-sender {
        font-size: 0.9375rem;
    }
    
    .email-subject {
        font-size: 0.9375rem;
    }
    
    .email-preview {
        font-size: 0.8125rem;
    }
    
    .email-meta {
        gap: 0.75rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .modal-content {
        padding: 1.25rem;
    }
    
    .modal-meta {
        flex-direction: column;
        align-items: flex-start;
        padding: 1rem;
    }
    
    .modal-avatar {
        width: 48px;
        height: 48px;
    }
    
    .modal-tabs {
        gap: 0.25rem;
    }
    
    .modal-tab {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .modal-actions .btn {
        width: 100%;
    }
    
    .modal-attachments-grid {
        grid-template-columns: 1fr;
    }
    
    .hide-mobile {
        display: none;
    }
    
    .btn-block-mobile {
        width: 100%;
    }
    
    [data-tooltip]:before {
        display: none;
    }
}

@media (max-width: 480px) {
    .mail-container {
        padding: 0.75rem;
    }
    
    .glass-card {
        padding: 1rem;
        border-radius: var(--radius-lg);
    }
    
    .section-title {
        font-size: 1.125rem;
        margin-bottom: 1rem;
    }
    
    .btn {
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
    }
    
    .form-control {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    
    .modal-container {
        border-radius: var(--radius-xl);
    }
    
    .modal-content {
        padding: 1rem;
    }
    
    .modal-close-floating {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .modal-meta {
        padding: 0.875rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-tab {
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
    }
    
    .modal-attachment-item {
        padding: 0.75rem;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.pulse {
    animation: pulse 2s infinite;
}

.shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}

.fade-in {
    animation: fadeIn 0.5s ease;
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
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--primary), var(--secondary-dark));
}

/* ===== UTILITAIRES ===== */
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.d-flex {
    display: flex;
}

.align-center {
    align-items: center;
}

.justify-between {
    justify-content: space-between;
}

.gap-1 {
    gap: 0.5rem;
}

.gap-2 {
    gap: 1rem;
}

.mt-2 {
    margin-top: 1rem;
}

.mb-2 {
    margin-bottom: 1rem;
}

.w-100 {
    width: 100%;
}
</style>

<div class="mail-container">

    <!-- Configuration IMAP -->
    <div class="glass-card">
        <h2 class="section-title">
            <i class="fas fa-cog"></i>
            Configuration IMAP
        </h2>

        <!-- Status Badge -->
        <div style="margin-bottom: 2rem;">
            @if($imap && $imap->last_test_success)
                <div class="status-badge status-success pulse">
                    <i class="fas fa-check-circle"></i>
                    <span>IMAP opérationnel - {{ $imap->host }}:{{ $imap->port }}</span>
                </div>
            @elseif($imap && $imap->last_test_success === false)
                <div class="status-badge status-error">
                    <i class="fas fa-times-circle"></i>
                    <span>Erreur de connexion IMAP</span>
                </div>
            @else
                <div class="status-badge status-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Configuration non testée</span>
                </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <a href="https://myaccount.google.com/security" target="_blank" class="btn btn-glass" data-tooltip="Sécurité du compte">
                <i class="fas fa-shield-alt"></i>
                <span class="hide-mobile">Sécurité Google</span>
            </a>
            <a href="https://myaccount.google.com/apppasswords" target="_blank" class="btn btn-glass" data-tooltip="Mots de passe application">
                <i class="fas fa-key"></i>
                <span class="hide-mobile">Mots de passe app</span>
            </a>
            <a href="https://mail.google.com/" target="_blank" class="btn btn-glass" data-tooltip="Ouvrir Gmail">
                <i class="fas fa-external-link-alt"></i>
                <span class="hide-mobile">Gmail</span>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Formulaire -->
            <div>
                <form method="POST" action="{{ route('client.mails.imap.save') }}">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-server"></i> Serveur IMAP</label>
                        <input type="text" name="host" class="form-control" value="{{ $imap->host ?? '' }}" placeholder="imap.gmail.com" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-plug"></i> Port</label>
                            <input type="number" name="port" class="form-control" value="{{ $imap->port ?? 993 }}" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-shield-alt"></i> Sécurité</label>
                            <select name="encryption" class="form-control">
                                <option value="ssl" {{ ($imap->encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ ($imap->encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="none" {{ ($imap->encryption ?? '') === 'none' ? 'selected' : '' }}>Aucune</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="username" class="form-control" value="{{ $imap->username ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="{{ $imap ? '••••••••' : '' }}" {{ $imap ? '' : 'required' }}>
                        @if($imap)<small style="color: var(--gray-500);">Laissez vide pour conserver</small>@endif
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Dossier racine</label>
                        <input type="text" name="folder" class="form-control" value="{{ $imap->folder ?? 'INBOX' }}" required>
                    </div>

                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                </form>

                @if($imap)
                <form method="POST" action="{{ route('client.mails.imap.test') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-vial"></i> Tester
                    </button>
                </form>
                @endif
                    </div>
            </div>

            <!-- Configuration actuelle -->
            <div>
                <div style="background: rgba(255,255,255,0.5); border-radius: var(--radius-lg); padding: 1.5rem; backdrop-filter: blur(10px);">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin-bottom: 1rem; display: flex; gap: 0.5rem;">
                        <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                        Configuration active
                    </h3>

                    @if($imap)
                    <div style="display: grid; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: white; border-radius: var(--radius-md);">
                            <i class="fas fa-circle" style="color: {{ $imap->last_test_success ? 'var(--success)' : ($imap->last_test_success === false ? 'var(--danger)' : 'var(--warning)') }}; font-size: 0.5rem;"></i>
                            <span style="font-weight: 500;">{{ $imap->host }}:{{ $imap->port }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: white; border-radius: var(--radius-md);">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i>
                            <span>{{ $imap->username }}</span>
                        </div>
                        @if($imap->last_sync_at)
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: white; border-radius: var(--radius-md);">
                            <i class="fas fa-clock" style="color: var(--gray-500);"></i>
                            <span>Dernière synchro: {{ $imap->last_sync_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="alert" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); color: var(--warning);">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Aucune configuration</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Boîte de réception -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <h2 class="section-title" style="margin-bottom: 0;">
                <i class="fas fa-inbox"></i>
                Messages reçus
            </h2>

            @if($imap && $imap->last_test_success)
            <form method="POST" action="{{ route('client.mails.imap.sync') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Synchroniser
                </button>
            </form>
            @endif
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-value">{{ $stats['unread'] ?? 0 }}</div>
                <div class="stat-label">Non lus</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-paperclip"></i></div>
                <div class="stat-value">{{ $stats['attachments'] ?? 0 }}</div>
                <div class="stat-label">Pièces jointes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-value">{{ $stats['week'] ?? 0 }}</div>
                <div class="stat-label">Cette semaine</div>
            </div>
        </div>

        @if($imap && $imap->last_sync_at)
        <div style="background: rgba(99, 102, 241, 0.1); border-radius: var(--radius-lg); padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(99, 102, 241, 0.2);">
            <i class="fas fa-sync-alt" style="color: var(--primary);"></i>
            <span style="color: var(--gray-700);">Dernière synchronisation : {{ $imap->last_sync_at->diffForHumans() }}</span>
        </div>
        @endif

        <!-- Liste emails -->
        <div class="email-grid">
            @forelse($messages as $message)
            @php
                $from = $message->getFrom()[0] ?? null;
                $senderName = $from ? ($from->personal ?? explode('@', $from->mail)[0]) : 'Inconnu';
                $senderEmail = $from->mail ?? 'inconnu@email.com';
                $avatar = strtoupper(substr($senderName, 0, 2));
                $date = $message->getDate() && $message->getDate()->first() 
                    ? \Carbon\Carbon::parse($message->getDate()->first()) 
                    : null;
            @endphp
            <div class="email-item" 
                 onclick="openMailModal({
                     subject: {{ json_encode($message->getSubject()) }},
                     from: {{ json_encode($senderEmail) }},
                     fromName: {{ json_encode($senderName) }},
                     date: '{{ $date ? $date->format('d/m/Y H:i') : '' }}',
                     body: {{ json_encode(Str::limit(strip_tags($message->getTextBody()), 1000)) }},
                     fullBody: {{ json_encode(strip_tags($message->getTextBody())) }},
                     attachments: {{ json_encode($message->getAttachments()->map(function($attachment) {
                         return [
                             'name' => $attachment->getName(),
                             'size' => $attachment->getSize(),
                             'type' => $attachment->getContentType()
                         ];
                     })) }}
                 })">
                
                <div class="email-avatar">{{ $avatar }}</div>
                
                <div class="email-content">
                    <div class="email-sender">
                        <i class="fas fa-circle" style="color: {{ $loop->index < 3 ? 'var(--success)' : 'var(--gray-300)' }}; font-size: 0.5rem;"></i>
                        <span>{{ $senderName }}</span>
                        <span style="color: var(--gray-400); font-size: 0.75rem; font-weight: normal;">&lt;{{ $senderEmail }}&gt;</span>
                    </div>
                    
                    <div class="email-subject">
                        <span>{{ $message->getSubject() ?: '(Sans objet)' }}</span>
                        @if($message->getAttachments()->count())
                            <span style="background: var(--gray-100); padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.75rem; color: var(--gray-600);">
                                <i class="fas fa-paperclip"></i> {{ $message->getAttachments()->count() }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="email-preview">
                        {{ Str::limit(strip_tags($message->getTextBody()), 80) }}
                    </div>
                    
                    <div class="email-meta">
                        <span class="email-date">
                            <i class="far fa-clock"></i>
                            {{ $date ? $date->format('H:i') : '' }}
                        </span>
                        <span>
                            <i class="far fa-calendar"></i>
                            {{ $date ? $date->format('d/m/Y') : '' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; background: rgba(255,255,255,0.5); border-radius: var(--radius-lg);">
                <i class="fas fa-envelope-open" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                <p style="color: var(--gray-500);">Aucun message dans la boîte de réception</p>
                @if($imap && $imap->last_test_success)
                <button onclick="document.querySelector('form[action*=\'sync\'] button').click()" class="btn btn-outline" style="margin-top: 1rem;">
                    <i class="fas fa-sync-alt"></i> Synchroniser maintenant
                </button>
                @endif
            </div>
            @endforelse
        </div>

        @if($messages instanceof \Illuminate\Pagination\LengthAwarePaginator && $messages->hasPages())
        <div class="pagination-modern">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>

<!-- MODAL CINÉMATIQUE AMÉLIORÉE (SANS HEADER) -->
<div id="mailModal" class="modal-overlay">
    <div class="modal-container">
        <!-- Bouton de fermeture flottant -->
        <button class="modal-close-floating" onclick="closeMailModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="modal-content">
            <div class="modal-meta">
                <div class="modal-avatar" id="modalAvatar">JD</div>
                <div class="modal-sender-info">
                    <div class="modal-sender-name" id="modalSenderName">Jean Dupont</div>
                    <div class="modal-sender-email" id="modalSenderEmail">jean.dupont@email.com</div>
                    <div class="modal-date" id="modalDate">
                        <i class="far fa-calendar-alt"></i>
                        15 mars 2024 à 14:30
                    </div>
                </div>
            </div>
            
            <!-- Tabs améliorées -->
            <div class="modal-tabs">
                <button class="modal-tab active" onclick="showTab('body', this)">
                    <i class="fas fa-envelope"></i>
                    <span>Message</span>
                </button>
                <button class="modal-tab" onclick="showTab('attachments', this)">
                    <i class="fas fa-paperclip"></i>
                    <span>Pièces jointes</span>
                </button>
                <button class="modal-tab" onclick="showTab('headers', this)">
                    <i class="fas fa-code"></i>
                    <span>En-têtes</span>
                </button>
            </div>
            
            <!-- Contenu des tabs -->
            <div id="modalBody" class="modal-body" style="display: block;">
                Chargement...
            </div>
            
            <div id="modalAttachments" class="modal-body" style="display: none;">
                <div id="attachmentsList"></div>
            </div>
            
            <div id="modalHeaders" class="modal-body" style="display: none;">
                <pre id="headersContent" class="modal-headers">En-têtes du message...</pre>
            </div>
            
            <!-- Actions -->
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="replyToEmail()">
                    <i class="fas fa-reply"></i>
                    <span>Répondre</span>
                </button>
                <button class="btn btn-outline" onclick="forwardEmail()">
                    <i class="fas fa-forward"></i>
                    <span>Transférer</span>
                </button>
                <button class="btn btn-primary" onclick="printEmail()">
                    <i class="fas fa-print"></i>
                    <span>Imprimer</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Gestionnaire de modal amélioré
let currentEmailData = null;
let currentTab = 'body';

function openMailModal(data) {
    currentEmailData = data;
    
    // Mise à jour du contenu
    document.getElementById('modalSenderName').innerText = data.fromName || 'Inconnu';
    document.getElementById('modalSenderEmail').innerHTML = data.from || 'inconnu@email.com';
    document.getElementById('modalDate').innerHTML = `<i class="far fa-calendar-alt"></i> ${data.date || 'Date inconnue'}`;
    document.getElementById('modalBody').innerHTML = data.fullBody || data.body || 'Aucun contenu';
    
    // Avatar
    const initials = (data.fromName || '??').substring(0, 2).toUpperCase();
    document.getElementById('modalAvatar').innerText = initials;
    
    // Gestion des pièces jointes
    if (data.attachments && data.attachments.length > 0) {
        let attachmentsHtml = '<div class="modal-attachments-grid">';
        data.attachments.forEach(att => {
            attachmentsHtml += `
                <div class="modal-attachment-item">
                    <div class="modal-attachment-icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <div class="modal-attachment-info">
                        <div class="modal-attachment-name">${att.name || 'Fichier'}</div>
                        <div class="modal-attachment-size">${formatFileSize(att.size)}</div>
                    </div>
                    <a href="#" class="modal-attachment-download" onclick="downloadAttachment('${att.name}')">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            `;
        });
        attachmentsHtml += '</div>';
        document.getElementById('attachmentsList').innerHTML = attachmentsHtml;
    } else {
        document.getElementById('attachmentsList').innerHTML = `
            <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                <i class="fas fa-paperclip" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>Aucune pièce jointe</p>
            </div>
        `;
    }
    
    // En-têtes
    document.getElementById('headersContent').innerHTML = `
De: ${data.fromName || ''} <${data.from || ''}>
Date: ${data.date || ''}
Sujet: ${data.subject || ''}
    `;
    
    // Reset tabs
    resetTabs();
    showTab('body', document.querySelector('.modal-tab'));
    
    // Afficher la modal
    document.querySelector('.modal-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeMailModal() {
    document.querySelector('.modal-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

function resetTabs() {
    document.querySelectorAll('.modal-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById('modalBody').style.display = 'none';
    document.getElementById('modalAttachments').style.display = 'none';
    document.getElementById('modalHeaders').style.display = 'none';
}

function showTab(tab, element) {
    // Mise à jour des boutons
    document.querySelectorAll('.modal-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    if (element) {
        element.classList.add('active');
    }
    
    // Affichage du contenu
    document.getElementById('modalBody').style.display = 'none';
    document.getElementById('modalAttachments').style.display = 'none';
    document.getElementById('modalHeaders').style.display = 'none';
    
    if (tab === 'body') {
        document.getElementById('modalBody').style.display = 'block';
    } else if (tab === 'attachments') {
        document.getElementById('modalAttachments').style.display = 'block';
    } else if (tab === 'headers') {
        document.getElementById('modalHeaders').style.display = 'block';
    }
    
    currentTab = tab;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadAttachment(filename) {
    alert('Téléchargement de ' + filename);
    // Implémenter le téléchargement réel
}

// Fonctions d'action
function replyToEmail() {
    if (currentEmailData) {
        window.location.href = `mailto:${currentEmailData.from}?subject=Re: ${currentEmailData.subject}`;
    }
}

function forwardEmail() {
    if (currentEmailData) {
        window.location.href = `mailto:?subject=Fwd: ${currentEmailData.subject}&body=${encodeURIComponent('\n\n-------- Message original --------\nDe: ' + currentEmailData.fromName + ' <' + currentEmailData.from + '>\nDate: ' + currentEmailData.date + '\n\n' + currentEmailData.fullBody)}`;
    }
}

function printEmail() {
    if (!currentEmailData) return;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>${currentEmailData.subject || 'Email'}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    padding: 2rem; 
                    line-height: 1.6;
                    max-width: 800px;
                    margin: 0 auto;
                }
                .header { 
                    margin-bottom: 2rem; 
                    padding-bottom: 1rem; 
                    border-bottom: 1px solid #ccc; 
                }
                .from { font-weight: bold; }
                .date { color: #666; font-size: 0.875rem; }
                .subject { 
                    font-size: 1.25rem; 
                    font-weight: bold; 
                    margin: 1rem 0;
                }
                .body { 
                    margin-top: 2rem;
                    white-space: pre-wrap;
                }
                @media print {
                    body { padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="from">De: ${currentEmailData.fromName || ''} &lt;${currentEmailData.from || ''}&gt;</div>
                <div class="date">Date: ${currentEmailData.date || ''}</div>
                <div class="subject">Sujet: ${currentEmailData.subject || ''}</div>
            </div>
            <div class="body">${(currentEmailData.fullBody || '').replace(/\n/g, '<br>')}</div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Fermeture avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMailModal();
    }
});

// Fermeture en cliquant sur l'overlay
document.querySelector('.modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMailModal();
    }
});

// Empêcher la fermeture en cliquant sur le contenu
document.querySelector('.modal-container').addEventListener('click', function(e) {
    e.stopPropagation();
});

// Animations au scroll
window.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.email-item');
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            card.style.opacity = '1';
            card.style.transform = 'translateX(0)';
        }
    });
});

// Initialisation des animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.email-item');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateX(-20px)';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateX(0)';
        }, index * 50);
    });
    
    // Gestion du swipe mobile pour fermer la modal
    let touchStartY = 0;
    const modal = document.querySelector('.modal-container');
    
    modal.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    
    modal.addEventListener('touchmove', function(e) {
        if (!touchStartY) return;
        const touchY = e.touches[0].clientY;
        const diff = touchY - touchStartY;
        
        if (diff > 100 && modal.scrollTop === 0) {
            closeMailModal();
            touchStartY = 0;
        }
    }, { passive: true });
    
    modal.addEventListener('touchend', function() {
        touchStartY = 0;
    });
});
</script>

<!-- Styles supplémentaires pour les améliorations -->
<style>
/* Améliorations pour le responsive */
@media (hover: none) and (pointer: coarse) {
    .email-item:hover {
        transform: none;
    }
    
    .btn:hover::before {
        width: 0;
        height: 0;
    }
    
    [data-tooltip]:before {
        display: none;
    }
}

/* Améliorations de l'accessibilité */
.btn:focus-visible,
.form-control:focus-visible,
.modal-close-floating:focus-visible,
.modal-tab:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
</style>
@endsection