<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'is_company',
        'company_id',
    ];

    public static function createEmployee( $params ){

        if( $params ){
            self::create($params);
            return true;
        }
        return false;

    }

    public static function updateEmployee( $companyId, $params ){

        if( $params ){
            $company = Employee::find($companyId);
            $company->update($params);
            return true;
        }
        return false;

    }

    public static function deleteEmployee( $companyId ){

        if( $companyId ){
            $company = Employee::find($companyId);
            $company->delete();
            return true;
        }
        return false;

    }

}
