// ============ SVG ICONS ============
const Icons = {
  sun: (
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round">
      <circle cx="8" cy="8" r="3"/>
      <line x1="8" y1="1" x2="8" y2="2.5"/>
      <line x1="8" y1="13.5" x2="8" y2="15"/>
      <line x1="1" y1="8" x2="2.5" y2="8"/>
      <line x1="13.5" y1="8" x2="15" y2="8"/>
      <line x1="3.05" y1="3.05" x2="4.1" y2="4.1"/>
      <line x1="11.9" y1="11.9" x2="12.95" y2="12.95"/>
      <line x1="12.95" y1="3.05" x2="11.9" y2="4.1"/>
      <line x1="4.1" y1="11.9" x2="3.05" y2="12.95"/>
    </svg>
  ),
  moon: (
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M13.5 10A6 6 0 0 1 6 2.5a6 6 0 1 0 7.5 7.5z"/>
    </svg>
  ),
  menu: (
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
      <line x1="2" y1="5" x2="16" y2="5"/>
      <line x1="2" y1="9" x2="16" y2="9"/>
      <line x1="2" y1="13" x2="16" y2="13"/>
    </svg>
  ),
  close: (
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
      <line x1="3" y1="3" x2="15" y2="15"/>
      <line x1="15" y1="3" x2="3" y2="15"/>
    </svg>
  ),
  eye: (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M1 7s2-4 6-4 6 4 6 4-2 4-6 4-6-4-6-4z"/>
      <circle cx="7" cy="7" r="1.5"/>
    </svg>
  ),
  calendar: (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <rect x="1.5" y="2.5" width="11" height="10" rx="1.5"/>
      <line x1="1.5" y1="5.5" x2="12.5" y2="5.5"/>
      <line x1="4.5" y1="1" x2="4.5" y2="4"/>
      <line x1="9.5" y1="1" x2="9.5" y2="4"/>
    </svg>
  ),
  pin: (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M7 1a3.5 3.5 0 0 1 3.5 3.5C10.5 7.5 7 13 7 13S3.5 7.5 3.5 4.5A3.5 3.5 0 0 1 7 1z"/>
      <circle cx="7" cy="4.5" r="1.2"/>
    </svg>
  ),
  ticket: (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M1.5 5a1.5 1.5 0 0 0 0 4V11a1 1 0 0 0 1 1h9a1 1 0 0 0 1-1V9a1.5 1.5 0 0 0 0-4V3a1 1 0 0 0-1-1h-9a1 1 0 0 0-1 1v2z"/>
      <line x1="5" y1="2" x2="5" y2="12" strokeDasharray="1.5 1.5"/>
    </svg>
  ),
  search: (
    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round">
      <circle cx="7" cy="7" r="5"/>
      <line x1="11" y1="11" x2="15.5" y2="15.5"/>
    </svg>
  ),
  arrow: (
    <svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <line x1="0" y1="5" x2="12" y2="5"/>
      <polyline points="8,1 12,5 8,9"/>
    </svg>
  ),
};

const { useState, useEffect, useRef, useMemo } = React;

