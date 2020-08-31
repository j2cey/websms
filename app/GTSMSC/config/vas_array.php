<?php
/**
 * Created by PhpStorm.
 * User: JudeParfait
 * Date: 06/03/2016
 * Time: 15:31
 */

$vasparams_array = [

    /*
    |--------------------------------------------------------------------------
    | Default Vas steps
    |--------------------------------------------------------------------------
    | Etapes des VAS par défaut
    |
    |
    */

    'steps' => [
        '0' => ['desc'=>'enregistrement requete', 'msg'=>'Bienvenue dans le service {0}. Votre requete a ete enregistree, vous serrez notifie a la fin du traitement'],
        '1' => ['desc'=>'requete executee avec succes', 'msg'=>''],
        '-1' => ['desc'=>'erreur innatendue','msg'=>''],
        '-2' => ['desc'=>'solde insuffisant','msg'=>''],
    ],

    'ucip'=> [
        'request' => [
            'max_wait' => 120,
        ]
    ],

    'rfialertes' => [
        'services'=> [
            'ussd'=> [
                '*131*7#' => 'hebdo',
                '*131*7' => 'hebdo',
                '*131*30#' => 'mois',
                '*131*30' => 'mois',
				'*131*0#' => 'annulation',
                '*131*0' => 'annulation',
            ]
        ],
        'hebdo'=> [
            'cost' => 100,
            'duration' => 7,
            'transaction_id' => 2031,
			'result_ok' => 'succes inscription',
        ],
        'mois'=> [
            'cost' => 300,
            'duration' => 30,
            'transaction_id' => 2032,
			'result_ok' => 'succes inscription',
        ],
		'annulation'=> [
            'cost' => 0,
            'duration' => 0,
            'transaction_id' => 2031,
			'result_ok' => 'succes annulation',
        ],
        'sms'=> [
            'sender' => 'MoovRFI',
            'reception' => 'Bienvenue dans le service RFI alertes SMS. Votre requete est encours de traitement. Vous serrez notifie a la fin.',
            'inscription_reussie' => 'Bravo! Inscription a la formule {0} effectuee avec succes. Vous recevrez des alertes RFI jusqu au {1}.',
            'solde_insuffisant' => 'Cher abonne, votre solde est insuffisant pour cette operation. Recharger votre compte de {0} F et reesayer.',
			'erreur_avant_solde' => 'Cher abonne, votre requete n a pas pu aboutir. Veuillez recommencer plus tard SVP.',
			'erreur_requete' => 'Cher abonne, votre requete n est pas correcte. Veuillez recommencer SVP.',
			'erreur_apres_solde' => 'Cher abonne, l execution de votre requete prendra beaucoup de temps. Vous serrez notifie a la fin.',
			'annulation_reussie' => 'Annulation effectuee avec succes. Pour vous reabonner composer *131*7# pour une inscription HEBDO, *131*30# pour une inscription MOIS.',
			'annulation_nulle' => 'Cher Abonne, vous n avez aucune souscription moov rfi alertes en cours. Abonnez-vous en composant *131*7# souscription HEBDO, *131*30# souscription MOIS.',
            'end_subscription_intermediate' => 'Cher Abonne, votre souscription rfi alertes prend fin le {0}. Gagnez d avantage de jours *131*7# souscription HEBDO, *131*30# souscription MOIS.',
            'end_subscription_total' => 'Cher Abonne, votre souscription rfi alertes a pris fin. Reabonnez-vous *131*7# souscription HEBDO, *131*30# souscription MOIS.',
        ],
        'smsalertes_sending'=> [
            'sender' => 'MoovRFI',
            'max_try' => 3,
        ],
        'end_subscription'=> [
            'notify_intermediate' => true,
            'notify_total' => true,
        ],
    ],
];
