<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card position-relative">

                    <div :class="{'d-none': !isLoading}" class="d-flex justify-content-center align-items-center position-absolute loading-con">
                        <img :src="`${url}/images/loading.gif`" width="70" alt="" />
                    </div>


                    <div class="card-header d-flex justify-content-between align-items-center">
                        Update Employee
                        <a :href="`${url}/admin/employee`" class="btn btn-success">
                            Employee List
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" @submit.prevent="updateEmployee" :action="`${url}/admin/employee`">

                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" ref="first_name" name="first_name" required autocomplete="first_name" :value="currentEmployee.first_name" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" ref="last_name" name="last_name" required autocomplete="last_name" :value="currentEmployee.last_name" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" ref="email" name="email"  required autocomplete="email" :value="currentEmployee.email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">Phone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" ref="phone" name="phone" required autocomplete="phone" :value="currentEmployee.phone" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" ref="is_company" name="is_company" id="is_company" :checked="{'checked': !companyFlag}" @change="companyShow">

                                        <label class="form-check-label" for="is_company">
                                            Is Company
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3" :class="{'d-none': !companyFlag}">
                                <label for="company_id" class="col-md-4 col-form-label text-md-end">Company</label>

                                <div class="col-md-6">
                                    <select id="company_id" class="form-control" ref="company_id" name="company_id">
                                        <option value=""> --- Select Company --- </option>
                                        <template v-for="(company, index) in companies">
                                            <option :value="company.id"  :selected="currentEmployee.company_id == company.id "> {{ company.name}} </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button @click.prevent="updateEmployee" type="submit" class="btn btn-primary">
                                        Update Employee
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "editEmployeeComponent",
        props: {
            req: {
                required: true,
            },
            id: {
                required: true,
            },
            base_url: {
                required: true,
            }
        },
        data(){
            return {
                errors: [],
                currentEmployee: "",
                companies: "",
                isLoading: false,
                url: this.base_url,
                companyFlag: false,
            }
        },
        mounted() {
            this.getEmployee();
            this.getCompanies();
        },
        methods: {
            updateEmployee(){

                this.isLoading = true;
                this.errors = [];

                this.req.put(`/admin/employee/${this.id}`, {
                    "id": this.id,
                    "first_name": this.$refs.first_name.value,
                    "last_name": this.$refs.last_name.value,
                    "email": this.$refs.email.value,
                    "phone": this.$refs.phone.value,
                    "is_company": this.$refs.is_company.value,
                    "company_id": this.$refs.company_id.value,
                })
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                        }
                        else{
                            this.isLoading = false;
                        }
                    })
                    .catch( error => {
                        /*if (error.response.status == 422){
                        }*/
                        this.isLoading = false;
                        this.errors.push( error.response.data.errors );
                    })
            },
            async getEmployee(){

                this.isLoading = true;
                this.errors = [];

                await this.req.get(`/admin/employee/${this.id}/current/employee`)
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                            this.currentEmployee = response.data.data;
                            this.companyFlag = (response.data.data.is_company == 1) ? true : false;
                            console.log( response.data.data.is_company );
                            if( response.data.data.is_company == 1 ){
                                this.companyFlag = true;
                                this.$refs.is_company.value = 1;
                            } else {
                                this.companyFlag = false;
                                this.$refs.is_company.value = 0;
                            }
                        }
                    })
                    .catch( error => {
                        /*if (error.response.status == 422){
                        }*/
                        this.isLoading = false;
                        this.errors.push( error.response.data.errors );
                    })
            },
            getCompanies(){

                this.isLoading = true;
                this.errors = [];

                this.req.get(`/admin/company-list`)
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                            this.companies = response.data.data;
                        }
                    })
                    .catch( error => {
                        this.isLoading = false;
                        this.errors.push( error.response.data.errors );
                    })
            },
            companyShow(e) {
                let self = this;
                if( e.target.checked == true ){
                    self.companyFlag = true;
                    e.target.setAttribute("value", 1);
                } else {
                    self.companyFlag = false;
                    e.target.setAttribute("value", 0);
                }
            },
        }
    }
</script>

<style scoped>

</style>
