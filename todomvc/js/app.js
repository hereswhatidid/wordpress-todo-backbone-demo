/*global $ */
/*jshint unused:false */
( function( $ ) {


var app = app || {};

$(function () {
	'use strict';

	app.Todo = Backbone.Model.extend({
		defaults: {
			title: '',
			completed: false
		},

		idAttribute: 'id'
	});

	var Todos = Backbone.Collection.extend({
		model: app.Todo,

		params: {
			'action': 'get_todos'
		},

		url: function() {
			return ajaxurl + '?' + $.param( this.params );
		}

	});

	app.todos = new Todos();

	app.TodoView = Backbone.View.extend({
		tagName:  'li',

		className: 'list-group-item',

		template: _.template($('#item-template').html()),

		events: {
			'click .destroy': 'clear'
		},

		initialize: function () {
			this.listenTo(this.model, 'destroy', this.remove);
		},
		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
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
			var view = new app.TodoView({ model: todo });
			$('#todo-list').append(view.render().el);
		},

		addAll: function () {
			this.$('#todo-list').html('');
			app.todos.each(this.addOne, this);
		}
	});

	new app.AppView();
});
})( jQuery );