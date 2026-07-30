<?php
/**
 * Archive React app mount (category / tag / search / date).
 *
 * Expects window.TS_ARCHIVE, TS_ARCHIVE_POSTS and TS_ARCHIVE_PAGINATION to have
 * been emitted first — call ts_the_archive_data() in the template.
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

	return (
		<ArchivePage
			archive={window.TS_ARCHIVE || {}}
			posts={window.TS_ARCHIVE_POSTS || []}
			pagination={window.TS_ARCHIVE_PAGINATION || {}}
			dark={dark}
			setDark={setDark}
			activeCat={activeCat}
			setActiveCat={setActiveCat}
		/>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