// ============ MEGA MENU TOPIC ICONS ============
const MEGA_ICONS = {
  // POP
  "Entertainment": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <polygon points="10,1.5 12.4,7.4 18.9,7.4 13.6,11.3 15.6,17.5 10,13.8 4.4,17.5 6.4,11.3 1.1,7.4 7.6,7.4"/>
    </svg>
  ),
  "Film": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <rect x="2" y="7" width="16" height="11" rx="1.5"/>
      <line x1="2" y1="11" x2="18" y2="11"/>
      <path d="M5.5 7L3.5 3h4l2 4"/>
      <path d="M10.5 7L8.5 3h4l2 4"/>
    </svg>
  ),
  "Music": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <ellipse cx="7.5" cy="15.5" rx="2.5" ry="2" transform="rotate(-10 7.5 15.5)"/>
      <line x1="10" y1="14.8" x2="10" y2="3"/>
      <path d="M10 3L17 1.5V6.5L10 8"/>
    </svg>
  ),
  "Fashion": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <circle cx="10" cy="3" r="1.5"/>
      <path d="M10 4.5V8"/>
      <path d="M10 8L3 13h14L10 8z"/>
      <line x1="3" y1="13" x2="17" y2="13"/>
    </svg>
  ),
  "K-POP": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M3 15V8l4 4 3-6 3 6 4-4v7H3z"/>
      <line x1="3" y1="17" x2="17" y2="17"/>
    </svg>
  ),
  "Art & Design": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M16.5 2L18 3.5 8 13.5l-4 1 1-4L16.5 2z"/>
      <line x1="14" y1="4.5" x2="15.5" y2="6"/>
      <path d="M3 17c0-1.5 1-2.5 2.5-2.5S8 15.5 8 17c0-1.5 1-2 2.5-2"/>
    </svg>
  ),
  // LIFE
  "Food & Drink": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <line x1="7" y1="2" x2="7" y2="18"/>
      <line x1="5" y1="2" x2="5" y2="8"/>
      <line x1="9" y1="2" x2="9" y2="8"/>
      <path d="M5 8Q7 10.5 9 8"/>
      <path d="M14 2L16 8Q16 11 14 11V18"/>
    </svg>
  ),
  "Place": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M10 2a6 6 0 0 1 6 6c0 4.5-6 11-6 11S4 12.5 4 8a6 6 0 0 1 6-6z"/>
      <circle cx="10" cy="8" r="2.2"/>
    </svg>
  ),
  "Active Leisure": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <circle cx="5" cy="14" r="3.2"/>
      <circle cx="15" cy="14" r="3.2"/>
      <path d="M5 14L10 7l5 7"/>
      <path d="M10 7l3.5-2.5L17 7"/>
      <path d="M10 7L8 9.5"/>
    </svg>
  ),
  "Body & Mind": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M10 16.5C5.5 13.5 3 10.8 3 8.2a4.2 4.2 0 0 1 7-3.1 4.2 4.2 0 0 1 7 3.1c0 2.6-2.5 5.3-7 8.3z"/>
    </svg>
  ),
  "Living": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <path d="M2 9.5L10 3l8 6.5"/>
      <path d="M4 8.2V18h12V8.2"/>
      <rect x="8" y="13" width="4" height="5"/>
    </svg>
  ),
  "Tech & Gadget": (
    <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round">
      <rect x="6" y="2" width="8" height="16" rx="2"/>
      <line x1="6" y1="5" x2="14" y2="5"/>
      <line x1="6" y1="15" x2="14" y2="15"/>
      <circle cx="10" cy="17" r="0.9" fill="currentColor" stroke="none"/>
    </svg>
  ),
};

