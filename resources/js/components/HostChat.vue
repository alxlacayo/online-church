<template>
	<div class="d-flex flex-column flex-grow-1 mh-0">
		<div
			:id="scrollContainerId"
			class="d-flex flex-column pt-32 px-30 px-md-40 flex-grow-1 border-bottom overflow-y"
		>	
			<div
				v-for="(comment, index) in comments"
				:key="comment.id"
				:id="'comment-' + comment.id"
				class="d-flex pb-32 flex-shrink-0"
			>	
				<img
					:src="comment.user.profile_picture"
					class="profile-picture mr-24 flex-shrink-0"
				>
				<div class="flex-grow-1">
					<div class="d-flex justify-content-between">
						<span class="font-weight-bold">{{ comment.user.name }}</span>
						<span class="xsmall text-muted">{{ timeElapsed(comment.created_at) }}</span>
					</div>
					<span>{{ comment.text }}</span>
				</div>
			</div>
		</div>
		<comment-form
			:value="newComment"
			@input="newComment = $event"
			@submit="submitComment"
		/>
	</div>
</template>

<script>
	import CommentForm from '../components/CommentForm'
	import chatMixin from '../mixins/chatMixin'
	import helperMixin from '../mixins/helperMixin'
	import { mapState } from 'vuex'

	export default {
		components: {
			CommentForm
		},
		mixins: [
			chatMixin,
			helperMixin
		],
		props: {
			scrollContainerId: String,
			previousComments: Array
		},
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				showLoadMore: true,
				isLoading: false,
				interval: '',
				hosts: [],
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			maxId: function() {
				return this.comments[0].id;
			},
			commentCount: function() {
				return this.comments.length;
			}
		},
		watch: {
			previousComments: function() {
				this.$_chatMixin_publishComments(this.previousComments);
			},
			hosts: function() {
				this.$emit('hosts-update', this.hosts);
			}
		},
		methods: {
			submitComment: function(e) {
				if (this.isLoading) { return; }

				if (this.newComment.length < 1) { return; }

				axios
					.post('/w/api/host/comments', {
						commentId: this.newCommentId,
						text: this.newComment
					})
					.then(response => {
						// Flip the array to start at the end would be better?
						const index = this.comments.findIndex(comment => {
							return comment.localCommentId == response.data.local_id;
						});

						this.comments[index] = response.data;
					})
					.catch(error => {
						// Do something if comment fails
						// this.newComment = this.cachedComment;
					})
					.then(() => {
						this.isLoading = false;
					});

				const comment = {
					localCommentId: this.newCommentId,
					text: this.newComment,
					user: this.user
				};

				this.$_chatMixin_publishComment(comment);

				this.cachedComment = this.newComment;
				this.newComment = '';
				this.newCommentId++;
				this.isLoading = true;
			},
			loadMore: function() {
				axios
					.get('/w/api/host/comments?maxid=' + this.maxId)
					.then(response => {
						if (response.data.comments.length < response.data.limit) {
							this.showLoadMore = false;
						}

						this.comments.unshift(...response.data.comments);
					})
					.catch(error => {

					})
					.then(() => {
						 
					});
			},
			timeElapsed: function(timestamp) {
				return Moment.utc(timestamp)
					.fromNow(true);
			}
		},
		created: function() {

			Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = this.$_helperMixin_getXSRFCookie();

			Echo.join('host.chat')
			    .here((users) => {
			        this.hosts = users;
			    })
			    .joining((user) => {
			    	this.hosts.push(user);
			    	console.log(user.name + ' has joined.');
			    })
			    .leaving((user) => {
			    	this.hosts = this.hosts.filter(host => host.id != user.id);
			        console.log(user.name + ' has left.');
			    })
			    .listen('HostCommentCreated', (comment) => {
			        this.$_chatMixin_publishComment(comment);
			    });

			this.interval = setInterval(() => this.$forceUpdate() , 10000);
		},
		beforeDestroy: function() {
			Echo.leave('host.chat');

			clearInterval(this.interval);
		}
	}
</script>