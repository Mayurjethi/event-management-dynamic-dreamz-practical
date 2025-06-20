<?php

/**
 * The template for displaying singular post-types: posts, pages, and custom post types.
 * Modern layout with sidebar for Hello Elementor.
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

while (have_posts()) :
	the_post();
?>

	<div class="single-page-wrap">
		<main id="content" <?php post_class('site-main single-page-main'); ?>>
			<?php if (apply_filters('hello_elementor_page_title', true)) : ?>
				<div class="page-header">
					<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
				</div>
			<?php endif; ?>

			<div class="page-content">
				<?php the_content(); ?>

				<?php wp_link_pages(); ?>

				<?php if (has_tag()) : ?>
					<div class="post-tags">
						<?php the_tags('<span class="tag-links">' . esc_html__('Tagged ', 'hello-elementor'), ', ', '</span>'); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php comments_template(); ?>
		</main>

		<aside class="single-event-sidebar">
			<?php
			if (is_active_sidebar('main-sidebar')) {
				dynamic_sidebar('main-sidebar');
			}
			?>
		</aside>
	</div>

	<style>
		.single-page-wrap {
			display: flex;
			flex-direction: row;
			gap: 2.2rem;
			max-width: 1200px;
			margin: 3rem auto;
			padding: 0 1rem;
		}

		.single-page-main {
			flex: 1 1 0;
			min-width: 0;
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 4px 24px rgba(0, 0, 0, 0.09);
			padding: 2.5rem 2rem 2rem 2rem;
			display: flex;
			flex-direction: column;
			gap: 1.5rem;
		}

		.single-event-sidebar {
			width: 260px;
			max-width: 100%;
			flex-shrink: 0;
		}

		@media (max-width: 900px) {
			.single-page-wrap {
				flex-direction: column;
			}

			.single-event-sidebar {
				width: 100%;
				padding: 0;
			}

			.single-page-main {
				padding: 1.2rem;
			}
		}

		@media (max-width: 600px) {
			.single-page-main {
				padding: 1rem;
			}

			.page-header .entry-title {
				font-size: 1.3rem;
			}
		}
	</style>

<?php
endwhile;
?>