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
							<h1><?php _e( 'Todos Demo', $this->Todo_Demo ); ?></h1>
						</header>
						<section id="main">
							<ul class="list-group" id="todo-list"></ul>
						</section>
					</section>
				</form>
			</div>
		</div>
		<script type="text/template" id="item-template">
			<%- title %> <a href="#" class="destroy btn btn-danger">X</a>				
		</script>
	</div>
</div>
