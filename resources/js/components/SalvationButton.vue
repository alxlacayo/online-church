<template>
	<span 
		@click="showSalvationConfirmation"
		class="d-flex salvation-button align-items-center"
	>
		I want to accept Jesus Christ
		<span class="salvation-button-underline"></span>
	</span>
</template>

<script>
	export default {
		methods: {
			showSalvationConfirmation() {
				this.$emit('show-salvation-confirmation');
			}
		}
	}
</script>
