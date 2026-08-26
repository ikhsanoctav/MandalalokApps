import re

with open('patch_fetch.js', 'r') as f:
    patch_content = f.read().strip()

def replace_in_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    # Regex to find window.performAjaxFetch = function(url) { ... }
    # This matches until the end of the function body
    pattern = r'window\.performAjaxFetch\s*=\s*function\s*\(\s*url\s*\)\s*\{.*?\n            \}(?=\n*(?:\s*\}\)\(\);|\s*\/\/\s*Initialize))'
    
    new_content = re.sub(pattern, patch_content, content, flags=re.DOTALL)
    
    with open(filepath, 'w') as f:
        f.write(new_content)
        print(f"Replaced in {filepath}")

replace_in_file('resources/views/superadmin/umkm/index.blade.php')
replace_in_file('resources/views/admin/umkm/index.blade.php')
