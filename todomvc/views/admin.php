<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Todo_Demo
 * @author    Gabe Shackle <gabe@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 */
?>
<style>
	#todo-list .row .edit {
		display: none;
	}
	#todo-list .row.editing label {
		display: none;
	}
	#todo-list .row.editing .edit {
		display: inline-block;
	}
</style>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div class="bootstrap-wrapper">
        <div id="post-types"></div>
        <div id="edit-post-type"></div>
        <script id="postTypeTemplate" type="text/template">
        	<td><input type="checkbox"></td>
        	<td><%- label %></td>
        	<td><%- source %></td>
        	<td><span class="label label-default">Unmodified</span></td>
        	<td>
        		<div class="btn-group pull-right">
        			<a href="#" class="btn btn-xs btn-success edit"><?php _e( 'Edit', $this->plugin_slug ); ?></a>
        			<a href="#" class="btn btn-xs btn-danger delete"><?php _e( 'Delete', $this->plugin_slug ); ?></a>
        		</div>
        	</td>
        </script>
        <script type="text/template" id="posttypeedit-template">
            <form action="#">
                <input id="source" type="hidden" value="<%- source %>" />
            	<label for="post_type"><?php _e( 'Post Type', $this->plugin_slug ); ?> <a href="#" data-toggle="popover" data-placement="top" data-content="<?php _e( 'Post type name. (max. 20 characters, can not contain capital letters or spaces)', $this->plugin_slug ); ?>">?</a></label>
	            <input type="text" name="post_type" value="<%- post_type %>">
	            <input type="text" name="label" value="<%- label %>">
				<input type="text" name="labels[name]" value="<%- labels.name %>">
				<input type="text" name="labels[singular_name]" value="<%- labels.singular_name %>">
				<input type="text" name="labels[menu_name]" value="<%- labels.menu_name %>">
				<input type="text" name="labels[all_items]" value="<%- labels.all_items %>">
				<input type="text" name="labels[add_new]" value="<%- labels.add_new %>">
				<input type="text" name="labels[add_new_item]" value="<%- labels.add_new_item %>">
				<input type="text" name="labels[edit_item]" value="<%- labels.edit_item %>">
				<input type="text" name="labels[new_item]" value="<%- labels.new_item %>">
				<input type="text" name="labels[view_item]" value="<%- labels.view_item %>">
				<input type="text" name="labels[search_items]" value="<%- labels.search_items %>">
				<input type="text" name="labels[not_found]" value="<%- labels.not_found %>">
				<input type="text" name="labels[not_found_in_trash]" value="<%- labels.not_found_in_trash %>">
				<input type="text" name="labels[parent_item_colon]" value="<%- labels.parent_item_colon %>">
				<textarea name="description"><%- description %></textarea>
				<select name="public">
					<option value="true"><?php _e( 'True', $this->plugin_slug ); ?></option>
					<option value="false"><?php _e( 'False', $this->plugin_slug ); ?></option>
				</select>
				<select name="exclude_from_search">
					<option value="true"><?php _e( 'True', $this->plugin_slug ); ?></option>
					<option value="false"><?php _e( 'False', $this->plugin_slug ); ?></option>
				</select>
				<select name="publicly_queryable">
					<option value="true"><?php _e( 'True', $this->plugin_slug ); ?></option>
					<option value="false"><?php _e( 'False', $this->plugin_slug ); ?></option>
				</select>

                <a class="btn btn-xs btn-success save"><?php _e( 'Save', $this->plugin_slug ); ?></a>
                <a class="btn btn-xs btn-warning cancel"><?php _e( 'Cancel', $this->plugin_slug ); ?></a>
            </form>
        </script>
		<script type="text/template" id="posttypes-template">
            <table class="">
            	<thead>
            		<tr>
            			<th><input type="checkbox"></th>
            			<th><?php _e( 'Post Type', $this->plugin_slug ); ?></th>
            			<th><?php _e( 'Source', $this->plugin_slug ); ?></th>
            			<th><?php _e( 'Status', $this->plugin_slug ); ?></th>
            			<th></th>
            		</tr>
            	</thead>
            	<tbody></tbody>
		</script>

		<script type="text/template" id="posttype-template">
        	<td><input type="checkbox"></td>
        	<td><%- label %></td>
        	<td><%- source %></td>
        	<td><span class="label label-default">Unmodified</span></td>
        	<td>
        		<div class="btn-group pull-right">
        			<a href="#" class="btn btn-xs btn-success edit"><?php _e( 'Edit', $this->plugin_slug ); ?></a>
        			<a href="#" class="btn btn-xs btn-danger delete"><?php _e( 'Delete', $this->plugin_slug ); ?></a>
        		</div>
        	</td>
		</script>
	</div>
</div>
