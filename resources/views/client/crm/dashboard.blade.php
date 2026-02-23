@extends('client.layouts.app')

@section('title', 'Dashboard CRM')

@section('content')
<div class="dashboard">
    <!-- En-tête -->
    <div class="dashboard__header">
        <div>
            <h1 class="dashboard__title">Dashboard</h1>
            <p class="dashboard__subtitle">Vue analytique de vos performances commerciales</p>
        </div>
        <div class="dashboard__date">
            {{ now()->format('d M Y') }}
        </div>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-card--total">
            <div class="kpi-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="kpi-card__content">
                <span class="kpi-card__label">Total Leads</span>
                <span class="kpi-card__value">{{ $totalLeads }}</span>
            </div>
        </div>

        <div class="kpi-card kpi-card--hot">
            <div class="kpi-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                </svg>
            </div>
            <div class="kpi-card__content">
                <span class="kpi-card__label">Leads Chauds</span>
                <span class="kpi-card__value">{{ $leadsChauds }}</span>
            </div>
        </div>

        <div class="kpi-card kpi-card--appointment">
            <div class="kpi-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="kpi-card__content">
                <span class="kpi-card__label">RDV Pris</span>
                <span class="kpi-card__value">{{ $rdvPris }}</span>
            </div>
        </div>

        <div class="kpi-card kpi-card--sold">
            <div class="kpi-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="kpi-card__content">
                <span class="kpi-card__label">Vendus</span>
                <span class="kpi-card__value">{{ $ventes }}</span>
            </div>
        </div>

        <div class="kpi-card kpi-card--conversion">
            <div class="kpi-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 1 1-9-9" stroke-linecap="round"></path>
                    <path d="M12 6v6l3 2"></path>
                </svg>
            </div>
            <div class="kpi-card__content">
                <span class="kpi-card__label">Taux Conversion</span>
                <span class="kpi-card__value">{{ $tauxConversion }}%</span>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-card__header">
                <h3 class="chart-card__title">Répartition des Leads</h3>
                <span class="chart-card__badge">Distribution</span>
            </div>
            <div class="chart-card__body">
                <canvas id="heatChart" class="chart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card__header">
                <h3 class="chart-card__title">Pipeline de Conversion</h3>
                <span class="chart-card__badge">Funnel</span>
            </div>
            <div class="chart-card__body">
                <canvas id="pipelineChart" class="chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques de Complétude Globales -->
    <div class="completion-section">
        <div class="completion-section__header">
            <div>
                <h3 class="completion-section__title">Complétude des Leads</h3>
                <p class="completion-section__subtitle">Moyenne globale: <strong>{{ $averageCompletion }}%</strong></p>
            </div>
            <span class="completion-section__badge">Qualité des données</span>
        </div>

        <div class="completion-grid">
            <!-- 0-20% -->
            <div class="completion-card">
                <div class="completion-card__header">
                    <span class="completion-card__label">0 - 20%</span>
                    <span class="completion-card__value">{{ $completionStats['0-20%'] }}</span>
                </div>
                <div class="completion-bar">
                    <div class="completion-bar__fill completion-bar__fill--low" 
                         style="width: {{ $totalLeads > 0 ? ($completionStats['0-20%'] / $totalLeads * 100) : 0 }}%">
                    </div>
                </div>
                <span class="completion-card__percentage">
                    {{ $totalLeads > 0 ? round($completionStats['0-20%'] / $totalLeads * 100) : 0 }}%
                </span>
            </div>

            <!-- 21-40% -->
            <div class="completion-card">
                <div class="completion-card__header">
                    <span class="completion-card__label">21 - 40%</span>
                    <span class="completion-card__value">{{ $completionStats['21-40%'] }}</span>
                </div>
                <div class="completion-bar">
                    <div class="completion-bar__fill completion-bar__fill--medium-low" 
                         style="width: {{ $totalLeads > 0 ? ($completionStats['21-40%'] / $totalLeads * 100) : 0 }}%">
                    </div>
                </div>
                <span class="completion-card__percentage">
                    {{ $totalLeads > 0 ? round($completionStats['21-40%'] / $totalLeads * 100) : 0 }}%
                </span>
            </div>

            <!-- 41-60% -->
            <div class="completion-card">
                <div class="completion-card__header">
                    <span class="completion-card__label">41 - 60%</span>
                    <span class="completion-card__value">{{ $completionStats['41-60%'] }}</span>
                </div>
                <div class="completion-bar">
                    <div class="completion-bar__fill completion-bar__fill--medium" 
                         style="width: {{ $totalLeads > 0 ? ($completionStats['41-60%'] / $totalLeads * 100) : 0 }}%">
                    </div>
                </div>
                <span class="completion-card__percentage">
                    {{ $totalLeads > 0 ? round($completionStats['41-60%'] / $totalLeads * 100) : 0 }}%
                </span>
            </div>

            <!-- 61-80% -->
            <div class="completion-card">
                <div class="completion-card__header">
                    <span class="completion-card__label">61 - 80%</span>
                    <span class="completion-card__value">{{ $completionStats['61-80%'] }}</span>
                </div>
                <div class="completion-bar">
                    <div class="completion-bar__fill completion-bar__fill--medium-high" 
                         style="width: {{ $totalLeads > 0 ? ($completionStats['61-80%'] / $totalLeads * 100) : 0 }}%">
                    </div>
                </div>
                <span class="completion-card__percentage">
                    {{ $totalLeads > 0 ? round($completionStats['61-80%'] / $totalLeads * 100) : 0 }}%
                </span>
            </div>

            <!-- 81-100% -->
            <div class="completion-card">
                <div class="completion-card__header">
                    <span class="completion-card__label">81 - 100%</span>
                    <span class="completion-card__value">{{ $completionStats['81-100%'] }}</span>
                </div>
                <div class="completion-bar">
                    <div class="completion-bar__fill completion-bar__fill--high" 
                         style="width: {{ $totalLeads > 0 ? ($completionStats['81-100%'] / $totalLeads * 100) : 0 }}%">
                    </div>
                </div>
                <span class="completion-card__percentage">
                    {{ $totalLeads > 0 ? round($completionStats['81-100%'] / $totalLeads * 100) : 0 }}%
                </span>
            </div>
        </div>
    </div>

    <!-- Tableau des performances par client avec répartition des pourcentages -->
    <div class="table-card">
        <div class="table-card__header">
            <div>
                <h3 class="table-card__title">Performance par utilisateur</h3>
                <p class="table-card__subtitle">Répartition des leads par niveau de complétude</p>
            </div>
            <span class="table-card__count">{{ count($clientsPerformance) }} utilisateurs</span>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead class="table__head">
                    <tr>
                        <th class="table__th">Utilisateur</th>
                        <th class="table__th">Rôle</th>
                        <th class="table__th">Total Leads</th>
                        <th class="table__th">Scrappings</th>
                        <th class="table__th" colspan="5">Répartition par complétude</th>
                    </tr>
                    <tr class="table__subhead">
                        <th colspan="3"></th>
                        <th></th>
                        <th class="table__th table__th--small">0-20%</th>
                        <th class="table__th table__th--small">21-40%</th>
                        <th class="table__th table__th--small">41-60%</th>
                        <th class="table__th table__th--small">61-80%</th>
                        <th class="table__th table__th--small">81-100%</th>
                    </tr>
                </thead>
                <tbody class="table__body">
                    @forelse($clientsPerformance as $client)
                    <tr class="table__row {{ $client['email'] == $sessionClient['email'] ? 'table__row--current' : '' }}">
                        <td class="table__td">
                            <div class="user-info">
                                <span class="user-info__name">{{ $client['name'] }}</span>
                                @if($client['email'] == $sessionClient['email'])
                                    <span class="user-info__badge">Vous</span>
                                @endif
                            </div>
                        </td>
                        <td class="table__td">
                            <span class="role-badge role-badge--{{ $client['role'] }}">
                                {{ ucfirst($client['role']) }}
                            </span>
                        </td>
                        <td class="table__td">
                            <span class="stat-number stat-number--total">{{ $client['leads_count'] }}</span>
                        </td>
                        <td class="table__td">
                            <span class="stat-number">{{ $client['scraping_count'] }}</span>
                        </td>
                        
                        <!-- 0-20% -->
                        <td class="table__td table__td--percentage">
                            <div class="percentage-cell">
                                <span class="percentage-cell__value">{{ $client['percentage_breakdown']['0-20%'] }}</span>
                                <div class="percentage-cell__bar">
                                    <div class="percentage-cell__fill percentage-cell__fill--low" 
                                         style="width: {{ $client['leads_count'] > 0 ? ($client['percentage_breakdown']['0-20%'] / $client['leads_count'] * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="percentage-cell__percent">
                                    {{ $client['leads_count'] > 0 ? round($client['percentage_breakdown']['0-20%'] / $client['leads_count'] * 100) : 0 }}%
                                </span>
                            </div>
                        </td>
                        
                        <!-- 21-40% -->
                        <td class="table__td table__td--percentage">
                            <div class="percentage-cell">
                                <span class="percentage-cell__value">{{ $client['percentage_breakdown']['21-40%'] }}</span>
                                <div class="percentage-cell__bar">
                                    <div class="percentage-cell__fill percentage-cell__fill--medium-low" 
                                         style="width: {{ $client['leads_count'] > 0 ? ($client['percentage_breakdown']['21-40%'] / $client['leads_count'] * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="percentage-cell__percent">
                                    {{ $client['leads_count'] > 0 ? round($client['percentage_breakdown']['21-40%'] / $client['leads_count'] * 100) : 0 }}%
                                </span>
                            </div>
                        </td>
                        
                        <!-- 41-60% -->
                        <td class="table__td table__td--percentage">
                            <div class="percentage-cell">
                                <span class="percentage-cell__value">{{ $client['percentage_breakdown']['41-60%'] }}</span>
                                <div class="percentage-cell__bar">
                                    <div class="percentage-cell__fill percentage-cell__fill--medium" 
                                         style="width: {{ $client['leads_count'] > 0 ? ($client['percentage_breakdown']['41-60%'] / $client['leads_count'] * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="percentage-cell__percent">
                                    {{ $client['leads_count'] > 0 ? round($client['percentage_breakdown']['41-60%'] / $client['leads_count'] * 100) : 0 }}%
                                </span>
                            </div>
                        </td>
                        
                        <!-- 61-80% -->
                        <td class="table__td table__td--percentage">
                            <div class="percentage-cell">
                                <span class="percentage-cell__value">{{ $client['percentage_breakdown']['61-80%'] }}</span>
                                <div class="percentage-cell__bar">
                                    <div class="percentage-cell__fill percentage-cell__fill--medium-high" 
                                         style="width: {{ $client['leads_count'] > 0 ? ($client['percentage_breakdown']['61-80%'] / $client['leads_count'] * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="percentage-cell__percent">
                                    {{ $client['leads_count'] > 0 ? round($client['percentage_breakdown']['61-80%'] / $client['leads_count'] * 100) : 0 }}%
                                </span>
                            </div>
                        </td>
                        
                        <!-- 81-100% -->
                        <td class="table__td table__td--percentage">
                            <div class="percentage-cell">
                                <span class="percentage-cell__value">{{ $client['percentage_breakdown']['81-100%'] }}</span>
                                <div class="percentage-cell__bar">
                                    <div class="percentage-cell__fill percentage-cell__fill--high" 
                                         style="width: {{ $client['leads_count'] > 0 ? ($client['percentage_breakdown']['81-100%'] / $client['leads_count'] * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="percentage-cell__percent">
                                    {{ $client['leads_count'] > 0 ? round($client['percentage_breakdown']['81-100%'] / $client['leads_count'] * 100) : 0 }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="table__empty">
                            <div class="empty-state">
                                <svg class="empty-state__icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <p class="empty-state__text">Aucun utilisateur trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration commune des graphiques
    Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#64748b';

    // Graphique en donut
    new Chart(document.getElementById('heatChart'), {
        type: 'doughnut',
        data: {
            labels: ['Froid', 'Tiède', 'Chaud', 'Mort'],
            datasets: [{
                data: [{{ $froid }}, {{ $tiede }}, {{ $chaud }}, {{ $mort }}],
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#1e293b'],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Graphique en barres
    new Chart(document.getElementById('pipelineChart'), {
        type: 'bar',
        data: {
            labels: ['Total Leads', 'RDV Pris', 'Vendus'],
            datasets: [{
                data: [{{ $totalLeads }}, {{ $rdvPris }}, {{ $ventes }}],
                backgroundColor: ['#6366f1', '#f59e0b', '#10b981'],
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });
});
</script>

<style>
/* Variables */
:root {
    --primary: #6366f1;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #0f172a;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-500: #64748b;
    --gray-700: #334155;
    --gray-900: #0f172a;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    --radius: 12px;
}

/* Layout principal */
.dashboard {
    padding: 2rem;
    background: var(--gray-50);
    min-height: 100vh;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* En-tête */
.dashboard__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.dashboard__title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1.2;
}

.dashboard__subtitle {
    color: var(--gray-500);
    font-size: 0.95rem;
    margin-top: 0.25rem;
}

.dashboard__date {
    background: var(--gray-900);
    color: white;
    padding: 0.5rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    letter-spacing: 0.3px;
    box-shadow: var(--shadow-md);
}

/* Grille KPI */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.kpi-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s, box-shadow 0.2s;
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.kpi-card__icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    color: var(--gray-700);
}

.kpi-card--total .kpi-card__icon { background: #e0e7ff; color: var(--primary); }
.kpi-card--hot .kpi-card__icon { background: #fee2e2; color: var(--danger); }
.kpi-card--appointment .kpi-card__icon { background: #fef3c7; color: var(--warning); }
.kpi-card--sold .kpi-card__icon { background: #d1fae5; color: var(--success); }
.kpi-card--conversion .kpi-card__icon { background: #e2e8f0; color: var(--gray-900); }

.kpi-card__content {
    flex: 1;
}

.kpi-card__label {
    display: block;
    font-size: 0.875rem;
    color: var(--gray-500);
    margin-bottom: 0.25rem;
}

.kpi-card__value {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1.2;
}

/* Grille graphiques */
.chart-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.chart-card__header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
}

.chart-card__badge {
    background: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700);
}

.chart-card__body {
    padding: 1.5rem;
    height: 280px;
    position: relative;
}

.chart {
    width: 100%;
    height: 100%;
}

/* Section Complétude */
.completion-section {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
    overflow: hidden;
}

.completion-section__header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.completion-section__title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.completion-section__subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.completion-section__subtitle strong {
    color: var(--gray-900);
    font-weight: 600;
}

.completion-section__badge {
    background: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700);
}

.completion-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.completion-card {
    background: var(--gray-50);
    border-radius: var(--radius);
    padding: 1rem;
}

.completion-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.completion-card__label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
}

.completion-card__value {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
}

.completion-bar {
    height: 6px;
    background: var(--gray-200);
    border-radius: 9999px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.completion-bar__fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.3s ease;
}

.completion-bar__fill--low { background: var(--danger); }
.completion-bar__fill--medium-low { background: var(--warning); }
.completion-bar__fill--medium { background: #3b82f6; }
.completion-bar__fill--medium-high { background: #8b5cf6; }
.completion-bar__fill--high { background: var(--success); }

.completion-card__percentage {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-500);
}

/* Tableau des performances */
.table-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-top: 2rem;
}

.table-card__header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.table-card__subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.table-card__count {
    background: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700);
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table__head {
    background: var(--gray-50);
}

.table__th {
    text-align: left;
    padding: 1rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-500);
    white-space: nowrap;
}

.table__th--small {
    font-size: 0.7rem;
    text-align: center;
    min-width: 80px;
}

.table__subhead .table__th {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    background: var(--gray-100);
}

.table__td {
    padding: 1rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: 0.875rem;
}

.table__td--percentage {
    padding: 0.75rem 0.5rem;
}

.table__row:hover {
    background: var(--gray-50);
}

.table__row--current {
    background: #e0e7ff;
}

.table__row--current:hover {
    background: #c7d2fe;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.user-info__name {
    font-weight: 500;
    color: var(--gray-900);
}

.user-info__badge {
    background: var(--primary);
    color: white;
    padding: 0.15rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
}

/* Role badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.role-badge--admin {
    background: #fef3c7;
    color: #92400e;
}

.role-badge--user {
    background: #e0e7ff;
    color: #3730a3;
}

/* Stat number */
.stat-number {
    font-weight: 600;
    color: var(--gray-900);
}

.stat-number--total {
    font-size: 1rem;
    color: var(--primary);
}

/* Percentage cell */
.percentage-cell {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 80px;
}

.percentage-cell__value {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
}

.percentage-cell__bar {
    height: 4px;
    background: var(--gray-200);
    border-radius: 9999px;
    overflow: hidden;
    width: 100%;
}

.percentage-cell__fill {
    height: 100%;
    border-radius: 9999px;
}

.percentage-cell__fill--low { background: var(--danger); }
.percentage-cell__fill--medium-low { background: var(--warning); }
.percentage-cell__fill--medium { background: #3b82f6; }
.percentage-cell__fill--medium-high { background: #8b5cf6; }
.percentage-cell__fill--high { background: var(--success); }

.percentage-cell__percent {
    font-size: 0.7rem;
    color: var(--gray-500);
    font-weight: 500;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-state__icon {
    color: var(--gray-200);
    margin-bottom: 1rem;
}

.empty-state__text {
    color: var(--gray-500);
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard {
        padding: 1rem;
    }

    .dashboard__header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
    }

    .kpi-card {
        padding: 1rem;
    }

    .kpi-card__icon {
        width: 40px;
        height: 40px;
    }

    .kpi-card__value {
        font-size: 1.5rem;
    }

    .chart-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .chart-card__body {
        height: 240px;
        padding: 1rem;
    }

    .completion-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        padding: 1rem;
    }

    .table__td {
        padding: 0.75rem 0.5rem;
    }

    .percentage-cell {
        min-width: 60px;
    }
}

@media (max-width: 480px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }

    .kpi-card {
        max-width: 100%;
    }

    .completion-section__header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .table-card__header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
@endsection