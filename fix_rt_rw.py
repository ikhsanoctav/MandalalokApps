import os
import glob
import re

files_to_check = glob.glob('resources/views/**/*.blade.php', recursive=True)

for filepath in files_to_check:
    with open(filepath, 'r') as f:
        content = f.read()

    # Pattern to match the <div class="grid grid-cols-2 gap-3"> containing RT and RW
    # It might have slightly different classes, but the structure is <div>...<label>RT...</div><div>...<label>RW...</div>
    
    # Let's do a more robust string replacement
    
    rt_div_start_index = content.find('<div>\n                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">RT</label>')
    if rt_div_start_index == -1:
        # maybe different indentation or single line
        rt_match = re.search(r'<div>\s*<label[^>]*>RT</label>', content)
        if rt_match:
            pass # we could use regex but exact string replacement based on superadmin/umkm/edit.blade.php is easier if they are identical
            
