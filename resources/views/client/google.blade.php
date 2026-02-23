@extends('client.layouts.app')

@section('title', 'Google Maps')

@section('content')
<div class="google-maps-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Google Maps</h1>
            <p class="page-subtitle">Recherche intelligente d'entreprises locales</p>
        </div>
        
        @if(isset($places) && $places->count())
            <div class="btn-group">
                <a href="{{ route('client.google.export.pdf', request()->only('filter_scrapping')) }}" class="btn btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>PDF</span>
                </a>

                <a href="{{ route('client.google.export.excel', request()->only('filter_scrapping')) }}" class="btn btn-excel">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Excel</span>
                </a>

                <button type="button" class="btn btn-scraping" id="retryScrapingBtn">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Relancer scraping</span>
                </button>

                <button type="button" class="btn btn-stats" id="scrapingStatsBtn">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Statistiques</span>
                </button>

                <button type="button" class="btn btn-reset" id="resetColumnsBtn" title="Réinitialiser les colonnes">
                    <i class="fa-solid fa-arrows-left-right"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <i class="fa-solid fa-info-circle"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Search Form --}}
    <form method="POST" action="{{ route('client.google.scrape') }}" class="search-form" id="searchForm">
        @csrf
        <div class="search-box">
            <input type="text"
                   name="query"
                   required
                   placeholder="Ex : plombier Paris"
                   value="{{ old('query') }}"
                   class="search-input">

            <input type="text"
                   name="nom_scrapping"
                   required
                   placeholder="Nom du scrapping (ex: Plombiers Paris Janvier)"
                   value="{{ old('nom_scrapping') }}"
                   class="search-input">

            <button type="submit" class="search-button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    {{-- Loader --}}
    <div id="loader" class="loader hidden">
        <div class="spinner"></div>
        <p>Scraping en cours... Analyse Google + Sites web</p>
    </div>

    {{-- Filter Section --}}
    <form method="GET" action="{{ route('client.google') }}" class="filter-form">
        <select name="filter_scrapping" onchange="this.form.submit()" class="filter-select">
            <option value="">-- Tous les scrappings --</option>
            @foreach($scrappings as $scrap)
                <option value="{{ $scrap }}" 
                    {{ request('filter_scrapping') == $scrap ? 'selected' : '' }}>
                    {{ $scrap }}
                </option>
            @endforeach
        </select>
    </form>

    @if(request('filter_scrapping'))
        <form method="POST" action="{{ route('client.google.export.lead.scrapping') }}" class="export-form">
            @csrf
            <input type="hidden" name="nom_scrapping" value="{{ request('filter_scrapping') }}">
            <button class="btn btn-export-all">
                <i class="fa-solid fa-download"></i>
                Exporter tout le scrapping
            </button>
        </form>
    @endif

    {{-- Results Section --}}
    @if(isset($places) && $places->count())
        <form method="POST" 
              action="{{ route('client.google.delete.selected') }}" 
              onsubmit="return confirm('Supprimer les lignes sélectionnées ?')"
              class="results-form">
            @csrf
            @method('DELETE')

            {{-- Toolbar --}}
            <div class="table-toolbar">
                <div class="selection-info">
                    <span id="selected-count">0</span> sélectionné(s)
                </div>
                <button type="submit" id="delete-btn" class="btn btn-delete" disabled>
                    <i class="fa-solid fa-trash"></i>
                    <span>Supprimer</span>
                </button>
            </div>

            {{-- Column Width Controls --}}
            <div class="column-controls">
                <div class="column-presets">
                    <span class="presets-label">
                        <i class="fa-solid fa-arrows-left-right"></i>
                        Largeur des colonnes:
                    </span>
                    <button type="button" class="preset-btn" data-preset="compact">Compact</button>
                    <button type="button" class="preset-btn" data-preset="normal">Normal</button>
                    <button type="button" class="preset-btn" data-preset="wide">Large</button>
                    <button type="button" class="preset-btn" data-preset="auto">Auto</button>
                </div>
                <div class="column-custom">
                    <button type="button" class="save-widths-btn" id="saveWidthsBtn">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Sauvegarder
                    </button>
                </div>
            </div>

            {{-- Table Container with Resizable Columns --}}
            <div class="table-container-wrapper">
                <div class="table-scroll-container">
                    <table class="data-table" id="resizableTable">
                        <thead>
                            <tr>
                                <th class="checkbox-col" style="width: 50px; min-width: 50px; max-width: 50px;">
                                    <input type="checkbox" id="select-all" class="checkbox">
                                </th>
                                <th class="resizable" data-index="11" data-default="150">Nom Scrapping</th>
                                <th class="resizable" data-index="1" data-default="200">Entreprise</th>
                                <th class="resizable" data-index="2" data-default="150">Catégorie</th>
                                <th class="resizable" data-index="3" data-default="200">Adresse</th>
                                <th class="resizable" data-index="4" data-default="150">Téléphone</th>
                                <th class="resizable" data-index="5" data-default="200">Site web</th>
                                <th class="resizable" data-index="6" data-default="200">Email</th>
                                <th class="resizable" data-index="7" data-default="120">Réseaux</th>
                                <th class="resizable" data-index="8" data-default="80" style="min-width: 80px;">Note</th>
                                <th class="resizable" data-index="9" data-default="80" style="min-width: 80px;">Avis</th>
                                <th class="resizable" data-index="10" data-default="120" style="min-width: 120px;">Statut</th>
                                <th class="resizable" data-index="12" data-default="100">Exporté</th>
                                <th style="width:120px; min-width:120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($places as $p)
                                <tr>
                                    <td class="checkbox-col">
                                        <input type="checkbox" name="selected[]" value="{{ $p->id }}" class="checkbox row-checkbox">
                                    </td>
                                    <td>{{ $p->nom_scrapping ?? '—' }}</td>
                                    <td class="company-name">{{ $p->name ?? '—' }}</td>
                                    <td>{{ $p->category ?? '—' }}</td>
                                    <td>{{ $p->address ?? '—' }}</td>
                                    <td>
                                        @if($p->phone)
                                            <a href="tel:{{ $p->phone }}" class="phone-link">
                                                <i class="fa-solid fa-phone"></i> {{ $p->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->website)
                                            <a href="{{ $p->website }}" target="_blank" class="website-link" title="{{ $p->website }}">
                                                <i class="fa-solid fa-globe"></i>
                                                {{ Str::limit(preg_replace('#^https?://#', '', $p->website), 20) }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->email)
                                            <span class="email-badge" title="{{ $p->email }}">
                                                <i class="fa-solid fa-envelope"></i>
                                                {{ Str::limit($p->email, 15) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="social-links">
                                            @if($p->facebook)
                                                <a href="{{ $p->facebook }}" target="_blank" class="social-link facebook" title="Facebook">
                                                    <i class="fa-brands fa-facebook-f"></i>
                                                </a>
                                            @endif
                                            @if($p->instagram)
                                                <a href="{{ $p->instagram }}" target="_blank" class="social-link instagram" title="Instagram">
                                                    <i class="fa-brands fa-instagram"></i>
                                                </a>
                                            @endif
                                            @if($p->linkedin)
                                                <a href="{{ $p->linkedin }}" target="_blank" class="social-link linkedin" title="LinkedIn">
                                                    <i class="fa-brands fa-linkedin-in"></i>
                                                </a>
                                            @endif
                                            @if(!$p->facebook && !$p->instagram && !$p->linkedin)
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($p->rating)
                                            <span class="rating-badge" title="{{ $p->rating }} étoiles">
                                                <i class="fa-solid fa-star"></i>
                                                {{ number_format($p->rating, 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->reviews_count)
                                            <span class="reviews-count" title="{{ $p->reviews_count }} avis">
                                                <i class="fa-solid fa-comment"></i>
                                                {{ number_format($p->reviews_count) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->website)
                                            @if($p->contact_scraped_at)
                                                <span class="status-badge status-success" title="Scrapé le {{ $p->contact_scraped_at->format('d/m/Y H:i') }}">
                                                    <i class="fa-solid fa-check-circle"></i>
                                                    Contacts OK
                                                </span>
                                            @elseif($p->website_scraped)
                                                <span class="status-badge status-warning">
                                                    <i class="fa-solid fa-clock"></i>
                                                    En attente
                                                </span>
                                            @else
                                                <span class="status-badge status-pending">
                                                    <i class="fa-solid fa-hourglass-half"></i>
                                                    Planifié
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->exported_to_lead)
                                            <span class="status-badge status-success">
                                                <i class="fa-solid fa-check"></i> Oui
                                            </span>
                                        @else
                                            <span class="status-badge status-warning">
                                                <i class="fa-solid fa-clock"></i> Non
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$p->exported_to_lead)
                                            <button 
                                                type="button"
                                                class="btn btn-primary btn-sm export-btn"
                                                data-url="{{ route('client.google.export.lead.single', $p->id) }}">
                                                Exporter
                                            </button>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($places->hasPages())
                <div class="pagination-wrapper">
                    @if ($places->onFirstPage())
                        <span class="pagination-item disabled">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $places->appends(request()->query())->previousPageUrl() }}" class="pagination-item">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($places->appends(request()->query())->getUrlRange(
                        max(1, $places->currentPage() - 2),
                        min($places->lastPage(), $places->currentPage() + 2)
                    ) as $page => $url)
                        @if ($page == $places->currentPage())
                            <span class="pagination-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($places->hasMorePages())
                        <a href="{{ $places->appends(request()->query())->nextPageUrl() }}" class="pagination-item">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="pagination-item disabled">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        </form>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <h3>Aucun résultat</h3>
            <p>Commencez par effectuer une recherche Google Maps</p>
        </div>
    @endif
