import { mapState } from 'vuex'

export default {
	data: function() {
		return {
			$_broadcastMixin_showVideo: true
		}
	},
	computed: {
		...mapState([
			'introVideo'
		]),
		$_broadcastMixin_isBroadcastLoaded: function() {
			return this.broadcast !== null;
		},
		$_broadcastMixin_isBroadcastClosed: function() {
			return this.broadcast.status == 'broadcast_closed';
		},
		$_broadcastMixin_isBroadcastOpen: function() {
			return this.broadcast.status != 'broadcast_closed';
		},
		$_broadcastMixin_isBroadcastInProgress: function() {
			return this.broadcast.status == 'broadcast_in_progress';
		},
		$_broadcastMixin_isBroadcastLive: function() {
			return this.broadcast.live;
		},
		$_broadcastMixin_hasNotes: function() {
			return (typeof this.broadcast.sermon !== 'undefined' && this.broadcast.sermon !== null)
				? (typeof this.broadcast.sermon.notes !== 'undefined' && this.broadcast.sermon.notes !== null)
					? true
					: false
				: false;
		},
		$_broadcastMixin_name: function() {
			return this.broadcast.live
				? this.broadcast.name
				: this.broadcast.sermon.title;
		},
		$_broadcastMixin_description: function() {
			return this.broadcast.live
				? this.broadcast.description
				: this.broadcast.sermon.description;
		},
		$_broadcastMixin_videoId: function() {
			return this.$_broadcastMixin_isBroadcastInProgress
				? this.broadcast.sermon.vimeo_id
				: this.introVideo.video_id;
		},
		$_broadcastMixin_image: function() {
			return this.broadcast.live
				? this.broadcast.image
				: this.broadcast.sermon.image;
		},
		$_broadcastMixin_timeElapsed: function() {
			return (typeof this.broadcast.time_elapsed !== 'undefined')
				? this.broadcast.time_elapsed
				: 0;
		},
		$_broadcastMixin_nextBroadcastTime: function() {
			// return Moment.utc(this.broadcast.starts_at)
			// 	.local()
			// 	.format('dddd [at] h:mm a');

			const moment = Moment.utc(this.broadcast.starts_at).local();

			return moment.calendar();
		}
	},
	created: function() {
		// If the broadcast is in progress then we need to hide the
		// vimeo player temporarily as we wait for the server to
		// respond with the broadcast data which includes the amount
		// of time that has elapsed since the broadcast began. This
		// has no effect on the Living As One live stream player.
		if (this.$_broadcastMixin_isBroadcastInProgress) {
			this.$data.$_broadcastMixin_showVideo = false;
		}
	}
}
