<?php
/**
 * Homepage React app mount.
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

	const filteredArticles = React.useMemo(() => {
		if (activeCat === 'ALL') return window.ARTICLES;
		return window.ARTICLES.filter(a => a.category === activeCat);
	}, [activeCat]);

	const heroPool = filteredArticles.length >= 4 ? filteredArticles : window.ARTICLES;

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
				<Hero articles={heroPool} variant={variant} />
				<OpinionSection />
				<PopularSection />
				<LatestGrid articles={filteredArticles} activeCat={activeCat} variant={variant} />
				<VideoSection />
				<ShortClipSection />
				<EventsSection />
			</main>
			<Footer />
		</div>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
