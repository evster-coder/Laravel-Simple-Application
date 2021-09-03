<template>
	<div>
		<h2 class="text-center">Количество книг по авторам</h2>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Имя</th>
					<th>Количество книг</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="author in authors" :key="author.id">
	                <td>{{ author.id }}</td>
	                <td>{{ author.name }}</td>
	                <td>{{ author.booksamount }}</td>
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
		this.$axios.get('/api/admin/authors-books')
			.then(response => {
				this.authors = response.data;
			})
			.catch(function (err) {
				console.log(err);
			});
	},

	beforeRouteEnter(to, from, next)
	{
		if(!window.Laravel.isLoggedin)
			window.location.href = "/login";
		next();
	}
}
</script>