</div>

<style>
/*=============================================================================
  GOOGLE MAPS - STYLE AMÉLIORÉ AVEC COLONNES REDIMENSIONNABLES
  =============================================================================*/

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --primary-50: #eff6ff;
    --primary-100: #dbeafe;
    --primary-200: #bfdbfe;
    --primary-300: #93c5fd;
    --primary-400: #60a5fa;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    --primary-800: #1e40af;
    --primary-900: #1e3a8a;
    
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --success-200: #bbf7d0;
    --success-300: #86efac;
    --success-400: #4ade80;
    --success-500: #22c55e;
    --success-600: #16a34a;
    --success-700: #15803d;
    --success-800: #166534;
    --success-900: #14532d;
    
    --danger-50: #fef2f2;
    --danger-100: #fee2e2;
    --danger-200: #fecaca;
    --danger-300: #fca5a5;
    --danger-400: #f87171;
    --danger-500: #ef4444;
    --danger-600: #dc2626;
    --danger-700: #b91c1c;
    --danger-800: #991b1b;
    --danger-900: #7f1d1d;
    
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-200: #fde68a;
    --warning-300: #fcd34d;
    --warning-400: #fbbf24;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    --warning-700: #b45309;
    --warning-800: #92400e;
    --warning-900: #78350f;
    
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    
    --transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    --spacing-1: 0.25rem;
    --spacing-2: 0.5rem;
    --spacing-3: 0.75rem;
    --spacing-4: 1rem;
    --spacing-5: 1.25rem;
    --spacing-6: 1.5rem;
    --spacing-8: 2rem;
    --spacing-10: 2.5rem;
    --spacing-12: 3rem;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.google-maps-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: var(--spacing-6);
    min-height: 100vh;
    background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
}

