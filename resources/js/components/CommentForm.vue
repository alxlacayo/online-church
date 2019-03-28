<template>
	<div class="comment-box d-flex px-30 px-md-40 flex-shrink-0 overflow-y">
		<form class="w-100 m-auto">	
			<textarea
				:value="value"
				@input="input"
				@keydown.enter="submit"
				class="d-block px-0 py-10 w-100 form-control border-0"
				placeholder="Write a message.."
				rows="1"
			></textarea>
		</form>
	</div>
</template>

<script>
	export default {
		props: {
			value: String
		},
		data: function() {
			return {
				textarea: null
			}
		},
		watch: {
			value: function(newValue) {				
				if (newValue.length == 0) {
					this.clearTextarea();
				}
			}
		},
		methods: {
			input: function(e) {
				this.$emit('input', e.target.value);
				this.resizeTextarea();
			},
			submit: function(e) {
				this.$emit('submit');
				e.preventDefault();
			},
			resizeTextarea: function() {
				this.textarea.style.height = 'auto';
				this.textarea.style.height = this.textarea.scrollHeight + 'px';
			},
			clearTextarea: function() {
				this.textarea.value = '';
				this.resizeTextarea();
			}
		},
		mounted: function() {
			this.textarea = this.$el.querySelector('textarea');
		}
	}
</script>
