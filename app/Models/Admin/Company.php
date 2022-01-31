<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'logo',
        'logo_url',
    ];

    public static function createCompany( $params ){

        if( $params ){
            return self::create($params);
        }
        return false;

    }

    public static function updateCompany( $companyId, $params ){

        if( $params ){
            $company = Company::find($companyId);
            $company->update($params);
            return true;
        }
        return false;

    }

    public static function deleteCompany( $companyId ){

        if( $companyId ){
            $company = Company::find($companyId);
            $company->delete();
            return true;
        }
        return false;

    }



}
