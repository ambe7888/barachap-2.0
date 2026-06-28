# -*- coding: utf-8 -*-
import json
import os
import time
from deep_translator import GoogleTranslator

default_path = r"C:\xampp\htdocs\barachap\core\resources\lang\default.json"
fr_path = r"C:\xampp\htdocs\barachap\core\resources\lang\fr_FR.json"

with open(default_path, "r", encoding="utf-8") as f:
    default_data = json.load(f)

translator = GoogleTranslator(source="en", target="fr")
fr_data = {}

print(f"Translating {len(default_data)} keys...")

count = 0
for k, v in default_data.items():
    count += 1
    if not v:
        fr_data[k] = ""
        continue
    
    try:
        translated = translator.translate(v)
        # Replacing job terms
        translated_clean = (translated
                            .replace("offre d'emploi", "demande")
                            .replace("offres d'emploi", "demandes")
                            .replace("l'emploi", "la demande")
                            .replace("d'emploi", "de demande")
                            .replace("d'emplois", "de demandes")
                            .replace("Emploi", "Demande")
                            .replace("Emplois", "Demandes")
                            .replace("emploi", "demande")
                            .replace("emplois", "demandes")
                            .replace("travail", "demande")
                            .replace("tâche", "demande")
                            .replace("tâches", "demandes"))
        fr_data[k] = translated_clean
        if count % 20 == 0:
            print(f"Translated {count}/{len(default_data)}...")
            with open(fr_path, "w", encoding="utf-8") as f_out:
                json.dump(fr_data, f_out, ensure_ascii=False, indent=4)
    except Exception as e:
        print(f"Error translating '{v}': {e}")
        fr_data[k] = v

with open(fr_path, "w", encoding="utf-8") as f_out:
    json.dump(fr_data, f_out, ensure_ascii=False, indent=4)

print("Translation done. fr_FR.json is now valid!")
