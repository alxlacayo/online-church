<template>
	<div class="d-flex px-30 flex-column flex-grow-1 align-items-center justify-content-center">
	 	<h1>Reset Password</h1>
		<form
			v-on:submit.prevent="register"
			class="form-narrow"
		>
			<ul
				v-if="Object.keys(alerts).length"
				class="mb-10 list-unstyled text-left"
			>
				<li
					v-for="alert in alerts"
					class="mb-0 py-0 text-danger"
				>
					{{ alert }}
				</li>
			</ul>
			<div class="form-group mb-10">
				<label for="email" class="sr-only font-weight-bold">Email</label>
				<input
					id="email"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.email ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="email"
					type="email"
					placeholder="Email"
				>
			</div>
			<button
				class="mb-32 py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Email password reset link</button>
		</form>
		<router-link v-bind:to="loginURL">
			Nvm, I know my password.
			<span class="ml-4 font-weight-bold">Login</span>
		</router-link>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				alerts: {},
				email: '',
				isLoading: false
			}
		},
		computed: {
			redirectPath: function() {
				return (typeof this.$route.query.redirect !== 'undefined')
					? this.$route.query.redirect
					: '/';
			},
			query: function() {
				return this.redirectPath != '/' ? '?redirect=' + this.redirectPath : null;
			},
			loginURL: function() {
				return this.query ? '/login' + this.query : '/login';
			}
		},
		methods: {
			register: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }
 
				axios
					.post('/w/api/password/email', {
						email: this.email,
					})
					.then(response => {
						this.setAlerts(response.data);
					})
					.catch(error => {
						if (typeof error.response.data.errors !== undefined) {
							this.setAlerts(error.response.data.errors);
						} else {
							this.setAlerts({error: 'Something went wrong. Try again.'});
						}
					})
					.then(() => {
						this.isLoading = false;
					});

				this.isLoading = true;
			},
			setAlerts: function(alerts) {
				const newAlerts = {};

				for (const property in alerts) {
					if (typeof alerts[property] === 'object') {
						newAlerts[property] = alerts[property][0];
					} else {
						newAlerts[property] = alerts[property];
					}
				}

				this.alerts = newAlerts;
			},
			isFormValid: function() {
				const newAlerts = {};
				let isFormValid = true;

				if (this.email.length == 0) {
					newAlerts.email = 'Enter a valid email address.';
					isFormValid = false;
				}

				this.setAlerts(newAlerts);

				return isFormValid;
			}
		}
	}
</script>