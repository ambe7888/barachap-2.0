import os
import re
import json

app_dir = r'c:\xampp\htdocs\barachap\web\core\app'
resources_dir = r'c:\xampp\htdocs\barachap\web\core\resources'
modules_dir = r'c:\xampp\htdocs\barachap\web\core\Modules'
fr_json_path = r'c:\xampp\htdocs\barachap\web\core\resources\lang\fr_FR.json'

# Load existing translations
if os.path.exists(fr_json_path):
    with open(fr_json_path, 'r', encoding='utf-8') as f:
        existing_translations = json.load(f)
else:
    existing_translations = {}

# Regex patterns
patterns = [
    re.compile(r"__\(\s*'([^']*)'\s*\)"),
    re.compile(r'__\(\s*"([^"]*)"\s*\)'),
    re.compile(r"@lang\(\s*'([^']*)'\s*\)"),
    re.compile(r'@lang\(\s*"([^"]*)"\s*\)'),
]

found_keys = set()

def scan_dir(directory):
    if not os.path.exists(directory):
        return
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith('.php'):
                file_path = os.path.join(root, file)
                try:
                    with open(file_path, 'r', encoding='utf-8') as f:
                        content = f.read()
                        for pattern in patterns:
                            for match in pattern.finditer(content):
                                found_keys.add(match.group(1))
                except Exception:
                    pass

print("Scanning directories...")
scan_dir(app_dir)
scan_dir(resources_dir)
scan_dir(modules_dir)

print(f"Total unique keys found in code: {len(found_keys)}")

missing_keys = []
for key in found_keys:
    # Filter out empty keys, keys with variable interpolations like $var, or keys already translated
    if key and not key.startswith('$') and key not in existing_translations:
        missing_keys.append(key)

print(f"Total missing keys: {len(missing_keys)}")
# Output some missing keys as example
print("Example missing keys:", missing_keys[:20])

# Save missing keys to a temporary file
with open(r'c:\xampp\htdocs\barachap\web\core\missing_keys.json', 'w', encoding='utf-8') as f:
    json.dump(missing_keys, f, indent=4)
