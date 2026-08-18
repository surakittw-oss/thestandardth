<?php
/**
 * Homepage React app mount.
 *
 * Hero comes from window.TS_FEATURED (sticky posts first), then one block per
 * category from window.TS_HOME_SECTIONS — both emitted by ts_the_home_data().
 * With no sections configured it falls back to a single latest-posts grid.
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
	const variant = 'minimal';

	React.useEffect(() => {
		document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
	}, [dark]);

	React.useEffect(() => {
		const saved = localStorage.getItem('ts-prefs-v2');
		if (saved) {
			try {
				const p = JSON.parse(saved);
				if (typeof p.dark === 'boolean') setDark(p.dark);
			} catch (e) {}
		}
	}, []);
	React.useEffect(() => {
		localStorage.setItem('ts-prefs-v2', JSON.stringify({ variant, dark }));
	}, [dark]);

	const articles = window.ARTICLES || [];
	const featured = (window.TS_FEATURED && window.TS_FEATURED.length) ? window.TS_FEATURED : articles;
	const sections = window.TS_HOME_SECTIONS || [];
	const latest   = window.TS_HOME_LATEST || null;

	return (
		<div data-screen-label="Homepage">
			<Header
				dark={dark}
				setDark={setDark}
				activeCat={activeCat}
				setActiveCat={setActiveCat}
				variant={variant}
			/>
			<main>
				<Hero articles={featured} variant={variant} />
				{sections.map(s => (
					<CategorySection
						key={s.label}
						title={s.label}
						url={s.url}
						sub={s.sub}
						posts={s.posts}
					/>
				))}
				{latest ? <CategorySection title={latest.label} posts={latest.posts} /> : null}
				{(!sections.length && !latest) && (
					<LatestGrid articles={articles} activeCat={'ALL'} variant={variant} />
				)}
			</main>
			<Footer />
		</div>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
