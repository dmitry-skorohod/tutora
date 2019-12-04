define([
	'base',
	'requirejs-text!apps/video/record_or_edit/templates/record.html',
	'ziggeo'
], function (Base,
			 template,
			 Ziggeo) {

	return _.patch(Base.LayoutView.extend({

		mixins: ['DialogueLayout'],

		ui: {
			'save': '.btn--save'
		},

		events: {
			'click @ui.save': 'onClickSave'
		},

		initialize: function (options) {
			this.user = options.user;
			this.attributes = {
				profile: {
					videoStatus: null
				}
			};
			this.uploading = false;
		},

		onShow: function () {
			var vm = this;

			this.$el.find('.btn--save').addClass('btn--disabled').removeClass('btn--save');

			setTimeout(function () {
				Ziggeo.embedResponsive('.js-recorder', $('.js-recorder-wrapper'), 1.33, {
					limit: 180,
					key: vm.user.id,
				});
			}, 100);

			Ziggeo.Events.on("uploading", function () {
				vm.uploading = true;
			})
				.on("submitted", function () {
					vm.attributes.profile.videoStatus = 'new';
					vm.$el.find('.btn--disabled').addClass('btn--save').removeClass('btn--disabled');
				});
		},

		onClickClose: function () {
			if (this.attributes.profile.videoStatus === 'new' ||
				this.uploading === true) {
				this.attributes.profile.videoStatus = 'canceled';
			}

			this.trigger('close');
		},

		onClickSave: function () {
			this.trigger('save');
		},

		template: _.template(template)

	}));

});