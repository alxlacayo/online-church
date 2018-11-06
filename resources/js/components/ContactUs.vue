<template>
	<div class="container-fluid px-30 px-lg-60 pt-45">
		<div class="row">
			<div class="col col-md-8 offset-md-2 col-lg-6 offset-lg-3 text-center">
				<h1>How can we help you?</h1>
				<p class="px-0 px-lg-60">Send us a message and we'll get back to you as soon as possible. </p>
		  
				<form @submit.prevent="submit">
					<div class="form-group mb-10">
						<input 
							v-model="email"
							class="form-control p-24 w-100 bg-light-grey border-0"
							type="email"
							placeholder="Email"
						>	
					</div>
					<div class="form-group mb-10">
						<input
							v-model="name"
							class="form-control p-24 w-100 bg-light-grey border-0"
							type="text"
							placeholder="Name"
						>	
					</div>
					<textarea
						v-model="message"
						class="d-block mb-10 p-24 w-100 bg-light-grey border-0"
						placeholder="Message"
						rows="6"
					></textarea>
					<button
						class="py-24 w-100 border-0 text-white bg-black" 
						type="submit"
					>Send message</button>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				email: null,
				name: null,
				message: null
			}
		},
		methods: {
			submit: function() {
				axios
					.post('/w/api/contact', {
						email: this.email,
						name: this.name,
						message: this.message
					})
					.then(response => {
						console.log(response.data);
					})
					.catch(error => {

					})
					.then(() => {

					});
			}
		}
	}
</script>