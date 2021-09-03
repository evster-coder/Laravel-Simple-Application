<template>
	<div>
		<h2 class="text-center">{{titleForm}}</h2>
		<div class="mt-4 mb-4">
			<ValidationErrors :errors="validationErrors"
			v-if="validationErrors">
			</ValidationErrors>

			<form @submit.prevent="updateAuthor" 
						v-if="update">
                <div class="form-group">
					<label>Логин автора</label>
					<input type="text"  required class="form-control"
							 v-model="author.username">
				</div>

                <div class="form-group">
					<label>Пароль</label>
					<input type="password" required ref="passwordField" 
							class="form-control"
							 v-model="author.password" disabled>
					<a class="btn btn-danger" @click="enableReset()"
					>
						Смена пароля
					</a>
				</div>

                <div class="form-group">
					<label>Имя автора</label>
					<input type="text"  required class="form-control"
							 v-model="author.name">
				</div>


                <button type="submit" class="mt-3 btn btn-primary">
                	Обновить
                </button>
			</form>

			<form @submit.prevent="storeAuthor"
						v-else>
                <div class="form-group">
					<label>Логин автора</label>
					<input type="text"  required class="form-control"
							 v-model="author.username">
				</div>

                <div class="form-group">
					<label>Пароль</label>
					<input type="password" required class="form-control"
							 v-model="author.password">
				</div>

                <div class="form-group">
					<label>Имя автора</label>
					<input type="text"  required class="form-control"
							 v-model="author.name">
				</div>
                <button type="submit" class="mt-3 btn btn-primary">
                	Добавить автора
                </button>
			</form>
		</div>
	</div>
	
</template>

<script>
import ValidationErrors from '../components/ValidationErrors';

export default{
	data() {
		return {
			author: {},
			titleForm: "",
			update: false,
			validationErrors: '',
		}
	},
	components: {
		ValidationErrors
	},

	created()
	{
        //if its update form
		if(this.$route.params.id)
		{
			//get editing item
	        this.$axios.get('/sanctum/csrf-cookie').then(response => {
	            this.$axios.get(`/api/admin/authors/${this.$route.params.id}`)
	                .then(response => {
	                    this.author = response.data.author;
	                })
	                .catch(function (err) {
	                    console.log(err);
	                });
	        });

	        //set vars
			this.titleForm = "Редактирование автора";
			this.update = true;
		}
		else
		{
			//else it is adding item
			//set vars
			this.titleForm = "Добавление автора";
			this.update = false;
		}

	},

	methods: {
		//form validation for updating author
		updateAuthor()
		{
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.put(`/api/admin/authors/${this.$route.params.id}`, this.author)
                    .then(response => {
                        this.$router.push({name: 'authors'});
                    })
                    .catch(err => {
                    	if(err.response.status == 422)
                    		this.validationErrors = err.response.data.errors;
                    	else
                    		console.log(err);
                    });
            })
		},

		//form validation for creating author
		storeAuthor()
		{
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/admin/authors', this.author)
                    .then(response => {
                    	this.$router.push({name: 'authors'});
                    })
                    .catch(err => {
                    	console.log(err.response.data.errors);
                    	if(err.response.status == 422)
                    		this.validationErrors = err.response.data.errors;
                    });
            });
		},

		//set password reset field active
		enableReset()
		{
			let inputField = this.$refs.passwordField;
			inputField.removeAttribute("disabled");
		}
	}
}
</script>