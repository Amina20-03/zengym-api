<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('desc')->nullable();
            $table->timestamps();
        });
        DB::table('pays')->insert([
            [

                'code' => 'TN',
                'desc' => 'Tunisie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AFG',
                'desc' => 'Afghanistan',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ZAF',
                'desc' => 'Afrique du Sud',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ALB',
                'desc' => 'Albanie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'DZA',
                'desc' => 'Algérie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'DEU',
                'desc' => 'Allemagne',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AND',
                'desc' => 'Andorre',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AGO',
                'desc' => 'Angola',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AIA',
                'desc' => 'Anguilla',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ATG',
                'desc' => 'Antigua-et-Barbuda',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ANT',
                'desc' => 'Antilles Néerlandaises',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'SAU',
                'desc' => 'Arabie Saoudite',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ARG',
                'desc' => 'Argentine',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ARM',
                'desc' => 'Arménie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ABW',
                'desc' => 'Aruba',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AUS',
                'desc' => 'Australie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AUT',
                'desc' => 'Autriche',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'AZE',
                'desc' => 'Azerbaïdjan',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BHS',
                'desc' => 'Bahamas',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BHR',
                'desc' => 'Bahreïn',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BGD',
                'desc' => 'Bangladesh',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BRB',
                'desc' => 'Barbade',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BEL',
                'desc' => 'Belgique',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BLZ',
                'desc' => 'Belize',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BEN',
                'desc' => 'Bénin',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BMU',
                'desc' => 'Bermudes',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BTN',
                'desc' => 'Bhoutan',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BLR',
                'desc' => 'Biélorussie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'MMR',
                'desc' => 'Birmanie (Myanmar)',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BOL',
                'desc' => 'Bolivie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BIH',
                'desc' => 'Bosnie-Herzégovine',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BWA',
                'desc' => 'Botswana',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BRA',
                'desc' => 'Brésil',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BRN',
                'desc' => 'Brunei',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BGR',
                'desc' => 'Bulgarie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BFA',
                'desc' => 'Burkina Faso',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'BDI',
                'desc' => 'Burundi',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KHM',
                'desc' => 'Cambodge',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CMR',
                'desc' => 'Cameroun',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CAN',
                'desc' => 'Canada',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CPV',
                'desc' => 'Cap-vert',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CHL',
                'desc' => 'Chili',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CHN',
                'desc' => 'Chine',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CYP',
                'desc' => 'Chypre',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'COL',
                'desc' => 'Colombie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'COM',
                'desc' => 'Comores',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'PRK',
                'desc' => 'Corée du Nord',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KOR',
                'desc' => 'Corée du Sud',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CRI',
                'desc' => 'Costa Rica',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CIV',
                'desc' => 'Côte d Ivoire',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'HRV',
                'desc' => 'Croatie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CUB',
                'desc' => 'Cuba',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'DNK',
                'desc' => 'Danemark',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'DJI',
                'desc' => 'Djibouti',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'DMA',
                'desc' => 'Dominique',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'EGY',
                'desc' => 'Égypte',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ARE',
                'desc' => 'Émirats Arabes Unis',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ECU',
                'desc' => 'Équateur',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ERI',
                'desc' => 'Érythrée',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ESP',
                'desc' => 'Espagne',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'EST',
                'desc' => 'Estonie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FSM',
                'desc' => 'États Fédérés de Micronésie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'USA',
                'desc' => 'États-Unis',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ETH',
                'desc' => 'Éthiopie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FJI',
                'desc' => 'Fidji',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FIN',
                'desc' => 'Finlande',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FRA',
                'desc' => 'France',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GAB',
                'desc' => 'Gabon',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GMB',
                'desc' => 'Gambie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GEO',
                'desc' => 'Géorgie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'SGS',
                'desc' => 'Géorgie du Sud et les Îles Sandwich du Sud',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GHA',
                'desc' => 'Ghana',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GIB',
                'desc' => 'Gibraltar',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GRC',
                'desc' => 'Grèce',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GRD',
                'desc' => 'Grenade',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GRL',
                'desc' => 'Groenland',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GLP',
                'desc' => 'Guadeloupe',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GUM',
                'desc' => 'Guam',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GTM',
                'desc' => 'Guatemala',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GIN',
                'desc' => 'Guinée',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GNB',
                'desc' => 'Guinée-Bissau',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GNQ',
                'desc' => 'Guinée Équatoriale',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GUY',
                'desc' => 'Guyana',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'GUF',
                'desc' => 'Guyane Française',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'HTI',
                'desc' => 'Haïti',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'HND',
                'desc' => 'Honduras',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'HKG',
                'desc' => 'Hong-Kong',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'HUN',
                'desc' => 'Hongrie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CXR',
                'desc' => 'Île Christmas',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IMN',
                'desc' => 'Île de Man',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'NFK',
                'desc' => 'Île Norfolk',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ALA',
                'desc' => 'Îles Åland',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CYM',
                'desc' => 'Îles Caïmans',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'CCK',
                'desc' => 'Îles Cocos (Keeling)',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'COK',
                'desc' => 'Îles Cook',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FRO',
                'desc' => 'Îles Féroé',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'FLK',
                'desc' => 'Îles Malouines',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'MNP',
                'desc' => 'Îles Mariannes du Nord',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'MHL',
                'desc' => 'Îles Marshall',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'PCN',
                'desc' => 'Îles Pitcairn',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'SLB',
                'desc' => 'Îles Salomon',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'TCA',
                'desc' => 'Îles Turks et Caïques',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'VGB',
                'desc' => 'Îles Vierges Britanniques',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'VIR',
                'desc' => 'Îles Vierges des États-Unis',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IND',
                'desc' => 'Inde',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IDN',
                'desc' => 'Indonésie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IRN',
                'desc' => 'Iran',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IRQ',
                'desc' => 'Iraq',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'IRL',
                'desc' => 'Irlande',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ISL',
                'desc' => 'Islande',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'ITA',
                'desc' => 'Italie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'JAM',
                'desc' => 'Jamaïque',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'JPN',
                'desc' => 'Japon',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'JOR',
                'desc' => 'Jordanie',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KAZ',
                'desc' => 'Kazakhstan',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KEN',
                'desc' => 'Kenya',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KGZ',
                'desc' => 'Kirghizistan',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KIR',
                'desc' => 'Kiribati',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'KWT',
                'desc' => 'Koweït',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'LAO',
                'desc' => 'Laos',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'VAT',
                'desc' => 'Le Vatican',

                'created_at' => now(),
                'updated_at' => now()
            ],
            [

                'code' => 'VAT',
                'desc' => 'Lesotho',

                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
