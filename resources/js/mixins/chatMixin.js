export default {
	methods: {
		$_chatMixin_publishComment: function(comment) {
			this.$_chatMixin_publishComments([comment]);
		},
		$_chatMixin_publishComments: function(comments, scrollToBottom = true) {
			const el = document.getElementById(this.scrollContainerId);
			const distanceFromBottom = el.scrollHeight - el.clientHeight - el.scrollTop;

			this.comments.push(...comments);
			
			this.$nextTick(function() {
				if (scrollToBottom && distanceFromBottom < 150) {
					el.scrollTop = el.scrollHeight - el.clientHeight;
				}
			});
		},
		$_chatMixin_timeAgo: function(timestamp) {
			let now = Moment.utc();
			let timePublished = Moment.utc(timestamp);
			let seconds = now.diff(timePublished, 'seconds');

			if (seconds < 60) {
				return seconds == 0 ? '1s' :  seconds + 's';
			}

			let minutes = now.diff(timePublished, 'minutes');

			if (minutes < 60) {
				return minutes + 'm';
			}

			let hours = now.diff(timePublished, 'hours');

			if (hours < 24) {
				return hours + 'h';
			}

			let weeks = now.diff(timePublished, 'weeks');

			if (weeks < 4) {
				let days = now.diff(timePublished, 'days');

				return days + 'd';
			}

			return weeks + 'w';
		}
	}
}