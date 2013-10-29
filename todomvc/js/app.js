(function($) {

	var postTypes = ContentToolkit.postTypes;

	//support legacy web servers
	Backbone.emulateHTTP = true;
	Backbone.emulateJSON = true;

	//define product model
	var PostType = Backbone.Model.extend({
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

	//define directory collection
	var PostTypesCollection = Backbone.Collection.extend({
		model: PostType
	});

	//define individual contact view
	var PostTypeView = Backbone.View.extend({
		tagName: 'tr',
		template: _.template( $( '#postTypeTemplate' ).html() ),
		editTemplate: _.template( $( '#postTypeEditTemplate' ).html() ),

		className: function() {
			if ( this.model.get( 'source' ) === 'core' ) {
				return '';
			} else {
				return '';
			}
		},

		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) );
			return this;
		},

		events: {
			'click a.delete': 'deletePostType',
			'click a.edit': 'editPostType',
			'click a.save': 'saveEdits',
			'click a.cancel': 'cancelEdit'
		},

		//delete a contact
		deletePostType: function() {
			//remove model
			this.model.destroy();

			//remove view from page
			this.remove();
		},

		//switch contact to edit mode
		editPostType: function() {
			console.log( this );
			this.$el.html( this.editTemplate( this.model.toJSON() ) );

			this.$el.find('input[type="hidden"]').remove();
		},

		saveEdits: function(e) {
			e.preventDefault();

			var formData = {},
				prev = this.model.previousAttributes();

			//get form data
			$(e.target).closest( 'form' ).find( ':input' ).not( 'button' ).each(function() {
				var el = $(this);
				formData[el.attr( 'class' )] = el.val();
			});

			//update model and save to server
			this.model.set(formData).save();

			//render view
			this.render();

			//update contacts array
			_.each( postTypes, function( postType ) {
				if ( _.isEqual( postType, prev ) ) {
					postTypes.splice( _.indexOf( postTypes, postType ), 1, formData );
				}
			});
		},

		cancelEdit: function( e ) {
			e.preventDefault();
			this.render();
		}
	});

	//define master view
	var PostTypesApp = Backbone.View.extend({
		el: $( '#post-types' ),

		initialize: function() {
			this.collection = new PostTypesCollection( postTypes );

			this.render();
			this.$el.find( '#filter ').append(this.createSelect());

			this.on( 'change:filterSource', this.filterBySource, this);
			this.collection.on( 'reset', this.render, this);
			this.collection.on( 'add', this.renderPostType, this);
			this.collection.on( 'remove', this.removePostType, this);
		},

		render: function() {
			this.$el.find( '#post-list' ).find( 'article' ).remove();

			_.each( this.collection.models, function( item ) {
				this.renderPostType( item );
			}, this);
		},

		renderPostType: function(item) {
			var postTypeView = new PostTypeView({
				model: item
			});
			this.$el.find( '#post-list' ).append( postTypeView.render().el );
		},

		getSources: function() {
			return _.uniq( this.collection.pluck( 'source' ), false, function( source ) {
				return source.toLowerCase();
			});
		},

		createSelect: function() {
			var filter = this.$el.find("#filter"),
				select = $( '<select/>', {
					html: '<option value="all">All</option>'
				});

			_.each( this.getSources(), function( item ) {
				var option = $( '<option/>', {
					value: item,
					text: item
				}).appendTo( select );
			});

			return select;
		},

		//add ui events
		events: {
			'change #filter select': 'setFilter',
			'click #add': 'addPostType',
			'click #showForm': 'showForm'
		},

		//Set filter property and fire change event
		setFilter: function( e ) {
			this.filterType = e.currentTarget.value;
			this.trigger( 'change:filterType' );
		},

		//filter the view
		filterBySource: function() {
			if ( this.filterType === 'all' ) {
				this.collection.reset( postTypes );
				postTypesRouter.navigate( 'filter/all' );
			} else {
				this.collection.reset( postTypes, { silent: true } );

				var filterType = this.filterType,
					filtered = _.filter( this.collection.models, function(item) {
						return item.get( 'type' ).toLowerCase() === filterType;
					});

				this.collection.reset( filtered );

				postTypesRouter.navigate( 'filter/' + filterType );
			}
		},

		//add a new contact
		addPostType: function( e ) {
			e.preventDefault();

			var formData = {};
			$( '#addPostType' ).children( 'input' ).each( function( i, el ) {
				if ( $( el ).val() !== '' ) {
					formData[el.id] = $( el ).val();
				}
			});

			//update data store
			postTypes.push( formData );

			//re-render select if new type is unknown
			if ( _.indexOf( this.getSources(), formData.type ) === -1 ) {
				this.$el.find("#filter").find("select").remove().end().append(this.createSelect());
			}

			//add to collection and save to server
			this.collection.create( formData );
		},

		removePostType: function(removedModel) {
			var removed = removedModel.attributes;

			//remove from contacts array
			_.each( postTypes, function( postType ) {
				if ( _.isEqual( postType, removed ) ) {
					postTypes.splice( _.indexOf( postTypes, postType ), 1 );
				}
			});
		},

		showForm: function() {
			this.$el.find( '#addPostType' ).slideToggle();
		}
	});

	//add routing
	var PostTypesRouter = Backbone.Router.extend({
		routes: {
			'filter/:type': 'urlFilter'
		},

		urlFilter: function(type) {
			postTypes.filterType = type;
			postTypes.trigger( 'change:filterType' );
		}
	});

	//create instance of master view
	var postTypesApp = new PostTypesApp();

	//create router instance
	var postTypesRouter = new PostTypesRouter();

	//start history service
	Backbone.history.start({
		root: '/wp-admin/options-general.php?page=todo-demo'
	});

} (jQuery));