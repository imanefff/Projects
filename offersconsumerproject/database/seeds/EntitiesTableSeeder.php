<?php

use Illuminate\Database\Seeder;
use App\Entity;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $adv = new Entity;
        $adv->id_entity   =  1;
        $adv->name        =  "PIM";
        $adv->path        =  "pim3uyLRGnw6cxutO3p";
        $adv->save();

        $adv = new Entity;
        $adv->id_entity   =  10;
        $adv->name        =  "DGX";
        $adv->path        =  "dgxS9qLNIUHql0jMHSE";
        $adv->save();

        $adv = new Entity;
        $adv->id_entity   =  11;
        $adv->name        =  "WD";
        $adv->path        =  "wdmRVzLWPgEU1Xrydfu";
        $adv->save();

        $adv = new Entity;
        $adv->id_entity   =  12;
        $adv->name        =  "EDB";
        $adv->path        =  "edbWspBfn1D27nGrgZm";
        $adv->save();

    }
}
