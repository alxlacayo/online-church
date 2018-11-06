<template>
	<div>
		<div id="youtube-player"></div>
	</div>
</template>
<script>
	export default {
		props: {
			videoId: String
		},
		data: function() {
			return {
				player: {}
			}
		},
		methods: {
			loadPlayer() {
				this.player = new YT.Player('youtube-player', {
					height: '390',
					width: '640',
					videoId: this.videoId,
					playerVars: {
						autoplay: '0',
						disablekb: '1',
						controls: '0',
						start: '0',
						modestbranding: '1',
						origin: 'http://second.test'
					},
					events: {
						'onReady': this.playerReady,
						'onStateChange': this.playerStateChange,
						'onError' : this.playerError
					}
				});
			},
			playerReady(event) {
				event.target.playVideo();
				this.player.setVolume(100);
			},
			playerStateChange(event) {
				if (event.data == YT.PlayerState.ENDED) {
					this.$emit('video-ended');
				}
			},
			mute() {
				this.player.isMuted() ? this.player.unMute() : this.player.mute(); 
			}
		},
		mounted: function() {
			if (this.$store.state.youtubeApiReady) {
				this.loadPlayer();
			} else {
				let watcher = this.$store.watch( state => state.youtubeApiReady, (newValue, oldValue) => {
					this.loadPlayer();
					watcher.unwatch;
				});
			}			
		}
	}
</script>