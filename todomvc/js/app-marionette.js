( function( $, Backbone ) {

	var PostTypesApp = new Backbone.Marionette.Application();

	PostTypesApp.addRegions({
		mainRegion: "#post-types",
		editRegion: '#edit-post-type'
	});

	PostType = Backbone.Model.extend({
		idAttribute: 'post_type',
		defaults: {
			post_type: '',
			label: '',
			labels: {
				name: '', // general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
				singular_name: '', // name for one object of this post type. Defaults to value of name
				menu_name: '', // the menu name text. This string is the name to give menu items. Defaults to value of name
				all_items: '', // the all items text used in the menu. Default is the Name label
				add_new: '', // the add new text. The default is Add New for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
				add_new_item: '', // the add new item text. Default is Add New Post/Add New Page
				edit_item: '', // the edit item text. Default is Edit Post/Edit Page
				new_item: '', // the new item text. Default is New Post/New Page
				view_item: '', // the view item text. Default is View Post/View Page
				search_items: '', // the search items text. Default is Search Posts/Search Pages
				not_found: '', // the not found text. Default is No posts found/No pages found
				not_found_in_trash: '', // the not found in trash text. Default is No posts found in Trash/No pages found in Trash
				parent_item_colon: '' // the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is Parent Page
			},
			description: '', // A short descriptive summary of what the post type is.
			public: false, // Whether a post type is intended to be used publicly either via the admin interface or by front-end users.
			exclude_from_search: true, // Whether to exclude posts with this post type from front end search results.
			publicly_queryable: false, // Whether queries can be performed on the front end as part of parse_request().
			source: 'core'
		},
		url: function() {
			return ajaxurl + '?action=edit_posttype&post_type=' + this.get( 'post_type' );
		}
	});

	PostTypes = Backbone.Collection.extend({
		model: PostType
	});

	PostTypeView = Backbone.Marionette.ItemView.extend({
		template: "#posttype-template",
		tagName: 'tr',
		className: function() {
			if ( this.model.get( 'source' ) === 'core' ) {
				return 'warning';
			} else {
				return 'success';
			}
		},

		events: {
			'click a.edit': 'editPostType',
			'click a.delete': 'deletePostType'
		},

		initialize: function(){
			this.listenTo(this.model, "change:post_type", this.render);
		},

		editPostType: function(){
			var postTypeEditView = new PostTypeEditView({
				model: this.model
			});
			PostTypesApp.mainRegion.$el.fadeOut( 200, function() {
				PostTypesApp.editRegion.show( postTypeEditView );
				PostTypesApp.editRegion.$el.fadeIn( 200 );
			} );
			
			
		},

		deletePostType: function(){
			this.model.destroy();
		}
	});

	PostTypeEditView = Backbone.Marionette.ItemView.extend({
		template: '#posttypeedit-template',

		events: {
			'click a.cancel': 'cancel',
			'click a.save': 'save'
		},

		initialize: function() {
			console.log( 'load edit view' );
		},

		cancel: function() {
			PostTypesApp.editRegion.$el.fadeOut( 200, function() {
				PostTypesApp.mainRegion.$el.fadeIn( 200 );
			} );
		},

		save: function() {
			this.model.save();
		}
	});

	PostTypesView = Backbone.Marionette.CompositeView.extend({
		tagName: "table",
		id: "posttypes",
		className: "table table-hover table-bordered",
		template: "#posttypes-template",
		itemView: PostTypeView,

		initialize: function(){

			// this.listenTo(this.collection, "sort", this.renderCollection);
		},

		appendHtml: function(collectionView, itemView){

			collectionView.$("tbody").append(itemView.el);
		}
	});

	PostTypesApp.addInitializer( function( options ) {
		
		var postTypesView = new PostTypesView({
			collection: options.postTypes
		});
		PostTypesApp.mainRegion.show(postTypesView);
	});

	$(document).ready(function(){
		var postTypes = new PostTypes( ContentToolkit.postTypes );
		PostTypesApp.start({
			postTypes: postTypes
		});

	});
})( jQuery, Backbone );