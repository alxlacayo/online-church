<template>
	<span 
		@click="showSalvationConfirmation"
		class="d-flex salvation-button align-items-center"
	>
		{{ buttonText }}
		<template v-if="!isSmallScreenButton">
			<span class="salvation-button-underline"></span>
		</template>
	</span>
</template>

<script>
	export default {
		props: {
			isSmallScreenButton: { 
				type: Boolean,
				default: true
			}
		},
		computed: {
			buttonText: function() {
				return this.isSmallScreenButton ? 'Accept Jesus Christ' : 'I want to accept Jesus Christ'; 
			}
		},
		methods: {
			showSalvationConfirmation() {
				this.$emit('show-salvation-confirmation');
			}
		}
	}
</script>
