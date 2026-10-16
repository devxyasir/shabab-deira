#!/usr/bin/env python3
"""Shabab Deira asset pipeline — content-hashed filenames for immutable caching.

Usage (from the repo root):
    python3 tools/build-assets.py

What it does:
  1. Copies css/style.css, js/main.js, js/i18n.js, js/i18n-ar.js,
     js/lucide.min.js, fonts/fonts.css, fonts/fonts-ar.css and every
     fonts/*.woff2 to `<name>.<sha256-8>.<ext>` next to the original.
  2. Rewrites all references in *.html (stylesheet links incl. the noscript
     fallback, script tags, font preloads) plus the dynamic
     `js/i18n-ar.js` reference inside js/i18n.js and the woff2 URLs inside
     fonts/fonts.css.
  3. Removes stale hashed copies of the same stems first, so the folders do
     not accumulate orphans. Source files are never modified except the
     i18n-ar reference inside js/i18n.js (re-hashed deterministically).

Re-run after any change to the files above, then commit the result.
Requires only the Python standard library.
"""
import glob
import hashlib
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SOURCES = [
    'css/style.css',
    'js/main.js',
    'js/i18n.js',
    'js/i18n-ar.js',
    'js/lucide.min.js',
    'fonts/fonts.css',
    'fonts/fonts-ar.css',
] + sorted(
    os.path.relpath(f, ROOT).replace(os.sep, '/')
    for f in glob.glob(os.path.join(ROOT, 'fonts', '*.woff2'))
    if not re.search(r'\.[0-9a-f]{8}\.', os.path.basename(f))
)


def hashed_name(path, digest):
    d, base = os.path.split(path)
    stem, ext = os.path.splitext(base)
    return os.path.join(d, '%s.%s%s' % (stem, digest, ext))


def clean_stale(path):
    d, base = os.path.split(path)
    stem, ext = os.path.splitext(base)
    for old in glob.glob(os.path.join(d, stem + '.????????' + ext)):
        if re.search(r'\.[0-9a-f]{8}\.', os.path.basename(old)):
            os.remove(old)
            print('  removed stale', os.path.relpath(old, ROOT))


def main():
    mapping = {}
    for rel in SOURCES:
        src = os.path.join(ROOT, rel) if not os.path.isabs(rel) else rel
        with open(src, 'rb') as f:
            digest = hashlib.sha256(f.read()).hexdigest()[:8]
        clean_stale(src)
        dest = hashed_name(src, digest)
        with open(src, 'rb') as f_in, open(dest, 'wb') as f_out:
            f_out.write(f_in.read())
        mapping[rel] = os.path.relpath(dest, ROOT).replace(os.sep, '/')
    # Order matters: patch the ar reference inside i18n.js BEFORE hashing it.
    # (Re-hash i18n.js after the patch so its hash covers the final bytes.)
    ar_hashed = mapping['js/i18n-ar.js']
    i18n_src = os.path.join(ROOT, 'js', 'i18n.js')
    with open(i18n_src, encoding='utf-8') as f:
        body = f.read()
    body, n = re.subn(r'js/i18n-ar\.js\?v=\d+', ar_hashed, body)
    assert n >= 1, 'i18n-ar reference not found in js/i18n.js'
    with open(i18n_src, 'w', encoding='utf-8') as f:
        f.write(body)
    with open(i18n_src, 'rb') as f:
        digest = hashlib.sha256(f.read()).hexdigest()[:8]
    clean_stale(i18n_src)
    dest = hashed_name(i18n_src, digest)
    with open(i18n_src, 'rb') as f_in, open(dest, 'wb') as f_out:
        f_out.write(f_in.read())
    mapping['js/i18n.js'] = os.path.relpath(dest, ROOT).replace(os.sep, '/')

    # Patch fonts.css woff2 URLs, then re-hash fonts.css the same way.
    fcss = os.path.join(ROOT, 'fonts', 'fonts.css')
    with open(fcss, encoding='utf-8') as f:
        css = f.read()
    for rel in [r for r in mapping if r.startswith('fonts/') and r.endswith('.woff2')]:
        css = css.replace("url('%s')" % os.path.basename(rel),
                          "url('%s')" % os.path.basename(mapping[rel]))
    with open(fcss, 'w', encoding='utf-8') as f:
        f.write(css)
    with open(fcss, 'rb') as f:
        digest = hashlib.sha256(f.read()).hexdigest()[:8]
    clean_stale(fcss)
    dest = hashed_name(fcss, digest)
    with open(fcss, 'rb') as f_in, open(dest, 'wb') as f_out:
        f_out.write(f_in.read())
    mapping['fonts/fonts.css'] = os.path.relpath(dest, ROOT).replace(os.sep, '/')

    # Rewrite references in every page.
    repl = [
        (r'css/style\.css\?v=[\w.]+', mapping['css/style.css']),
        (r'js/main\.js\?v=[\w.]+', mapping['js/main.js']),
        (r'js/i18n\.js\?v=[\w.]+', mapping['js/i18n.js']),
        (r'js/lucide\.min\.js\?v=[\w.]+', mapping['js/lucide.min.js']),
        (r'fonts/fonts\.css(?=["\'])', mapping['fonts/fonts.css']),
        (r'fonts/outfit-latin\.woff2', mapping[[r for r in mapping if 'outfit-latin' in r][0]]),
        (r'fonts/jakarta-latin\.woff2', mapping[[r for r in mapping if 'jakarta-latin' in r][0]]),
        (r"a\.href='fonts/fonts-ar\.css'", "a.href='%s'" % mapping['fonts/fonts-ar.css']),
    ]
    pages = sorted(glob.glob(os.path.join(ROOT, '*.html')))
    for fp in pages:
        with open(fp, encoding='utf-8') as f:
            html = f.read()
        orig = html
        for pat, new in repl:
            html = re.sub(pat, new, html)
        if html != orig:
            with open(fp, 'w', encoding='utf-8') as f:
                f.write(html)
    print('hashed mapping:')
    for k in sorted(mapping):
        print('  %s -> %s' % (k, mapping[k]))
    print('pages updated:', len(pages))


if __name__ == '__main__':
    sys.exit(main())
