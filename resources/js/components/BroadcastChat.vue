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
					class="d-flex mb-32 flex-shrink-0 comment"
				>	
					<img
						:src="comment.user.profile_picture"
						class="profile-picture mr-24 flex-shrink-0"
					>
					<div class="flex-grow-1 mw-0">
						<div class="d-flex">
							<span class="font-weight-bold mr-8">{{ comment.user.name }}</span>
							<span
								v-if="comment.user.is_host"
								class="small text-muted"
							>Host</span>
						</div>
						<span>{{ comment.text }}</span>
					</div>
				</div>
			</div>
		</div>
		<comment-form
			v-if="isUserAuthenticated"
			:value="localCommentText"
			@input="localCommentText = $event"
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
			broadcastId: Number,
			scrollContainerId: String,
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
				localCommentId: 1,
				localCommentText: '',
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
			submitComment: function() {
				if (this.isLoading) { return; }

				if (this.localCommentText.length < 1) { return; }

				axios
					.post('/w/api/broadcasts/' + this.broadcastId + '/comments', {
						localCommentId: this.localCommentId,
						text: this.localCommentText
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
					localCommentId: this.localCommentId,
					text: this.localCommentText,
					user: this.user
				};

				this.$_chatMixin_publishComment(comment);

				this.cachedComment = this.localCommentText;
				this.localCommentText = '';
				this.newLocalCommentId++;
				this.isLoading = true;
			},
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			}
		},
		mounted: function() {
			Echo.channel('broadcast.chat.' + this.broadcastId)
				.listen('BroadcastComment\\BroadcastCommentCreated', comment => {
					this.$_chatMixin_publishComment(comment);
			});	
		},
		beforeDestroy: function() {
			Echo.leave('broadcast.chat.' + this.broadcastId);
		}
	}
</script>
