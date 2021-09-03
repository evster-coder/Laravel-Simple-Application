<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-danger" role="alert" v-if="error !== null">
                    {{ error }}
                </div>

                <div class="card card-default">
                    <div class="card-header">Авторизация</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row mb-3">
                                <label for="username" class="col-sm-4 col-form-label text-md-right">Логин</label>
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" v-model="username" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="submitLogin">
                                        Войти
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
    data() {
        return {
            username: "",
            password: "",
            error: null
        }
    },
    methods: {
        //validate user data and send request to api for login
        submitLogin(e) {
            e.preventDefault()

            //data isnt empty
            if (this.password.length > 0 && this.username.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/login-admin', {
                        username: this.username,
                        password: this.password
                    }) 
                        .then(response => {
                            //if we login need to redirect for /books
                            if (response.data.success) {
                                console.log(response.data.author.admin);
                                this.$router.go('/books')
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        }
    },

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('books');
        }
        next();
    }
}
</script>
