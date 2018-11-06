<template>
	<div
		:class="{ 'overflow-hidden' : isMenuActive }"
		class="d-flex flex-column"
	>
		<the-header @menu-toggled="isMenuActive = $event"/>
		<main class="d-flex flex-grow-1 mh-0" role="main">
			<slot/>
		</main>
		<the-footer></the-footer>
	</div>
</template>

<script>
	import TheHeader from '../components/TheHeader'
	import TheFooter from '../components/TheFooter'

	export default {
		components: {
			TheHeader,
			TheFooter
		},
		data: function() {
			return {
				isMenuActive: false,
			}
		}
	}
</script>