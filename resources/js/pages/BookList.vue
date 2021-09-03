<template>
	<div>
		<h2 class="text-center">Авторства книг</h2>
		<ul class="list-group">
			<li v-for="book in books" class="list-group-item">
				{{book.name}} - {{book.author.name}}
			</li>
		</ul>
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

	beforeRouteEnter(to, from, next)
	{
		if(!window.Laravel.isLoggedin)
			window.location.href = "/login";
		next();
	}
}
</script>