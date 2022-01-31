<?php

namespace Database\Seeders;

use \App\Models\Admin\Company as CompanyModel;
use Illuminate\Database\Seeder;

class Company extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $companyData = [];
        $companyData["name"]    = "XE GROUP";
        $companyData["email"]    = "hr@xegroup.com";

        CompanyModel::create( $companyData );

    }
}
