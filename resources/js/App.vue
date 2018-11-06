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
	import { mapMutations } from 'vuex'

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
				return this.$route.name.replace('.', '-');
			}
		},
		methods: {
			...mapMutations([
				'setNextBroadcast',
			]),
		    broadcastStatusChanged: function(broadcast) {
		    	console.log(broadcast);
		    	
		    	this.setNextBroadcast(broadcast);
		    	// if ((this.$router.currentRoute.name == 'broadcast' 
		    	// 	&& this.$refs.router.broadcast.id == broadcast.id)
		    	// 	|| this.$router.currentRoute.name == 'host') {

		    	// 	this.$refs.router.$_broadcastMixin_broadcastStatusChanged(broadcast);
		    	// }
		    }
		},
	    created: function() {
			Echo.channel('main')
				.listen('BroadcastOpen', data => {
					this.broadcastStatusChanged(data);
				})
				.listen('BroadcastStarting', data => {
					this.broadcastStatusChanged(data)
				})
				.listen('BroadcastClosed', data => {
					this.broadcastStatusChanged(data);
				});
	    }
	}
</script>
