<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card position-relative">

                    <div :class="{'d-none': !isLoading}" class="d-flex justify-content-center align-items-center position-absolute loading-con">
                        <img :src="`${url}/images/loading.gif`" width="70" alt="" />
                    </div>

                    <div class="card-header d-flex justify-content-between align-items-center">
                        Create Company
                        <a :href="`${url}/admin/company`" class="btn btn-success">
                        Companies List
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

                        <form method="POST" action="/admin/company" enctype="multipart/form-data">

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end"> Company Name </label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" ref="name" name="name" required autocomplete="name" autofocus>

                                    <span class="invalid-feedback" role="alert">
                                        <strong> Message </strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end"> Email Address </label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" ref="email" name="email" autocomplete="email">

                                    <span class="invalid-feedback" role="alert">
                                        <strong>Message</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="logo" class="col-md-4 col-form-label text-md-end"> Company Logo </label>

                                <div class="col-md-6">
                                    <input id="logo" type="file" ref="logo" class="form-control" name="logo" @change="onFileChange">
                                    <img width="100" alt="Company logo" v-if="imageUrl" :src="imageUrl" />

                                    <span class="invalid-feedback" role="alert">
                                        <strong>Message</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button @click.prevent="createCompany" type="submit" class="btn btn-primary">
                                        Create Company
                                    </button>
                                </div>
                            </div><!-- row end -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        name: "createCompanyComponent",
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
                currentCompany: "",
                isLoading: false,
                imageUrl: "",
                image: "",
                url: this.base_url,
            }
        },
        mounted() {
        },
        methods: {
            createCompany(){

                this.isLoading = true;
                this.errors = [];

                this.req.post('/admin/company', {
                    "name": this.$refs.name.value,
                    "email": this.$refs.email.value,
                    "logo": this.image,
                    "logo_url": this.imageUrl,
                })
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                            this.successFlag = true;
                            this.$refs.name.value = "";
                            this.$refs.email.value = "";
                            this.$refs.logo.value = "";
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

            onFileChange(e) {
                this.isLoading = true;
                this.errors = [];

                const file = e.target.files[0];
                this.imageUrl = URL.createObjectURL(file);

                let data = new FormData();
                data.append("logo", file );


                this.req.post('/upload-image', data)
                    .then( response => {
                        if( response.data.code === 0 ){
                            this.isLoading = false;
                            this.imageUrl = response.data.data.logo_url;
                            this.image = response.data.data.logo;
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
        }
    }
</script>

<style scoped>

</style>
