import os
from pathlib import Path
from markdown import markdown
from bs4 import BeautifulSoup
from docx import Document

root = Path(r'c:\xampp\htdocs\MCP\apps\SBN_MCP\docs')
md_files = sorted([p for p in root.iterdir() if p.suffix.lower() == '.md'])

if not md_files:
    print('No Markdown files found.')
    raise SystemExit(0)

for md in md_files:
    docx_path = md.with_suffix('.docx')
    print(f'Converting {md.name} -> {docx_path.name}')
    text = md.read_text(encoding='utf-8')
    html = markdown(text, extensions=['fenced_code', 'tables'])
    soup = BeautifulSoup(html, 'html.parser')
    doc = Document()
    for elem in soup.children:
        if elem.name is None:
            txt = elem.strip()
            if txt:
                doc.add_paragraph(txt)
            continue
        if elem.name in ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']:
            level = max(0, min(4, int(elem.name[1:]) - 1))
            doc.add_heading(elem.get_text(strip=True), level=level)
        elif elem.name == 'p':
            doc.add_paragraph(elem.get_text(strip=True))
        elif elem.name in ['ul', 'ol']:
            for i, li in enumerate(elem.find_all('li', recursive=False), 1):
                if elem.name == 'ol':
                    p = doc.add_paragraph(f'{i}. {li.get_text(separator=" ", strip=True)}', style='List Number')
                else:
                    p = doc.add_paragraph(li.get_text(separator=' ', strip=True), style='List Bullet')
        elif elem.name == 'pre':
            code = elem.get_text()
            p = doc.add_paragraph()
            r = p.add_run(code)
            r.font.name = 'Courier New'
        elif elem.name == 'code':
            doc.add_paragraph(elem.get_text())
        elif elem.name == 'table':
            rows = elem.find_all('tr')
            cols = rows[0].find_all(['th','td']) if rows else []
            table = doc.add_table(rows=len(rows), cols=len(cols))
            table.style = 'Light List Accent 1'
            for ri, row in enumerate(rows):
                cells = row.find_all(['th','td'])
                for ci, cell in enumerate(cells):
                    table.cell(ri, ci).text = cell.get_text(strip=True)
        else:
            doc.add_paragraph(elem.get_text(strip=True))
    doc.save(docx_path)
print('Conversion completed.')
