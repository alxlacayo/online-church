export default {
	data: function () {
		return {
			$_salvationMixin_isSalvationConfirmationVisible: false
		}
	},
	methods: {
	    $_salvationMixin_showSalvationConfirmation () {
	    	this.$data.$_salvationMixin_isSalvationConfirmationVisible = true;

	    },
	    $_salvationMixin_hideSalvationConfirmation () {
	    	this.$data.$_salvationMixin_isSalvationConfirmationVisible = false;
	    }
	}
}