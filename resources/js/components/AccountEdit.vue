<template>
	<div class="d-flex pt-45 px-30 flex-column flex-grow-1 align-items-center justify-content-center">
	 	<h1>Edit Profile</h1>
	 	
		<form enctype="multipart/form-data">
			<div class="d-flex mb-32 align-items-center justify-content-center picture-upload-wrapper">
				<img :src="user.profile_picture" class="profile-picture profile-picture-large">
				<div></div>
			    <input type="file" @change="uploadProfilePicture" ref="fileInput">
		    </div>
		</form>

		<form
			@submit.prevent="updateUser"
			class="form-narrow"
		>
			<ul
				v-if="Object.keys(errors).length"
				class="mb-10 list-unstyled text-left"
			>
				<li
					class="mb-0 py-0 text-danger"
					v-for="error in errors">{{ error }}
				</li>
			</ul>
			<div
				v-show="saved"
				class="mb-10"
			>
				<span>Your profile has been updated.</span>
			</div>
			<div class="form-group mb-10">
				<label for="name" class="sr-only font-weight-bold">Name</label>
				<input
					id="name"
					class="form-control p-24 w-100 bg-light-grey"
					:class="[ errors.name ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="name"
					type="text"
					placeholder="Name"
				>
			</div>
			<div class="form-group mb-10">
				<label for="email" class="sr-only font-weight-bold">Email</label>
				<input
					id="email"
					class="form-control p-24 w-100 bg-light-grey"
					:class="[ errors.email ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="email"
					type="email"
					placeholder="Email"
				>
			</div>
			<button
				class="mb-32 py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Save</button>
		</form>
		<!-- <span @click="deleteProfilePicture">Delete picture</span> -->
	</div>
</template>

<script>
	import apiResponseMixin from '../mixins/apiResponseMixin'
	import { mapState } from 'vuex'
	import { mapMutations } from 'vuex'

	export default {
		mixins: [apiResponseMixin],
		data: function() {
			return {
				errors: {},
				name: null,
				email: null,
				saved: false
			}
		},
		computed: {
			...mapState([
				'user',
			]) 
		},
		methods: {
			...mapMutations([
				'setUser',
				'setUserProfilePicture'
			]),
			uploadProfilePicture: function(e) {
				const picture = e.target.files[0];
				const formData = new FormData();

				formData.append('_method', 'PATCH');
				formData.append('picture', picture);

				axios
					.post('/w/api/user/picture', formData, {
					    headers: {
					      'Content-Type': 'multipart/form-data'
					    }
					})
					.then(response => {
						this.setUserProfilePicture(response.data.profile_picture);
						this.saved = true;
					})
					.catch(error => {
 						alert('Something went wrong. Try again.');
					});

				this.$refs.fileInput.value = '';
				this.saved = false;
			},
			deleteProfilePicture: function() {
				axios
					.post('/w/api/user/picture', {
						_method: 'DELETE'
					})
					.then(response => {
						this.setUserProfilePicture(response.data.profile_picture);
					})
					.catch(error => {
 
					});
			},
			updateUser: function() {
				axios
					.post('/w/api/user/edit', {
						_method: 'PATCH',
						name: this.name,
						email: this.email
					})
					.then(response => {
						this.setUser(response.data);
						this.saved = true;
					})
					.catch(error => {
						if (error.response.status == 422) {
							this.$_apiResponseMixin_setErrors(error.response.data.errors);
						} else {
							alert('Something went wrong. Try again.');
						}
					})
					.then(() => {
					
					});

				this.saved = false;
			}
		},
		created: function() {
			this.name = this.user.name;
			this.email = this.user.email;
		}
	}
</script>