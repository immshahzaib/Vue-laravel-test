<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Models\Admin\Company;
use App\Models\Admin\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::where("deleted_at", null)->paginate(10);

        return view("pages.admin.employee.index", compact("employees"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::where("deleted_at", null)->get();
        return view("pages.admin.employee.create", compact("companies"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        $params = $request->all();
        try{
            $createEmployee = Employee::createEmployee( $params );
            if( $createEmployee ){
                return response()->json(['code'=> 0, 'success'=>'Successfully '], 200);
            }
            abort( 403, "Not Created Employee" );
        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find( $id );
        $companies = Company::where("deleted_at", null)->get();
        return view("pages.admin.employee.edit", compact("id", "companies"));
    }

    public function getCurrentEditEmployee($id)
    {
        $employee = Employee::find( $id );
        return response()->json(['code'=> 0, 'success'=>'Successfully ', 'data'=>$employee], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        $params = $request->all();
        $employeeId = $id;

        try{
            $updateEmployee = Employee::updateEmployee( $employeeId, $params );
            if( $updateEmployee ){
                return response()->json(['code'=> 0, 'success'=>'Successfully '], 200);
            }
            abort( 403, "Not Created Employee" );

        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeId = $id;

        try{
            $deleteEmployee = Employee::deleteEmployee( $employeeId );
            if( $deleteEmployee ){
                return redirect()->route("admin.employee.index" );
            }
            abort( 403, "Not deleted Employee" );

        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }


}
