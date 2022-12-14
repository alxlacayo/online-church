<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1 mw-0">
		<template v-if="$_broadcastMixin_isBroadcastLoaded && $_broadcastMixin_isBroadcastOpen">
			<div class="d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 justify-content-between overflow-auto bg-black">
				<div class="d-flex mx-30 mx-md-60 flex-shrink-0 align-items-center bar">
					<span
						@click="goBack"
						class="close"
					></span>
				</div>
				<div class="d-flex mx-0 mx-lg-60 flex-md-grow-1 flex-shrink-1 video-wrapper">	
					<video-player-living-as-one
						v-if="$_broadcastMixin_isBroadcastLive && $_broadcastMixin_isBroadcastInProgress"
						v-html="broadcast.embed_code"
						class="d-flex flex-grow-1"
					/>
					<video-player-vimeo
						v-if="!$_broadcastMixin_isBroadcastLive && $data.$_broadcastMixin_showVideo"
						:video-id="$_broadcastMixin_videoId"
						:time-elapsed="$_broadcastMixin_timeElapsed"
						class="px-0 px-lg-60"
					/>
				</div>
				<div class="d-none d-md-flex flex-shrink-0 justify-content-center bar">
					<salvation-button
						@show-salvation-confirmation="$_salvationMixin_showSalvationConfirmation"
						:isSmallScreenButton="false"
					/>
				</div>
			</div>
			<broadcast-chat
				ref="broadcastChat"
				:broadcast-id="broadcast.id"
				:scoll-to-bottom-on-load="false"
				scroll-container-id="broadcast-comments"
				class="video-sidebar bg-light-grey"
			>
				<salvation-button
					@show-salvation-confirmation="$_salvationMixin_showSalvationConfirmation"
					class="d-md-none flex-shrink-0 justify-content-center salvation-button--small-screen"
				/>
				<div class="px-30 px-md-40 py-40 bg-white">
					<h1>{{ $_broadcastMixin_name }}</h1>
					<p>{{ $_broadcastMixin_description }}</p>
					<template v-if="$_broadcastMixin_hasNotes">
						<p
							@click="toggleNotes"
							class="font-weight-bold clickable"
						>
							{{ showNotes ? 'Hide notes' : 'See notes' }}
						</p>
						<div
							v-show="showNotes"
							v-html="broadcast.sermon.notes"
						></div>
					</template>
				</div>				 
			</broadcast-chat>
		</template>
		<template v-if="$_broadcastMixin_isBroadcastLoaded && $_broadcastMixin_isBroadcastClosed">
			<span>{{ $_broadcastMixin_nextBroadcastTime }}</span>
		</template>
		<salvation-confirmation
			@hide-salvation-confirmation="$_salvationMixin_hideSalvationConfirmation"
			:isSalvationConfirmationVisible="$data.$_salvationMixin_isSalvationConfirmationVisible"
		/>
	</div>
</template>

<script>
	import VideoPlayerVimeo from '../components/VideoPlayerVimeo'
	import VideoPlayerLivingAsOne from '../components/VideoPlayerLivingAsOne'
	import BroadcastChat from '../components/BroadcastChat'
	import SalvationButton from '../components/SalvationButton'
	import SalvationConfirmation from '../components/SalvationConfirmation'
	import broadcastMixin from '../mixins/broadcastMixin'
	import salvationMixin from '../mixins/salvationMixin'
	import { mapState } from 'vuex'
	import { mapActions } from 'vuex'

	export default {
		components: {
			VideoPlayerVimeo,
			VideoPlayerLivingAsOne,
			BroadcastChat,
			SalvationButton,
			SalvationConfirmation
		},
		mixins: [
			broadcastMixin,
			salvationMixin
		],
		data: function() {
			return {
				showNotes: false,
				previousPage: null
			}
		},
		beforeRouteEnter (to, from, next) {
			next(vm => vm.previousPage = from);		
		},
		computed: {
			...mapState([
				'broadcasts',
			]),
			broadcast: function() {
				let broadcastId = parseInt(this.$route.params.broadcast_id);
				let index = this.broadcasts.findIndex(broadcast => broadcast.id == broadcastId);

				return index > -1 ? this.broadcasts[index] : null;
			},
		},
		methods: {
			...mapActions([
				'updateBroadcast',
			]),
	        toggleNotes: function() {
	        	this.showNotes = !this.showNotes;
	        },
		    goBack () {
		    	if (this.previousPage.name === null) {
		    		this.$router.push({name: 'home'});
		    	} else {
		    		this.$router.go(-1);
		    	}
		    }
		},
		created: function() {
			// Load the current broadcast data from the server.
			axios
				.get('/w/api/broadcasts/' + this.broadcast.id)
				.then(response => {
					this.updateBroadcast(response.data.broadcast);
					this.$refs.broadcastChat.comments.unshift(...response.data.comments);
					this.$data.$_broadcastMixin_showVideo = true;
				})
				.catch(error => {
					//
				});
		}
	}
</script>