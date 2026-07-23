<?php
/**
 * Closing body — scroll-reveal enhancement + wp_footer.
 *
 * @package the-standard
 */
?>
<script>
// Magazine scroll reveal — IntersectionObserver (GPU-only, no layout impact).
(function () {
	if (!('IntersectionObserver' in window)) return;
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (e) {
			if (e.isIntersecting) {
				e.target.classList.add('is-visible');
				io.unobserve(e.target);
			}
		});
	}, { threshold: 0.07, rootMargin: '0px 0px -40px 0px' });

	function init() {
		document.querySelectorAll('.popular-wrap, .section, .events-section, .site-footer').forEach(function (el) {
			el.classList.add('reveal');
			io.observe(el);
		});
		document.querySelectorAll('.hero-minimal-grid, .grid-editorial, .grid-magazine, .grid-minimal, .grid-bento, .events-rest').forEach(function (el) {
			el.classList.add('reveal-children');
			io.observe(el);
		});
	}

	// React mounts asynchronously (Babel transform runs on DOMContentLoaded);
	// poll briefly until #root has content, then wire up the observer.
	var tries = 0;
	(function waitForMount() {
		var root = document.getElementById('root');
		if (root && root.children.length) { setTimeout(init, 60); return; }
		if (tries++ < 60) setTimeout(waitForMount, 100);
	})();
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
