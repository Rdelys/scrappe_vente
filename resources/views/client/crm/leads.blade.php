@extends('client.layouts.app')

@section('title', 'Mes Leads')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-left">
            <h1>Mes Leads</h1>
            <p>Liste des prospects importés depuis Google & Web.</p>
        </div>
        <div class="header-actions">
            <button class="btn-excel"
                onclick="window.location='{{ route('client.crm.leads.export', request()->query()) }}'">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </button>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i>
                Ajouter un lead
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $totalLeads }}</span>
            <span class="stat-label">Total leads</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $relanceCount }}</span>
            <span class="stat-label">À relancer</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $rdvPrisCount }}</span>
            <span class="stat-label">RDV pris</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $clotureCount }}</span>
            <span class="stat-label">Clôturés</span>
        </div>
    </div>

</div>


   <form method="GET" action="{{ route('client.crm.leads') }}" id="filterForm">
    <div class="filters-bar">

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text"
                   name="search"
                   id="searchInput"
                   value="{{ request('search') }}"
                   placeholder="Rechercher un lead...">
        </div>

        <div class="filter-actions">

            <select name="status" class="filter-select auto-submit">
                <option value="">Tous les statuts</option>
                <option value="En cours" {{ request('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                <option value="À relancer plus tard" {{ request('status') == 'À relancer plus tard' ? 'selected' : '' }}>À relancer</option>
                <option value="RDV proposé" {{ request('status') == 'RDV proposé' ? 'selected' : '' }}>RDV proposé</option>
                <option value="RDV pris" {{ request('status') == 'RDV pris' ? 'selected' : '' }}>RDV pris</option>
                <option value="Refus" {{ request('status') == 'Refus' ? 'selected' : '' }}>Refus</option>
                <option value="Clôturé" {{ request('status') == 'Clôturé' ? 'selected' : '' }}>Clôturé</option>
            </select>

            <select name="chaleur" class="filter-select auto-submit">
                <option value="">Toute chaleur</option>
                <option value="Froid" {{ request('chaleur') == 'Froid' ? 'selected' : '' }}>Froid</option>
                <option value="Tiède" {{ request('chaleur') == 'Tiède' ? 'selected' : '' }}>Tiède</option>
                <option value="Chaud" {{ request('chaleur') == 'Chaud' ? 'selected' : '' }}>Chaud</option>
            </select>

            {{-- 🔥 Filtre Date --}}
            <input type="date"
                   name="date_from"
                   class="filter-select auto-submit"
                   value="{{ request('date_from') }}">

            <input type="date"
                   name="date_to"
                   class="filter-select auto-submit"
                   value="{{ request('date_to') }}">

        </div>
    </div>
</form>


    <!-- Table responsive -->
    <div class="table-responsive">
    <table class="leads-table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" class="select-all">
                </th>
                <th>Nom du scrapping
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nom de la campagne ou de la source d’import du lead.
                        </span>
                    </span>
                </th>
                <th>Prénom Nom
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nom complet du prospect tel qu’importé ou renseigné manuellement.
                        </span>
                    </span>
                </th>
                <th>Commentaire
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Notes internes sur le prospect (échanges, contexte, informations utiles).
                        </span>
                    </span>
                </th>
                <th>
                    Chaleur
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Niveau d’intérêt du prospect : Froid, Tiède ou Chaud.
                        </span>
                    </span>
                </th>                
                <th>Status 
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            État actuel du lead dans le processus commercial.
                        </span>
                    </span>
                </th>
                <th>Status Relance
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Étape actuelle dans la séquence de relance (emails, réseaux, WhatsApp…).
                        </span>
                    </span>
                </th>
                <th>Lead à jour de ses infos
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                        Chaque ligne des prospects/lead doit être complétée intégralement, en particulier tous les URL, afin d’identifier facilement le client.
                        Indiquez 100 % lorsque toutes les informations sont correctement renseignées.                        
                        </span>
                    </span>
                </th>
                <th>Date de relance
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Date de la dernière relance effectuée.
                        </span>
                    </span>
                </th>
                <th>Devis
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Statut du devis envoyé au prospect.
                        </span>
                    </span>
                </th>
                <th>LinkedIn
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Est ce que vous êtes connecté sur Linkedin avec le Lead ?.
                        </span>
                    </span>
                </th>
                <th>Téléphone
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Numero du Lead ?.
                        </span>
                    </span>
                </th>
                <th>MP Insta
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nombre de messages privés envoyés au prospect sur Instagram.
                        </span>
                    </span>
                </th>
                <th>Follow Insta
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Indique si le compte Instagram du prospect a été suivi.
                        </span>
                    </span>
                </th>
                <th>Com Insta
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nombre de commentaires laissés sur les publications Instagram du prospect.
                        </span>
                    </span>
                </th>
                <th>Formulaire
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Indique si un message a été envoyé via le formulaire du site et son statut.
                        </span>
                    </span>
                </th>
                <th>Messenger 
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Statut du contact effectué via Messenger (envoyé, réponse reçue ou indisponible).
                        </span>
                    </span>
                </th>
                <th>Nom entreprise
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nom de l’entreprise ou de la société associée au prospect.
                        </span>
                    </span>
                </th>
                <th>Fonction du prospect
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Intitulé du poste du lead (nous recherchons le gérant : gérant, gérante, directeur, etc.).
                        </span>
                    </span>
                </th>
                <th>Email
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Adresse email professionnelle du prospect.
                        </span>
                    </span>
                </th>
                <th>Tel Fixe
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Numéro de téléphone fixe de l’entreprise.
                        </span>
                    </span>
                </th>
                <th>Portable
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Numéro de téléphone mobile du prospect.
                        </span>
                    </span>
                </th>
                <th>URL LinkedIn
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Lien vers le profil LinkedIn du prospect.
                        </span>
                    </span>
                </th>
                <th>URL Maps
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Lien vers la fiche Google Maps de l’entreprise.
                        </span>
                    </span>
                </th>
                <th>Site web
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Site internet officiel de l’entreprise.
                        </span>
                    </span>
                </th>
                <th>Compte Insta
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Nom d’utilisateur du compte Instagram du prospect.
                        </span>
                    </span>
                </th>
                <th>Actions
                    <span class="info-tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">
                            Modifier, supprimer ou exporter ce lead.
                        </span>
                    </span>
                </th>
            </tr>
        </thead>

        <tbody>
        @forelse($leads as $lead)

            <tr onclick='openEditModal(@json($lead))' style="cursor:pointer;">

                <td onclick="event.stopPropagation()">
                    <input type="checkbox" class="select-row">
                </td>

                <td>{{ $lead->nom_global ?? '-' }}</td>

                <td>
                    <div class="lead-info">
                        <strong>{{ $lead->prenom_nom }}</strong>
                        <small>ID: {{ $lead->id }}</small>
                    </div>
                </td>

                <td>{{ $lead->commentaire ?? '-' }}</td>

                <td>
                    <span class="badge {{ strtolower($lead->chaleur) }}">
                        {{ $lead->chaleur ?? '-' }}
                    </span>
                </td>

                <td>
                    <span class="badge">
                        {{ $lead->status ?? '-' }}
                    </span>
                </td>

                <td>
                    <span class="badge relance-status">
                        {{ $lead->status_relance ?? '-' }}
                    </span>
                </td>

                <td>
                    <span class="badge pourcent">
                        {{ $lead->enfants_percent ?? '-' }}
                    </span>
                </td>

                <td>
                    {{ $lead->date_statut ? \Carbon\Carbon::parse($lead->date_statut)->format('d/m/Y') : '-' }}
                </td>

                <td>
                    <span class="badge">
                        {{ $lead->devis ?? '-' }}
                    </span>
                </td>

                <td><span class="badge">{{ $lead->linkedin_status ?? '-' }}</span></td>
                <td><span class="badge">{{ $lead->appel_tel ?? '-' }}</span></td>
                <td><span class="badge">{{ $lead->mp_instagram ?? '-' }}</span></td>

                <td class="checkbox-cell" onclick="event.stopPropagation()">
                    <input type="checkbox" disabled {{ $lead->follow_insta ? 'checked' : '' }}>
                </td>

                <td><span class="badge">{{ $lead->com_instagram ?? '-' }}</span></td>
                <td><span class="badge">{{ $lead->formulaire_site ?? '-' }}</span></td>
                <td><span class="badge">{{ $lead->messenger ?? '-' }}</span></td>

                <td>{{ $lead->entreprise ?? '-' }}</td>
                <td>{{ $lead->fonction ?? '-' }}</td>
                <td>{{ $lead->email ?? '-' }}</td>
                <td>{{ $lead->tel_fixe ?? '-' }}</td>
                <td>{{ $lead->portable ?? '-' }}</td>

                <td class="url-cell">
                    @if($lead->url_linkedin)
                        <a href="{{ $lead->url_linkedin }}" target="_blank" class="url-link" onclick="event.stopPropagation()">
                            Voir
                        </a>
                    @else -
                    @endif
                </td>

                <td class="url-cell">
                    @if($lead->url_maps)
                        <a href="{{ $lead->url_maps }}" target="_blank" class="url-link" onclick="event.stopPropagation()">
                            Voir
                        </a>
                    @else -
                    @endif
                </td>

                <td class="url-cell">
                    @if($lead->url_site)
                        <a href="{{ $lead->url_site }}" target="_blank" class="url-link" onclick="event.stopPropagation()">
                            Voir
                        </a>
                    @else -
                    @endif
                </td>

                <td>{{ $lead->compte_insta ?? '-' }}</td>

                <td class="actions" onclick="event.stopPropagation()">

                    <button class="btn-icon"
                        onclick='openEditModal(@json($lead))'
                        title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn-icon"
                        onclick="openDeleteModal({{ $lead->id }})"
                        title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn-icon"
                        onclick="window.location='{{ route('client.crm.leads.export.single', $lead->id) }}'"
                        title="Exporter">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="29" style="text-align:center; padding:30px;">
                    Aucun lead trouvé.
                </td>
            </tr>

        @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination">

    <div class="pagination-info">
        Affichage de {{ $leads->firstItem() ?? 0 }}
        à {{ $leads->lastItem() ?? 0 }}
        sur {{ $leads->total() }} leads
    </div>

    <div class="pagination-controls">

        {{-- Bouton précédent --}}
        <button class="btn-pagination"
            {{ $leads->onFirstPage() ? 'disabled' : '' }}
            onclick="window.location='{{ $leads->previousPageUrl() }}'">
            <i class="fas fa-chevron-left"></i>
        </button>

        {{-- Pages --}}
        @for ($i = 1; $i <= $leads->lastPage(); $i++)

            @if ($i == $leads->currentPage())
                <button class="btn-pagination active">
                    {{ $i }}
                </button>
            @elseif (
                $i == 1 ||
                $i == $leads->lastPage() ||
                abs($i - $leads->currentPage()) <= 2
            )
                <button class="btn-pagination"
                    onclick="window.location='{{ $leads->url($i) }}'">
                    {{ $i }}
                </button>
            @elseif (
                abs($i - $leads->currentPage()) == 3
            )
                <span>...</span>
            @endif

        @endfor

        {{-- Bouton suivant --}}
        <button class="btn-pagination"
            {{ !$leads->hasMorePages() ? 'disabled' : '' }}
            onclick="window.location='{{ $leads->nextPageUrl() }}'">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>

</div>


<!-- Modal Ajouter/Modifier -->
<div id="leadModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Ajouter un lead</h2>
            <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="leadForm" method="POST" action="{{ route('client.crm.leads.store') }}">
                @csrf

                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="lead_id" id="leadId">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nom Global</label>
                        <input type="text" name="nom_global">
                    </div>

                    <div class="form-group">
                        <label>Prénom Nom *</label>
                        <input type="text" name="prenom_nom" required>
                    </div>

                    <div class="form-group">
                        <label>Commentaire</label>
                        <textarea name="commentaire" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Chaleur</label>
                        <select name="chaleur">
                            <option value="Froid">Froid</option>
                            <option value="Tiède">Tiède</option>
                            <option value="Chaud">Chaud</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option>En cours</option>
                            <option>À relancer plus tard</option>
                            <option>Répondu – à traiter</option>
                            <option>RDV proposé</option>
                            <option>RDV pris</option>
                            <option>Refus</option>
                            <option>Clôturé</option>
                            <option>Vendu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Relance</label>
                        <select name="status_relance">
                            <option>J0 – Email envoyé</option> 
                            <option>J0 – Réseaux envoyé</option> 
                            <option>J+1 – Relance réseaux</option> 
                            <option>J+2 – Email relance</option> 
                            <option>J+3 – WhatsApp/SMS 1</option> 
                            <option>J+4 – Email angle problème</option> 
                            <option>J+5 – Réseaux angle problème</option> 
                            <option>J+7 – Email proposition RDV</option> 
                            <option>J+8 – WhatsApp/SMS 2</option> 
                            <option>J+10 – Email final</option> 
                            <option>J+12 – Réseaux final</option> 
                            <option>Répondu – à traiter</option> 
                            <option>RDV proposé</option> 
                            <option>RDV pris</option> 
                            <option>Refus</option> 
                            <option>À relancer plus tard</option> 
                            <option>Clôturé</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lead à jour de ses infos</label>
                        <select name="enfants_percent">
                            <option>0%</option>
                            <option>40%</option>
                            <option>60%</option>
                            <option>80%</option>
                            <option>100%</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date du relance</label>
                        <input type="date" name="date_statut">
                    </div>

                    <div class="form-group">
                        <label>LinkedIn</label>
                        <select name="linkedin_status">
                            <option>Validé</option>
                            <option>Non</option>
                            <option>En attente de réponse</option>
                            <option>Refusé</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Appel Téléphonique</label>
                        <select name="appel_tel">
                            <option>Message 1</option> 
                            <option>Message 2</option> 
                            <option>Message 3</option> 
                            <option>Message 4</option> 
                            <option>Message 5</option> 
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>MP Instagram</label>
                        <select name="mp_instagram">
                            <option>MP 1</option> 
                            <option>MP 2</option> 
                            <option>MP 3</option> 
                            <option>MP 4</option> 
                            <option>MP 5</option> 
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Follow Insta</label>
                        <input type="checkbox" name="follow_insta" value="1">
                    </div>

                    <div class="form-group">
                        <label>Com Insta</label>
                        <select name="com_instagram">
                            <option>Com 1</option> 
                            <option>Com 2</option> 
                            <option>Com 3</option> 
                            <option>Com 4</option> 
                            <option>Com 5</option> 
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Formulaire</label>
                        <select name="formulaire_site">
                            <option>Oui, attente réponse</option> 
                            <option>Oui, avec réponse reçu</option> 
                            <option>Non, Pas de Formulaire</option> 
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Messenger</label>
                        <select name="messenger">
                            <option>Oui, attente réponse</option> 
                            <option>Oui, avec réponse reçu</option> 
                            <option>Non, Pas de compte</option> 
                            <option>Mort</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Entreprise</label>
                        <input type="text" name="entreprise">
                    </div>

                    <div class="form-group">
                        <label>Fonction</label>
                        <input type="text" name="fonction">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>

                    <div class="form-group">
                        <label>Tel Fixe</label>
                        <input type="text" name="tel_fixe">
                    </div>

                    <div class="form-group">
                        <label>Portable</label>
                        <input type="text" name="portable">
                    </div>

                    <div class="form-group">
                        <label>URL LinkedIn</label>
                        <input type="text" name="url_linkedin">
                    </div>

                    <div class="form-group">
                        <label>URL Maps</label>
                        <input type="text" name="url_maps">
                    </div>

                    <div class="form-group">
                        <label>URL Site</label>
                        <input type="text" name="url_site">
                    </div>

                    <div class="form-group">
                        <label>Compte Insta</label>
                        <input type="text" name="compte_insta">
                    </div>

                    <div class="form-group">
                        <label>Devis</label>
                        <select name="devis">
                            <option>Pas encore</option>
                            <option>A faire</option>
                            <option>Envoyé</option>
                            <option>Validé</option>
                            <option>Perdu</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Modal Suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Confirmer la suppression</h2>
            <button type="button" class="close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>

        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer ce lead ?</p>
        </div>

        <div class="modal-footer">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <button type="submit" class="btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>


<style>
    /* Import Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f0f4f8;
    }

    /* Premium Card Style */
    .card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px -12px rgba(0, 20, 30, 0.15), 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 28px;
        margin: 20px;
        border: 1px solid rgba(226, 232, 240, 0.6);
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 30px 50px -12px rgba(0, 20, 30, 0.2);
    }

    /* Header Styles */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 28px;
    }

    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #1a2639, #2d3b55);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 4px 0;
        letter-spacing: -0.5px;
    }

    .header-left p {
        color: #5f6b7a;
        font-size: 15px;
        margin: 0;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Button Styles */
    .btn-excel {
        background: linear-gradient(135deg, #0e6b3e, #1e8a4f);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(14, 107, 62, 0.2);
    }

    .btn-excel:hover {
        background: linear-gradient(135deg, #0a5330, #15733f);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(14, 107, 62, 0.3);
    }

    .btn-add {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f8fafc, #f1f4f9);
        border-radius: 20px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid rgba(203, 213, 225, 0.4);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
    .stat-icon.green { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; }
    .stat-icon.yellow { background: linear-gradient(135deg, #fed7aa, #fde68a); color: #92400e; }
    .stat-icon.purple { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8; }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 13px;
        color: #5f6b7a;
        font-weight: 500;
    }

    /* Filters Bar */
    .filters-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 10px 16px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        min-width: 280px;
    }

    .search-box i {
        color: #94a3b8;
        font-size: 14px;
    }

    .search-box input {
        border: none;
        outline: none;
        font-size: 14px;
        width: 100%;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        font-size: 14px;
        color: #1e293b;
        cursor: pointer;
        outline: none;
    }

    .btn-filter {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #1e293b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #f1f5f9;
    }

    /* Premium Table */
    .table-responsive {
        overflow-x: auto;
        margin: 0 -28px;
        padding: 0 28px;
        border-radius: 20px;
    }

    .leads-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 2300px;
        font-size: 13px;
    }

    .leads-table th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        color: #1e293b;
        font-size: 12px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #d1d5db;
        white-space: nowrap;
    }

    .leads-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        white-space: nowrap;
        vertical-align: middle;
    }

    .leads-table tbody tr {
        transition: all 0.2s;
        cursor: pointer;
    }

    .leads-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    /* Lead Info */
    .lead-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .lead-info strong {
        color: #0f172a;
        font-weight: 600;
    }

    .lead-info small {
        color: #64748b;
        font-size: 11px;
    }

    /* Checkbox */
    .select-all, .select-row {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }

    .checkbox-cell {
        text-align: center;
    }

    /* URL Links */
    .url-cell {
        max-width: 150px;
    }

    .url-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 12px;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .url-link:hover {
        text-decoration: underline;
        color: #1d4ed8;
    }

    /* Badges améliorés */
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .badge.chaud { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fecaca; }
    .badge.tiede { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }
    .badge.froid { background: linear-gradient(135deg, #e2e3e5, #d3d6d8); color: #383d41; border: 1px solid #d3d6d8; }
    .badge.encours { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }
    .badge.relance { background: linear-gradient(135deg, #fff3cd, #ffe69c); color: #856404; border: 1px solid #ffe69c; }
    .badge.relance-status { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8; border: 1px solid #d8b4fe; }
    .badge.rdv-badge { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.pourcent { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.attente { background: linear-gradient(135deg, #cff4fc, #b6effb); color: #055160; border: 1px solid #b6effb; }
    .badge.valide { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.succes { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.envoye { background: linear-gradient(135deg, #cfe2ff, #b6d4fe); color: #052c65; border: 1px solid #b6d4fe; }
    .badge.rdv-pris { background: linear-gradient(135deg, #d1e7dd, #badbcc); color: #0f5132; border: 1px solid #badbcc; }
    .badge.refus { background: linear-gradient(135deg, #f8d7da, #f5c2c7); color: #842029; border: 1px solid #f5c2c7; }

    /* Actions */
    .actions {
        display: flex;
        gap: 6px;
    }

    .btn-icon {
        background: white;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        padding: 8px;
        border-radius: 10px;
        color: #5f6b7a;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-icon:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: #5f6b7a;
        font-size: 14px;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-pagination {
        min-width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #334155;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-pagination:hover:not(:disabled) {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .btn-pagination.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .btn-pagination:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Modal styles améliorés */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        overflow-y: auto;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        border-radius: 28px;
        max-width: 900px;
        margin: 20px auto;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .modal-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 28px 28px 0 0;
    }

    .modal-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .close-btn {
        background: white;
        border: 1px solid #e2e8f0;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        font-size: 20px;
        cursor: pointer;
        color: #5f6b7a;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .close-btn:hover {
        background: #f1f5f9;
        color: #ef4444;
        border-color: #fecaca;
    }

    .modal-body {
        padding: 28px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .form-group input:hover,
    .form-group select:hover,
    .form-group textarea:hover {
        border-color: #94a3b8;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .modal-footer {
        padding: 24px 28px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        background: #f8fafc;
        border-radius: 0 0 28px 28px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        padding: 12px 24px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #334155;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .card {
            padding: 20px;
            margin: 12px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            flex-direction: column;
        }

        .btn-excel, .btn-add {
            width: 100%;
            justify-content: center;
        }

        .filters-bar {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
        }

        .modal-content {
            margin: 10px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 16px;
            margin: 8px;
            border-radius: 18px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            width: 100%;
        }

        .pagination-controls {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    /* Tooltip colonne version simple bas */
.info-tooltip {
    position: relative;
    display: inline-block;
    margin-left: 4px;
    cursor: pointer;
    color: #94a3b8;
}

.info-tooltip i {
    font-size: 11px;
}

/* Tooltip */
.info-tooltip .tooltip-text {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    top: 125%; /* 🔥 en bas */
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: #fff;
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 11px;
    white-space: nowrap;
    transition: opacity 0.15s ease;
    z-index: 999;
}

/* Petite flèche vers le haut */
.info-tooltip .tooltip-text::after {
    content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 4px;
    border-style: solid;
    border-color: transparent transparent #1e293b transparent;
}

.info-tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* Container pour tableau avec colonnes fixes */
.table-responsive {
    position: relative;
    overflow-x: auto;
    margin: 0 -28px;
    padding: 0 28px;
    border-radius: 20px;
    max-width: 100%;
}

/* Style pour tableau avec colonnes fixes */
.leads-table {
    width: 100%;
    border-collapse: collapse; /* Changé de separate à collapse */
    min-width: 2300px;
    font-size: 13px;
    table-layout: auto; /* ou fixed selon vos besoins */
}

/* ===== COLONNES FIXES ===== */
/* Première colonne (Checkbox) */
.leads-table th:first-child,
.leads-table td:first-child {
    position: sticky;
    left: 0;
    width: 50px;
    min-width: 50px;
    max-width: 50px;
    background: white;
    z-index: 30;
    box-sizing: border-box;
}

/* Deuxième colonne (Nom du scrapping) */
.leads-table th:nth-child(2),
.leads-table td:nth-child(2) {
    position: sticky;
    left: 50px;
    width: 180px;
    min-width: 180px;
    max-width: 180px;
    background: white;
    z-index: 25;
    box-sizing: border-box;
}

/* Troisième colonne (Prénom Nom) - Forcer la largeur */
.leads-table th:nth-child(3),
.leads-table td:nth-child(3) {
    position: sticky;
    left: 230px; /* 50px + 180px */
    width: 510px !important;
    min-width: 510px !important;
    max-width: 510px !important;
    background: white;
    z-index: 20;
    box-sizing: border-box;
}

/* Styles spécifiques pour les en-têtes des colonnes fixes */
.leads-table th:first-child,
.leads-table th:nth-child(2),
.leads-table th:nth-child(3) {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    z-index: 40;
}

/* Assurer que le dégradé de fond s'applique correctement */
.leads-table th:first-child {
    z-index: 45;
}

.leads-table th:nth-child(2) {
    z-index: 44;
}

.leads-table th:nth-child(3) {
    z-index: 43;
}

/* Ombre portée subtile */
.leads-table th:first-child::after,
.leads-table td:first-child::after,
.leads-table th:nth-child(2)::after,
.leads-table td:nth-child(2)::after,
.leads-table th:nth-child(3)::after,
.leads-table td:nth-child(3)::after {
    content: '';
    position: absolute;
    top: 0;
    right: -2px;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to right, rgba(0,0,0,0.02), transparent);
    pointer-events: none;
}

/* Gestion du contenu dans la troisième colonne */
.leads-table td:nth-child(3) .lead-info {
    width: 100%;
    max-width: 510px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.leads-table td:nth-child(3) .lead-info strong {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 14px;
}

.leads-table td:nth-child(3) .lead-info small {
    font-size: 11px;
    color: #64748b;
}

/* Solution alternative avec une classe spécifique pour les cellules de la 3ème colonne */
.leads-table td:nth-child(3) {
    white-space: nowrap;
}

/* Amélioration du survol */
.leads-table tbody tr:hover td:first-child,
.leads-table tbody tr:hover td:nth-child(2),
.leads-table tbody tr:hover td:nth-child(3) {
    background: #f8fafc;
}

/* Ajustement des tooltips */
.leads-table th:nth-child(2) .info-tooltip,
.leads-table th:nth-child(3) .info-tooltip {
    position: relative;
    z-index: 50;
}

/* Optionnel : Ajustement pour mobile */
@media (max-width: 768px) {
    .leads-table th:nth-child(2),
    .leads-table td:nth-child(2) {
        width: 140px;
        min-width: 140px;
        max-width: 140px;
    }
    
    .leads-table th:nth-child(3),
    .leads-table td:nth-child(3) {
        left: 190px;
        width: 250px !important;
        min-width: 250px !important;
        max-width: 250px !important;
    }
    
    .leads-table td:nth-child(3) .lead-info {
        max-width: 230px;
    }
}

</style>
<script>

    /*
    |--------------------------------------------------------------------------
    | VARIABLES GLOBALES
    |--------------------------------------------------------------------------
    */
    let currentLeadId = null;

    const leadModal = document.getElementById('leadModal');
    const deleteModal = document.getElementById('deleteModal');
    const leadForm = document.getElementById('leadForm');
    const deleteForm = document.getElementById('deleteForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');

    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODAL AJOUT
    |--------------------------------------------------------------------------
    */
    function openAddModal() {

        currentLeadId = null;

        modalTitle.textContent = "Ajouter un lead";

        leadForm.action = "{{ route('client.crm.leads.store') }}";
        formMethod.value = "POST";

        leadForm.reset();

        leadModal.style.display = "block";
    }

    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODAL EDIT
    |--------------------------------------------------------------------------
    */
    function openEditModal(lead) {

        currentLeadId = lead.id;

        modalTitle.textContent = "Modifier le lead";

        leadForm.action = "/crm/leads/" + lead.id;
        formMethod.value = "PUT";

        // Remplissage automatique des champs
        Object.keys(lead).forEach(function (key) {

            const field = leadForm.querySelector(`[name="${key}"]`);

            if (field) {

                if (field.type === "checkbox") {
                    field.checked = lead[key] ? true : false;
                } else {
                    field.value = lead[key] ?? '';
                }

            }

        });

        leadModal.style.display = "block";
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER MODAL
    |--------------------------------------------------------------------------
    */
    function closeModal() {
        leadModal.style.display = "none";
    }

    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODAL SUPPRESSION
    |--------------------------------------------------------------------------
    */
    function openDeleteModal(leadId) {

        deleteForm.action = "/crm/leads/" + leadId;

        deleteModal.style.display = "block";
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER MODAL SUPPRESSION
    |--------------------------------------------------------------------------
    */
    function closeDeleteModal() {
        deleteModal.style.display = "none";
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER EN CLIQUANT A L'EXTERIEUR
    |--------------------------------------------------------------------------
    */
    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            closeModal();
            closeDeleteModal();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | SELECT ALL CHECKBOX
    |--------------------------------------------------------------------------
    */
    document.addEventListener("DOMContentLoaded", function() {

        const selectAll = document.querySelector(".select-all");

        if (selectAll) {
            selectAll.addEventListener("change", function(e) {

                document.querySelectorAll(".select-row")
                    .forEach(cb => cb.checked = e.target.checked);

            });
        }

    });

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("filterForm");

    // Auto submit pour selects + dates
    document.querySelectorAll(".auto-submit").forEach(element => {
        element.addEventListener("change", function () {
            form.submit();
        });
    });

    // 🔥 Recherche avec délai (anti reload à chaque lettre)
    const searchInput = document.getElementById("searchInput");
    let timeout = null;

    searchInput.addEventListener("keyup", function () {

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            form.submit();
        }, 500); // 500ms delay

    });

});
</script>

@endsection