@media (min-width: 640px) {
    .google-maps-container {
        padding: var(--spacing-8);
    }
}

@media (min-width: 1024px) {
    .google-maps-container {
        padding: var(--spacing-10);
    }
}

/* Header */
.page-header {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
    margin-bottom: var(--spacing-8);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    padding: var(--spacing-6);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
    margin-bottom: var(--spacing-2);
    letter-spacing: -0.03em;
}

.page-subtitle {
    font-size: 1rem;
    color: var(--gray-500);
    margin: 0;
    font-weight: 400;
}

/* Button Group */
.btn-group {
    display: flex;
    gap: var(--spacing-2);
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    padding: var(--spacing-3) var(--spacing-4);
    font-size: 0.9375rem;
    font-weight: 600;
    line-height: 1.5;
    border-radius: var(--radius-lg);
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
    white-space: nowrap;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
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
}

.btn:active::before {
    width: 300px;
    height: 300px;
}

.btn-pdf {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger-600);
}

.btn-pdf:hover {
    background: var(--danger-50);
    border-color: var(--danger-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-excel {
    background: white;
    border-color: var(--gray-200);
    color: var(--success-600);
}

.btn-excel:hover {
    background: var(--success-50);
    border-color: var(--success-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-scraping {
    background: white;
    border-color: var(--gray-200);
    color: var(--primary-600);
}

.btn-scraping:hover {
    background: var(--primary-50);
    border-color: var(--primary-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-stats {
    background: white;
    border-color: var(--gray-200);
    color: var(--gray-700);
}

.btn-stats:hover {
    background: var(--gray-100);
    border-color: var(--gray-500);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-reset {
    background: white;
    border-color: var(--gray-200);
    color: var(--gray-700);
    padding: var(--spacing-3);
}

.btn-reset:hover {
    background: var(--gray-100);
    border-color: var(--gray-500);
    transform: translateY(-2px) rotate(90deg);
    box-shadow: var(--shadow-lg);
}

.btn-export-all {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    color: white;
    border: none;
    padding: var(--spacing-3) var(--spacing-6);
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    margin-bottom: var(--spacing-4);
    border: 1px solid var(--primary-500);
}

.btn-export-all:hover {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-800));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-delete {
    background: white;
    border-color: var(--gray-200);
    color: var(--danger-600);
}

.btn-delete:hover:not(:disabled) {
    background: var(--danger-50);
    border-color: var(--danger-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-delete:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: var(--gray-50);
    border-color: var(--gray-200);
    color: var(--gray-400);
    box-shadow: none;
    transform: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    color: white;
    border: none;
    padding: var(--spacing-2) var(--spacing-4);
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-base);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-800));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-primary.btn-sm {
    padding: var(--spacing-1) var(--spacing-3);
    font-size: 0.875rem;
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    padding: var(--spacing-4) var(--spacing-5);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-6);
    background: white;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-md);
    animation: slideIn 0.3s ease-out;
}

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

.alert-success {
    border-left: 4px solid var(--success-600);
    background: linear-gradient(to right, var(--success-50), white);
    color: var(--success-700);
}

.alert-info {
    border-left: 4px solid var(--primary-600);
    background: linear-gradient(to right, var(--primary-50), white);
    color: var(--primary-700);
}

.alert-danger {
    border-left: 4px solid var(--danger-600);
    background: linear-gradient(to right, var(--danger-50), white);
    color: var(--danger-700);
}

.alert i {
    font-size: 1.25rem;
}

/* Search Form */
.search-form {
    margin-bottom: var(--spacing-8);
}

.search-box {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    background: white;
    padding: var(--spacing-2);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

@media (min-width: 480px) {
    .search-box {
        flex-direction: row;
    }
}

.search-input {
    flex: 1;
    padding: var(--spacing-4) var(--spacing-5);
    border: none;
    border-radius: var(--radius-xl);
    font-size: 1rem;
    transition: var(--transition-base);
    background: transparent;
    color: var(--gray-900);
}

.search-input:focus {
    outline: none;
    box-shadow: inset 0 0 0 2px var(--primary-200);
}

.search-button {
    padding: var(--spacing-4) var(--spacing-6);
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    color: white;
    border: none;
    border-radius: var(--radius-xl);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    width: 100%;
}

@media (min-width: 480px) {
    .search-button {
        width: auto;
        min-width: 120px;
    }
}

.search-button:hover {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-800));
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

/* Filter Form */
.filter-form {
    margin-bottom: var(--spacing-4);
}

.filter-select {
    width: 100%;
    max-width: 300px;
    padding: var(--spacing-3) var(--spacing-4);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-size: 0.9375rem;
    color: var(--gray-700);
    background: white;
    cursor: pointer;
    transition: var(--transition-base);
    box-shadow: var(--shadow-sm);
}

.filter-select:hover {
    border-color: var(--primary-400);
    box-shadow: var(--shadow-md);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.export-form {
    margin-bottom: var(--spacing-4);
}

/* Loader */
.loader {
    text-align: center;
    padding: var(--spacing-8);
    background: white;
    border-radius: var(--radius-2xl);
    margin-bottom: var(--spacing-6);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--gray-200);
    border-top-color: var(--primary-600);
    border-right-color: var(--primary-400);
    border-bottom-color: var(--primary-300);
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
    margin: 0 auto var(--spacing-4);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.hidden {
    display: none;
}

/* Table Toolbar */
.table-toolbar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    margin: var(--spacing-6) 0 var(--spacing-4);
    padding: var(--spacing-3) var(--spacing-4);
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-md);
}

@media (min-width: 640px) {
    .table-toolbar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.selection-info {
    padding: var(--spacing-2) var(--spacing-4);
    background: var(--gray-100);
    border-radius: 9999px;
    font-size: 0.9375rem;
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--gray-200);
}

.selection-info span {
    font-weight: 700;
    color: var(--gray-900);
    margin-right: var(--spacing-1);
}

/* Column Controls */
.column-controls {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    margin-bottom: var(--spacing-4);
    padding: var(--spacing-3) var(--spacing-4);
    background: white;
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-md);
}

@media (min-width: 768px) {
    .column-controls {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.column-presets {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    flex-wrap: wrap;
}

.presets-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    font-size: 0.9375rem;
    color: var(--gray-600);
    font-weight: 500;
}

.presets-label i {
    color: var(--primary-600);
}

.preset-btn {
    padding: var(--spacing-2) var(--spacing-3);
    font-size: 0.875rem;
    font-weight: 600;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    background: white;
    color: var(--gray-700);
    cursor: pointer;
    transition: var(--transition-base);
}

.preset-btn:hover {
    background: var(--gray-100);
    border-color: var(--gray-300);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.preset-btn.active {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    border-color: var(--primary-600);
    color: white;
}

.column-custom {
    display: flex;
    gap: var(--spacing-2);
}

.save-widths-btn {
    padding: var(--spacing-2) var(--spacing-4);
    font-size: 0.875rem;
    font-weight: 600;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--success-600), var(--success-700));
    color: white;
    cursor: pointer;
    transition: var(--transition-base);
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    border: none;
}

.save-widths-btn:hover {
    background: linear-gradient(135deg, var(--success-700), var(--success-800));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

/* Table Container */
.table-container-wrapper {
    width: 100%;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    background: white;
    margin-bottom: var(--spacing-4);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.table-scroll-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    position: relative;
}

/* Resizable Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.9375rem;
    table-layout: fixed;
}

.data-table thead {
    background: linear-gradient(to bottom, var(--gray-50), white);
    border-bottom: 2px solid var(--gray-200);
}

.data-table th {
    padding: var(--spacing-4) var(--spacing-3);
    text-align: left;
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
    position: relative;
    user-select: none;
    background: inherit;
    border-right: 1px solid var(--gray-200);
    transition: background-color 0.2s;
}

.data-table th:last-child {
    border-right: none;
}

.data-table th.resizable {
    cursor: col-resize;
}

.data-table th.resizable::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 4px;
    height: 100%;
    background: transparent;
    transition: background 0.2s;
}

.data-table th.resizable:hover::after,
.data-table th.resizable.resizing::after {
    background: var(--primary-400);
    cursor: col-resize;
}

.data-table td {
    padding: var(--spacing-3) var(--spacing-3);
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    vertical-align: middle;
    transition: background-color 0.2s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.data-table tbody tr {
    transition: var(--transition-base);
}

.data-table tbody tr:hover {
    background: var(--gray-50);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Checkbox column */
.checkbox-col {
    width: 50px !important;
    min-width: 50px !important;
    max-width: 50px !important;
    text-align: center;
}

.checkbox {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: var(--radius-sm);
    border: 2px solid var(--gray-400);
    cursor: pointer;
    accent-color: var(--primary-600);
    transition: var(--transition-base);
}

.checkbox:hover {
    border-color: var(--primary-600);
    transform: scale(1.1);
}

/* Company name */
.company-name {
    font-weight: 700;
    color: var(--gray-900);
}

/* Links */
.website-link, .phone-link {
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-base);
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
}

.website-link:hover, .phone-link:hover {
    color: var(--primary-700);
    text-decoration: underline;
}

/* Badges */
.email-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    color: var(--primary-700);
    border-radius: 9999px;
    font-size: 0.875rem;
    border: 1px solid var(--primary-200);
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.rating-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
    color: var(--warning-700);
    border-radius: 9999px;
    font-weight: 700;
    font-size: 0.875rem;
    border: 1px solid var(--warning-200);
}

