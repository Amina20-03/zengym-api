<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/clear', function () {
    Cache::flush();
    Artisan::call('view:clear');
    Artisan::call('route:cache');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("login", [\App\Http\Controllers\ApiControllers\AuthController::class, "login"]);

Route::get("instructeur_by_id/profile/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_profile_by_id"]);
Route::get("instructeur_by_id/profile/photos/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_photos_by_id_instr"]);
Route::get("instructeur_by_id/profile/videos/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_videos_by_id_instr"]);
Route::get("instructeur_by_id/profile/docs/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_docs_by_id_instr"]);

Route::get("evennements_by_categ/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "index_evennement_by_categ"]);
Route::get("evennements/details/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "detail_evenement"]);
Route::get("evennements/details/photos/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "detail_evenement_photos"]);
Route::post("evennements/inscription/candidats/register", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "inscription_candidat"]);
Route::post("evennements/payer/candidats/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "payer_candidat"]);

Route::get("formations_by_categ/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "index_formation_by_categ"]);
Route::get("instructeurs_by_categ/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "index_instructeur_by_categ"]);
Route::get("formations/details/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "detail_formation"]);
Route::get("formations/details/photos/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "detail_formation_photos"]);
Route::get("formations/details/videos/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "detail_formation_videos"]);
Route::post("formations/inscription/candidats/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "inscription_candidat"]);
Route::post("formations/payer/candidats/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "payer_candidat"]);

Route::get("cours", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "index_cours"]);
Route::get("cours_by_categ/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "index_cours_by_categ"]);
Route::post("cours_by_categ/search", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "search_cours"]);

Route::get("shop/produits", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "index_produit_shop"]);
Route::get("shop/produits/by_categ/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "produit_by_categ"]);
Route::get("shop/produits/detail/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "detail_produit"]);
Route::post("shop/produits/cart/inscription", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "inscription"]);

Route::post("devenir_adherent/payer/candidats/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "payer_candidat"]);

// Route publique gallerie vitrine (sans auth)
Route::get("gallerie/public", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "index_public"]);
Route::get("gallerie/public/load_more", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "load_more"]);

// Routes publiques articles vitrine (sans auth)
Route::get("articles/public",           [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "index_public"]);
Route::get("articles/public/load_more", [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "load_more"]);
Route::get("articles/public/{id}",      [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "show_public"]);
// Route publique programmes vitrine (sans auth)
Route::get('programmes/public', [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, 'index_public'])
     ->name('api.programmes.public');Route::group([
    "middleware" => ['refresh:api', 'auth:api']
], function () {

    Route::post("evennements/details/photos/add/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "add_evenement_photo"]);
    Route::get("evennements/details/photos/delete/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_evenement_photo"]);
    Route::get("evennements/candidats/list/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "get_candidats_evennement"]);
    Route::post("evennements/candidats/change_payment_status", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "change_candidat_payment_status"]);
    Route::get("my_evennements", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "my_evenements"]);
    Route::post("evennements/details/videos/add/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "add_evenement_video"]);
    Route::get("evennements/details/videos/delete/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_evenement_video"]);

    Route::post("shop/produits/add-to-card/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "cart_store"]);
    Route::get("shop/produits/my-card/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "cart_index"]);
    Route::get("shop/produits/my-card/destroy/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "cart_destroy"]);
    Route::post("shop/produits/my-card/payer", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "payer"]);

    Route::get("profile", [\App\Http\Controllers\ApiControllers\AuthController::class, "profile"]);
    Route::get("refresh", [\App\Http\Controllers\ApiControllers\AuthController::class, "refreshToken"]);
    Route::get("logout", [\App\Http\Controllers\ApiControllers\AuthController::class, "logout"]);

    Route::get("admins", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "index_admin"]);
    Route::get("admin/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "edit_admin"]);
    Route::post("admin_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "update_admin"]);
    Route::post("admin_update_password/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "update_admin_password"]);
    Route::post("admin_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "delete_admin"]);
    Route::post("admin_add", [\App\Http\Controllers\ApiControllers\AdminControllers\AdminController::class, "add_admin"]);

    Route::get("categorie_instructeurs", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "index_cat_instructeur"]);
    Route::get("categorie_instructeur/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "edit_cat_instructeur"]);
    Route::post("categorie_instructeur_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "update_cat_instructeur"]);
    Route::post("categorie_instructeur_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "delete_cat_instructeur"]);
    Route::post("categorie_instructeur_add", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "add_cat_instructeur"]);

    Route::get("pays", [\App\Http\Controllers\ApiControllers\AdminControllers\PaysController::class, "index"]);
    Route::get("pays/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PaysController::class, "edit"]);
    Route::post("pays_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PaysController::class, "update"]);
    Route::post("pays_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\PaysController::class, "delete"]);
    Route::post("pays_add", [\App\Http\Controllers\ApiControllers\AdminControllers\PaysController::class, "add"]);

    Route::get("instructeurs", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "index_instructeur"]);
    Route::get("instructeur/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "edit_instructeur"]);
    Route::post("instructeur_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "update_instructeur"]);
    Route::post("instructeur_update_password/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "update_instructeur_password"]);
    Route::get("update_instructeur_diplome_status/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "update_instructeur_diplome_status"]);
    Route::post("instructeur_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "delete_instructeur"]);
    Route::post("instructeur_add", [\App\Http\Controllers\ApiControllers\AdminControllers\InstructeurController::class, "add_instructeur"]);

    Route::get("categorie_representants", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "index_cat_rep"]);
    Route::get("categorie_representant/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "edit_cat_rep"]);
    Route::post("categorie_representant_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "update_cat_rep"]);
    Route::post("categorie_representant_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "delete_cat_rep"]);
    Route::post("categorie_representant_add", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "add_cat_rep"]);

    Route::get("representants", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "index_representant"]);
    Route::get("representant/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "edit_representant"]);
    Route::post("representant_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "update_representant"]);
    Route::post("representant_update_password/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "update_representant_password"]);
    Route::post("representant_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "delete_representant"]);
    Route::post("representant_add", [\App\Http\Controllers\ApiControllers\AdminControllers\RepresentantController::class, "add_representant"]);

    Route::get("evenement/types", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "index_type"]);
    Route::get("evenement/type/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "edit_type"]);
    Route::post("evenement/type/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "update_type"]);
    Route::post("evenement/type/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_type"]);
    Route::post("evenement/type/add", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "add_type"]);

    Route::get("evennements", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "index_evenement"]);
    Route::get("fetch_evennements_cal", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "fetch_events"]);
    Route::get('fetch-ceremonies', [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, 'fetchCeremoniesByMonth']);
    Route::get("evennements/create/{val?}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "create_evenement"]);
    Route::post("evenements/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_evenement"]);
    Route::post("evenements/add", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "add_evenement"]);
    Route::post("evenements/affecter_users", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "affecter_user"]);
    Route::post("evenements/affecter_users/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_affect_user"]);
    Route::post("evenements/affecter_candidat/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_affect_candidat"]);

    Route::get("pourcentages", [\App\Http\Controllers\ApiControllers\AdminControllers\PourcentageController::class, "index"]);
    Route::get("pourcentage/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PourcentageController::class, "edit"]);
    Route::post("pourcentage/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PourcentageController::class, "update"]);
    Route::post("pourcentage/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\PourcentageController::class, "delete"]);
    Route::post("pourcentage/add", [\App\Http\Controllers\ApiControllers\AdminControllers\PourcentageController::class, "add"]);

    Route::get("produits/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "index_categ_produit"]);
    Route::get("produits/categorie/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "edit_categ_produit"]);
    Route::post("produits/categorie/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "update_categ_produit"]);
    Route::post("produits/categorie/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "delete_categ_produit"]);
    Route::post("produits/categorie/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "add_categ_produit"]);

    Route::get("produits", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "index_produit"]);
    Route::get("produit/create", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "create_produit"]);
    Route::get("produits/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "edit_produit"]);
    Route::post("produits/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "update_produit"]);
    Route::post("produits/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "delete_produit"]);
    Route::post("produits/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProduitController::class, "add_produit"]);

    Route::get("cours/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "index_categ_cours"]);
    Route::get("cours/categories/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "edit_categ_cours"]);
    Route::post("cours/categories/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "update_categ_cours"]);
    Route::post("cours/categories/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "delete_categ_cours"]);
    Route::post("cours/categories/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "add_categ_cours"]);

    Route::get("cours/create", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "create_cours"]);
    Route::get("cours/demande/create", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "create_dmd_cours"]);
    Route::post("cours/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "add_cours"]);
    Route::get("cours/demande", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "index_dmd_cours"]);
    Route::post("cours/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "delete_cours"]);

    Route::get("candidats/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "index_categ_candidat"]);
    Route::get("candidats/categories/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "edit_categ_candidat"]);
    Route::post("candidats/categories/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "update_categ_candidat"]);
    Route::post("candidats/categories/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "delete_categ_candidat"]);
    Route::post("candidats/categories/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "add_categ_candidat"]);

    Route::get("candidats/salle_de_sports", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "index_salle_sport"]);
    Route::get("candidats/salle_de_sports/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "edit_salle_sport"]);
    Route::post("candidats/salle_de_sports/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "update_salle_sport"]);
    Route::post("candidats/salle_de_sports/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "delete_salle_sport"]);
    Route::post("candidats/salle_de_sports/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "add_salle_sport"]);

    Route::get("candidats", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "index_candidat"]);
    Route::get("candidats/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "edit_candidat"]);
    Route::post("candidats/update/{val}",    [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "update_candidat"]);
    Route::post("candidats/photo/{id}",      [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "upload_photo_candidat"]);
    Route::post("candidats/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "delete_candidat"]);
    Route::post("candidats/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "add_candidat"]);

    Route::get("vente_abonnement/abonnements/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "index_categ_abo"]);
    Route::get("vente_abonnement/abonnements/categories/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "edit_categ_abo"]);
    Route::post("vente_abonnement/abonnements/categories/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "update_categ_abo"]);
    Route::post("vente_abonnement/abonnements/categories/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "delete_categ_abo"]);
    Route::post("vente_abonnement/abonnements/categories/add", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "add_categ_abo"]);

    Route::get("vente_abonnement/abonnements/types", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "index_type_abo"]);
    Route::get("vente_abonnement/abonnements/types/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "edit_type_abo"]);
    Route::post("vente_abonnement/abonnements/types/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "update_type_abo"]);
    Route::post("vente_abonnement/abonnements/types/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "delete_type_abo"]);
    Route::post("vente_abonnement/abonnements/types/add", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "add_type_abo"]);

    Route::get("abonnements/adherents/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "index_categ_abo"]);
    Route::get("abonnements/adherents/categories/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "edit_categ_abo"]);
    Route::post("abonnements/adherents/categories/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "update_categ_abo"]);
    Route::post("abonnements/adherents/categories/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "delete_categ_abo"]);
    Route::post("abonnements/adherents/categories/add", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "add_categ_abo"]);

    Route::get("abonnements/adherents/types", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "index_type_abo"]);
    Route::get("abonnements/adherents/types/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "edit_type_abo"]);
    Route::post("abonnements/adherents/types/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "update_type_abo"]);
    Route::post("abonnements/adherents/types/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "delete_type_abo"]);
    Route::post("abonnements/adherents/types/add", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "add_type_abo"]);

    Route::get("formations/categories", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "index_categ_formation"]);
    Route::get("formations/categories/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "edit_categ_formation"]);
    Route::post("formations/categories/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "update_categ_formation"]);
    Route::post("formations/categories/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "delete_categ_formation"]);
    Route::post("formations/categories/add", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "add_categ_formation"]);
    Route::post("formations/affecter_candidats", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "affecter_candidat"]);
    Route::post("formations/affecter_candidats/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "delete_affect_candidat"]);
    Route::get("formations/affecter_candidats/transferer_candidat_vers_instructeur/{id_user}/{id_formation}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "transferer_candidat_vers_instructeur"]);
    Route::post("formations/photos/add/{id_formation}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "add_photos_formation"]);
    Route::get("formations/photos/delete/{id_photo}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "delete_photo_formation"]);

    Route::get("vente_prods", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "index_vente"]);
    Route::get("vente_prods/create", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "create_vente"]);
    Route::post("vente_prods/add", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "add_vente"]);
    Route::post("vente_prods/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "delete_vente"]);
    Route::post("vente_prods/ligne_vente/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "delete_ligne_vente"]);
    Route::get("vente_prods/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "edit_vente"]);
    Route::post("vente_prods/search", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "search_vente"]);
    Route::get("vente_prods/encaisse/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteProdController::class, "encaisse_vente"]);

    Route::get("vente_abo", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "index_abo"]);
    Route::get("vente_abo/create", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "create_abo"]);
    Route::post("vente_abo/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "delete_abo"]);
    Route::post("vente_abo/add", [\App\Http\Controllers\ApiControllers\AdminControllers\VenteAboController::class, "add_abo"]);

    Route::get("abonnement_adherent", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "index_abo"]);
    Route::get("abonnement_adherent/valider_abo_adherent/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "valider_abo_adherent"]);
    Route::post("abonnement_adherent/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\AboAdherentController::class, "delete_abo"]);

    Route::get("stock_vente/fournisseurs", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "index_fournisseur"]);
    Route::post("stock_vente/fournisseurs/add", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "add_fournisseur"]);
    Route::post("stock_vente/fournisseurs/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "delete_fournisseur"]);
    Route::get("stock_vente/fournisseurs/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "edit_fournisseur"]);
    Route::post("stock_vente/fournisseurs/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "update_fournisseur"]);

    Route::get("stock_vente/stock_produit", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "index_stock_prod"]);

    Route::get("stock_vente/Bon_entree", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "index_bon_entree"]);
    Route::post("stock_vente/Bon_entree/search", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "search_bon_entree"]);
    Route::get("stock_vente/Bon_entree/create", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "create_bon_entree"]);
    Route::post("stock_vente/Bon_entree/add", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "add_bon_entree"]);
    Route::get("stock_vente/Bon_entree/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "edit_bon_entree"]);
    Route::post("stock_vente/Bon_entree/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "update_bon_entree"]);
    Route::post("stock_vente/Bon_entree/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "delete_bon_entree"]);

    Route::get("stock_vente/bon_sorties", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "index_bon_sortie"]);
    Route::post("stock_vente/bon_sorties/search", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "search_bon_sortie"]);
    Route::get("stock_vente/bon_sortie/create", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "create_bon_sortie"]);
    Route::post("stock_vente/bon_sortie_add", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "add_bon_sortie"]);
    Route::get("stock_vente/bon_sortie/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "edit_bon_sortie"]);
    Route::post("stock_vente/bon_sortie_update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "update_bon_sortie"]);
    Route::post("stock_vente/bon_sortie_delete", [\App\Http\Controllers\ApiControllers\AdminControllers\StockVenteController::class, "delete_bon_sortie"]);

    Route::get("comptes", [\App\Http\Controllers\ApiControllers\AdminControllers\CompteController::class, "index_compte"]);
    Route::get("comptes/operations/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CompteController::class, "index_operation"]);
    Route::post("comptes/operations/search", [\App\Http\Controllers\ApiControllers\AdminControllers\CompteController::class, "search_operation"]);
    Route::post("comptes/operations/add", [\App\Http\Controllers\ApiControllers\AdminControllers\CompteController::class, "add_operation"]);

    Route::get("formations/demande", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "index_dmd_formation"]);
    Route::get("formations/demande/create", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "create_dmd_formation"]);
    Route::get("formations/demande/approuver/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "aprouver_dmd_formation"]);
    Route::get("formations/demande/refuser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "refuser_dmd_formation"]);
    Route::get("formations", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "index_formation"]);
    Route::get("candidat_formations/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "candidat_index_formation"]);
    Route::get("candidat_formations/details/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "candidat_detail_formation"]);
    Route::post("formations/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "delete_formation"]);
    Route::post("formations/add", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "add_formation"]);
    Route::get("formation/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "edit_formation"]);
    Route::post("formations/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "update_formation"]);
    Route::post("formations/realiser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "realiser_formation"]);
    Route::get("formations/create", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "create_formation"]);
    Route::get("formations/encaisse/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "encaisse_formation"]);

    Route::get("instructeur/profile/infos", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_profile"]);
    Route::post("instructeur/profile/payer_abonnement", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "payer_abonnement"]);

    Route::get("evennements/demandes/list", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "index_dmd_evennement"]);
    Route::get("evennements/demandes/my",   [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "my_dmd_evennements"]);
    Route::get("evennements/demandes/create", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "create_dmd_evennement"]);
    Route::post("evennements/demandes/add", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "add_dmd_evennement"]);
    Route::post("evennements/demandes/update/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "update_dmd_evennement"]);
    Route::post("evennements/demandes/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "delete_evenement"]);
    Route::get("evennements/demande/approuver/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "aprouver_dmd_evennement"]);
    Route::get("evennements/demande/refuser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "refuser_dmd_evennement"]);
    Route::get("evennements/demande/realiser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\EvenementController::class, "realiser_evennement"]);

    Route::get("cours2", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "index_cours2"]);
    Route::post("cours/realiser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "realiser_cours"]);
    Route::post("cours/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "delete_cours"]);
    Route::get("cours/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "edit_cours"]);
    Route::get("cours/details/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "detail_cours"]);
    Route::get("cours/demande/approuver/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "aprouver_dmd_cours"]);
    Route::get("cours/demande/refuser/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "refuser_dmd_cours"]);
    Route::post("cours/affecter_candidats", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "affecter_candidat"]);
    Route::post("cours/affecter_candidats/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\CoursController::class, "delete_affect_candidat"]);

    Route::get("passage_de_grades", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "index_passage_grade"]);
    Route::post("passage_de_grades/add", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "add_passage_grade"]);
    Route::get("passage_de_grades/edit/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "edit_passage_grade"]);
    Route::post("passage_de_grades/update/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "update_passage_grade"]);
    Route::post("passage_de_grades/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "delete_passage_grade"]);
    Route::get("passage_de_grades/get_users", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "get_users"]);
    Route::get("passage_de_grades/get_users/devenir_instructeur/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "devenir_instructeur"]);
    Route::get("passage_de_grades/get_users/passage_grade_instructeur/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\PassageDeGradeController::class, "passage_grade_instructeur"]);

    Route::get("instructeur/profile/photos", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_photos"]);
    Route::post("instructeur/profile/photos/categorie/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_categ"]);
    Route::post("instructeur/profile/documents/categorie/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_categ_document"]);
    Route::post("instructeur/profile/videos/categorie/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_categ_video"]);
    Route::post("instructeur/profile/photos/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_photos"]);
    Route::post("instructeur/profile/photos/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "delete_photo"]);
    Route::post("instructeur/profile/photos/search", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "search_photos"]);

    Route::get("instructeur/profile/videos", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_videos"]);
    Route::post("instructeur/profile/videos/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "delete_video"]);
    Route::post("instructeur/profile/videos/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_videos"]);
    Route::post("instructeur/profile/videos/search", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "search_videos"]);

    Route::get("instructeur/profile/playlist", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_playlist"]);

    Route::get("instructeur/profile/documents", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "index_docs"]);
    Route::post("instructeur/profile/documents/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "delete_doc"]);
    Route::post("instructeur/profile/documents/add", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "add_docs"]);
    Route::post("instructeur/profile/documents/search", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "search_docs"]);
    Route::get("instructeur/profile/documents/details/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProfileController::class, "detailsDocument"]);

    Route::get("categorie_media/photos", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "index_cat_photos"]);
    Route::post("categorie_media/photos/add", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "add_cat_photos"]);
    Route::get("categorie_media/photos/edit/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "edit_cat_photos"]);
    Route::post("categorie_media/photos/update/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "update_cat_photos"]);
    Route::post("categorie_media/photos/delete", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "delete_cat_photos"]);

    Route::get("categorie_media/videos", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "index_cat_videos"]);
    Route::post("categorie_media/videos/add", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "add_cat_videos"]);
    Route::get("categorie_media/videos/edit/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "edit_cat_videos"]);
    Route::post("categorie_media/videos/update/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "update_cat_videos"]);
    Route::post("categorie_media/videos/delete", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "delete_cat_videos"]);

    Route::get("categorie_media/documents", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "index_cat_documents"]);
    Route::post("categorie_media/documents/add", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "add_cat_documents"]);
    Route::get("categorie_media/documents/edit/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "edit_cat_documents"]);
    Route::post("categorie_media/documents/update/{val}", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "update_cat_documents"]);
    Route::post("categorie_media/documents/delete", [\App\Http\Controllers\ApiControllers\CategorieMedia::class, "delete_cat_documents"]);

    Route::post("candidat/passage_vers_instructeur/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "passage_vers_instructeur"]);
    Route::get("candidat/passage_vers_instructeur/form/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatController::class, "passage_vers_instructeur_form"]);

    Route::get("candidat/examen_page", [\App\Http\Controllers\ApiControllers\QCMController::class, "examen_page"]);
    Route::post("candidat/verifier_la_bonne_reponse", [\App\Http\Controllers\ApiControllers\QCMController::class, "verifier_la_bonne_reponse"]);
    Route::post("candidat/valider_examen", [\App\Http\Controllers\ApiControllers\QCMController::class, "valider_examen"]);
    Route::post("candidat/create_certificat", [\App\Http\Controllers\ApiControllers\QCMController::class, "create_certif"]);

    Route::get("formations/en_ligne/{val}", [\App\Http\Controllers\ApiControllers\AdminControllers\FormationController::class, "show"]);

    // -------------------------------------------------------------------------
    // RENDEZ-VOUS
    // -------------------------------------------------------------------------
    Route::get("rendez_vous",                [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "index"]);
    Route::post("rendez_vous/add",           [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "store"]);
    Route::get("rendez_vous/my",             [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "mySrdv"]);
    Route::post("rendez_vous/accepter/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "accepter"]);
    Route::post("rendez_vous/refuser/{id}",  [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "refuser"]);
    Route::get("rendez_vous/delete/{id}",    [\App\Http\Controllers\ApiControllers\AdminControllers\RendezVousController::class, "destroy"]);

    // -------------------------------------------------------------------------
    // CANDIDAT PROGRAMMES
    // -------------------------------------------------------------------------
    Route::get("candidat_programmes/{candidat_id}",          [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatProgrammeController::class, "index"]);
    Route::get("candidat_programmes/{candidat_id}/available", [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatProgrammeController::class, "available"]);
    Route::post("candidat_programmes/{candidat_id}/assign",   [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatProgrammeController::class, "assign"]);
    Route::get("candidat_programmes/unassign/{pivot_id}",     [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatProgrammeController::class, "unassign"]);
    Route::post("candidat_programmes/statut/{pivot_id}",      [\App\Http\Controllers\ApiControllers\AdminControllers\CandidatProgrammeController::class, "updateStatut"]);

    // -------------------------------------------------------------------------
    // PROGRAMMES
    // -------------------------------------------------------------------------
    Route::get("programmes",                [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "index"]);
    Route::post("programmes/add",           [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "store"]);
    Route::get("programmes/{id}",           [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "show"]);
    Route::post("programmes/update/{id}",   [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "update"]);
    Route::get("programmes/delete/{id}",    [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "destroy"]);
    Route::get("programmes/video/delete/{video_id}", [\App\Http\Controllers\ApiControllers\AdminControllers\ProgrammeController::class, "deleteVideo"]);

    // -------------------------------------------------------------------------
    // SUIVI SANTÉ CANDIDAT (auto-suivi)
    // -------------------------------------------------------------------------
    Route::get("suivi_candidat/{candidat_id}",       [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteCandidatController::class, "index"]);
    Route::post("suivi_candidat/{candidat_id}/add",  [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteCandidatController::class, "store"]);
    Route::get("suivi_candidat/detail/{id}",         [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteCandidatController::class, "show"]);
    Route::post("suivi_candidat/update/{id}",        [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteCandidatController::class, "update"]);
    Route::get("suivi_candidat/delete/{id}",         [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteCandidatController::class, "destroy"]);

    // -------------------------------------------------------------------------
    // SUIVI SANTÉ
    // -------------------------------------------------------------------------
    Route::get("suivi_sante/{candidat_id}",         [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "index"]);
    Route::post("suivi_sante/{candidat_id}/add",    [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "store"]);
    Route::get("suivi_sante/{candidat_id}/rapport", [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "rapport"]);
    Route::get("suivi_sante/detail/{id}",           [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "show"]);
    Route::post("suivi_sante/update/{id}",          [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "update"]);
    Route::get("suivi_sante/delete/{id}",           [\App\Http\Controllers\ApiControllers\AdminControllers\SuiviSanteController::class, "destroy"]);

    // -------------------------------------------------------------------------
    // GALLERIE (admin protégé)
    // -------------------------------------------------------------------------
    Route::get("gallerie", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "index"]);
    Route::post("gallerie/add", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "store"]);
    Route::post("gallerie/update/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "update"]);
    Route::post("gallerie/delete", [\App\Http\Controllers\ApiControllers\AdminControllers\GallerieController::class, "destroy"]);

    // -------------------------------------------------------------------------
    // ARTICLES (admin protégé)
    // -------------------------------------------------------------------------
    Route::get("article_categories",           [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleCategorieController::class, "index"]);
    Route::post("article_categories/add",      [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleCategorieController::class, "add"]);
    Route::get("article_categories/edit/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleCategorieController::class, "edit"]);
    Route::post("article_categories/update/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleCategorieController::class, "update"]);
    Route::post("article_categories/delete",   [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleCategorieController::class, "delete"]);

    Route::get("articles",              [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "index"]);
    Route::post("articles/add",         [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "store"]);
    Route::get("articles/edit/{id}",    [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "edit"]);
    Route::post("articles/update/{id}", [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "update"]);
    Route::post("articles/delete",      [\App\Http\Controllers\ApiControllers\AdminControllers\ArticleController::class, "destroy"]);

});
