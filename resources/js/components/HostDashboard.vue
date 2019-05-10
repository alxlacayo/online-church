<template>
	<div class="d-flex no-gutters flex-grow-1 mw-0">
		<div class="d-flex flex-column col-4 bg-black">
			<div class="bar px-30 px-md-40 d-flex align-items-center flex-shrink-0">
				<router-link
					:to="{ name: 'home' }"
					class="text-white"
				>Back</router-link>
			</div>
			<template v-if="$_broadcastMixin_isBroadcastLoaded">
				<video-player-living-as-one
					v-if="$_broadcastMixin_isBroadcastLive && $_broadcastMixin_isBroadcastInProgress"
					v-html="broadcast.embed_code"
					class="d-flex"
				/>
				<video-player-vimeo 
					v-if="!$_broadcastMixin_isBroadcastLive && $data.$_broadcastMixin_showVideo"
					:video-id="$_broadcastMixin_videoId"
					:time-elapsed="$_broadcastMixin_timeElapsed"
					:autoplay="autoplay"
					class="video-container bg-black"
				/>
				<div class="p-30 p-md-40 text-white overflow-auto">	
					<h1>{{ $_broadcastMixin_name }}</h1>
					<p>{{ $_broadcastMixin_description }}</p>
					<div
						v-if="$_broadcastMixin_hasNotes"
						v-html="broadcast.sermon.notes"
					></div>
				</div>
			</template>
		</div>
		<host-chat
			scroll-container-id="host-chat"
			class="d-flex flex-column col-4 bg-light-grey border-right"
		/>
		<div class="d-flex flex-column col-4">
			<div class="bar d-flex px-30 px-md-40 flex-shrink-0 align-items-center border-bottom">
				<span class="xlarge font-weight-bold">Broadcast chat</span>
			</div>
			<broadcast-chat
				v-if="$_broadcastMixin_isBroadcastOpen"
				ref="broadcastChat"
				:broadcast-id="broadcast.id"
				:border-on-comment-form="true"
				scroll-container-id="broadcast-comments"
			/>
		</div>
	</div>
</template>

<script>
	import VideoPlayerVimeo from '../components/VideoPlayerVimeo'
	import VideoPlayerLivingAsOne from '../components/VideoPlayerLivingAsOne'
	import HostChat from '../components/HostChat'
	import BroadcastChat from '../components/BroadcastChat'
	import broadcastMixin from '../mixins/broadcastMixin'
	import { mapState } from 'vuex'
	import { mapActions } from 'vuex'

	export default {
		components: {
			VideoPlayerVimeo,
			VideoPlayerLivingAsOne,
			HostChat,
			BroadcastChat
		},
		mixins: [broadcastMixin],
		data: function() {
			return {
			}
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get('/w/api/host/authorize')
				.then(response => next())
				.catch(error => {
					switch (error.response.status) {
						case 401:
							next('login');
							break;
						default:
							next('/');
					}
				});
		},
		computed: {
			...mapState([
				'broadcasts',
			]),
			broadcast: function() {
				return this.broadcasts[0];
			},
			autoplay: function() {
				return this.$_broadcastMixin_isBroadcastInProgress;
			}
		},
		watch: {
			broadcast: function(val) {
				console.log(val.status);
			}
		},
		methods: {
			...mapActions([
				'updateBroadcast',
			])
		},
		created: function() {
			// Load the current broadcast data from the server.
			axios
				.get('/w/api/broadcasts/' + this.broadcast.id)
				.then(response => {
					this.updateBroadcast(response.data.broadcast);
					this.$refs.broadcastChat.comments.unshift(...response.data.comments);
					//this.$data.$_broadcastMixin_showVideo = true;
				})
				.catch(error => {
					//
				});
		}
	}
</script>