<?php
$page = get_page_by_path('looks', OBJECT, 'page');

$title = get_field('looks_page_title', $page) ?? get_the_title();
$descr = get_field('looks_page_descr', $page) ?? '';
$posts_per_page = get_field('looks_posts_per_page', $page) ?? 4;

$initial_args = array(
	'post_type' => 'looks',
	'posts_per_page' => $posts_per_page,
	'post_status' => 'publish',
	'paged' => 1,
	'orderby' => 'date',
	'order' => 'DESC',
);

$initial_query = new WP_Query($initial_args);
$total_pages = $initial_query->max_num_pages;
?>

<section class="section section-looks" id="section-looks">
	<div class="container section-looks__container">
		<div class="section-looks__grid looks-grid" id="looks-grid"
			data-posts-per-page="<?php echo esc_attr($posts_per_page); ?>">
			<?php if ($initial_query->have_posts()):
				while ($initial_query->have_posts()):
					$initial_query->the_post();
					get_template_part('partials/looks/looks-card');
				endwhile;
				wp_reset_postdata();
			else: ?>
				<p class="section-looks__empty">Образы пока не добавлены.</p>
			<?php endif; ?>
		</div>

		<?php if ($total_pages > 1): ?>
			<div class="section-looks__actions">
				<button type="button" class="section-looks__load-more btn btn--stroke-accent" id="looks-load-more" data-page="1"
					data-total="<?php echo esc_attr($total_pages); ?>">
					Показать ещё
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
// ВСТРАИВАЕМ JAVASCRIPT ПРЯМО ЗДЕСЬ — это гарантирует работу
add_action('wp_footer', function () use ($posts_per_page) {
	?>
	<script>
		jQuery(document).ready(function ($) {
			console.log('Looks: script loaded');

			var $grid = $('#looks-grid');
			var $btn = $('#looks-load-more');

			if (!$btn.length) {
				console.log('Looks: кнопка не найдена');
				return;
			}

			console.log('Looks: кнопка найдена, total=' + $btn.data('total'));

			$btn.on('click', function () {
				var $this = $(this);
				if ($this.prop('disabled')) return;

				var currentPage = parseInt($this.data('page')) + 1;
				var postsPerPage = parseInt($grid.data('posts-per-page')) || <?php echo (int) $posts_per_page; ?>;

				console.log('Looks: загрузка страницы ' + currentPage);
				$this.prop('disabled', true).text('Загрузка...');

				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'load_more_looks',
						page: currentPage,
						posts_per_page: postsPerPage
					},
					success: function (res) {
						console.log('Looks: ответ', res);

						if (res.success && res.data && res.data.html) {
							$grid.append(res.data.html);

							if (res.data.has_more) {
								$this.data('page', currentPage).prop('disabled', false).text('Показать ещё');
							} else {
								console.log('Looks: все загружено');
								$this.fadeOut(300, function () { $(this).remove(); });
							}
						} else {
							console.error('Looks: ошибка ответа', res);
							$this.prop('disabled', false).text('Показать ещё');
						}
					},
					error: function (xhr, status, error) {
						console.error('Looks: AJAX error', status, error);
						$this.prop('disabled', false).text('Показать ещё');
					}
				});
			});
		});
	</script>
	<?php
});
?>