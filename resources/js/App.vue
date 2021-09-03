<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <a class="navbar-brand">Админка</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <!-- Navbar for authorized users-->
                <div class="navbar-nav" v-if="isLoggedIn">
                    <router-link to="/authors" class="nav-item nav-link">
                        Авторы
                    </router-link>
                    <router-link to="/books" class="nav-item nav-link">
                        Книги
                    </router-link>
                    <router-link to="/book-list" class="nav-item nav-link">
                        Книги с авторами
                    </router-link>
                    <router-link to="/author-stat" class="nav-item nav-link">
                        Количества книг
                    </router-link>
                    <a class="nav-item nav-link" style="cursor:pointer;" @click="logout">
                        Выйти
                    </a>
                </div>

                <!-- Navbar for unauthorized users-->
                <div class="navbar-nav me-2" v-else>
                    <router-link to="/login" class="nav-item nav-link">
                        Войти
                    </router-link>
                </div>
            </div>
    </div>

        </nav>
        <br/>

        <router-view/>

    </div>
</template>

<script>
    export default {
    name: "App",

    data() {
        return {
            isLoggedIn: false,
        }
    },

    created() {
        //user is authorised
        if (window.Laravel.isLoggedin)
            this.isLoggedIn = true;
    },

    methods: {
        logout(e) {
            e.preventDefault();

            //send request to initialize csrf protection
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                //then sign out to app
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/login";
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        }
    },
}
</script>

