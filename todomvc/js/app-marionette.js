PostTypesApp = new Backbone.Marionette.Application();

PostTypesApp.addRegions({
	mainRegion: "#post-types"
});

PostType = Backbone.Model.extend({
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
	className: 'angry_cat',

	events: {
		'click .rank_up img': 'rankUp',
		'click .rank_down img': 'rankDown',
		'click a.disqualify': 'disqualify'
	},

	initialize: function(){
		this.listenTo(this.model, "change:votes", this.render);
	},

	rankUp: function(){
		this.model.addVote();
		MyApp.trigger("rank:up", this.model);
	},

	rankDown: function(){
		this.model.addVote();
		MyApp.trigger("rank:down", this.model);
	},

	disqualify: function(){
		MyApp.trigger("cat:disqualify", this.model);
		this.model.destroy();
	}
});

AngryCatsView = Backbone.Marionette.CompositeView.extend({
	tagName: "table",
	id: "angry_cats",
	className: "table-striped table-bordered",
	template: "#angry_cats-template",
	itemView: AngryCatView,

	initialize: function(){
		this.listenTo(this.collection, "sort", this.renderCollection);
	},

	appendHtml: function(collectionView, itemView){
		collectionView.$("tbody").append(itemView.el);
	}
});

MyApp.addInitializer(function(options){
	var angryCatsView = new AngryCatsView({
		collection: options.cats
	});
	MyApp.mainRegion.show(angryCatsView);
});

$(document).ready(function(){
	var cats = new AngryCats([
		new AngryCat({ name: 'Wet Cat', image_path: 'assets/images/cat2.jpg' }),
		new AngryCat({ name: 'Bitey Cat', image_path: 'assets/images/cat1.jpg' }),
		new AngryCat({ name: 'Surprised Cat', image_path: 'assets/images/cat3.jpg' })
		]);

	MyApp.start({cats: cats});

	cats.add(new AngryCat({
		name: 'Cranky Cat',
		image_path: 'assets/images/cat4.jpg',
		rank: cats.size() + 1
	}));
});