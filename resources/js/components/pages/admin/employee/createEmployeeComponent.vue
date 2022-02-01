<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card position-relative">

                    <div :class="{'d-none': !isLoading}" class="d-flex justify-content-center align-items-center position-absolute loading-con">
                        <img :src="`${url}/images/loading.gif`" width="70" alt="" />
                    </div>


                    <div class="card-header d-flex justify-content-between align-items-center">
                        Create Employee
                        <a :href="`${url}/admin/employee`" class="btn btn-success">
                        Employee List
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="col-sm-12" v-if="errors.length"><!-- col start -->
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Some fields require!</h4>
                                <div v-for="(arrEerror, arrIndex) in errors" :key="arrIndex">
                                    <div v-for="(error, index) in arrEerror" :key="index">
                                        <p class="mb-2">
                                            {{ index }}-{{ error }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- validation con end -->

                        <div class="col-sm-12" v-if="successFlag"><!-- col start -->
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Successfully Create!</h4>
                            </div>
                        </div><!-- validation con end -->


                        <form method="POST" @submit.prevent="createEmployee" :action="`${url}/admin/employee`">

                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" ref="first_name" name="first_name" required autocomplete="first_name" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" ref="last_name" name="last_name" required autocomplete="last_name" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" ref="email" name="email"  required autocomplete="email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">Phone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" ref="phone" name="phone" required autocomplete="phone" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" ref="is_company" name="is_company" id="is_company" @change="companyShow">

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
                                            <option :value="company.id"> {{ company.name}} </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button @click.prevent="createEmployee" type="submit" class="btn btn-primary">
                                        Create Employee
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
        name: "createEmployeeComponent",
        props: {
            req: {
                required: true,
            },
            base_url: {
                required: true,
            }
        },
        data(){
            return {
                errors: [],
                successFlag: false,
                companies: "",
                isLoading: false,
                url: this.base_url,
                companyFlag: false,
            }
        },
        mounted() {
            this.getCompanies();
        },
        methods: {
            createEmployee(){

                this.isLoading = true;
                this.errors = [];

                let data = {
                    "first_name": this.$refs.first_name.value,
                    "last_name": this.$refs.last_name.value,
                    "email": this.$refs.email.value,
                    "phone": this.$refs.phone.value,
                    "is_company": this.$refs.is_company.value,
                    "company_id": this.$refs.company_id.value,
                };

                this.req.post('/admin/employee', data)
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                            this.successFlag = true;
                            this.$refs.first_name.value = "";
                            this.$refs.last_name.value = "";
                            this.$refs.email.value = "";
                            this.$refs.phone.value = "";
                            this.$refs.is_company.value = "";
                            this.$refs.company_id.value = "";
                        }
                        else{
                            this.isLoading = false;
                        }
                    })
                    .catch( error => {
                        if (error.response.status == 422){
                            // console.log( error.response.data.errors );
                        }
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
            }
        }
    }
</script>

<style scoped>

</style>
