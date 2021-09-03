<template>
	<div>
		<h2 class="text-center">{{titleForm}}</h2>
		<div class="mt-4 mb-4">
			<ValidationErrors :errors="validationErrors"
			v-if="validationErrors">
			</ValidationErrors>

			<form @submit.prevent="updateBook" 
						v-if="update">
                <div class="form-group">
					<label>Название книги</label>
					<input type="text"  required class="form-control"
							 v-model="book.name">
				</div>

				<div class="form-group">
					<label>Автор книги</label>
					<select class="form-select" 
							 v-model="book.author_id">
						<option v-for="option in options"
								v-bind:value="option.id">
						{{option.name}}
						</option>
				 	</select>
				</div>

                <div class="form-group">
					<label>Год издания</label>
					<input type="number" required class="form-control" max="3000"
							 v-model="book.year">
				</div>

				<div class="form-group">
					<label>Издательство</label>
					<input type="text" required class="form-control" 
							 v-model="book.publisher">
				</div>

                <button type="submit" class="mt-3 btn btn-primary">
                	Обновить
                </button>
			</form>

			<form @submit.prevent="storeBook"
						v-else>
                <div class="form-group">
					<label>Название книги</label>
					<input type="text" required class="form-control"
							 v-model="book.name">
				</div>

				<div class="form-group">
					<label>Автор книги</label>
					<select class="form-select" 
							 v-model="book.author_id">
						<option v-for="option in options"
								v-bind:value="option.id">
						{{option.name}}
						</option>
				 	</select>
				</div>

                <div class="form-group">
					<label>Год издания</label>
					<input type="number" required class="form-control" max="3000"
							 v-model="book.year">
				</div>

				<div class="form-group">
					<label>Издательство</label>
					<input type="text" required class="form-control" 
							 v-model="book.publisher">
				</div>

                <button type="submit" class="mt-3 btn btn-primary">
                	Добавить книгу
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
			book: {},
			titleForm: "",
			update: false,
			options: [],
			validationErrors: '',
		}
	},
	components: {
		ValidationErrors
	},

	created()
	{
		//get options for authors select
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/admin/authors')
                .then(response => {
                    this.options = response.data;
                })
                .catch(function (err) {
                    console.log(err);
                });
        });


        //if its update form
		if(this.$route.params.id)
		{
			//get editing item
	        this.$axios.get('/sanctum/csrf-cookie').then(response => {
	            this.$axios.get(`/api/admin/books/${this.$route.params.id}`)
	                .then(response => {
	                    this.book = response.data.book;
	                })
	                .catch(function (err) {
	                    console.log(err);
	                });
	        });

	        //set vars
			this.titleForm = "Редактирование книги";
			this.update = true;
		}
		else
		{
			//else it is adding item
			//set vars
			this.titleForm = "Добавление книги";
			this.update = false;
		}

	},

	methods: {
		//form validation for updating book
		updateBook()
		{
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.put(`/api/admin/books/${this.$route.params.id}`, this.book)
                    .then(response => {
                        this.$router.push({name: 'books'});
                    })
                    .catch(err => {
                    	if(err.response.status == 422)
                    		this.validationErrors = err.response.data.errors;
                    	else
                    		console.log(err);
                    });
            })
		},

		//form validation for creating book
		storeBook()
		{
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/admin/books', this.book)
                    .then(response => {
                    	this.$router.push({name: 'books'});
                    })
                    .catch(err => {
                    	if(err.response.status == 422)
                    		this.validationErrors = err.response.data.errors;
                    });
            });
		}
	}
}
</script>