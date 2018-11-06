<template>
	<div class="p-30">
		<editor v-bind:api-key="tinyMCEKey" v-bind:init="options" v-model="content"></editor>
		<button v-on:click="submit">Submit</button>
	</div>
</template>

<script>
	import Editor from '@tinymce/tinymce-vue';

	export default {
		components: {
			Editor
		},
		data: function() {
			return {
				content: '',
				tinyMCEKey: process.env.MIX_TINY_MCE_KEY,
				options: {
				    menubar:false,
    				statusbar: false,
					plugins: 'lists',
					toolbar: 'undo redo formatselect | bold italic underline strikethrough | bullist numlist',
					height: 600
				}

			}
		},
		methods: {
			submit: function() {
				console.log(this.content);

				axios
					.post('/w/api/admin/sermons/1/edit', {
						_method: 'PUT',
						description: this.content
					})
					.then(response => {
 					
					})
					.catch(error => {

					});
			}
		}
	}

</script>