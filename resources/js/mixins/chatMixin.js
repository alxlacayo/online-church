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
		}
	}
}