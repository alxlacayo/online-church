<template>
	<div
		v-bind:class="{ 'overflow-hidden' : isMenuActive }"
		class="d-flex flex-column h-100"
	>
		<!-- <the-host-header @menu-toggled="isMenuActive = $event"/> -->
		<main class="d-flex flex-grow-1 mh-0" role="main">
			<slot/>
		</main>
	</div>
</template>

<script>
	import TheHostHeader from '../components/TheHostHeader'

	export default {
		components: {
			TheHostHeader
		},
		data: function() {
			return {
				isMenuActive: false,
			}
		}
	}
</script>