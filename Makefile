# Silence output slightly
# .SILENT:

DB := dhil_btd
PROJECT := btd

include etc/Makefile.legacy

# Override any of the options above by copying them to makefile.local
-include Makefile.local

## -- No targets yet
