(function($) {
	'use strict'

	app.viewModels.profile = {

		/**
		 * Stores all the games in their original state.
		 * 
		 * @type Array
		 */
		sourceItems: ko.observableArray(),

		/**
		 * Latest genre user has selected.
		 * 
		 * @type string,
		 */
		genre: ko.observableArray(),


		/**
		 * Stores all parameters user is currently restricting titles query by.
		 * 
		 * @type {Object}
		 */
		params: {

			/**
			 * Currently selected sorting option.
			 * 
			 * @type ko.observable(String)
			 */
			order: ko.observable(),

			/**
			 * Stores users query to filters games on.
			 * 
			 * @type ko.observable(String),
			 */
			query: ko.observable().extend({ rateLimit: { method: "notifyWhenChangesStop", timeout: 400 } }),

			/**
			 * Filter titles that were released after this date.
			 * 
			 * @type ko.observable(String),
			 */
			after: ko.observable(),

			/**
			 * Filter titles that were released before this date.
			 * 
			 * @type ko.observable(String),
			 */
			before: ko.observable(),

			/**
			 * Filter movies to only ones that specified actors appeared in.
			 * 
			 * @type string
			 */
			cast: ko.observable(),

			/**
			 * Filter titles by genres.
			 * 
			 * @type string,
			 */
			genres: ko.observableArray(),

			type: ko.observable(),

			minRating: ko.observable(),

			maxRating: ko.observable(),

			listName: ko.observable('watchlist'),

			userId: ko.observable(),
		},

		/**
		 * Uri to hit for paginated results.
		 * 
		 * @type {String}
		 */
		uri: 'titles',

		removeFromList: function(title) {
			var self = app.viewModels.profile;

			app.utils.ajax({
				url: vars.urls.baseUrl + '/lists/remove',
				data: ko.toJSON({ _token: vars.token, title_id: title.id, list_name: self.params.listName() }),
				success: function(data) {
					app.utils.noty(data, 'success');

					self.sourceItems.remove(title);
				}
			});
		},

		/**
		 * Remove a genre from genres array.
		 * 
		 * @param  string genre
		 * @return void
		 */
		removeGenre: function(genre) {
			app.viewModels.titles.index.params.genres.remove(genre);
		},

		start: function(id) {
			var self = this;

			self.params.userId(parseInt(id));

			//push a genre user selects to genres array
			self.genre.subscribe(function(value) {

				if (value) {
					self.params.genres.push(value);
				}
			   
			});

			app.paginator.start(app.viewModels.profile, '#content', 15);
		},
	};

})(jQuery);