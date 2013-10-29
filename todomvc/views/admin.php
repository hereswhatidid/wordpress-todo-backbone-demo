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
        <div id="post-types">
            <header>
                <div id="filter"><label>Show me:</label></div>
                <a id="showForm" href="#">Add new post type</a>
                <form id="addPostType" action="#">
	                <input id="source" type="hidden" value="">
		            <input type="text" name="post_type" value="">
		            <input type="text" name="label" value="">
					<input type="text" name="labels[name]" value="">
					<input type="text" name="labels[singular_name]" value="">
					<input type="text" name="labels[menu_name]" value="">
					<input type="text" name="labels[all_items]" value="">
					<input type="text" name="labels[add_new]" value="">
					<input type="text" name="labels[add_new_item]" value="">
					<input type="text" name="labels[edit_item]" value="">
					<input type="text" name="labels[new_item]" value="">
					<input type="text" name="labels[view_item]" value="">
					<input type="text" name="labels[search_items]" value="">
					<input type="text" name="labels[not_found]" value="">
					<input type="text" name="labels[not_found_in_trash]" value="">
					<input type="text" name="labels[parent_item_colon]" value="">
					<textarea name="description"></textarea>
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
                    <button id="add">Add</button>
                </form>
            </header>

            <table>
            	<thead>
            		<tr>
            			<th><?php _e( 'Post Type', $this->plugin_slug ); ?></th>
            			<th><?php _e( 'Source', $this->plugin_slug ); ?></th>
            			<th></th>
            		</tr>
            	</thead>
            	<tbody id="post-list"></tbody>
            </table>
        </div>
        <script id="postTypeTemplate" type="text/template">
        	<td><%- label %></td>
        	<td><%- source %></td>
        	<td>
        		<div class="btn-group">
        			<a href="#" class="btn btn-xs btn-success edit"><?php _e( 'Edit', $this->plugin_slug ); ?></a>
        			<a href="#" class="btn btn-xs btn-danger delete"><?php _e( 'Delete', $this->plugin_slug ); ?></a>
        		</div>
        	</td>
        </script>
        <script id="postTypeEditTemplate" type="text/template">
            <form action="#">
                <input id="source" type="hidden" value="<%- source %>" />
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

                <button class="save"><?php _e( 'Save', $this->plugin_slug ); ?></button>
                <button class="cancel"><?php _e( 'Cancel', $this->plugin_slug ); ?></button>
            </form>
        </script>
		<script type="text/template" id="item-template">
			<td><%- postType %></td>
			<td><%- source %></td>
			<td>
			<div class="btn-group">
			<a href="#edit/<%- postType %>" class="btn btn-primary btn-xs"><?php _e( 'Edit', $this->plugin_slug ); ?></a>
			<a href="#revert/<%- postType %>" class="btn btn-warning btn-xs"><?php _e( 'Revert', $this->plugin_slug ); ?></a>
			<a href="#disable/<%- postType %>" class="btn btn-danger btn-xs"><?php _e( 'Disable', $this->plugin_slug ); ?></a>
			</div>
			</td>
		</script>
	</div>
</div>
