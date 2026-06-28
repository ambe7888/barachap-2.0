#!/bin/bash

# Locate the local_keys.g.dart file
filePath=$(find . -type f -name "local_keys.g.dart")

if [ -z "$filePath" ]; then
    echo "File local_keys.g.dart not found. Please make sure it exists."
    exit 1
fi

echo "Found file at: $filePath"

# Extract all getter names more reliably
getters=$(awk -F'get ' '/static String get / {print $2}' "$filePath" | awk -F' =>' '{print $1}')

# Iterate over each getter
while read -r getter; do
    if [ -z "$getter" ]; then
        continue
    fi

    # Check if the getter is used in the "lib" folder, properly excluding local_keys.g.dart
    usageCount=$(grep -r -w "$getter" lib --exclude="$filePath" | wc -l)

    if [ "$usageCount" -eq 0 ]; then
        echo "Removing unused getter: $getter"

        # Remove the getter line
        sed -i '' "/static String get $getter/d" "$filePath"

        # Find and remove the associated private variable
        sed -i '' "/static const String _$getter/d" "$filePath"

        # Remove the variable from stringsMap
        sed -i '' "/_$getter: \".*\"/d" "$filePath"
    else
        echo "Getter $getter is in use."
    fi
done <<< "$getters"

echo "Cleanup completed."