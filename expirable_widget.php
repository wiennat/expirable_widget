<?php
/*
Plugin Name: Expirable widget
Plugin URI: https://github.com/wiennat/expirable_widget
Description: This plugin allow you to have a widget with a expire date. The widget does not display itself if it expires.
Version: 1.0
Author: wiennat
Author URI: http://onedd.net/
License: GPL2

Copyright 2013 Wiennat (email : wiennat@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Expirable_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'expirable_widget', // Base ID
			'Expirable widget', // Name
			array( 'description' => __( 'An expirable widget', 'expirable_widget' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array   $args     Widget arguments.
	 * @param array   $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$show_title = isset( $instance['show_title'] ) ? 1:0;

		if ( ! empty ( $instance['expire_date'] ) ) {
			try {
				$expire_date = new DateTime( $instance['expire_date'] );
				$show_widget = isset( $expire_date ) && $expire_date > new DateTime();
			} catch ( Exception $e ) {
				// Parse error. Do not display the widget.
				$show_widget = 0;
			}
		} else {
			// No expire date. Always display the widget.
			$show_widget = 1;
		}

		if ( $show_widget ) {
			echo $before_widget;
			if ( $show_title && ! empty( $title ) )
				echo $before_title . $title . $after_title;?>
		<div class="textwidget"><?php echo $text; ?></div>
		<?php
			echo $after_widget;
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array   $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'show_title' => false, 'text' => '', 'expire_date' => '' ) );

?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $instance['text']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'expire_date' ); ?>"><?php _e( 'Expire date:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'expire_date' ); ?>" name="<?php echo $this->get_field_name( 'expire_date' ); ?>" type="text" value="<?php echo esc_attr( $instance['expire_date'] ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_title'], true ) ?> id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array   $new_instance Values just sent to be saved.
	 * @param array   $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_title'] = isset( $new_instance['show_title'] ) ? 1: 0;
		$instance['text'] = strip_tags( $new_instance['text'] );
		$instance['expire_date'] = strip_tags( $new_instance['expire_date'] );
		return $instance;
	}

}

class Expirable_Image_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'expirable_image_widget', // Base ID
			'Expirable image', // Name
			array( 'description' => __( 'An expirable image widget', 'expirable_widget' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array   $args     Widget arguments.
	 * @param array   $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$image_url = empty( $instance['image'] ) ? '' : $instance['image'];
		$link_url = empty( $instance['link'] ) ? '' : $instance['link'];
		$show_title = isset( $instance['show_title'] ) ? $instance['show_title']:0;

		if ( ! empty ( $instance['expire_date'] ) ) {
			try {
				$expire_date = new DateTime( $instance['expire_date'] );
				$show_widget = $expire_date >= new DateTime();

			} catch ( Exception $e ) {
				// Parse error. Do not display the widget.
				$show_widget = 0;
			}
		} else {
			// No expire date. Always display the widget.
			$show_widget = 1;
		}

		if ( $show_widget && ! empty ( $image_url ) ) {

			echo $before_widget;
			if ( $show_title && ! empty( $title ) )
				echo $before_title . $title . $after_title;?>

			<?php if ( ! empty ( $link_url ) ): ?>
			<a href="<?php echo esc_attr( $link_url ); ?>">
			<?php endif; ?>
			<img src="<?php echo esc_attr( $image_url ); ?>">
			<?php if ( ! empty ( $link_url ) ): ?>
			</a>
			<?php endif; ?>
			<?php
			echo $after_widget;
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array   $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'show_title' => false, 'text' => '', 'expire_date' => '' ) );

?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image url:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_attr( $instance['image'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link url:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $instance['link'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'expire_date' ); ?>"><?php _e( 'Expire date:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'expire_date' ); ?>" name="<?php echo $this->get_field_name( 'expire_date' ); ?>" type="text" value="<?php echo esc_attr( $instance['expire_date'] ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_title'], true ) ?> id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array   $new_instance Values just sent to be saved.
	 * @param array   $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_title'] = isset( $new_instance['show_title'] ) ? 1: 0;
		$instance['image'] = strip_tags( $new_instance['image'] );
		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['expire_date'] = strip_tags( $new_instance['expire_date'] );
		return $instance;
	}

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Expirable_Widget");' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget("Expirable_Image_Widget");' ) );
