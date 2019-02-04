Please add the following code to ./git/hooks/pre-commit

# On Mac
```
#!/usr/bin/env bash

for SCRIPT in tools/git-hooks/*hook;.
do.
$SCRIPT;
done
```

# On Linux
```
#!/usr/bin/bash

for SCRIPT in tools/git-hooks/*hook;.
do.
$SCRIPT;
done
```