.reviews-count {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    background: var(--gray-100);
    color: var(--gray-700);
    border-radius: 9999px;
    font-size: 0.875rem;
    border: 1px solid var(--gray-200);
    font-weight: 600;
}

/* Social links */
.social-links {
    display: flex;
    gap: var(--spacing-1);
    flex-wrap: wrap;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    color: white;
    transition: var(--transition-smooth);
    text-decoration: none;
    font-size: 1rem;
}

.social-link.facebook {
    background: #1877f2;
}

.social-link.instagram {
    background: linear-gradient(45deg, #f09433, #d62976, #962fbf, #4f5bd5);
}

.social-link.linkedin {
    background: #0077b5;
}

.social-link:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: var(--shadow-md);
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
    border: 1px solid transparent;
    box-shadow: var(--shadow-xs);
}

.status-success {
    background: linear-gradient(135deg, var(--success-50), var(--success-100));
    color: var(--success-700);
    border-color: var(--success-200);
}

.status-warning {
    background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
    color: var(--warning-700);
    border-color: var(--warning-200);
}

.status-pending {
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    color: var(--gray-700);
    border-color: var(--gray-200);
}

.text-muted {
    color: var(--gray-400);
    font-style: italic;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    margin-top: var(--spacing-8);
}

.pagination-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.75rem;
    height: 2.75rem;
    padding: 0 var(--spacing-2);
    border-radius: var(--radius-lg);
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-base);
    font-size: 0.9375rem;
    box-shadow: var(--shadow-xs);
}