// ============ HEADER ============
function Header({ dark, setDark, activeCat, setActiveCat, variant }) {
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [megaCat, setMegaCat] = useState(null);   // which category's mega menu is open
  const [mobileExpanded, setMobileExpanded] = useState(null); // mobile accordion
  const closeTimer = useRef(null);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    const onKey = (e) => { if (e.key === 'Escape') setMegaCat(null); };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, []);

  const today = new Date().toLocaleDateString('th-TH', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
  });

  const isDesktop = () => window.matchMedia('(min-width: 981px)').matches;

  const openMega = (cat) => {
    if (!cat) { setMegaCat(null); return; }
    clearTimeout(closeTimer.current);
    setMegaCat(cat);
  };
  const scheduleClose = () => {
    clearTimeout(closeTimer.current);
    closeTimer.current = setTimeout(() => setMegaCat(null), 160);
  };
  const cancelClose = () => clearTimeout(closeTimer.current);

  return (
    <header
      className={`site-header ${scrolled ? 'is-scrolled' : ''} variant-${variant} ${megaCat ? 'mega-open' : ''}`}
    >
      <div className="masthead">
        <div className="masthead-inner">
          <div className="masthead-meta left">
            <span className="date-label">{today}</span>
          </div>
          <a href="#" className="wordmark" aria-label="THE STANDARD">
            <img src="assets/logo-the-standard.png" alt="THE STANDARD" className="wordmark-img" />
          </a>
          <div className="masthead-meta right">
            <button className="icon-btn" onClick={() => setDark(d => !d)} aria-label="Toggle theme">
              {dark ? Icons.sun : Icons.moon}
            </button>
            <button className="subscribe-btn">MEMBERSHIP</button>
            <button
              className="menu-btn"
              aria-label="Menu"
              onClick={() => setMenuOpen(o => !o)}
            >
              {menuOpen ? Icons.close : Icons.menu}
            </button>
          </div>
        </div>
      </div>

      <nav className={`primary-nav ${menuOpen ? 'is-open' : ''}`}>
        <div className="nav-inner">
          <div className="nav-cats">
            {window.NAV_ITEMS.map((item, i) => {
              const hasMega = !!item.mega;
              const isActive = i === 0; // HOME default
              return (
                <div
                  key={item.label}
                  className="nav-cat-wrap"
                  onMouseEnter={() => isDesktop() && (hasMega ? openMega(item.mega) : setMegaCat(null))}
                  onMouseLeave={() => isDesktop() && scheduleClose()}
                >
                  <button
                    onClick={() => {
                      if (item.cat) setActiveCat(item.cat);
                      setMenuOpen(false);
                      if (!isDesktop() && hasMega) {
                        setMobileExpanded(m => (m === item.mega ? null : item.mega));
                      }
                    }}
                    className={`nav-cat ${isActive ? 'is-active' : ''} ${megaCat === item.mega && hasMega ? 'is-hovered' : ''}`}
                    aria-haspopup={hasMega ? 'true' : undefined}
                    aria-expanded={megaCat === item.mega ? 'true' : undefined}
                  >
                    {item.label}
                  </button>
                  {hasMega && mobileExpanded === item.mega && (
                    <div className="mega-mobile">
                      {(window.MEGA_MENU[item.mega]?.topics || window.CATEGORIES.filter(c=>c!=='ALL')).map(t => (
                        <a href="#" key={t} className="mega-mobile-link"
                          onClick={() => { setActiveCat(t); setMobileExpanded(null); setMenuOpen(false); }}
                        >{t}</a>
                      ))}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Desktop mega panel */}
        {megaCat && (
          <MegaPanel
            cat={megaCat}
            onEnter={cancelClose}
            onLeave={scheduleClose}
            onPick={(c) => { setActiveCat(c); setMegaCat(null); }}
          />
        )}
      </nav>
    </header>
  );
}

// ============ MEGA PANEL ============
function MegaPanel({ cat, onEnter, onLeave, onPick }) {
  const meta = window.MEGA_MENU[cat] || { label: cat, color: '#E6332A', topics: [] };
  const accent = meta.color || '#E6332A';
  const isNews = cat === 'NEWS';
  const isVisual = (cat === 'POP' || cat === 'LIFE'); // visual card layout
  const featured = React.useMemo(
    () => isNews
      ? window.ARTICLES.slice(0, 2)
      : window.ARTICLES.filter(a => a.category !== 'ALL').slice(0, 3),
    [cat]
  );

  return (
    <div
      className={`mega-panel mega-panel--${cat.toLowerCase().replace(/\s/g,'-')}`}
      onMouseEnter={onEnter}
      onMouseLeave={onLeave}
      style={{ '--mega-accent': accent }}
    >
      {/* Accent top bar */}
      <div className="mega-topbar">
        <div className="mega-topbar-inner">
          <span className="mega-topbar-label">{meta.label}</span>
          <button className="mega-topbar-all" onClick={() => onPick(cat)}>
            ดูทั้งหมด →
          </button>
        </div>
      </div>

      <div className="mega-body">
        {/* Topics column */}
        <div className={`mega-topics-col ${isNews ? 'is-wide' : ''}`}>
          {isVisual ? (
            // POP / LIFE: visual card grid
            <div className="mega-visual-grid">
              {meta.topics.map((t, i) => (
                <a href="#" key={t} className="mega-visual-card" onClick={() => onPick(t)}>
                  <span className="mega-visual-icon">
                    {MEGA_ICONS[t] || <span className="mega-visual-idx">{String(i+1).padStart(2,'0')}</span>}
                  </span>
                  <span className="mega-visual-name">{t}</span>
                </a>
              ))}
            </div>
          ) : isNews ? (
            // NEWS: two-column topic list
            <div className="mega-news-grid">
              {meta.topics.map((t) => (
                <a href="#" key={t} className="mega-news-topic" onClick={() => onPick(t)}>
                  <span className="mega-news-bullet"></span>
                  {t}
                </a>
              ))}
            </div>
          ) : (
            // Other: simple list
            <div className="mega-topic-list">
              {meta.topics.map(t => (
                <a href="#" key={t} className="mega-topic" onClick={() => onPick(t)}>{t}</a>
              ))}
            </div>
          )}
        </div>

        {/* Divider */}
        <div className="mega-divider"></div>

        {/* Featured articles */}
        <div className="mega-featured-col">
          <span className="mega-featured-head">เรื่องเด่น</span>
          <div className={`mega-feature-list ${isNews ? 'is-row' : ''}`}>
            {featured.map(a => (
              <a href="#" key={a.id} className="mega-feature-card">
                <div className="mega-feature-img" style={{ backgroundImage: `url(${a.image})` }}></div>
                <div className="mega-feature-body">
                  <span className="cat-tag-sm">{a.category}</span>
                  <h5>{a.title}</h5>
                  <span className="meta-sub">{a.time}</span>
                </div>
              </a>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

// ============ TICKER ============
function Ticker() {
  const items = window.TICKER_ITEMS;
  const loop = [...items, ...items];
  return (
    <div className="ticker">
      <div className="ticker-label">
        <span className="live-dot"></span>
        ข่าวสด
      </div>
      <div className="ticker-track">
        <div className="ticker-marquee">
          {loop.map((it, i) => (
            <span className="ticker-item" key={i}>
              <span className="ticker-cat">{it.cat}</span>
              <span className="ticker-text">{it.text}</span>
              <span className="ticker-sep">•</span>
            </span>
          ))}
        </div>
      </div>
    </div>
  );
}

// ============ HERO ============
function Hero({ articles, variant }) {
  const main = articles[0];
  const sides = articles.slice(1, 4);
  if (!main) return null;

  if (variant === 'bento') {
    return (
      <section className="hero hero-bento">
        <a href="#" className="bento-cell bento-hero">
          <div className="bento-hero-img" style={{ backgroundImage: `url(${main.image})` }}></div>
          <div className="bento-hero-overlay">
            <span className="cat-tag light">{main.category}</span>
            <h1 className="bento-hero-title">{main.title}</h1>
            <p className="bento-hero-excerpt">{main.excerpt}</p>
            <div className="meta-line light">
              <span>{main.author}</span>
              <span className="dot">·</span>
              <span>{main.time}</span>
            </div>
          </div>
        </a>
        {sides.map((a, i) => (
          <a href="#" key={a.id} className={`bento-cell bento-side bento-side-${i}`}>
            <div className="bento-side-img" style={{ backgroundImage: `url(${a.image})` }}></div>
            <div className="bento-side-body">
              <span className="cat-tag">{a.category}</span>
              <h3>{a.title}</h3>
              <span className="meta-sub">{a.time}</span>
            </div>
          </a>
        ))}
      </section>
    );
  }

  if (variant === 'magazine') {
    return (
      <section className="hero hero-magazine">
        <article className="hero-main-mag">
          <div className="hero-main-mag-img" style={{ backgroundImage: `url(${main.image})` }}>
            <div className="hero-main-mag-overlay">
              <span className="cat-tag light">{main.category}</span>
              <h1 className="hero-title-xl">{main.title}</h1>
              <p className="hero-excerpt light">{main.excerpt}</p>
              <div className="meta-line light">
                <span>{main.author}</span>
                <span className="dot">·</span>
                <span>{main.time}</span>
              </div>
            </div>
          </div>
        </article>
        <div className="hero-side-mag">
          {sides.map(a => (
            <a href="#" className="hero-side-card-mag" key={a.id}>
              <div className="hero-side-thumb" style={{ backgroundImage: `url(${a.image})` }}></div>
              <div className="hero-side-body">
                <span className="cat-tag">{a.category}</span>
                <h3>{a.title}</h3>
                <span className="meta-sub">{a.time}</span>
              </div>
            </a>
          ))}
        </div>
      </section>
    );
  }

  if (variant === 'minimal') {
    const grid6 = articles.slice(1, 7);
    return (
      <section className="hero hero-minimal">
        <a href="#" className="hero-minimal-feature">
          <div className="hero-minimal-feature-img" style={{ backgroundImage: `url(${main.image})` }}></div>
          <div className="hero-minimal-feature-body">
            <span className="cat-tag">{main.category}</span>
            <h1 className="hero-title-minimal">{main.title}</h1>
            <p className="hero-excerpt">{main.excerpt}</p>
            <div className="meta-line">
              <span>{main.author}</span>
              <span className="dot">·</span>
              <span>{main.time}</span>
              <span className="dot">·</span>
              <span>อ่าน {main.readTime}</span>
            </div>
          </div>
        </a>
        <div className="hero-minimal-grid">
          {grid6.map(a => (
            <a href="#" key={a.id} className="hero-minimal-card">
              <div className="hero-minimal-card-img" style={{ backgroundImage: `url(${a.image})` }}></div>
              <div className="hero-minimal-card-body">
                <span className="cat-tag-sm">{a.category}</span>
                <h4>{a.title}</h4>
                <span className="meta-sub">{a.time}</span>
              </div>
            </a>
          ))}
        </div>
      </section>
    );
  }

  // editorial default
  return (
    <section className="hero hero-editorial">
      <article className="hero-main">
        <a href="#" className="hero-main-link">
          <div className="hero-main-img" style={{ backgroundImage: `url(${main.image})` }}></div>
          <div className="hero-main-body">
            <span className="cat-tag">{main.category}</span>
            <h1 className="hero-title-xl">{main.title}</h1>
            <p className="hero-excerpt">{main.excerpt}</p>
            <div className="meta-line">
              <span>โดย <b>{main.author}</b></span>
              <span className="dot">·</span>
              <span>{main.time}</span>
              <span className="dot">·</span>
              <span>อ่าน {main.readTime}</span>
            </div>
          </div>
        </a>
      </article>
      <aside className="hero-side">
        {sides.map((a, i) => (
          <a href="#" className="hero-side-card" key={a.id}>
            {i === 0 && (
              <div className="hero-side-thumb-lg" style={{ backgroundImage: `url(${a.image})` }}></div>
            )}
            <div className="hero-side-body">
              <span className="cat-tag">{a.category}</span>
              <h3>{a.title}</h3>
              <span className="meta-sub">{a.time} · อ่าน {a.readTime}</span>
            </div>
          </a>
        ))}
      </aside>
    </section>
  );
}

// ============ LATEST GRID ============
function LatestGrid({ articles, activeCat, variant }) {
  const filtered = useMemo(() => {
    const list = activeCat === 'ALL' ? articles : articles.filter(a => a.category === activeCat);
    const skip = variant === 'minimal' ? 7 : 4;
    return list.slice(skip);
  }, [articles, activeCat, variant]);

  return (
    <section className="section latest-section">
      <div className="section-head">
        <div className="section-head-left">
          <span className="section-rule"></span>
          <h2 className="section-title">ข่าวล่าสุด</h2>
          <span className="section-sub">อัปเดตทุกนาที</span>
        </div>
        <a href="#" className="section-link">ดูทั้งหมด →</a>
      </div>

      {filtered.length === 0 ? (
        <div className="empty-state">
          <p>ยังไม่มีข่าวในหมวด <b>{activeCat}</b></p>
        </div>
      ) : (
        <div className={`grid-${variant}`}>
          {filtered.map((a, i) => (
            <ArticleCard key={a.id} article={a} idx={i} variant={variant} />
          ))}
        </div>
      )}
    </section>
  );
}

function ArticleCard({ article, idx, variant }) {
  if (variant === 'bento') {
    // Sized via parent grid; image-first card with overlay
    return (
      <a href="#" className="bento-card">
        <div className="bento-card-img" style={{ backgroundImage: `url(${article.image})` }}></div>
        <div className="bento-card-body">
          <span className="cat-tag">{article.category}</span>
          <h3 className="bento-card-title">{article.title}</h3>
          <div className="art-card-meta">
            <span>{article.time}</span>
          </div>
        </div>
      </a>
    );
  }
  // editorial: mixed card sizes for rhythm
  const isFeatured = variant === 'editorial' && idx % 6 === 0;
  const isWide = variant === 'editorial' && idx % 6 === 3;

  return (
    <a href="#" className={`art-card ${isFeatured ? 'is-featured' : ''} ${isWide ? 'is-wide' : ''}`}>
      <div className="art-card-img" style={{ backgroundImage: `url(${article.image})` }}></div>
      <div className="art-card-body">
        <span className="cat-tag">{article.category}</span>
        <h3 className="art-card-title">{article.title}</h3>
        {(isFeatured || isWide) && <p className="art-card-excerpt">{article.excerpt}</p>}
        <div className="art-card-meta">
          <span>{article.author}</span>
          <span className="dot">·</span>
          <span>{article.time}</span>
        </div>
      </div>
    </a>
  );
}

// ============ POPULAR ============
function PopularSection() {
  const items = window.POPULAR;
  const [active, setActive] = React.useState(0);
  const top = items[active];

  return (
    <section className="popular-wrap">
      <div className="popular-inner">
        {/* Left: heading + big featured */}
        <div className="popular-left">
          <div className="popular-heading">
            <span className="popular-eyebrow">TRENDING NOW</span>
            <h2 className="popular-big-title">บทความ<br/>ยอดนิยม</h2>
            <p className="popular-big-sub">อัปเดตทุก 15 นาที</p>
          </div>
          <a href="#" className="popular-feature-card">
            <div className="popular-feature-img" style={{ backgroundImage: `url(${top.image})` }}>
              <span className="popular-feature-rank">#{top.rank}</span>
            </div>
            <div className="popular-feature-info">
              <span className="popular-feature-cat">{top.cat}</span>
              <h3 className="popular-feature-title">{top.title}</h3>
              <div className="popular-feature-meta">
                <span className="popular-views-badge">{Icons.eye} {top.views} วิว</span>
                <span className="popular-dot">·</span>
                <span>{top.time}</span>
              </div>
            </div>
          </a>
        </div>

        {/* Right: ranked list */}
        <div className="popular-right">
          {items.map((item, i) => (
            <a
              href="#"
              key={item.id}
              className={`popular-row ${i === active ? 'is-active' : ''}`}
              onMouseEnter={() => setActive(i)}
            >
              <span className="popular-row-num">{String(item.rank).padStart(2, '0')}</span>
              <div className="popular-row-thumb" style={{ backgroundImage: `url(${item.image})` }}></div>
              <div className="popular-row-body">
                <span className="popular-row-cat">{item.cat}</span>
                <h4 className="popular-row-title">{item.title}</h4>
                <span className="popular-row-views">{item.views} วิว</span>
              </div>
              <span className="popular-row-arrow">{Icons.arrow}</span>
            </a>
          ))}
        </div>
      </div>
    </section>
  );
}

// ============ EVENTS ============
function EventsSection() {
  const events = window.EVENTS;
  const featured = events.find(e => e.featured);
  const rest = events.filter(e => !e.featured);

  return (
    <section className="events-section">
      {/* Section header */}
      <div className="events-header">
        <div className="events-header-inner">
          <div className="events-header-left">
            <span className="section-rule"></span>
            <h2 className="section-title">Events</h2>
            <span className="section-sub">กิจกรรมจาก THE STANDARD</span>
          </div>
          <a href="#" className="section-link">ดูกิจกรรมทั้งหมด →</a>
        </div>
      </div>

      <div className="events-body">
        {/* Featured: large magazine card */}
        {featured && (
          <a href="#" className="event-featured" style={{ '--ev-accent': featured.accent, '--ev-text': featured.textColor }}>
            {/* Poster image — displayed at natural ratio, no crop */}
            <div className="event-featured-poster">
              <img src={featured.image} alt={featured.title} className="event-featured-img" />
              <span className="event-featured-badge">{featured.tag}</span>
            </div>
            {/* Editorial text panel */}
            <div className="event-featured-body">
              <div className="event-featured-series">{featured.series}</div>
              <h3 className="event-featured-title">{featured.title}</h3>
              <p className="event-featured-sub">{featured.subtitle}</p>
              <div className="event-featured-details">
                <div className="event-detail-row">
                  <span className="event-detail-icon">{Icons.calendar}</span>
                  <span className="event-detail-text">{featured.date}</span>
                </div>
                <div className="event-detail-row">
                  <span className="event-detail-icon">{Icons.pin}</span>
                  <span className="event-detail-text">{featured.venue}</span>
                </div>
                {featured.ticketDate && (
                  <div className="event-detail-row event-ticket-note">
                    <span className="event-detail-icon">{Icons.ticket}</span>
                    <span className="event-detail-text">{featured.ticketDate}</span>
                  </div>
                )}
              </div>
              <span className="event-featured-cta">{featured.cta} →</span>
            </div>
          </a>
        )}

        {/* Supporting events */}
        <div className="events-rest">
          {rest.map(ev => (
            <a href="#" key={ev.id} className="event-rest-card" style={{ '--ev-accent': ev.accent }}>
              <div className="event-rest-img" style={{ backgroundImage: `url(${ev.image})` }}>
                <span className="event-rest-tag">{ev.tag}</span>
              </div>
              <div className="event-rest-body">
                <div className="event-rest-series">{ev.series}</div>
                <h4 className="event-rest-title">{ev.title}</h4>
                <div className="event-rest-meta">
                  <span>{Icons.calendar} {ev.date}</span>
                  <span>{Icons.pin} {ev.venue}</span>
                </div>
                <span className="event-rest-cta">{ev.cta} →</span>
              </div>
            </a>
          ))}
        </div>
      </div>
    </section>
  );
}

// ============ OPINION ============
function OpinionSection() {
  const items = window.OPINIONS;
  if (!items || items.length === 0) return null;
  const featured = items[0];
  const rest = items.slice(1, 4);

  return (
    <section className="opinion-section">
      <div className="opinion-inner">
        {/* Header bar */}
        <div className="opinion-header">
          <span className="opinion-header-deco">"</span>
          <div className="opinion-header-text">
            <span className="opinion-eyebrow">OPINION</span>
            <h2 className="opinion-section-label">มุมมอง &amp; บทวิเคราะห์</h2>
          </div>
          <a href="#" className="opinion-all-link">ดูคอลัมน์ทั้งหมด →</a>
        </div>

        {/* Body: featured left, list right */}
        <div className="opinion-body">
          {/* Featured columnist */}
          <a href="#" className="opinion-featured">
            <div className="opinion-featured-author">
              <img className="opinion-avatar" src={featured.avatar} alt={featured.columnist} />
              <div>
                <span className="opinion-author-name">{featured.columnist}</span>
                <span className="opinion-col-tag">{featured.tag}</span>
              </div>
            </div>
            <p className="opinion-pull-quote">"{featured.excerpt}"</p>
            <h3 className="opinion-headline">{featured.title}</h3>
            <span className="opinion-readtime">อ่าน {featured.readTime}</span>
          </a>

          {/* Vertical divider */}
          <div className="opinion-vdivider"></div>

          {/* Columnist list */}
          <div className="opinion-list">
            {rest.map(op => (
              <a href="#" key={op.id} className="opinion-row">
                <img className="opinion-row-avatar" src={op.avatar} alt={op.columnist} />
                <div className="opinion-row-body">
                  <div className="opinion-row-top">
                    <span className="opinion-row-author">{op.columnist}</span>
                    <span className="opinion-row-tag">{op.tag}</span>
                  </div>
                  <h4 className="opinion-row-title">{op.title}</h4>
                  <span className="opinion-row-time">อ่าน {op.readTime}</span>
                </div>
              </a>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

// ============ FOOTER ============
function Footer() {
  return (
    <footer className="site-footer">
      <div className="footer-inner">
        <div className="footer-brand">
          <div className="wordmark wordmark-footer">
            <img src="assets/logo-the-standard.png" alt="THE STANDARD" className="wordmark-img" />
          </div>
          <p className="footer-tag">สำนักข่าวที่มุ่งสร้างความเปลี่ยนแปลงเชิงบวกแก่สังคม</p>
        </div>
        <div className="footer-cols">
          <div>
            <h5>NEWS</h5>
            <a href="#">Politics</a>
            <a href="#">Business</a>
            <a href="#">Thailand</a>
            <a href="#">World</a>
            <a href="#">Tech</a>
          </div>
          <div>
            <h5>VERTICALS</h5>
            <a href="#">Wealth</a>
            <a href="#">POP</a>
            <a href="#">Life</a>
            <a href="#">The Secret Sauce</a>
            <a href="#">Opinion</a>
          </div>
          <div>
            <h5>COMPANY</h5>
            <a href="#">About</a>
            <a href="#">Careers</a>
            <a href="#">Contact</a>
            <a href="#">Complaint</a>
          </div>
          <div>
            <h5>FOLLOW</h5>
            <a href="#">Facebook</a>
            <a href="#">X / Twitter</a>
            <a href="#">Instagram</a>
            <a href="#">YouTube</a>
          </div>
        </div>
      </div>
      <div className="footer-bottom">
        <span>© 2026 The Standard Co. Ltd.</span>
        <span>Terms · Privacy</span>
      </div>
    </footer>
  );
}

Object.assign(window, { Header, MegaPanel, Ticker, Hero, LatestGrid, ArticleCard, PopularSection, OpinionSection, EventsSection, Footer });
