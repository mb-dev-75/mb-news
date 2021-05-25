<?php
/**
 * Adds mb_News_Widget widget.
 */
class mb_News_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'mb-news_widget', esc_html__( 'MB News', 'mb_domain' ),
			array( 'description' => esc_html__( 'A carousel with the last articles',	'mb_domain' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$nb_articles = $instance['nb_articles'];
		$lastArticles = get_posts( array( 'posts_per_page'=> $nb_articles, 'order'=>'DESC', 'orderby'=>'date' ) );
		$nb_slides = $instance['nb_slides'];
		$categories = $instance['display_categories'];
		$nb_categories = $instance['nb_categories'];
		$date = $instance['display_date'];
		$excerpt = $instance['display_excerpt'];
		$comments = $instance['display_nb_comments'];
		$arrows = $instance['display_arrows'];
		$dots = $instance['display_dots'];
		$autoplay = $instance['enable_autoplay'];
		$autoplay_speed = $instance['autoplay_speed'];

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
	?>
		<div class="container mb-news">
			<div class="row">
				<div class="col-md-12">
					<div class="slick-carousel">
						<?php foreach ( $lastArticles as $article ) : setup_postdata($article); ?>
							<div class="col-md-12 mb-news-article">
								<a href="<?php echo get_permalink($article->ID); ?>">
									<?php echo get_the_post_thumbnail($article->ID); ?>

									<?php
										if ($categories === 'yes') {
											$cats = get_the_category($article->ID);
											$nb_cats = 0;

											echo '<div class="mb-news-article-categories">';
												foreach( $cats as $cat ) {
													if ($nb_cats < $nb_categories) {
														echo'
															<a class="mb-news-article-categories-link" href='.get_category_link($cat->term_id).'>
																'.$cat->name.'
															</a>';
													}
														$nb_cats++;
												};
											echo '</div>';
										}
									?>
									<div class="mb-news-article-details">
										<?php
											if ($date === 'yes') {
												echo '<div class="mb-news-article-date">'.date('d/m/Y', strtotime($article->post_date)).'</div>';
											}
										?>
										<?php
											if($comments === 'yes') {
												echo '
												<div class="mb-news-article-comments">
													<svg viewBox="0 0 24 24">
														<path d="M22,4C22,2.89 21.1,2 20,2H4A2,2 0 0,0 2,4V16A2,2 0 0,0 4,18H18L22,22V4Z" />
													</svg>
													'.get_comments_number($article->ID).'
												</div>';
											}
										?>
									</div>
									<div class="mb-news-article-title"><?php echo $article->post_title; ?></div>
									<?php
										if ($excerpt === 'yes') {
											echo '<div class="mb-news-article-content">'.get_the_excerpt($article->ID).'</div>';
										}
									?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<!-- Carousel params -->
		<script type="text/javascript">
			jQuery(function ($) {
				$('.slick-carousel').slick({
					autoplay: <?php echo $autoplay; ?>,
  				autoplaySpeed: <?php echo $autoplay_speed; ?>,
					speed: 300,
					prevArrow: '<span class="slick-prev-custom" aria-label="Previous"><svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg></span>',
          nextArrow: '<span class="slick-next-custom" aria-label="Next"><svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></span>',
					slidesToShow: <?php echo $nb_slides; ?>,
					slidesToScroll: <?php echo $nb_slides; ?>,
					infinite: true,
					dots: <?php echo $dots; ?>,
					arrows: <?php echo $arrows; ?>,
					responsive: [
						{
							breakpoint: 1200,
							settings: {
								slidesToShow: 3,
								slidesToScroll: 3,
								infinite: true,
								dots: <?php echo $dots; ?>,
							},
						},
						{
							breakpoint: 900,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2,
								infinite: true,
								dots: <?php echo $dots; ?>,
							},
						},
						{
							breakpoint: 660,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1,
								infinite: true,
								dots: false,
							},
						},
					],
				});
			});
		</script>

		<?php
			echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$nb_articles = ! empty( $instance['nb_articles'] ) ? $instance['nb_articles'] : '';
		$nb_slides = ! empty( $instance['nb_slides'] ) ? $instance['nb_slides'] : '';
		$display_categories = ! empty( $instance['display_categories'] ) ? $instance['display_categories'] : '';
		$nb_categories = ! empty( $instance['nb_categories'] ) ? $instance['nb_categories'] : '';
		$display_date = ! empty( $instance['display_date'] ) ? $instance['display_date'] : '';
		$display_excerpt = ! empty( $instance['display_excerpt'] ) ? $instance['display_excerpt'] : '';
		$display_nb_comments = ! empty( $instance['display_nb_comments'] ) ? $instance['display_nb_comments'] : '';
		$display_arrows = ! empty( $instance['display_arrows'] ) ? $instance['display_arrows'] : '';
		$display_dots = ! empty( $instance['display_dots'] ) ? $instance['display_dots'] : '';
		$enable_autoplay = ! empty( $instance['enable_autoplay'] ) ? $instance['enable_autoplay'] : '';
		$autoplay_speed = ! empty( $instance['autoplay_speed'] ) ? $instance['autoplay_speed'] : '';
		?>
		<!-- Title -->
		<p>
		  <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
        <?php esc_attr_e( 'Title:', 'mb_domain' ); ?>
      </label>
		  <input
        class="widefat"
        id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
        name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
        type="text"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>
		<!-- Articles number -->
		<p>
		  <label for="<?php echo esc_attr( $this->get_field_id( 'nb_articles' ) ); ?>">
        <?php esc_attr_e( 'Number of Articles:', 'mb_domain' ); ?>
      </label>
			<select
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'nb_articles' ) ); ?>"
        name="<?php echo esc_attr( $this->get_field_name( 'nb_articles' ) ); ?>"
			>
				<option value="6" <?php selected( $instance['nb_articles'], '6' ); ?> >6</option>
				<option value="8" <?php selected( $instance['nb_articles'], '8' ); ?> >8</option>
				<option value="9" <?php selected( $instance['nb_articles'], '9' ); ?> >9</option>
				<option value="12" <?php selected( $instance['nb_articles'], '12' ); ?> >12</option>
				<option value="15" <?php selected( $instance['nb_articles'], '15' ); ?> >15</option>
			</select>
		</p>

		<div style="display: flex; justify-content: space-between">
			<!-- Display categories -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'display_categories' ) ); ?>">
					<?php esc_attr_e( 'Categories:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'display_categories' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display_categories' ) ); ?>"
				>
					<option value="yes" <?php selected( $instance['display_categories'], 'yes' ); ?> >Yes</option>
					<option value="no" <?php selected( $instance['display_categories'], 'no' ); ?> >No</option>
				</select>
			</p>
			<!-- Categories qtity -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'nb_categories' ) ); ?>">
					<?php esc_attr_e( 'Max cat.:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'nb_categories' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'nb_categories' ) ); ?>"
				>
					<option value="1" <?php selected( $instance['nb_categories'], '1' ); ?> >1</option>
					<option value="2" <?php selected( $instance['nb_categories'], '2' ); ?> >2</option>
					<option value="3" <?php selected( $instance['nb_categories'], '3' ); ?> >3</option>
				</select>
			</p>
		</div>

		<div style="display: flex; justify-content: space-between">
			<!-- Display date -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'display_date' ) ); ?>">
					<?php esc_attr_e( 'Date:', 'mb_domain' ); ?>
				</label>
				<select
					id="<?php echo esc_attr( $this->get_field_id( 'display_date' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display_date' ) ); ?>"
				>
					<option value="yes" <?php selected( $instance['display_date'], 'yes' ); ?> >Yes</option>
					<option value="no" <?php selected( $instance['display_date'], 'no' ); ?> >No</option>
				</select>
			</p>
			<!-- Display nb of comments -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'display_nb_comments' ) ); ?>">
					<?php esc_attr_e( 'Comments:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'display_nb_comments' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display_nb_comments' ) ); ?>"
				>
					<option value="yes" <?php selected( $instance['display_nb_comments'], 'yes' ); ?> >Yes</option>
					<option value="no" <?php selected( $instance['display_nb_comments'], 'no' ); ?> >No</option>
				</select>
			</p>
		</div>

		<!-- Display Excerpt -->
		<p style="margin-top: 0; display: flex; flex-direction: column">
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_excerpt' ) ); ?>">
				<?php esc_attr_e( 'Excerpt:', 'mb_domain' ); ?>
			</label>
			<select
				id="<?php echo esc_attr( $this->get_field_id( 'display_excerpt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'display_excerpt' ) ); ?>"
			>
				<option value="yes" <?php selected( $instance['display_excerpt'], 'yes' ); ?> >Yes</option>
				<option value="no" <?php selected( $instance['display_excerpt'], 'no' ); ?> >No</option>
			</select>
		</p>

		<!-- Slides number -->
		<p style="margin-top: 0; display: flex; flex-direction: column">
			<label for="<?php echo esc_attr( $this->get_field_id( 'nb_slides' ) ); ?>">
				<?php esc_attr_e( 'Number of Slides:', 'mb_domain' ); ?>
			</label>
			<select
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'nb_slides' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'nb_slides' ) ); ?>"
			>
				<option value="2" <?php selected( $instance['nb_slides'], '2' ); ?> >2</option>
				<option value="3" <?php selected( $instance['nb_slides'], '3' ); ?> >3</option>
				<option value="4" <?php selected( $instance['nb_slides'], '4' ); ?> >4</option>
			</select>
		</p>

		<div style="display: flex; justify-content: space-between">
			<!-- Display arrows -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'display_arrows' ) ); ?>">
					<?php esc_attr_e( 'Arrows:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'display_arrows' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display_arrows' ) ); ?>"
				>
					<option value="true" <?php selected( $instance['display_arrows'], 'true' ); ?> >Yes</option>
					<option value="false" <?php selected( $instance['display_arrows'], 'false' ); ?> >No</option>
				</select>
			</p>
			<!-- Display dots -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'display_dots' ) ); ?>">
					<?php esc_attr_e( 'Dots:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'display_dots' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'display_dots' ) ); ?>"
				>
					<option value="true" <?php selected( $instance['display_dots'], 'true' ); ?> >Yes</option>
					<option value="false" <?php selected( $instance['display_dots'], 'false' ); ?> >No</option>
				</select>
			</p>
		</div>

		<div style="display: flex; justify-content: space-between">
			<!-- Enable autoplay -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'enable_autoplay' ) ); ?>">
					<?php esc_attr_e( 'Autoplay:', 'mb_domain' ); ?>
				</label>
				<select
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'enable_autoplay' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'enable_autoplay' ) ); ?>"
				>
					<option value="true" <?php selected( $instance['enable_autoplay'], 'true' ); ?> >Yes</option>
					<option value="false" <?php selected( $instance['enable_autoplay'], 'false' ); ?> >No</option>
				</select>
			</p>
			<!-- Autoplay speed -->
			<p style="margin-top: 0; width: 48%; display: flex; flex-direction: column">
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay_speed' ) ); ?>">
					<?php esc_attr_e('Autoplay speed:', 'mb_domain' ); ?>
				</label>
				<input
					class="widefat"
					type="text"
					placeholder="6000 = 6s"
					id="<?php echo esc_attr( $this->get_field_id( 'autoplay_speed' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'autoplay_speed' ) ); ?>"
					value="<?php echo esc_attr( $autoplay_speed ); ?>"
				/>
			</p>
		</div>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
    $instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['nb_articles'] = ( ! empty( $new_instance['nb_articles'] ) ) ? sanitize_text_field( $new_instance['nb_articles'] ) : '';
		$instance['nb_slides'] = ( ! empty( $new_instance['nb_slides'] ) ) ? sanitize_text_field( $new_instance['nb_slides'] ) : '';
		$instance['display_categories'] = ( ! empty( $new_instance['display_categories'] ) ) ? sanitize_text_field( $new_instance['display_categories'] ) : '';
		$instance['nb_categories'] = ( ! empty( $new_instance['nb_categories'] ) ) ? sanitize_text_field( $new_instance['nb_categories'] ) : '';
		$instance['display_date'] = ( ! empty( $new_instance['display_date'] ) ) ? sanitize_text_field( $new_instance['display_date'] ) : '';
		$instance['display_excerpt'] = ( ! empty( $new_instance['display_excerpt'] ) ) ? sanitize_text_field( $new_instance['display_excerpt'] ) : '';
		$instance['display_nb_comments'] = ( ! empty( $new_instance['display_nb_comments'] ) ) ? sanitize_text_field( $new_instance['display_nb_comments'] ) : '';
		$instance['display_arrows'] = ( ! empty( $new_instance['display_arrows'] ) ) ? sanitize_text_field( $new_instance['display_arrows'] ) : '';
		$instance['display_dots'] = ( ! empty( $new_instance['display_dots'] ) ) ? sanitize_text_field( $new_instance['display_dots'] ) : '';
		$instance['enable_autoplay'] = ( ! empty( $new_instance['enable_autoplay'] ) ) ? sanitize_text_field( $new_instance['enable_autoplay'] ) : '';
		$instance['autoplay_speed'] = ( ! empty( $new_instance['autoplay_speed'] ) ) ? sanitize_text_field( $new_instance['autoplay_speed'] ) : '';
		return $instance;
	}

}