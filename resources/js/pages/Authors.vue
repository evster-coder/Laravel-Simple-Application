<template>
	<div>
		<h2 class="text-center">Авторы</h2>
		<div class="mt-4 mb-4">
			<router-link class="btn btn-primary" to="/store-author">
				Добавить
			</router-link>
		</div>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Имя</th>
					<th>Логин</th>
					<th>Действия</th>
				</tr>
			</thead>
			<tbody>
				<td colspan="4" v-if="authors == null">
					Записи отсутствуют 
				</td>
				<tr v-for="author in authors" :key="author.id">
	                <td>{{ author.id }}</td>
	                <td>{{ author.name }}</td>
	                <td>{{ author.username }}</td>
	                <td>
	                	<router-link :to="{name:'update-author',
	                					 params:{id: author.id}}"
                						class="btn btn-secondary"> 
    						Изменить 
    					</router-link>
    					<button class="btn btn-danger" 
    								@click="destroy(author.id)">
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
            authors: []
        }
    },

	created() 
	{
		//load data from api
		this.$axios.get('/api/admin/authors')
			.then(response => {
				this.authors = response.data;
			})
			.catch(function (err) {
				console.log(err);
			});
	},

	methods: {
		destroy(id) 
		{
	   		if(confirm("Запись будет удалена?"))
	   		{
			//delete author
	            this.$axios.get('/sanctum/csrf-cookie').then(response => {
	                this.$axios.delete(`/api/admin/authors/${id}`)
	                    .then(response => {
	                    	//find this item and erase from html table
	                        let i = this.authors.map(item => item.id).indexOf(id);
	                        this.authors.splice(i, 1)
	                    })
	                    .catch(function (err) {
	                        console.error(err);
	                    });
	            });

			}
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