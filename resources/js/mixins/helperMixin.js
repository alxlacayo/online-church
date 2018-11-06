export default {
	methods: {
		$_helperMixin_getCookie: function(name) {
			const cookies = document.cookie.replace(/\s/g, '').split(';');

			const found = cookies.find(element => element.split('=')[0] == name);

			return found !== undefined
				? decodeURIComponent(found.split('=')[1])
				: null;
		},
		$_helperMixin_getXSRFCookie: function() {
			return this.$_helperMixin_getCookie('XSRF-TOKEN');
		}
	}
}