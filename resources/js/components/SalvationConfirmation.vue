<template>
	<div
		@click.self="hideSalvationConfirmation"
		v-bind:class="[ isSalvationConfirmationVisible ? 'd-flex' : 'd-none' ]"
		class="salvation-confirmation-wrapper justify-content-center align-items-center"
	>
		<div class="salvation-confirmation">
			<span
				@click="hideSalvationConfirmation"
				class="close close-grey"
			></span>
			<span class="salvation-confirmation-emoji">🙌</span>
			<span class="salvation-confirmation-title">Congratulations!</span>
			<span class="salvation-confirmation-description">Click continue to fill out your commitment form and one of our team members will contact you!</span>
			<a href="https://fs17.formsite.com/second/form190/index.html" target="_blank" class="salvation-confirmation-btn">Continue</a>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			isSalvationConfirmationVisible: Boolean
		},
		methods: {
		    hideSalvationConfirmation () {
		    	this.$emit('hide-salvation-confirmation');
		    }
		}
	}
</script>