<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('employee');

        return [
            "first_name"  =>  "required|max:200|unique:employees,first_name,".$id.",id",
            "last_name"  =>  "required|max:200|unique:employees,last_name,".$id.",id",
            "email"     =>  "nullable|unique:employees,email,".$id.",id",
            "phone"     =>  "nullable|unique:employees,phone,".$id.",id",
            "is_company"     =>  "nullable",
            "company_id"     =>  "nullable",
        ];
    }
}
