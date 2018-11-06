<template>
	<div id="vimeo-player" class="embed-responsive embed-responsive-16by9"></div>
</template>

<script>
	export default {
		props: {
			videoId: String,
			timeElapsed: {
				type: Number,
				default: 0
			},
			autoplay: {
				type: Boolean,
				default: true
			}
		},
		data: function() {
			return {
				player: {}
			}
		},
		watch: {
			videoId: function() {
				this.loadNewVideo();
			}
		},
		methods: {
			loadVideo: function() {
			    var options = {
			        id: this.videoId,
			        autoplay: this.autoplay
			    };

			    this.player = new Vimeo('vimeo-player', options);

			    if (this.timeElapsed > 0) {
					this.player.setCurrentTime(this.timeElapsed);
			    }

			    this.player.setVolume(1);
			},
			loadNewVideo: function() {
				this.player.destroy().then(() => {
				    this.loadVideo();
				}).catch(function(error) {
				    alert('Something went wrong. Reload the page.');
				});

			}
		},
		mounted: function() {
			this.loadVideo();
		},
		beforeDestroy: function() {
			this.player.destroy().then(function() {
			    // the player was destroyed
			}).catch(function(error) {
			    // an error occurred
			});
		}
	}
</script>