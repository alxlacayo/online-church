<template>
	<div class="d-flex flex-column flex-grow-1 mh-0">
		<div
			:id="scrollContainerId"
			class="d-flex flex-column flex-grow-1 overflow-auto"
		>
			<slot/>

			<div class="px-30 px-md-40 pt-32">
				<div
					v-for="comment in comments"
					:key="comment.id"
					:id="'comment-' + comment.id"
					class="comment d-flex mb-32 flex-shrink-0"
				>	
					<img
						:src="comment.user.profile_picture"
						class="profile-picture mr-24 flex-shrink-0"
					>
					<div class="flex-grow-1">
						<div class="d-flex">
							<span class="font-weight-bold mr-8">{{ comment.user.name }}</span>
							<span
								v-if="comment.user.is_host"
								class="small text-muted"
							>Host</span>
						</div>
						<template v-if="comment.user.is_host">
							<span v-html="formatCommentLinks(comment.text)"></span>
						</template>
						<template v-else>
							<span>{{ comment.text }}</span>
						</template>
					</div>
				</div>
			</div>
		</div>
		<comment-form
			v-if="isUserAuthenticated"
			:value="newComment"
			@input="newComment = $event"
			@submit="submitComment"
			:class="[borderOnCommentForm ? 'border-top' : '', 'bg-white']"
		/>
		<span
			v-else
			@click="login"
			class="d-block p-30 flex-shrink-0 font-weight-bold text-center bg-white"
		>Login to chat</span>
	</div>
</template>

<script>
	import CommentForm from '../components/CommentForm'
	import chatMixin from '../mixins/chatMixin'
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'

	export default {
		props: {
			scrollContainerId: String,
			broadcastId: Number,
			scollToBottomOnLoad: {
				type: Boolean,
				default: true
			},
			borderOnCommentForm: {
				type: Boolean,
				default: false
			}
		},
		components: {
			CommentForm,
		},
		mixins: [chatMixin],
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				isLoading: false,
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			...mapGetters([
				'isUserAuthenticated',
			])
		},
		methods: {
			formatCommentLinks: function(text) {
				return LinkifyHtml(text);
			},
			submitComment: function() {
				if (this.isLoading) { return; }

				if (!this.isUserAuthenticated) { return; }

				axios
					.post('/w/api/broadcasts/' + this.broadcastId + '/comments', {
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
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			}
		},
		created: function() {
			axios
				.get('/w/api/broadcasts/' + this.broadcastId + '/comments')
				.then(response => {
					this.$_chatMixin_publishComments(response.data, this.scollToBottomOnLoad);
				})
				.catch(error => {
					console.log(error);
				});
		},
		mounted: function() {
			Echo.channel('broadcast.chat.' + this.broadcastId)
				.listen('BroadcastCommentCreated', comment => {
					this.$_chatMixin_publishComment(comment);
			});	
		},
		beforeDestroy: function() {
			Echo.leave('broadcast.chat.' + this.broadcastId);
		}
	}
</script>
