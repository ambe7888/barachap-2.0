-keep class org.slf4j.** { *; }

# Keep all SLF4J logging classes
-keep class org.slf4j.** { *; }

# Suppress warnings for missing classes
-dontwarn org.slf4j.impl.StaticMDCBinder
-dontwarn org.slf4j.impl.StaticMarkerBinder
-dontwarn org.slf4j.impl.StaticLoggerBinder