<template>
	<div class="d-flex px-30 flex-column flex-grow-1 align-items-center justify-content-center">
		<h1>Login.</h1>
		<form
			v-on:submit.prevent="login"
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
					v-model="email"
					:class="[ alerts.email ? 'border border-1 border-danger' : 'border-0' ]"
					id="email"
					class="form-control p-24 w-100 bg-light-grey"
					type="email"
					placeholder="Email"
				>
			</div>
			<div class="form-group mb-10"> 
				<label for="password" class="sr-only font-weight-bold">Password</label>
				<div
					:class="[ alerts.password ? 'border border-1 border-danger' : 'border-0' ]"
					class="d-flex align-items-center bg-light-grey"
				>
					<input
						v-model="password"
						id="password"
						class="form-control p-24 flex-grow-1 bg-light-grey border-0"
						type="password"
						placeholder="Password"
					>
					<router-link
						class="mx-24 font-weight-bold"
						:to="passwordResetURL">
							Reset
					</router-link>
				</div>
			</div>
			<button
				class="mb-32 py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Login</button>
		</form>
		<router-link :to="registerURL">
			Dont have an account?
			<span class="ml-4 font-weight-bold">Sign up</span>
		</router-link>
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
			registerURL: function() {
				return this.query ? '/register' + this.query : '/register';
			},
			passwordResetURL: function() {
				return this.query ? '/password/reset' + this.query : '/password/reset';
			}
		},
		methods: {
			...mapMutations([
				'setUser',
			]),
			login: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

				axios
					.post('/w/api/login', {
						email: this.email,
						password: this.password
					})
					.then(response => {
						this.setUser(response.data);

						this.$router.push(this.redirectPath);
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

				this.setAlerts(newAlerts);

				return isFormValid;
			}
		}
	}
</script>