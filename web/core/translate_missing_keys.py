import json
import os
import time
from deep_translator import GoogleTranslator

missing_keys_path = r'c:\xampp\htdocs\barachap\web\core\missing_keys.json'
fr_json_path = r'c:\xampp\htdocs\barachap\web\core\resources\lang\fr_FR.json'

with open(missing_keys_path, 'r', encoding='utf-8') as f:
    missing_keys = json.load(f)

if os.path.exists(fr_json_path):
    with open(fr_json_path, 'r', encoding='utf-8') as f:
        data = json.load(f)
else:
    data = {}

# Filter keys that are already present in fr_FR.json
keys_to_translate = [k for k in missing_keys if k not in data]

print(f"Loaded {len(missing_keys)} missing keys. Already translated: {len(missing_keys) - len(keys_to_translate)}. Remaining to translate: {len(keys_to_translate)}")

translator = GoogleTranslator(source='en', target='fr')

# Increased chunk size to 100 for faster processing
chunk_size = 100
for i in range(0, len(keys_to_translate), chunk_size):
    chunk = keys_to_translate[i:i+chunk_size]
    print(f"Translating chunk {i//chunk_size + 1}/{(len(keys_to_translate)+chunk_size-1)//chunk_size}...")
    try:
        translated = translator.translate_batch(chunk)
        for idx, key in enumerate(chunk):
            if translated[idx] is not None:
                data[key] = translated[idx]
        
        # Save progress
        with open(fr_json_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)
        
        time.sleep(2)
    except Exception as e:
        print(f"Error at chunk starting at {i}: {e}")
        time.sleep(10)
        # Retry once
        try:
            translated = translator.translate_batch(chunk)
            for idx, key in enumerate(chunk):
                if translated[idx] is not None:
                    data[key] = translated[idx]
            with open(fr_json_path, 'w', encoding='utf-8') as f:
                json.dump(data, f, ensure_ascii=False, indent=4)
        except Exception as re:
            print(f"Retry failed: {re}. Skipping chunk.")

print("Finished translating all missing keys!")
