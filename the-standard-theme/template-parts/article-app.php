<?php
/**
 * Article page React app mount.
 *
 * Data source priority:
 *   1. window.TS_ARTICLE  — real WordPress post (set by single.php from the loop)
 *   2. ?id=<article-id>   — static entry from window.ARTICLES (homepage cards)
 *   3. window.ARTICLES[0] — fallback
 *
 * @package the-standard
 */
?>
<div id="root"></div>
<?php ts_the_runtime_scripts(); ?>
<script type="text/babel">
function App() {
	const [dark, setDark] = React.useState(false);
	const [activeCat, setActiveCat] = React.useState('ALL');

	React.useEffect(() => {
		document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
	}, [dark]);

	const params = new URLSearchParams(window.location.search);
	const wantId = params.get('id');

	// 1) real WP post, 2) static ?id lookup, 3) first article
	const article =
		window.TS_ARTICLE ||
		(wantId && window.ARTICLES.find(a => a.id === wantId)) ||
		window.ARTICLES[0];

	// Related: WP-provided list, else same-category static articles
	let related = (window.TS_RELATED && window.TS_RELATED.length)
		? window.TS_RELATED
		: window.ARTICLES.filter(a => a.category === article.category && a.id !== article.id).slice(0, 4);
	if (!related.length) related = window.ARTICLES.slice(1, 5);

	return (
		<ArticlePage
			article={article}
			dark={dark}
			setDark={setDark}
			activeCat={activeCat}
			setActiveCat={setActiveCat}
			related={related}
		/>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
