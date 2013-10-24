/*global $ */
/*jshint unused:false */
( function( $ ) {


var app = app || {};

$(function () {
	'use strict';

	app.PostType = Backbone.Model.extend({
		defaults: {
			postType: '',
			source: false
		},

		idAttribute: 'id'
	});

	var PostTypes = Backbone.Collection.extend({
		model: app.PostType,

		params: {
			'action': 'todos'
		},

		url: function() {
			return ajaxurl + '?' + $.param( this.params );
		},

		parse: function( resp, xhr ) {
			// console.log( 'Response: ', resp, xhr );
			return resp.postTypes;
		}

	});

	app.todos = new PostTypes();

	app.PostTypeView = Backbone.View.extend({
		tagName:  'tr',

		// className: 'list-group-item',

		template: _.template($('#item-template').html()),

		events: {
			'click .destroy': 'clear'
		},

		initialize: function () {
			this.listenTo(this.model, 'destroy', this.remove);
		},
		render: function () {
			this.$el.html( this.template( this.model.toJSON() ) );
			return this;
		},

		clear: function () {
			this.model.destroy();
		}
	});

	app.AppView = Backbone.View.extend({

		el: '#todoapp',

		initialize: function () {

			this.$main = this.$('#main');

			this.listenTo(app.todos, 'reset', this.addAll);
			app.todos.fetch({reset: true});
		},
		render: function () {
			if (app.todos.length) {
				this.$main.show();
			} else {
				this.$main.hide();
			}
		},

		addOne: function (todo) {
			var view = new app.PostTypeView({ model: todo });
			$('#posttype-list').append(view.render().el);
		},

		addAll: function () {
			this.$('#posttype-list').html('');
			app.todos.each(this.addOne, this);
		}
	});

	var PostTypeRouter = Backbone.Router.extend({
		routes: {
			'': 'index',
			'edit/:id': 'editPostType',
			'disable/:id': 'disablePostType'
		},

		index: function() {
			console.log( 'Index page!' );
		},

		editPostType: function ( postType ) {
			// Set the current filter to be used
			app.PostTypeID = postType || '';

			console.log( 'Edit post type: ', app.PostTypeID );
			
		},

		disablePostType: function ( postType ) {
			// Set the current filter to be used
			app.PostTypeID = postType || '';

			console.log( 'Disable post type: ', app.PostTypeID );
			
		}
	});

	app.PostTypeRouter = new PostTypeRouter();
	Backbone.history.start({
		root: '/wp-admin/options-general.php?page=todo-demo'
	});

	new app.AppView();


});
})( jQuery );