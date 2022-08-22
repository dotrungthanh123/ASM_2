```mermaid
graph TD;
    ASM_2-->migrations
    ASM_2-->.env
    ASM_2-->config
    ASM_2-->public
    ASM_2-->README.md
    ASM_2-->templates
    public-->images
    public-->mycss
    ASM_2-->src
    src-->Controller
    src-->DataFixtures
    src-->Entity
    src-->Form
    src-->Kernel.php
    src-->Repository
    src-->Security
    
    