<template>
	<div>
		<h2 class="text-center">Книги</h2>
		<div class="mt-4 mb-4">
			<router-link class="btn btn-primary" to="/store-book">
				Добавить
			</router-link>
		</div>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Наименование</th>
					<th>Год выпуска</th>
					<th>Издатель</th>
					<th>Автор</th>
					<th>Действия</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="book in books" :key="book.id">
	                <td>{{ book.id }}</td>
	                <td>{{ book.name }}</td>
	                <td>{{ book.year }}</td>
	                <td>{{ book.publisher }}</td>
	                <td>{{ book.author.name }}</td>
	                <td>
	                	<router-link :to="{name:'update-book',
	                					 params:{id: book.id}}"
                						class="btn btn-secondary"> 
    						Изменить 
    					</router-link>
    					<button class="btn btn-danger" @click="destroy(book.id)">
    						Удалить
    					</button>
    				</td>
				</tr> 
			</tbody>
		</table>
	</div>
</template>

<script>
export default {
    data() {
        return {
            books: []
        }
    },

	created() 
	{
		//load data from api
		this.$axios.get('/api/admin/books')
			.then(response => {
				this.books = response.data;
			})
			.catch(function (err) {
				console.log(err);
			});
	},

	methods: {

		//delete book
		destroy(id) 
		{
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/admin/books/${id}`)
                    .then(response => {
                		//find this item and erase from html table
                        let i = this.books.map(item => item.id).indexOf(id);
                        this.books.splice(i, 1)
                    })
                    .catch(function (err) {
                        console.error(err);
                    });
            });

		}
	},
	beforeRouteEnter(to, from, next)
	{
		if(!window.Laravel.isLoggedin)
			window.location.href = "/login";
		next();
	}
}
</script>