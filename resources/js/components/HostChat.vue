<template>
	<div>
		<div class="bar d-flex px-40 flex-shrink-0 align-items-center justify-content-between border-bottom host-chat-header">
			<span class="xlarge font-weight-bold">Host chat</span>
			<span class="small text-muted">
				<span
					v-if="hosts && hosts.length > 0"
					class="online-icon"
				></span>
				<span>{{ hosts.length }} {{ hosts.length == 1 ? 'host' : 'hosts' }} online</span>
			</span>
			<div class="host-online-list py-16 px-40">
				<div
					v-for="host in hosts"
					class="d-flex py-8 align-items-center"
				>
					<img
						:src="host.profile_picture"
						class="profile-picture profile-picture--small mr-16 flex-shrink-0"
					>
					<span>{{ host.name }}</span>
				</div>
			</div>
		</div>
		<div 
			:id="scrollContainerId"
			:class="['px-40 flex-grow-1 overflow-y', showLoadMoreButton ? 'pt-24' : 'pt-32']"
		>	
			<span
				v-if="showLoadMoreButton"
				@click="loadMore"
				class="d-block mb-32 text-button text-button--small text-muted"
			>
				Load previous comments
			</span>
			<div
				v-for="(comment, index) in comments"
				:key="comment.id"
				:id="'comment-' + comment.id"
				class="d-flex mb-32 flex-shrink-0"
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
			class="border-top"
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
				showLoadMoreButton: true,
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
							this.showLoadMoreButton = false;
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