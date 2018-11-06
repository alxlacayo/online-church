import { mapState } from 'vuex'

export default {
	computed: {
		...mapState([
			'introVideo',
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
			return this.broadcast.sermon && this.broadcast.sermon.notes !== null;
		},
		$_broadcastMixin_title: function() {
			return this.broadcast.live
				? this.broadcast.name
				: this.broadcast.sermon.title;
		},
		$_broadcastMixin_videoId: function() {
			return this.$_broadcastMixin_isBroadcastInProgress
				? this.broadcast.sermon.vimeo_id
				: this.introVideo.video_id;
		},
		$_broadcastMixin_timeElapsed: function() {
			return this.broadcast.time_elapsed !== undefined ? this.broadcast.time_elapsed : 0;
		},
		$_broadcastMixin_image: function() {
			return this.broadcast.live
				? this.broadcast.image
				: this.broadcast.sermon.image;
		},
		$_broadcastMixin_description: function() {
			return this.broadcast.live
				? this.broadcast.description
				: this.broadcast.sermon.description;
		},
		$_broadcastMixin_nextBroadcastTime: function() {
			// return Moment.utc(this.broadcast.starts_at)
			// 	.local()
			// 	.format('dddd [at] h:mm a');

			const moment = Moment.utc(this.broadcast.starts_at).local();

			return moment.calendar();
		}
	},
	methods: {
		$_broadcastMixin_broadcastStatusChanged: function(broadcast) {
			this.broadcast = broadcast;
		}
	}
}