.pagination-item:hover:not(.active):not(.disabled) {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.pagination-item.active {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    border-color: var(--primary-600);
    color: white;
    box-shadow: var(--shadow-md);
}

.pagination-item.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: var(--spacing-12) var(--spacing-6);
    background: white;
    border-radius: var(--radius-2xl);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto var(--spacing-4);
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--primary-600);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--spacing-2);
}

.empty-state p {
    color: var(--gray-500);
    font-size: 1rem;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: white;
    border-radius: var(--radius-2xl);
    padding: var(--spacing-6);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-2xl);
    animation: slideUp 0.3s ease;
    border: 1px solid var(--gray-200);
}

.modal-content h3 {
    margin-bottom: var(--spacing-4);
    color: var(--gray-900);
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
}

.modal-content h3 i {
    color: var(--primary-600);
}

.modal-content p {
    margin-bottom: var(--spacing-6);
    color: var(--gray-600);
    font-size: 1rem;
    line-height: 1.6;
}

.modal-actions {
    display: flex;
    gap: var(--spacing-3);
    justify-content: flex-end;
    margin-top: var(--spacing-6);
}

/* Stats Modal */
.stats-modal {
    max-width: 600px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: var(--spacing-4);
    margin-bottom: var(--spacing-6);
}

.stat-card {
    background: var(--gray-50);
    padding: var(--spacing-4);
    border-radius: var(--radius-lg);
    text-align: center;
    border: 1px solid var(--gray-200);
    transition: var(--transition-base);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card.highlight {
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    border-color: var(--primary-200);
}

.stat-card.highlight .stat-value {
    color: var(--primary-700);
}

.stat-card.success {
    background: linear-gradient(135deg, var(--success-50), var(--success-100));
    border-color: var(--success-200);
}

.stat-card.success .stat-value {
    color: var(--success-700);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--gray-900);
    line-height: 1.2;
    margin-bottom: var(--spacing-1);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

.progress-section {
    margin-bottom: var(--spacing-6);
    background: var(--gray-50);
    padding: var(--spacing-4);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
}

.progress-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-2);
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

