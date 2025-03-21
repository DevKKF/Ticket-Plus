<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangerPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\APIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//SITE
Route::get('/gestion-des-sites/ajouter-un-site', [SiteController::class, 'AjouterSite'])->name('ajouter_site');
Route::post('/gestion-des-sites/save-site', [SiteController::class, 'SaveSite'])->name('save_site');
Route::get('/gestion-des-sites/liste-des-sites', [SiteController::class, 'ListeSite'])->name('liste_site');
Route::get('/gestion-des-sites/details-site/{site_id}-{titre}', [SiteController::class, 'DetailsSite'])->name('details_site');
Route::get('/gestion-des-sites/nouveau-ticket-site/{site_id}-{titre}', [SiteController::class, 'NouveauTicketSite'])->name('nouveau_ticket_site');
Route::post('/gestion-des-sites/nouveau-ticket-site/{site_id}', [SiteController::class, 'SaveNouveauTicketSite'])->name('save_nouveau_ticket_site');
Route::get('/gestion-des-sites/modifier-site/{site_id}', [SiteController::class, 'ModifierSite'])->name('modifier_site');
Route::post('/gestion-des-sites/modifier-site/{site_id}', [SiteController::class, 'SaveModifierSite'])->name('save_modifier_site');
Route::post('save_importer_site', [SiteController::class, 'SaveImporterSite'])->name('save_importer_site');
Route::post('supprimer_site', [SiteController::class, 'SupprimerSite'])->name('supprimer_site');
Route::get('/sites/export/csv', [SiteController::class, 'exporterCSV'])->name('sites.export.csv');
Route::get('/sites/export/excel', [SiteController::class, 'exporterExcel'])->name('sites.export.excel');
Route::get('/sites/export/pdf', [SiteController::class, 'exporterPDF'])->name('sites.export.pdf');

//TICKETS
Route::get('/gestion-des-tickets/ajouter-un-ticket', [TicketController::class, 'AjouterTicket'])->name('ajouter_ticket');
Route::post('/gestion-des-tickets/save-ticket', [TicketController::class, 'SaveTicket'])->name('save_ticket');
Route::get('/gestion-des-tickets/liste-des-tickets', [TicketController::class, 'ListeTicket'])->name('liste_ticket');
Route::get('/gestion-des-tickets/ticket-pm', [TicketController::class, 'TicketPM'])->name('ticket_pm');
Route::get('/gestion-des-tickets/ticket-cm', [TicketController::class, 'TicketCM'])->name('ticket_cm');
Route::get('/gestion-des-tickets/details-ticket/{ticket_id}-{titre}', [TicketController::class, 'DetailsTicket'])->name('details_ticket');
Route::get('/gestion-des-tickets/modifier-ticket/{ticket_id}', [TicketController::class, 'ModifierTicket'])->name('modifier_ticket');
Route::post('/gestion-des-tickets/modifier-ticket/{ticket_id}', [TicketController::class, 'SaveModifierTicket'])->name('save_modifier_ticket');
Route::post('supprimer_ticket', [TicketController::class, 'SupprimerTicket'])->name('supprimer_ticket');
Route::get('/tickets/export/csv', [TicketController::class, 'exporterCSV'])->name('tickets.export.csv');
Route::get('/tickets/export/excel', [TicketController::class, 'exporterExcel'])->name('tickets.export.excel');
Route::get('/tickets/export/pdf', [TicketController::class, 'exporterPDF'])->name('tickets.export.pdf');

//UTILISATEUR
Route::get('/gestion-des-utilisateurs/ajouter-un-utilisateur', [UtilisateurController::class, 'AjouterUtilisateur'])->name('ajouter_utilisateur');
Route::post('/gestion-des-utilisateurs/save-utilisateur', [UtilisateurController::class, 'SaveUtilisateur'])->name('save_utilisateur');
Route::get('/gestion-des-utilisateurs/liste-des-utilisateurs', [UtilisateurController::class, 'ListeUtilisateur'])->name('liste_utilisateur');
Route::get('/gestion-des-utilisateurs/details-utilisateur/{id}-{titre}', [UtilisateurController::class, 'DetailsUtilisateur'])->name('details_utilisateur');
Route::get('/gestion-des-utilisateurs/sites-geres/{id}-{titre}', [UtilisateurController::class, 'SiteGeresUtilisateur'])->name('sites_geres');
Route::get('/gestion-des-utilisateurs/tickets-enregistres/{id}-{titre}', [UtilisateurController::class, 'TicketsEnregistresUtilisateur'])->name('tickets_enregistres');
Route::get('/gestion-des-utilisateurs/modifier-utilisateur/{id}', [UtilisateurController::class, 'ModifierUtilisateur'])->name('modifier_utilisateur');
Route::post('/gestion-des-utilisateurs/modifier-utilisateur/{id}', [UtilisateurController::class, 'SaveModifierUtilisateur'])->name('save_modifier_utilisateur');
Route::post('save_importer_utilisateur', [UtilisateurController::class, 'SaveImporterUtilisateur'])->name('save_importer_utilisateur');
Route::post('supprimer_utilisateur', [UtilisateurController::class, 'SupprimerUtilisateur'])->name('supprimer_utilisateur');
 
