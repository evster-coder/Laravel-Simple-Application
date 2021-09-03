<template>
	<div v-if="validationErrors">
    <ul class="alert alert-danger">
        <li class="ms-2" v-for="(value, key, index) in validationErrors">@{{ value }}</li>
    </ul>
    </div>
</template>

<script>
export default{
	data(){
		return{

		}
	},
	props: ['errors'],
	
    computed: {
    	//set error to handy view 
	    validationErrors(){
	        let errors = Object.values(this.errors);
	        errors = errors.flat();
	        return errors;
    	}
	}
}
</script>