import re

path = "resources/views/admin/theme-options/gallery-page.blade.php"
close = bytes([60, 47, 100, 105, 118, 62]).decode()
open_div = bytes([60, 100, 105, 118]).decode() + ' class="'

with open(path, encoding="utf-8") as f:
    content = f.read()

marker = 'for="empty_btn_text"'
start = content.find(marker)
if start == -1:
    raise SystemExit("empty_btn_text not found")

row_start = content.rfind('<motion class="row">', 0, start)
row_start = content.rfind('<div class="row">', 0, start)
url_pos = content.find('name="empty_btn_url"', start)
end = content.find(close, url_pos)
end = content.find(close, end + 1)
end = content.find(close, end + 1)
end += len(close)

new_content = content[:row_start] + content[end:]
new_content = re.sub(
    r'(<p class="text-muted small fw-semibold mb-3">Empty state \(no published items\)</p>\s*)<div class="mb-3">',
    r'\1<div class="mb-0">',
    new_content,
    count=1,
)

bottom_marker = 'id="gallery-bottom-pane"'
bottom_idx = new_content.find(bottom_marker)
if bottom_idx == -1:
    raise SystemExit("bottom pane not found")

# Insert grid tab-pane close immediately before bottom tab-pane opening.
tab_open = new_content.rfind('<div class="tab-pane', 0, bottom_idx)
insert_at = tab_open
new_content = (
    new_content[:insert_at]
    + "                "
    + close
    + "\n\n                "
    + new_content[insert_at:]
)

with open(path, "w", encoding="utf-8", newline="\n") as f:
    f.write(new_content)
print("done")
