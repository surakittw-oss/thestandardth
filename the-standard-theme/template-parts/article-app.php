<?php
/**
 * Article page React app mount.
 *
 * Static build: picks the article from window.ARTICLES by the ?id= query
 * param (falls back to the first article). If a real post id/slug is later
 * wired to a data entry, this is the single place to bridge it.
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
	const article = (wantId && window.ARTICLES.find(a => a.id === wantId)) || window.ARTICLES[0];

	const related = window.ARTICLES
		.filter(a => a.category === article.category && a.id !== article.id)
		.slice(0, 4);

	return (
		<ArticlePage
			article={article}
			dark={dark}
			setDark={setDark}
			activeCat={activeCat}
			setActiveCat={setActiveCat}
			related={related.length ? related : window.ARTICLES.slice(1, 5)}
		/>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
