<template>
	<div>
		<div class="bar d-flex px-40 flex-shrink-0 align-items-stretch justify-content-between border-bottom">
			<span class="align-self-center xlarge font-weight-bold">Host chat</span>
			<div class="d-flex align-items-center host-online-count">
				<span class="online-icon"></span>
				<span class="small text-muted">{{ hosts.length }} {{ hosts.length == 1 ? 'host' : 'hosts' }} online</span>
				<div class="host-online-list py-16 px-40 border-top">
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
		</div>
		<div 
			:id="scrollContainerId"
			:class="['px-40 flex-grow-1 overflow-auto', showLoadPreviousCommentsButton ? 'pt-24' : 'pt-32']"
		>	
			<span
				v-if="showLoadPreviousCommentsButton"
				@click="loadComments"
				class="d-block mb-32 text-button text-button--small text-muted"
			>
				Load previous comments
			</span>
			<div
				v-for="(comment, index) in comments"
				:key="comment.id"
				:id="'comment-' + comment.id"
				class="d-flex mb-32 flex-shrink-0 comment"
			>	
				<img
					:src="comment.user.profile_picture"
					class="profile-picture mr-24 flex-shrink-0"
				>
				<div class="flex-grow-1 mw-0">
					<div class="d-flex justify-content-between">
						<span class="font-weight-bold">{{ comment.user.name }}</span>
						<span class="xsmall text-muted">{{ $_chatMixin_timeAgo(comment.created_at) }}</span>
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
			scrollContainerId: String
		},
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				showLoadPreviousCommentsButton: false,
				isLoading: false,
				interval: null,
				hosts: [],
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			maxId: function() {
				return (typeof this.comments[0] !== 'undefined') 
					? this.comments[0].id
					: null;
			},
			commentCount: function() {
				return this.comments.length;
			}
		},
		methods: {
			submitComment: function(e) {
				if (this.isLoading) { return; }

				if (this.newComment.length < 1) { return; }

				axios
					.post('/w/api/host/comments', {
						localCommentId: this.newCommentId,
						text: this.newComment
					})
					.then(response => {
						const index = this.comments.findIndex(comment => {
							return comment.localCommentId == response.data.local_comment_id;
						});

						this.comments[index] = response.data.comment;
					})
					.catch(error => {
						//
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
			loadComments: function() {
				let params = {};

				if (this.maxId !== null) {
					params.maxId = this.maxId;
				}

				axios
					.get('/w/api/host/comments', params)
					.then(response => {
						// update this
						if (response.data.comments.length < response.data.limit) {
							this.showLoadPreviousCommentsButton = false;
						}

						this.prependPreviousComments(response.data.comments);
					})
					.catch(error => {

					})
					.then(() => {
						 
					});
			},
			prependPreviousComments: function(comments) {
				if (this.comments.length == 0) {
					this.$_chatMixin_publishComments(comments);
					return;
				}

				let hostChat = document.getElementById("host-chat");
				let hostChatScrollTop = hostChat.scrollTop;
				let firstComment = document.getElementById("comment-" + this.comments[0].id);
				let firstCommentOffsetTop = firstComment.offsetTop;

				this.comments.unshift(...comments);

				this.$nextTick(function() {
					hostChat.scrollTop = firstComment.offsetTop + hostChatScrollTop - firstCommentOffsetTop;
				});
			}
		},
		created: function() {

			this.loadComments();

			Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = this.$_helperMixin_getXSRFCookie();

			Echo.join('host.chat')
			    .here((users) => {
			        this.hosts = users;
			    })
			    .joining((user) => {
			    	this.hosts.push(user);
			    })
			    .leaving((user) => {
			    	this.hosts = this.hosts.filter(host => host.id != user.id);
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