.progress-bar {
    height: 8px;
    background: var(--gray-200);
    border-radius: 9999px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
    border-radius: 9999px;
    transition: width 0.3s ease;
}

/* Notification */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    border-left: 4px solid var(--primary-600);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transform: translateX(120%);
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 9999;
    min-width: 300px;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left-color: var(--success-600);
    background: linear-gradient(to right, var(--success-50), white);
}

.notification-error {
    border-left-color: var(--danger-600);
    background: linear-gradient(to right, var(--danger-50), white);
}

.notification i {
    font-size: 1.25rem;
}

/* Animations */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ripple Effect */
.btn .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: var(--radius-lg);
}

::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: var(--radius-lg);
    transition: var(--transition-base);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
}

/* Focus States */
:focus-visible {
    outline: 2px solid var(--primary-500);
    outline-offset: 2px;
}

/* Responsive */
@media (max-width: 640px) {
    .page-header {
        padding: var(--spacing-4);
    }
    
    .btn {
        width: 100%;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .table-toolbar .btn {
        width: 100%;
    }
    
    .checkbox {
        width: 1.5rem;
        height: 1.5rem;
    }
    
    .column-presets {
        width: 100%;
        justify-content: center;
    }
    
    .presets-label {
        width: 100%;
        justify-content: center;
    }
    
    .column-custom {
        width: 100%;
    }
    
    .save-widths-btn {
        width: 100%;
        justify-content: center;
    }
    
    .notification {
        min-width: auto;
        width: 90%;
        left: 5%;
        right: 5%;
    }
}
</style>

<script>
(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTableSelection();
        initializeFormLoader();
        initializeButtonEffects();
        initializeScrapingButtons();
        initializeResizableColumns();
        initializeColumnPresets();
        initializeWidthSaving();
        loadSavedWidths();
    });

    // Table selection management
    function initializeTableSelection() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const deleteBtn = document.getElementById('delete-btn');
        const selectedCount = document.getElementById('selected-count');

        if (!selectAll || !checkboxes.length || !deleteBtn || !selectedCount) return;

        function updateSelection() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCount.textContent = checked;
            deleteBtn.disabled = checked === 0;
            
            if (selectAll) {
                selectAll.checked = checked === checkboxes.length && checkboxes.length > 0;
                selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelection);
        });

        updateSelection();
    }

    // Form loader
    function initializeFormLoader() {
        const searchForm = document.getElementById('searchForm');
        const loader = document.getElementById('loader');

        if (searchForm && loader) {
            searchForm.addEventListener('submit', function(e) {
                const queryInput = this.querySelector('input[name="query"]');
                if (queryInput && queryInput.value.trim() === '') {
                    e.preventDefault();
                    showNotification('Veuillez saisir un terme de recherche', 'error');
                    return;
                }
                
                loader.classList.remove('hidden');
            });
        }
    }

    // Button ripple effects
    function initializeButtonEffects() {
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                ripple.style.width = ripple.style.height = Math.max(rect.width, rect.height) + 'px';
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    }

    // Resizable Columns
    function initializeResizableColumns() {
        const table = document.getElementById('resizableTable');
        if (!table) return;

        const cols = table.querySelectorAll('th.resizable');
        let isResizing = false;
        let currentCol = null;
        let startX = 0;
        let startWidth = 0;
        let minWidth = 50;

        cols.forEach(col => {
            col.addEventListener('mousedown', function(e) {
                // Only if clicking near the right edge
                const rect = this.getBoundingClientRect();
                const threshold = 10;
                
                if (e.clientX > rect.right - threshold) {
                    isResizing = true;
                    currentCol = this;
                    startX = e.clientX;
                    startWidth = this.offsetWidth;
                    
                    // Get min width from data attribute or default
                    const minWidthAttr = this.dataset.minWidth;
                    if (minWidthAttr) {
                        minWidth = parseInt(minWidthAttr);
                    }
                    
                    this.classList.add('resizing');
                    
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                    
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });

        function onMouseMove(e) {
            if (!isResizing || !currentCol) return;
            
            const diff = e.clientX - startX;
            const newWidth = Math.max(minWidth, startWidth + diff);
            
            currentCol.style.width = newWidth + 'px';
            currentCol.style.minWidth = newWidth + 'px';
        }

        function onMouseUp() {
            if (isResizing) {
                isResizing = false;
                if (currentCol) {
                    currentCol.classList.remove('resizing');
                    currentCol = null;
                }
                
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
                
                // Auto-save widths
                saveWidthsToStorage();
            }
        }
    }

    // Column Presets
    function initializeColumnPresets() {
        const presetBtns = document.querySelectorAll('.preset-btn');
        const resetBtn = document.getElementById('resetColumnsBtn');

        presetBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const preset = this.dataset.preset;
                applyColumnPreset(preset);
                
                presetBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                resetColumnWidths();
                presetBtns.forEach(b => b.classList.remove('active'));
            });
        }

        // Load active preset from storage
        const savedPreset = localStorage.getItem('googleMapsActivePreset');
        if (savedPreset) {
            const activeBtn = document.querySelector(`.preset-btn[data-preset="${savedPreset}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }
    }

    function applyColumnPreset(preset) {
        const table = document.getElementById('resizableTable');
        if (!table) return;

        const cols = table.querySelectorAll('th.resizable');
        
        const presets = {
            compact: {
                '1': 150,   // Entreprise
                '2': 120,   // Catégorie
                '3': 150,   // Adresse
                '4': 120,   // Téléphone
                '5': 150,   // Site web
                '6': 150,   // Email
                '7': 100,   // Réseaux
                '8': 70,    // Note
                '9': 70,    // Avis
                '10': 100,  // Statut
                '11': 120,  // Nom Scrapping
                '12': 80    // Exporté
            },
            normal: {
                '1': 200,
                '2': 150,
                '3': 200,
                '4': 150,
                '5': 200,
                '6': 200,
                '7': 120,
                '8': 80,
                '9': 80,
                '10': 120,
                '11': 150,
                '12': 100
            },
            wide: {
                '1': 250,
                '2': 200,
                '3': 250,
                '4': 200,
                '5': 250,
                '6': 250,
                '7': 150,
                '8': 100,
                '9': 100,
                '10': 150,
                '11': 200,
                '12': 120
            },
            auto: {} // Auto will use content-based widths
        };

        if (preset === 'auto') {
            cols.forEach(col => {
                col.style.width = '';
                col.style.minWidth = col.dataset.default ? col.dataset.default + 'px' : '';
            });
        } else {
            cols.forEach(col => {
                const index = col.dataset.index;
                if (presets[preset][index]) {
                    col.style.width = presets[preset][index] + 'px';
                    col.style.minWidth = presets[preset][index] + 'px';
                }
            });
        }

        // Save preset to storage
        localStorage.setItem('googleMapsActivePreset', preset);
        saveWidthsToStorage();
        
        showNotification(`Colonnes en mode ${preset}`, 'success');
    }

    function resetColumnWidths() {
        const table = document.getElementById('resizableTable');
        if (!table) return;

        const cols = table.querySelectorAll('th.resizable');
        cols.forEach(col => {
            const defaultWidth = col.dataset.default;
            if (defaultWidth) {
                col.style.width = defaultWidth + 'px';
                col.style.minWidth = defaultWidth + 'px';
            } else {
                col.style.width = '';
                col.style.minWidth = '';
            }
        });

        localStorage.removeItem('googleMapsColumnWidths');
        localStorage.removeItem('googleMapsActivePreset');
        
        showNotification('Colonnes réinitialisées', 'success');
    }

    // Save/Load widths
    function initializeWidthSaving() {
        const saveBtn = document.getElementById('saveWidthsBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                saveWidthsToStorage();
                showNotification('Largeurs sauvegardées', 'success');
            });
        }
    }

    function saveWidthsToStorage() {
        const table = document.getElementById('resizableTable');
        if (!table) return;

        const widths = {};
        const cols = table.querySelectorAll('th.resizable');
        
        cols.forEach(col => {
            const index = col.dataset.index;
            widths[index] = col.style.width;
        });

        localStorage.setItem('googleMapsColumnWidths', JSON.stringify(widths));
    }

    function loadSavedWidths() {
        const table = document.getElementById('resizableTable');
        if (!table) return;

        const saved = localStorage.getItem('googleMapsColumnWidths');
        if (!saved) return;

        try {
            const widths = JSON.parse(saved);
            const cols = table.querySelectorAll('th.resizable');
            
            cols.forEach(col => {
                const index = col.dataset.index;
                if (widths[index] && widths[index] !== '') {
                    col.style.width = widths[index];
                    col.style.minWidth = widths[index];
                }
            });
        } catch (e) {
            console.error('Error loading column widths:', e);
        }
    }

    // Notification system
    function showNotification(message, type = 'info') {
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) {
            existing.remove();
        }

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icon = type === 'success' ? 'circle-check' : 
                     type === 'error' ? 'circle-exclamation' : 'info-circle';
        
        notification.innerHTML = `
            <i class="fa-solid fa-${icon}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Confirmation modal
    function showConfirmation(title, message, onConfirm) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <h3><i class="fa-solid fa-question-circle"></i> ${title}</h3>
                <p>${message}</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary" id="cancelBtn">Annuler</button>
                    <button class="btn btn-primary" id="confirmBtn">Confirmer</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        document.getElementById('cancelBtn').addEventListener('click', () => {
            modal.remove();
        });
        
        document.getElementById('confirmBtn').addEventListener('click', () => {
            modal.remove();
            onConfirm();
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Stats modal
    function showStatsModal(stats) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content stats-modal">
                <h3><i class="fa-solid fa-chart-simple"></i> Statistiques de scraping</h3>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">${stats.total || 0}</div>
                        <div class="stat-label">Total entreprises</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value">${stats.with_website || 0}</div>
                        <div class="stat-label">Avec site web</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value">${stats.scraped || 0}</div>
                        <div class="stat-label">Sites scrappés</div>
                    </div>
                    
                    <div class="stat-card highlight">
                        <div class="stat-value">${stats.pending || 0}</div>
                        <div class="stat-label">En attente</div>
                    </div>
                    
                    <div class="stat-card success">
                        <div class="stat-value">${stats.with_email || 0}</div>
                        <div class="stat-label">Emails trouvés</div>
                    </div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-label">
                        <span>Progression</span>
                        <span>${Math.round((stats.scraped / stats.with_website) * 100 || 0)}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${(stats.scraped / stats.with_website) * 100 || 0}%"></div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-primary" id="closeStatsBtn">Fermer</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        document.getElementById('closeStatsBtn').addEventListener('click', () => {
            modal.remove();
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Scraping buttons
    function initializeScrapingButtons() {
        const retryBtn = document.getElementById('retryScrapingBtn');
        if (retryBtn) {
            retryBtn.addEventListener('click', function() {
                showConfirmation(
                    'Relancer le scraping',
                    'Voulez-vous relancer le scraping pour tous les sites web non traités ?',
                    function() {
                        retryBtn.disabled = true;
                        retryBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Scraping en cours...';
                        
                        fetch('{{ route("client.google.retry-scraping") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`HTTP ${response.status}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showNotification(data.message, 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showNotification(data.message, 'error');
                                retryBtn.disabled = false;
                                retryBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Relancer scraping';
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            showNotification('Erreur lors du lancement du scraping', 'error');
                            retryBtn.disabled = false;
                            retryBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Relancer scraping';
                        });
                    }
                );
            });
        }

        const statsBtn = document.getElementById('scrapingStatsBtn');
        if (statsBtn) {
            statsBtn.addEventListener('click', function() {
                statsBtn.disabled = true;
                statsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Chargement...';
                
                fetch('{{ route("client.google.scraping-stats") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(stats => {
                    showStatsModal(stats);
                    statsBtn.disabled = false;
                    statsBtn.innerHTML = '<i class="fa-solid fa-chart-simple"></i> Statistiques';
                })
                .catch(error => {
                    console.error('Erreur stats:', error);
                    showNotification('Erreur lors du chargement des statistiques', 'error');
                    statsBtn.disabled = false;
                    statsBtn.innerHTML = '<i class="fa-solid fa-chart-simple"></i> Statistiques';
                });
            });
        }
    }

    // Export single button handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('export-btn') || e.target.closest('.export-btn')) {
            const btn = e.target.classList.contains('export-btn') ? e.target : e.target.closest('.export-btn');
            const url = btn.dataset.url;

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(res => {
                if (!res.ok) throw new Error('Erreur export');
                return res.json();
            })
            .then(data => {
                showNotification('Export réussi', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification("Erreur lors de l'export", 'error');
                btn.disabled = false;
                btn.innerHTML = 'Exporter';
            });
        }
    });
})();
</script>
@endsection