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
		<div class="container">
			<div class="row">
				<form action="#" class="form-horizontal">
					<section id="todoapp">
						<header id="header">
							<h1><?php _e( 'Todos Demo', $this->plugin_slug ); ?></h1>
						</header>
						<section id="main">
							<table class="table">
								<thead>
									<tr>
										<th><?php _e( 'Post Type', $this->plugin_slug ); ?></th>
										<th><?php _e( 'Source', $this->plugin_slug ); ?></th>
										<th><?php _e( 'Controls', $this->plugin_slug ); ?></th>
									</tr>
								</thead>
								<tbody id="posttype-list">
								</tbody>
							</table>
						</section>
					</section>
				</form>
			</div>
		</div>
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
