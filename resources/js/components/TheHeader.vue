<template>
	<header
		:class="{ 'menu-activated': showMenu }"
		class="d-flex px-30 px-lg-60 flex-shrink-0 align-items-center"
	>
		<div class="d-flex flex-grow-1">
			<router-link
				:to="{ name: 'home' }"
				@click.native="hideMenu"
				class="logo line-height-1"
			>Second Online</router-link>
		</div>
		<div class="d-flex flex-grow-1 justify-content-end justify-content-md-center">
			<span
				@click="toggleMenu"
				class="menu-toggle"
			></span>
			<div class="menu overflow-hidden">	
				<div class="h-100 overflow-y">
					<ul class="px-30">
						<li @click="hideMenu"><router-link :to="{ name: 'home' }">Home</router-link></li>
						<li @click="hideMenu"><router-link :to="{ name: 'schedule' }">Schedule</router-link></li>
						<li @click="hideMenu"><router-link :to="{ name: 'sermons' }">Sermons</router-link></li>
						<li @click="hideMenu"><router-link :to="{ name: 'contact' }">Contact</router-link></li>
						<li><a href="https://pushpay.com/g/secondhouston?src=hpp" target="_blank">Give</a></li>
						<template v-if="isUserAuthenticated">
							<li
								v-if="isUserHost"
								@click="hideMenu"
							>
								<router-link :to="{ name: 'host' }">Host</router-link>
							</li>
							<li @click="hideMenu"><router-link :to="{ name: 'user.edit' }">My Account</router-link></li>
							<li @click="hideMenu">
								<span
									@click="logOut"
									class="clickable"
								>Logout</span>
							</li>
						</template>
						<template v-else>
							<li @click="hideMenu"><router-link :to="{ name: 'login', query: { redirect: $route.path } }">Login</router-link></li>
						</template>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-none d-md-flex flex-grow-1 justify-content-end">
			<router-link
				v-if="isUserAuthenticated"
				:to="{ name: 'user.edit'}"
				@click.native="hideMenu"
				class="user large font-weight-bold"
			>{{ user.name }}</router-link>
			<router-link
				v-else
				:to="{ name: 'login', query: { redirect: $route.path } }"
				@click.native="hideMenu"
				class="large font-weight-bold"
			>Login</router-link>
		</div>
	</header>
</template>

<script>
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'
	import { mapActions } from 'vuex'

	export default {
		data: function() {
			return {
				showMenu: false
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			...mapGetters([
				'isUserAuthenticated',
				'isUserHost'
			])
		},
		watch: {
			showMenu: function() {
		    	this.$emit('menu-toggled', this.showMenu);
			}
		},
		methods: {
			...mapActions([
				'logUserOut'
			]),
			hideMenu: function() {
				this.showMenu = false;
			},
		    toggleMenu: function() {
		    	this.showMenu = !this.showMenu;
		    },
		    logOut: function() {
				axios
					.post('/w/api/logout')
					.then(response => {
						console.log(response.data)
					})
					.catch(error => {
						console.log(error.response.status);
					})
					.then(() => {
						this.logUserOut();
						this.$router.push('/');
					});	
		    }
		}
	}
</script>