//STATISTIQUES
Route::get('/statistiques/statistiques-journaliere', [StatistiquesController::class, 'StatistiquesJournaliere'])->name('statistiques_journaliere');

//PARAMETRE
Route::get('/gestion-des-parametres/zones', [ParametreController::class, 'GestionZone'])->name('gestion_zone');
Route::post('/gestion-des-parametres/zones', [ParametreController::class, 'SaveZone'])->name('save_zone');
Route::post('/gestion-des-parametres/modifier-zone/{zone_id}', [ParametreController::class, 'ModifierZone'])->name('modifier_zone');
Route::post('supprimer_zone', [ParametreController::class, 'SupprimerZone'])->name('supprimer_zone');

Route::get('/gestion-des-parametres/regions', [ParametreController::class, 'GestionRegion'])->name('gestion_region');
Route::post('/gestion-des-parametres/regions', [ParametreController::class, 'SaveRegion'])->name('save_region');
Route::post('/gestion-des-parametres/modifier-region/{region_id}', [ParametreController::class, 'ModifierRegion'])->name('modifier_region');
Route::post('supprimer_region', [ParametreController::class, 'SupprimerRegion'])->name('supprimer_region');

Route::get('/gestion-des-parametres/types-actions', [ParametreController::class, 'GestionTypeAction'])->name('gestion_type_action');
Route::post('/gestion-des-parametres/types-actions', [ParametreController::class, 'SaveTypeAction'])->name('save_type_action');
Route::post('/gestion-des-parametres/modifier-type-action/{type_action_id}', [ParametreController::class, 'ModifierTypeAction'])->name('modifier_type_action');
Route::post('supprimer_type_action', [ParametreController::class, 'SupprimerTypeAction'])->name('supprimer_type_action');

Route::get('/gestion-des-parametres/operateurs', [ParametreController::class, 'GestionOperateur'])->name('gestion_operateur');
Route::post('/gestion-des-parametres/operateurs', [ParametreController::class, 'SaveOperateur'])->name('save_operateur');
Route::post('/gestion-des-parametres/modifier-operateur/{operateur_id}', [ParametreController::class, 'ModifierOperateur'])->name('modifier_operateur');
Route::post('supprimer_operateur', [ParametreController::class, 'SupprimerOperateur'])->name('supprimer_operateur');

Route::get('/gestion-des-parametres/priorites-ihs', [ParametreController::class, 'GestionPrioriteIHS'])->name('gestion_priorite_ihs');
Route::post('/gestion-des-parametres/priorites-ihs', [ParametreController::class, 'SavePrioriteIHS'])->name('save_priorite_ihs');
Route::post('/gestion-des-parametres/modifier-priorite_ihs/{priorite_ihs_id}', [ParametreController::class, 'ModifierPrioriteIHS'])->name('modifier_priorite_ihs');
Route::post('supprimer_priorite_ihs', [ParametreController::class, 'SupprimerPrioriteIHS'])->name('supprimer_priorite_ihs');

Route::get('/gestion-des-parametres/topologies-typologies', [ParametreController::class, 'GestionTopologieTypologie'])->name('gestion_topologie_typologie');
Route::post('/gestion-des-parametres/topologies-typologies', [ParametreController::class, 'SaveTopologieTypologie'])->name('save_topologie_typologie');
Route::post('/gestion-des-parametres/topologie-typologie/{topologie_typologie_id}', [ParametreController::class, 'ModifierTopologieTypologie'])->name('modifier_topologie_typologie');
Route::post('supprimer_topologie_typologie', [ParametreController::class, 'SupprimerTopologieTypologie'])->name('supprimer_topologie_typologie');


//API
Route::get('/chargement/region/{region_id}/zone', [APIController::class, 'ChargementRegionZone'])->name('chargement_region_zone');
Route::get('/profil/{profil_id}/actions', [APIController::class, 'getActions']);
Route::get('/profil-modification/{profil_id}/actions/{user_id?}', [APIController::class, 'getActionsModification']);
Route::post('/action/update-statut/{id}', [APIController::class, 'updateStatut'])->name('update_statut');
Route::get('/chargement/site/{site_id}/info', [APIController::class, 'ChargementSiteInfo'])->name('chargement_site_info');
Route::get('/chargement/users/{site_id}/site', [APIController::class, 'ChargementUserSite'])->name('chargement_user_site');
Route::post('chargement/ajax_statistiques_journaliere', [APIController::class, 'AjaxStatistiqueJournaliere'])->name('ajax_statistiques_journaliere');

//CHANGER MOT DE PASSE
Route::get('mot-de-passe-oublie', [ChangerPasswordController::class, 'ChangePassword'])->name('password.change');
Route::post('changer/mot-de-passe/save', [ChangerPasswordController::class, 'ChangePasswordSave'])->name('password.email.change');
Route::get('password/change/email/{token}', [ChangerPasswordController::class, 'ChangePasswordForm'])->name('password-change-save');
Route::post('password/change/email/update', [ChangerPasswordController::class, 'ChangePasswordFinalSave'])->name('password.update.save');



