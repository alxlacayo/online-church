<template>
	<component
		:is="layout"
		:class="pageName"
	>
		<router-view ref="router"></router-view>
	</component>
</template>

<script>
	import DefaultLayout from './layouts/DefaultLayout'
	import NoHeaderLayout from './layouts/NoheaderLayout'
	import HostLayout from './layouts/HostLayout'
	import { mapState } from 'vuex'
	import { mapActions } from 'vuex'

	export default {
		components: {
			DefaultLayout,
			NoHeaderLayout,
			HostLayout
		},
		computed: {
			...mapState([
				'layout',
			]),
			pageName: function() {
				return this.$route.name !== null
					? this.$route.name.replace('.', '-')
					: '';
			}
		},
		methods: {
			...mapActions([
				'updateBroadcast',
			])
		},
	    created: function() {
			Echo.channel('main')
				.listen('Broadcast\\BroadcastOpen', data => {
					this.updateBroadcast(data);
				})
				.listen('Broadcast\\BroadcastInProgress', data => {
					this.updateBroadcast(data);
				})
				.listen('Broadcast\\BroadcastClosed', data => {
					//this.broadcastStatusChanged(data);
				})
				.listen('Broadcast\\BroadcastChanged', data => {
					this.updateBroadcast(data);
				});
	    }
	}
</script>
