<template>
	<div class="d-flex no-gutters flex-grow-1">
		<div class="d-flex col-4 flex-column flex-grow-1 bg-black">
			<div class="bar px-30 px-md-40 d-flex align-items-center flex-shrink-0">
				<router-link
					:to="{ name: 'home' }"
					class="text-white"
				>Back</router-link>
			</div>
			<template v-if="$_broadcastMixin_isBroadcastLoaded">
				<video-player-living-as-one
					v-if="$_broadcastMixin_isBroadcastLive && $_broadcastMixin_isBroadcastInProgress"
				>
					<div
						v-html="broadcast.embed_code"
						class="video-container"
					></div>
				</video-player-living-as-one>
				<video-player-vimeo 
					v-else
					:video-id="$_broadcastMixin_videoId"
					:time-elapsed="$_broadcastMixin_timeElapsed"
					:autoplay="autoplay"
					class="video-container bg-black"
				/>
				<div 
					v-if="$_broadcastMixin_isBroadcastLive"
					class="p-30 p-md-40 text-white overflow-y"
				>
					<h1>{{ broadcast.name }}</h1>
					<p>{{ broadcast.description }}</p>
				</div>
				<div
					v-else-if="$_broadcastMixin_hasNotes"
					v-html="broadcast.sermon.notes"
					class="p-30 p-md-40 text-white overflow-y"
				></div>
			</template>
		</div>
		<div class="d-flex flex-column col-4 border-left border-right bg-light-grey">
			<div class="bar d-flex px-30 px-md-40 flex-shrink-0 align-items-center justify-content-between border-bottom">
				<span class="xlarge font-weight-bold">Host chat</span>
				<span class="small text-muted">
					<span
						v-if="hosts && hosts.length > 0"
						class="online-icon"
					></span>
					<span>{{ hosts.length }} {{ hosts.length == 1 ? 'host' : 'hosts' }} online</span>
				</span>
			</div>
			<host-chat
				:previousComments="hostComments"
				@hosts-update="hosts = $event"
				scroll-container-id="host-chat"
			/>
		</div>
		<div class="d-flex flex-column col-4">
			<div class="bar d-flex px-30 px-md-40 flex-shrink-0 align-items-center border-bottom">
				<span class="xlarge font-weight-bold">Broadcast chat</span>
			</div>
			<broadcast-chat
				v-if="$_broadcastMixin_isBroadcastLoaded && $_broadcastMixin_isBroadcastOpen"
				:broadcast-id="broadcast.id"
				scroll-container-id="broadcast-comments"
			></broadcast-chat>
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
				hosts: [],
				hostComments: []
			}
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get('/w/api/host/dashboard')
				.then(response => {
					next(vm => vm.setData(response.data));
				})
				.catch(error => {
					if (error.response.status === 401) { 
						next('login');
					} else {
						next('/');
					}
				});
		},
		computed: {
			...mapState([
				'nextBroadcast',
			]),
			broadcast: function() {
				return this.nextBroadcast;
			},
			autoplay: function() {
				return this.$_broadcastMixin_isBroadcastInProgress;
			}
		},
		methods: {
			setData: function(data) {
				this.hostComments.push(...data.host_comments);
			}
		}
	}
</script>