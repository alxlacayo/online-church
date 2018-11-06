export default {
	methods: {
		$_apiResponseMixin_setErrors: function(errors) {
			const newErrors = {};

			for (const property in errors) {
				if (typeof errors[property] === 'object') {
					newErrors[property] = errors[property][0];
				} else {
					newErrors[property] = errors[property];
				}
			}

			this.errors = newErrors;
		},
	}
}