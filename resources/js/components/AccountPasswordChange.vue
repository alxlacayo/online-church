<template>
	<div class="d-flex px-30 flex-column flex-grow-1 align-items-center justify-content-center">
		<h1>Reset Password</h1>
		<form
			v-on:submit.prevent="resetPassword"
			class="form-narrow"
		>
			<ul
				v-if="Object.keys(alerts).length"
				class="mb-10 list-unstyled text-left"
			>
				<li
					class="mb-0 py-0 text-danger"
					v-for="alert in alerts">{{ alert }}
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
			<div class="form-group mb-10">
				<label for="password" class="sr-only font-weight-bold">Password</label>
				<input
					id="password"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.password ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="password"
					type="password"
					placeholder="Password"
				>
			</div>
			<div class="form-group mb-10">
				<label for="confirmPassword" class="sr-only font-weight-bold">Confirm Password</label>
				<input
					id="confirmPassword"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.password ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="confirmPassword"
					type="password"
					placeholder="Confirm Password"
				>
			</div>
			<button
				class="py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Reset Password</button>
		</form>
	</div>
</template>
<script>
	import { mapMutations } from 'vuex'

	export default {
		data: function() {
			return {
				alerts: {},
				email: '',
				password: '',
				confirmPassword: '',
				token: this.$route.params.token,
				isLoading: false
			}
		},
		methods: {
			...mapMutations([
				'setUser',
			]),
			resetPassword: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

				axios
					.post('/w/api/password/reset', {
						email: this.email,
						password: this.password,
						password_confirmation: this.confirmPassword,
						token: this.token
					})
					.then(response => {
						this.setUser(response.data);

						this.$router.push('/');
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

				if (this.password.length < 6) {
					newAlerts.password = 'Password must be at least 6 characters.';
					isFormValid = false;
				}

				if (this.password != this.confirmPassword) {
					newAlerts.password = 'Passwords do not match.';
					isFormValid = false;
				}
				this.setAlerts(newAlerts);

				return isFormValid;
			}
		}
	}
</script>