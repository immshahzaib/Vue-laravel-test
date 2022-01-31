<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Admin\Employee as EmployeeModel;

class Employee extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $employeeData = [];
        $employeeData["first_name"]    = "Saqib";
        $employeeData["last_name"]    = "Hussain";
        $employeeData["email"]    = "saqibhussain@xegroup.com";
        $employeeData["phone"]    = "03004878399";
        $employeeData["is_company"]    = true;
        $employeeData["company_id"]    = "1";

        EmployeeModel::create( $employeeData );

    }
}
